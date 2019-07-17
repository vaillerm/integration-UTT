<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use View;
use EtuUTT;
use Mail;
use Request;
use Redirect;
use Response;
use Auth;
use DB;

/**
 * Handle student management pages and administrators actions
 */
class StudentsController extends Controller
{

    /**
     * REST API method: GET on Student model
     *
     * @return Response
     */
    public function index()
    {
        $user = $user = Auth::guard('api')->user();

        if (!$user->admin && !$user->secu) {
            return Response::json(["message" => "You are not allowed."], 403);
        }

        $query = DB::table('users');

        // apply query filters
        if (Request::all()) {
            foreach (Request::all() as $key => $value) {
                $query = $query->where($key, $value);
            }
        }

        // return all by default
        return Response::json($query->get());
    }

    /**
     * Return the students that match the name in the request
     *
     * @return Response
     */
    public function autocomplete()
    {
        if (!Request::has('name')) {
            return Response::json(array(["message" => "Missing name parameter."]), 400);
        }

        $parts = explode(' ', Request::get('name'));
        $query = DB::table('users');
        for ($i = 0; $i < sizeof($parts); $i++) {
            $query = $query
                ->where('first_name', 'like',  $parts[$i].'%')
                ->orWhere('last_name', 'like',  $parts[$i].'%');
        }

        return Response::json($query->get());
    }

    /**
     * Return the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::guard('api')->user();

        // if id is "0" in the route, it becomes the authenticated user's id
        if ($id == "0") {
            $id = $user->id;
        }

        $requested_user = User::where('id', $id)->with('team', 'godFather', 'team.faction')->first();

        // check if authorized to see this user
        return Response::json($this->removeUnauthorizedFields($requested_user));
    }

    /**
     * Update the given student
     *
     * @param string $id: the id of the student to update
     * @return Response
     */
    public function update($id)
    {
        $requester = Auth::guard('api')->user();

        // check if the requester is authorized to update this User
        if (!$requester->admin && $requester->id != $id) {
            return Response::json(["message" => "You are not allowed to update this User."], 403);
        }

        $student = User::find($id);
        if (!$student) {
            return Response::json(["message" => "Cant't find this Student."], 404);
        }

        $student->update(Request::all());

        return Response::json($student);
    }

    /**
     * List authorized fields for the API
     *
     * @param Student $student
     * @return Array field list
     */
    private function removeUnauthorizedFields($student, $is_godfather = false)
    {
        $user = Auth::guard('api')->user();
        $output = $student->toArray();


        $authorizedFields = [];
        if($user->isAdmin()) {
            return $output;
        }
        else if($user->id == $output['id']) {
            $authorizedFields = array_merge($authorizedFields,
            [
                'qrcode',
                'id',
                'first_name',
                'last_name',
                'surname',
                'student_id',
                'is_newcomer',
                'sex',
                'email',
                'phone',
                'postal_code',
                'team',
                'country',
                'postal_code',
                'city',
                'phone',
                'email',
                'student_id',
                'branch',
                'level',
                'admin',
                'orga',
                'volunteer',
                'secu',
                'ce',
                'wei_majority',
                'team_id',
                'god_father'
            ]);

            // filter godfather fields
            if ($student->godFather) {
                $output['god_father'] = $this->removeUnauthorizedFields($student->godFather, true);
            }

            // filter team fields
            if ($student->team) {
                $output['team'] = [
                    'id' => $student->team->id,
                    'name' => $student->team->name,
                    'description' => $student->team->description,
                    'img' => $student->team->img,
                    'facebook' => $student->team->facebook,
                    'faction' => null,
                ];
                if($student->team->faction) {
                    $output['team']['faction'] = [
                        'id' => $student->team->faction->id,
                        'name' => $student->team->faction->name,
                    ];
                }
            }
        }
        else {
            if($is_godfather){
                $authorizedFields = [
                    'id',
                    'first_name',
                    'last_name',
                    'surname',
                    'student_id',
                    'branch',
                    'level',
                    'is_newcomer',
                    'referral_text',
                    'city',
                    'country',
                    'email',
                    'phone'
                ];
            }
            else {
                $authorizedFields = [
                    'id',
                    'first_name',
                    'last_name',
                    'surname',
                    'student_id',
                    'branch',
                    'level',
                    'email',
                    'is_newcomer'
                ];
            }
            // For everyone else
            unset($output['god_father']);
            unset($output['team']);
        }

        // Remove unauthorzed fields
        $unauthorizedFields = array_diff(array_keys($output), $authorizedFields);
        foreach ($unauthorizedFields as $value) {
            unset($output[$value]);
        }

        return $output;
    }
}
