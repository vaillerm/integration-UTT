<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationCron;

use Request;
use Response;
use Validator;
use Redirect;
use Auth;

class NotificationController extends Controller
{
    /**
     * Get all the notification crons
     *
     * @return Response
     */
    public function index()
    {
        $notification_crons = NotificationCron::all();
        return view('dashboard.notifications.index', compact('notification_crons'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.notifications.create');
    }

    /**
     * Store a new notification crons
     *
     * @return Response
     */
    public function store()
    {
        // validate the request inputs
        $validator = Validator::make(Request::all(), NotificationCron::webStoreRules());
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        $targets = implode(', ', array_keys(Request::get('targets')));
        $user = Auth::user();
        $notification_cron = new NotificationCron();
        $notification_cron->title = Request::get('title');
        $notification_cron->message = Request::get('message');
        $notification_cron->send_date = new \DateTime($this->formatDate(Request::get('send_date'), Request::get('send_hour')));
        $notification_cron->targets = $targets;
        $notification_cron->createdBy()->associate($user);
        $notification_cron->is_sent = false;
        $notification_cron->save();
        return redirect()->route('dashboard.notifications');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $notification_cron = NotificationCron::find($id);
        return view('dashboard.notifications.edit', compact('notification_cron'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        // validate the request inputs
        $validator = Validator::make(Request::all(), NotificationCron::webUpdateRules($id));
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $notification_cron = NotificationCron::find($id);
        $targets = implode(', ', Request::get('targets'));
        $notification_cron->fill(array_merge(Request::all(), ['targets' => $targets]));
        $notification_cron->save();

        return redirect()->route('dashboard.notifications');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      NotificationCron::destroy($id);
        return redirect()->route('dashboard.notifications');
    }

    private function formatDate($date, $hour)
    {
        $date = implode('-', array_reverse(explode('-', $date)));
        return $date.' '.$hour;
    }

}
