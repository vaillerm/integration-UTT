<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GuzzleHttp\Client;

class sendNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $targets = [];
    private $title = "";
    private $message = "";
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($targets, $title, $message)
    {
      $this->targets = $targets;
      $this->title = $title;
      $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

      $devices = [];
      foreach($this->targets as $target) {
        foreach($target->devices as $device) {
          array_push($devices, $device->push_token);
        }
      }
      $data = ['title' => $this->title, 'message' => $this->message];
        $notifs = [];
        foreach ($devices as $token) {
            array_push($notifs, [
              "to" => "ExponentPushToken[".$token."]",
              "title" => $this->title,
              "body" => $this->message,
              "data" => $data
            ]);
        }
        $client = new Client();
        $client->post('https://exp.host/--/api/v2/push/send', [
            "body" => json_encode($notifs),
            "headers" => [
                'content-type' => 'application/json'
            ]
        ]);
    }
}
