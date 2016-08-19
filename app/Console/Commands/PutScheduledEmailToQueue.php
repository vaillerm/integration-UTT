<?php

namespace App\Console\Commands;

use DB;
use Blade;
use Crypt;
use App\Models\Email;
use App\Models\Student;
use App\Models\Newcomer;
use App\Jobs\SendEmail;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class PutScheduledEmailToQueue extends Command
{

    use DispatchesJobs;

    protected $currentUser = null;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inte:emails-to-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Put scheduled email in the laravel queue';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Find email that are ready to be started
        $emails = Email::where('started', 0)
            ->whereNotNull('scheduled_for')
            ->where('scheduled_for', '<=', DB::RAW('NOW()'))
            ->get();

        // set every email as started
        foreach ($emails as $email) {
            $email->started = 1;
            $email->save();
        }

        foreach ($emails as $email) {
            // Generate email list
            // To add more email list edit this file and `App/Models/Email.php
            $list = [];
            $students = [];
            $newcomers = [];
            switch ($email->list) {
                case Email::STUPRELISTE:
                    $list['stupre-liste@utt.fr'] = [ 'name' => 'STUPRE-liste', 'user' => null ];
                    break;
                case Email::VOLUNTEERS:
                    $students = Student::where('volunteer', 1)->get();
                    break;
                case Email::CE_VALIDATED:
                    $students = Student::where('ce', 1)->whereNotNull('team_id')->where('team_accepted', 1)->get();
                    break;
                case Email::REFERRALS_VALIDATED:
                    $students = Student::where('referral', 1)->where('referral_validated', 1)->get();
                    break;
                case Email::REFERRALS_INCOMPLETE:
                    $students = Student::where('referral', 1)
                    ->where('referral_validated', 0)
                    ->where(function ($query) {
                        $query->where('phone', '')
                        ->orWhereNull('phone')
                        ->orWhere('email', '')
                        ->orWhereNull('email')
                        ->orWhere('referral_text', '')
                        ->orWhereNull('referral_text');
                    })
                    ->get();
                    break;
                case Email::REFERRALS_VALIDATED_BRANCH:
                    $students = Student::where('referral', 1)->where('referral_validated', 1)->where('branch', '<>', 'tc')->get();
                    break;
                case Email::REFERRALS_VALIDATED_TC:
                    $students = Student::where('referral', 1)->where('referral_validated', 1)->where('branch', '=', 'tc')->get();
                    break;
                case Email::ORGA:
                    $students = Student::where('orga', 1)->get();
                    break;
                case Email::ADMIN:
                    $students = Student::where('admin', 100)->get();
                    break;
                case Email::NEWCOMERS_ALL:
                    $newcomers = Newcomer::all();
                    break;
                case Email::NEWCOMERS_ALL_TC:
                    $newcomers = Newcomer::where('branch', 'TC')->get();
                    break;
                case Email::NEWCOMERS_ALL_BRANCH:
                    $newcomers = Newcomer::where('branch', '<>', 'TC')->where('branch', '<>', 'MP')->get();
                    break;
                case Email::NEWCOMERS_ALL_MASTER:
                    $newcomers = Newcomer::where('branch', 'MP')->get();
                    break;
                case Email::NEWCOMERS_FILLED:
                    $newcomers = Newcomer::where('email', '<>', '')->whereNotNull('email')->get();
                    break;
                case Email::NEWCOMERS_FILLED_TC:
                    $newcomers = Newcomer::where('branch', 'TC')->where('email', '<>', '')->whereNotNull('email')->get();
                    break;
                case Email::NEWCOMERS_FILLED_BRANCH:
                    $newcomers = Newcomer::where('branch', '<>', 'TC')->where('branch', '<>', 'MP')->where('email', '<>', '')->whereNotNull('email')->get();
                    break;
                case Email::NEWCOMERS_FILLED_MASTER:
                    $newcomers = Newcomer::where('branch', 'MP')->where('email', '<>', '')->whereNotNull('email')->get();
                    break;
                default:
                    echo 'Error : Unknown email list id';
                    break;
            }

            // Select email to put in the list
            foreach ($students as $student) {
                $list[$student->email] = [ 'name' => $student->first_name.' '.$student->last_name, 'user' => $student ];
            }
            foreach ($newcomers as $newcomer) {
                if (!empty($newcomer->email)) {
                    $list[$newcomer->email] = [ 'name' => $newcomer->first_name.' '.$newcomer->last_name, 'user' => $newcomer ];
                } elseif (!empty($newcomer->registration_email)) {
                    $list[$newcomer->registration_email] = [ 'name' => $newcomer->first_name.' '.$newcomer->last_name, 'user' => $newcomer ];
                }
            }

            // Set count in db
            $email->done = 0;
            $email->total = count($list);
            $email->save();

            // Send emails
            $delay = 0;
            foreach ($list as $dest => $val) {
                $this->currentUser = $val['user'];
                $view = $email->template;
                if ($email->is_plaintext) {
                    $view = preg_replace_callback('/{{([A-Z0-9_]+)}}/i', array($this, 'replaceCallback'), $view);
                    $view = nl2br(e($view));
                } else {
                    $view = preg_replace_callback('/{{([A-Z0-9_]+)}}/i', array($this, 'replaceCallback'), $view);
                }
                $job = new SendEmail($email, $view, $dest, $val['name']);
                $job->delay($delay);
                $this->dispatch($job);

                $delay += 5;
            }
        }
    }

    protected function replaceCallback($matches)
    {
        switch ($matches[1]) {
            case 'login': return $this->currentUser->login;
            case 'password': return Crypt::decrypt($this->currentUser->password);
            case 'first_name': return $this->currentUser->first_name;
            case 'last_name': return $this->currentUser->last_name;
            case 'branch': return $this->currentUser->branch;
            case 'level': return $this->currentUser->level;
            case 'fillotlist':
                $branchCount = [];
                foreach ($this->currentUser->newcomers as $newcomer) {
                    if (isset($branchCount[$newcomer->branch])) {
                        $branchCount[$newcomer->branch]++;
                    } else {
                        $branchCount[$newcomer->branch] = 1;
                    }
                }
                $out = '<ul>';
                foreach ($branchCount as $branch => $count) {
                    $out .= '<li>'.$count.' fillot'.(($count>1)?'s':'').' entrant en '.$branch.'</li>';
                }
                $out .= '</ul>';
                return $out;
        }
        return '';
    }
}
