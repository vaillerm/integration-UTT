<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Request;
use Response;
use Validator;
use Auth;

use App\Models\Perm;

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
      if ($perm->open < new \DateTime('now')) {
        return Response::json(["message" => "Perm not open."], 403);
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
        return Response::json(["message" => "You can't join that perm."], 403);
      }
      if ($perm->open < new \DateTime('now')) {
        return Response::json(["message" => "Perm not open."], 403);
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
    Request::flash();

    // validate the request inputs
    $validator = Validator::make(Request::all(), [
      'users' => 'array',
      'users.*' => 'exists:users,id',
    ]);
    if ($validator->fails()) {
      return Redirect::back()->withErrors($validator);
    }
    $perm = Perm::find($id);
    $perm->permanenciers()->attach(Request::get('users'), ['respo' => false]);
    return Response::json('OK');
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
}
