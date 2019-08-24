<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\generateMailBatch;
use App\Models\Email;
use App\Models\MailCron;
use App\Models\MailTemplate;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Excel;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\EtuUTT;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Classes\MailHelper;
use App\Jobs\SendEmail;
use App\Console\Commands\PutScheduledEmailToQueue;
use Illuminate\Support\Facades\Cache;

class EmailsController extends Controller
{

    /**
     * @return Response
     */
    public function getIndex()
    {

        // All crons with the count of each state for associated mail_histories
        $crons = MailCron::select('mail_crons.*',
            DB::raw('sum(mail_histories.state="PENDING") as `count_pending`'),
            DB::raw('sum(mail_histories.state="SENDING") as `count_sending`'),
            DB::raw('sum(mail_histories.state="SENT") as `count_sent`'),
            DB::raw('sum(mail_histories.state="ERROR") as `count_error`'),
            DB::raw('sum(mail_histories.open_at IS NOT NULL) as `count_opened`')
        )->leftJoin('mail_histories', 'mail_crons.id' , '=', 'mail_histories.mail_cron_id')
        ->groupBy('mail_crons.id')
        ->orderBy('send_date', 'desc')
        ->get();

        // Cached list of destination email count foreach cron
        // Because this page will be "refreshed" a lot
        $updatedRecipients = Cache::remember('App\Http\Controllers\Admin\EmailsController::getIndex()::$updatedRecipients', 5, function () use ($crons) {
            $rtn = [];
            foreach($crons as $cron) {
                $rtn[$cron->id] = MailHelper::mailFromLists($cron->lists, $cron->mail_template->is_publicity, $cron->mail_template, $cron->unique_send);
            }
            return $rtn;
        });

        return View::make('dashboard.emails.index', [
            'mail_templates' => MailTemplate::all(),
            'mail_crons'    => $crons,
            'file_templates'    => File::allFiles(resource_path('views/emails/template')),
            'listToFrench' => MailHelper::$listToFrench,
            'updatedRecipients' => $updatedRecipients,
        ]);
    }

    public function getTemplatePreview($id, $user_id=null)
    {
        $mail = MailTemplate::findOrFail($id);
        $user = User::find($user_id);

        return $mail->generateHtml($user);
    }

    public function createTemplate()
    {
        $this->validate(Request::instance(), [
            'subject' => 'required',
            'template' => 'required',
            'isPublicity' => 'boolean',
        ]);

        $data = Request::only([
            'subject',
            'template',
            'content',
            'isPublicity',
        ]);

        $template = new MailTemplate($data);
        $template->created_by = Auth::user()->id;

        if ($template->save()) {
            return $this->success('Le modèle de mail a été créé !');
        }
        return $this->error('Impossible de créer le modèle de mail !');
    }

    /**
     * Show the edit template
     *
     * @param  int $id
     * @return RedirectResponse|array
     */
    public function editTemplate($id)
    {
        $template = MailTemplate::where('id', $id)->firstOrFail();
        return View::make('dashboard.emails.edit', [
            'template' => $template,
            'file_templates'    => File::allFiles(resource_path('views/emails/template')),
            'varlist' => MailTemplate::getVarArray(Auth::user()),
        ]);
    }

    /**
     * Execute edit form for a email template
     *
     * @param  int $id
     * @return RedirectResponse|array
     */
    public function editTemplateSubmit($id)
    {
        $template = MailTemplate::where('id', $id)->firstOrFail();
        $data = Request::only([
            'subject',
            'template',
            'content',
            'isPublicity',
        ]);
        $this->validate(Request::instance(), [
            'subject' => 'required',
            'template' => 'required',
            'isPublicity' => 'boolean',
        ]);

        $template->update($data);
        $template->save();

        return Redirect::back()->withSuccess('Vos modifications ont été sauvegardées.');
    }

    /**
     * Schedule template form
     *
     * @param  int $id
     * @return RedirectResponse|array
     */
    public function scheduleTemplate($id, $cronId = null)
    {
        $template = MailTemplate::where('id', $id)->firstOrFail();
        $name = $template->subject;

        $cron = null;
        if ($cronId) {
            $cron = MailCron::where('id', $cronId)->firstOrFail();
            if(preg_match('/^(.+) \(Réédition ([0-9]+)\)$/', $cron->name, $matches)) {
                $name = $matches[1]. ' (Réédition '. ($matches[2]+1) .')';
            }
            else {
                $name = $cron->name . ' (Réédition 1)';
            }

        }

        return View::make('dashboard.emails.schedule', [
            'name' => $name,
            'cron' => $cron,
            'template' => $template,
            'lists' => MailHelper::$listToFrench,
        ]);
    }

    public function scheduleTemplateSubmit($id)
    {
        $this->validate(Request::instance(), [
            'name' => 'required',
            'lists' => 'required|array|min:1',
            'unique_send' => 'required|boolean',
            'send_date_date' => 'required|regex:/^([0-9]{4}-[0-9]{2}-[0-9]{2})$/',
            'send_date_time' => 'required|regex:/^([0-9]{2}:[0-9]{2})$/',
        ]);

        $data = Request::only([
            'name',
            'lists',
            'unique_send',
            'send_date_date',
            'send_date_time',
        ]);

        $cron = new MailCron;
        $cron->name = $data['name'];
        $cron->lists = implode(',', $data['lists']);
        $cron->send_date = new \Datetime($data['send_date_date'] . ' ' . $data['send_date_time']);
        $cron->mail_template_id = $id;
        $cron->created_by = Auth::user()->id;
        $cron->unique_send = $data['unique_send'] == 1;

        if ($cron->send_date <= new \Datetime()) {
            return Redirect::back()->withInput()->withError('La date d\'envoi doit être dans le futur.');
        }

        if ($cron->save()) {
            generateMailBatch::dispatch()->delay($cron->send_date);
            return Redirect::route('dashboard.emails.index')->withSuccess('L\'envoi a bien été programmé !');
        }
        return $this->error('Impossible de programmer l\'envoi !');
    }

    public function cancelCron($id)
    {
        $cron = MailCron::where('id', $id)->firstOrFail();

        if ($cron->is_sent) {
            return Redirect::back()->withError('Impossible d\'annuler un envoi d\'email en cours.');
        }

        $cron->delete();
        return Redirect::back()->withSuccess('Envoi d\'email annulé.');
    }
}
