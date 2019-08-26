<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Request;
use Response;
use Validator;
use Auth;

use App\Models\Perm;
use DateTime;

class PermController extends Controller
{
  public function show()
  {
    $result = [];
    foreach (Perm::all() as $perm) {
      $perm->type = $perm->type;
      unset($perm['perm_type_id']);
      $permanenciers = $this->removeFieldsFromArray($perm->permanenciers, $this->isAllowed($perm));
      if (!$this->isAllowed($perm)) {
        unset($perm->type['points']);
      }
      unset($perm['permanenciers']);
      $perm['permanenciers'] = $permanenciers;
      $respos = $this->removeFieldsFromArray($perm->respos);
      unset($perm['respos']);
      $perm['respos'] = $respos;
      array_push($result, $perm);
    }
    return Response::json($result);
  }

  public function adminshow()
  {
    $result = [];
    foreach (Perm::all() as $perm) {
      if ($this->isAllowed($perm)) {
        $perm->type = $perm->type;
        unset($perm['perm_type_id']);
        $permanenciers = $this->removeFieldsFromArray($perm->permanenciers, true);
        unset($perm['permanenciers']);
        $perm['permanenciers'] = $permanenciers;
        $respos = $this->removeFieldsFromArray($perm->respos);
        unset($perm['respos']);
        $perm['respos'] = $respos;
        array_push($result, $perm);
      }
    }
    return Response::json($result);
  }

  /*
    *   only get user's perms
    */
  public function index()
  {
    $user = Auth::guard('api')->user();
    $result = [];
    foreach ($user->perms as $perm) {
      $perm->type = $perm->type;
      unset($perm['perm_type_id']);
      $permanenciers = $this->removeFieldsFromArray($perm->permanenciers);
      unset($perm['permanenciers']);
      $perm['permanenciers'] = $permanenciers;
      $respos = $this->removeFieldsFromArray($perm->respos);
      unset($perm['respos']);
      $perm['respos'] = $respos;
      array_push($result, $perm);
      //unset($perm['pivot']);
    }
    return Response::json($result);
  }

  /*
    *   add user to perm
    */
  public function join($id)
  {
    $validator = Validator::make(Request::all(), Perm::apiJoinRules());
    if ($validator->fails()) {
      return Response::json(["errors" => $validator->errors()], 400);
    }
    $user = Auth::guard('api')->user(); // user that make the request
    $perm = Perm::find($id);
    $userId = Request::get('userId'); // user to add
    if ($user->is_newcomer) {
      return Response::json(["message" => "You are not allowed."], 403);
    }
    if ($user->id != $userId && !$this->isAllowed($perm)) {
      return Response::json(["message" => "You are not allowed."], 403);
    }
    if ($user->id == $userId && !$user->admin) {
      if ($perm->open == null) {
        return Response::json(["message" => "You can't join that perm."], 403);
      }
      // Cas d'une perm en préouverture
      $open = new DateTime();
      $open->setTimestamp($perm->open);
      $pre_open = new DateTime();
      $pre_open->setTimestamp($perm->pre_open);
      $start = new DateTime();
      $start->setTimestamp($perm->start);
      $now = new \DateTime('now');

      if ((!$this->isRequestFromUTT() && $open > $now) ||
            ($this->isRequestFromUTT() && $pre_open > $now)) {
        return Response::json(["message" => "Perm not open."], 403);
      }

      //Rejoindre une heure avant max
      $diff_h = $start->diff($now);
      if($diff_h < 1 || $start < $now)
      {
        return Response::json(["message" => "Too late dude ! (less than 1 hour left before start)."], 403);
      }
    }

    $perm->permanenciers()->attach($userId, ['respo' => false]);

    return Response::json('OK');
  }
  /*
    *   remove user from perm
    */
  public function leave($id)
  {
    $validator = Validator::make(Request::all(), Perm::apiJoinRules());
    if ($validator->fails()) {
      return Response::json(["errors" => $validator->errors()], 400);
    }
    $user = Auth::guard('api')->user(); // user that make the request
    $perm = Perm::find($id);
    $userId = Request::get('userId'); // user to add
    if ($user->is_newcomer) {
      return Response::json(["message" => "You are not allowed."], 403);
    }

    if ($user->id != $userId && !$this->isAllowed($perm)) {
      return Response::json(["message" => "You are not allowed."], 403);
    }
    if ($user->id == $userId && !$user->admin) {
      if ($perm->open == null) {
        return Response::json(["message" => "You can't leave that perm."], 403);
      }

      //Test temps de leave
      $start = new DateTime();
      $start->setTimestamp($perm->start);
      $now = new \DateTime('now');

      //Rejoindre une heure avant max
      $diff_h = $start->diff($now);
      if($diff_h < 48 || $start < $now)
      {
        return Response::json(["message" => "Too late for leave dude ! (less than 48 hour left before start)."], 403);
      }
    }
    $perm->permanenciers()->detach($userId);

    return Response::json('OK');
  }



  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @param  int  $userId
   * @return \Illuminate\Http\Response
   */
  public function userabsent($id, $userId)
  {
    // validate the request inputs
    $validator = Validator::make(Request::all(), ['absence_reason' => 'string', 'commentary' => 'string', 'pointsPenalty' => 'integer']);
    if ($validator->fails()) {
      return Redirect::back()->withErrors($validator);
    }
    $perm = Perm::find($id);
    $perm->permanenciers()->updateExistingPivot($userId, [
      'presence' => 'absent',
      'absence_reason' => Request::get('absence_reason'),
      'commentary' => Request::get('commentary'),
      'pointsPenalty' => Request::get('pointsPenalty') + $perm->type->points,
    ]);

    return Response::json('OK');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @param  int  $userId
   * @return \Illuminate\Http\Response
   */
  public function userpresent($id, $userId)
  {
    // validate the request inputs
    $validator = Validator::make(Request::all(), ['commentary' => 'string', 'pointsPenalty' => 'integer']);
    if ($validator->fails()) {
      return Redirect::back()->withErrors($validator);
    }
    $perm = Perm::find($id);
    $found = false;

    foreach ($perm->permanenciers as $student) {

      if($student->id == $userId) {
        $found = true;
        break;
      }
    }
    if(!$found) {
      $perm->permanenciers()->attach([$userId], ['respo' => false]);
    }
    $perm->permanenciers()->updateExistingPivot($userId, [
      'presence' => 'present',
      'absence_reason' => '',
      'commentary' => Request::get('commentary'),
      'pointsPenalty' => Request::get('pointsPenalty'),
    ]);

    return Response::json('OK');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return \Illuminate\Http\Response
   */
  public function userstore($id)
  {
    // validate the request inputs
    $validator = Validator::make(Request::all(), [
      'users' => 'array',
      'users.*' => 'exists:users,id',
    ]);
    if ($validator->fails()) {
      return Redirect::back()->withErrors($validator);
    }
    $perm = Perm::find($id);
    if($perm->nbr_permanenciers > count($perm->permanenciers())){
      $perm->permanenciers()->attach(Request::get('users'), ['respo' => false]);
      return Response::json('OK');
    } else {
      return Response::json(["message" => "Perm full"], 403);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @param  int  $userid
   * @return \Illuminate\Http\Response
   */
  public function userdestroy($id, $userId)
  {
    $perm = Perm::find($id);
    $perm->permanenciers()->detach($userId);
    return Response::json('OK');
  }

  private function removeFieldsFromArray($students, $withPivot = false)
  {
    $output = [];
    foreach ($students as $student) {
      array_push($output, $this->removeFields($student, $withPivot));
    }
    return $output;
  }

  private function removeFields($student, $withPivot = false)
  {
    $output = $student->toArray();
    $fields = [
      'id',
      'first_name',
      'last_name',
      'surname',
      'student_id'
    ];

    // Remove fields
    $filtered = array_diff(array_keys($output), $fields);
    foreach ($filtered as $value) {
      unset($output[$value]);
    }
    if ($withPivot) {
      $output['presence'] = $student->pivot->presence;
      $output['pointsPenalty'] = $student->pivot->pointsPenalty;
      $output['commentary'] = $student->pivot->commentary;
      $output['absence_reason'] = $student->pivot->absence_reason;
    }
    return $output;
  }

  private function isAllowed($perm)
  {
    $user = Auth::guard('api')->user(); // user that make the request
    $found = false;
    foreach ($perm->respos as $respo) {
      if ($respo->id == $user->id) {
        $found = true;
      }
    }
    return $user->admin || $found;
  }

  private function isRequestFromUTT()
  {
      $utt_subnets = explode(',', config('services.utt.wifi_subnet'));
      foreach($utt_subnets as $subnet)
      {
          if($this->ip_in_range(Request::ip(), $subnet))
          {
              return true;
          }
      }

      return false;
  }
  /**
 * Check if a given ip is in a network
 * @param  string $ip    IP to check in IPV4 format eg. 127.0.0.1
 * @param  string $range IP/CIDR netmask eg. 127.0.0.0/24, also 127.0.0.1 is accepted and /32 assumed
 * @return boolean true if the ip is in this range / false if not.
 */
  private function ip_in_range( $ip, $range ) {
    if ( strpos( $range, '/' ) == false ) {
      $range .= '/32';
    }
    // $range is in IP/CIDR format eg 127.0.0.1/24
    list( $range, $netmask ) = explode( '/', $range, 2 );
    $range_decimal = ip2long( $range );
    $ip_decimal = ip2long( $ip );
    $wildcard_decimal = pow( 2, ( 32 - $netmask ) ) - 1;
    $netmask_decimal = ~ $wildcard_decimal;
    return ( ( $ip_decimal & $netmask_decimal ) == ( $range_decimal & $netmask_decimal ) );
  }
}
