<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class NotificationCron extends Model
{
  /**
   * @var string
   */
  public $table = 'notification_crons';

  public $fillable = ['title', 'message', 'targets', 'send_date', 'is_sent', 'created_by'];

  public $hidden = [
    'created_at',
    'updated_at',
  ];

  /**
   * Define constraints of the Model's attributes for store action
   * from web requests
   *
   * @return array
   */
  public static function webStoreRules()
  {
    return [
      'send_date' => 'required|date|date_format:Y-m-d',
      'send_hour'=> 'required|date_format:H:i',
      'title' => 'required|string',
      'message' => 'required|string',
      'targets' => 'required|array',
    ];
  }

  /**
   * Define constraints of the Model's attributes for update action
   * from web requests
   *
   * @return array
   */
  public static function webUpdateRules()
  {
    return NotificationCron::webStoreRules();
  }

  /**
   * Define the One-to-Many relation with User;
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function createdBy()
  {
      return $this->belongsTo(User::class, 'created_by', 'id');
  }
}
