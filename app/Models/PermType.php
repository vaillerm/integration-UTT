<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

use App\Models\User;

class PermType extends Model
{
  /**
   * @var string
   */
  public $table = 'perm_types';

  public $fillable = ['name', 'description', 'points'];

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
      'name' => 'required|string|unique:perm_types,name',
      'description' => 'required|string',
      'points' => 'required|integer',
      'users' => 'required|array',
      'users.*' => 'exists:users,id'
    ];
  }

  /**
   * Define constraints of the Model's attributes for update action
   * from web requests
   *
   * @param string $permTypeId : the checkin to update
   * @return array
   */
  public static function webUpdateRules($permTypeId)
  {
    return [
      'name' => [
        'required',
        'string',
        Rule::unique('perm_types')->ignore($permTypeId)
      ],
      'description' => 'required|string',
      'points' => 'required|integer',
      'users' => 'required|array',
      'users.*' => 'exists:users,id'
    ];
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
    return $this->belongsToMany(User::class, 'perm_type_respos', 'perm_type_id', 'user_id');
  }
}
