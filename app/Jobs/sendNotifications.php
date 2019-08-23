<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GuzzleHttp\Client;
use App\Models\NotificationCron;
use App\Models\User;

class sendNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $cronid = null;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($cronid)
    {
      $this->cronid = $cronid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $notification_cron = NotificationCron::find($this->cronid);
      if(!$notification_cron) return; //cron was removed
      if($notification_cron->is_sent) return; // just in case
      $notification_cron->is_sent = true;
      $notification_cron->save();
      $requestTargets = explode(', ', $notification_cron->targets);
      $notificationTargets = User::whereHas('devices');
      if (!in_array("all", $requestTargets)) {
        $notificationTargets = $notificationTargets->where($requestTargets[0], '>', 0);
        for ($i = 1; $i < sizeof($requestTargets); $i++) {
          $notificationTargets = $notificationTargets->orWhere($requestTargets[$i], '>', 0);
        }
      }
      $targets = $notificationTargets->get();
      $devices = [];
      foreach($targets as $target) {
        foreach($target->devices as $device) {
          array_push($devices, $device->push_token);
        }
      }
      $data = ['title' => $notification_cron->title, 'message' => $notification_cron->message];
      $notifs = [];
      foreach ($devices as $token) {
          array_push($notifs, [
            "to" => "ExponentPushToken[".$token."]",
            "title" => $notification_cron->title,
            "body" => $notification_cron->message,
            "data" => $data
          ]);
      }
      $client = new Client();
      while (count($notifs) > 0) {
        $toSend = array_splice($notifs, 0, 99);
        $client->post('https://exp.host/--/api/v2/push/send', [
          "body" => json_encode($toSend),
          "headers" => [
              'content-type' => 'application/json'
          ]
      ]);
      }
    }
}
