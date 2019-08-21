<?php

namespace App\Schedule;

use App\Models\User;
use App\Jobs\sendNotifications;
use Carbon\Carbon;

class NotificationScheduler
{

  public function __construct()
  { }

  /**
   * Look for unsend notifications that should have been sent
   *
   * @return void
   */
  public function sendPassedNotifications()
  {
    $crons = \App\Models\NotificationCron::where('send_date', '<=', Carbon::now())
      ->where('is_sent', false)
      ->get();

    if ($crons) {
      foreach ($crons as $cron) {
        $requestTargets = explode(', ', $cron->targets);
        $notificationTargets = User::whereHas('devices');
        if (!in_array("all", $requestTargets)) {
          $notificationTargets = $notificationTargets->where($requestTargets[0], '>', 0);
          for ($i = 1; $i < sizeof($requestTargets); $i++) {
            $notificationTargets = $notificationTargets->orWhere($requestTargets[$i], '>', 0);
          }
        }

        $targets = $notificationTargets->get();
        dispatch(new sendNotifications($targets, $cron->title, $cron->message));

        $cron->is_sent = true;
        $cron->save();
      }
    }
  }
}
