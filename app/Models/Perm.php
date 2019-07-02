<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\PermType;

class Perm extends Model
{
  /**
   * @var string
   */
  public $table = 'perms';

  public $fillable = ['start', 'end', 'description', 'place', 'nbr_permanenciers', 'free_join', 'perm_type_id'];

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
      'start_date' => 'required|date|date_format:Y-m-d|before_or_equal:end_date',
      'end_date'=> 'required|date|date_format:Y-m-d|after_or_equal:start_date',
      'start_hour'=> 'required|date_format:H:i',
      'end_hour'=> 'required|date_format:H:i',
      'description' => 'required|string',
      'place' => 'required|string',
      'nbr_permanenciers' => 'required|integer',
      'respos' => 'array',
      'respos.*' => 'exists:users,id',
      'permanenciers' => 'array',
      'permanenciers.*' => 'exists:users,id',
      'type' => 'exists:perm_types,id'
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
    return Perm::webStoreRules();
  }

  /**
   * Define constraints of the Model's attributes for store action
   *
   * @return array
   */
  public static function addRespoRules()
  {
    return [
      'uid' => 'required|min:36|max:36|exists:users,qrcode'
    ];
  }

  /**
   * The respo of the PermType.
   */
  public function respos()
  {
    return $this->belongsToMany(User::class, 'perm_users', 'perm_id', 'user_id')->wherePivot('respo', true);
  }

  /**
   * Define constraints of the Model's attributes for store action
   *
   * @return array
   */
  public static function addPermanencierRules()
  {
    return [
      'uid' => 'required|min:36|max:36|exists:users,qrcode'
    ];
  }

  /**
   * The respos of the Perm.
   */
  public function permanenciers()
  {
    return $this->belongsToMany(User::class, 'perm_users', 'perm_id', 'user_id')
      ->wherePivot('respo', false)
      ->withPivot('presence')
      ->withPivot('pointsPenalty')
      ->withPivot('commentary')
      ->withPivot('absence_reason');
  }

  /**
   * Define the One-to-Many relation with PermType;
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function type()
  {
      return $this->belongsTo(PermType::class, 'perm_type_id', 'id');
  }
}
