<?php

namespace App\Http\Controllers;

use App\Models\Newcomer;
use App\Models\Student;
use Request;
use View;
use Validator;
use Redirect;

/**
 *
 * @author  Thomas Chauchefoin <thomas@chauchefoin.fr>
 * @license MIT
 */
class NewcomersController extends Controller
{

    /**
     * Show the list of the newcomers.
     *
     * @return Response
     */
    public function list()
    {
        return View::make('dashboard.newcomers.list', [
            'newcomers' => Newcomer::all()
        ]);
    }

    /**
     * Create a newcomer.
     *
     * @return Response
     */
    public function create()
    {
        $this->validate(Request::instance(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'sex' => 'required|boolean',
            'birth' => 'date',
            'registration_email' => 'email',
            'branch' => 'required',
            'postal_code' => 'required|integer|min:0|max:99999',
            'country' => 'required',
        ]);

        $newcomer = Newcomer::create(Request::only([
            'first_name',
            'last_name',
            'sex',
            'birth',
            'registration_email',
            'registration_cellphone',
            'registration_phone',
            'postal_code',
            'country',
            'branch',
            'ine',
        ]));

        if ($newcomer->save()) {
            return $this->success('L\'utilisateur a été créé !');
        }
        return $this->error('Impossible de créer l\'utilisateur !');
    }

    /**
     * Create a newcomer.
     *
     * @return Response
     */
    public function createcsv()
    {
        // Parse CSV
        $result = [];
        $temp = tmpfile();
        fwrite($temp, Request::get('csv'));
        fseek($temp, 0);
        $i = 0;
        while (($data = fgetcsv($temp, 0, ";")) !== false) {
            if ($data === false) {
                return Redirect::back()
                            ->withErrors('Erreur de lecture à la ligne '.($i+1))
                            ->withInput();
            } elseif (count($data) != 11) {
                return Redirect::back()
                            ->withErrors('La ligne '.($i+1).' comporte '.count($data).' champ au lieu de 11 séparés par des ;')
                            ->withInput();
            }
            $line = [
                'first_name' => $data[0],
                'last_name' => $data[1],
                'sex' => $data[2],
                'birth' => $data[3],
                'branch' => $data[4],
                'registration_email' => $data[5],
                'registration_cellphone' => $data[6],
                'registration_phone' => $data[7],
                'postal_code' => $data[8],
                'country' => $data[9],
                'ine' => $data[10],
            ];

            // Validate
            $validator = Validator::make($line, [
                'first_name' => 'required',
                'last_name' => 'required',
                'sex' => 'required|in:M,F,m,f',
                'birth' => 'date',
                'registration_email' => 'email',
                'branch' => 'required'
            ],
            [
                'sex.in' => 'Le champ sex doit valoir seulement M ou F'
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                $errors->add('form', 'Les erreurs ont été trouvés à la ligne '.($i+1));
                return Redirect::back()
                            ->withErrors($errors)
                            ->withInput();
            }

            // Transform fields and save it to the main array
            $line['sex'] = (strtolower($line['sex']) == 'f')?1:0;
            $result[] = $line;
            $i++;
        }
        fclose($temp);

        // Save array to db
        foreach ($result as $value) {
            if (!Newcomer::create($value)) {
                return $this->error('Impossible de créer tous les nouveaux !');
            };
        }
        return $this->success('Les nouveaux ont été créé !');
    }

    /**
     * Display one or multiple newcomer's letter
     *
     * @param  int $id
     * @param  int $limit
     * @param  string $category
     * @return Response
     */
    public function letter($id, $limit = null, $category = null)
    {
        if ($limit === null) {
            $newcomers = [Newcomer::findOrFail($id)];
        } elseif ($limit && $category) {
            if($category == "TC")
                $newcomers = Newcomer::where('branch', '=', 'TC')->offset($id)->limit($limit)->get();
            else
                $newcomers = Newcomer::where('branch', '!=', 'TC')->offset($id)->limit($limit)->get();

        } else {
            $newcomers = Newcomer::offset($id)->limit($limit)->get();
        }

        // Parse phone number and save it to db
        foreach ($newcomers as $newcomer) {
            if (isset($newcomer->referral->phone)) {
                if (preg_match('/^(?:0([0-9])|(?:00|\+)[0-9]{2,3}([0-9]))[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?$/', $newcomer->referral->phone, $m)
                        && $newcomer->referral->phone != '0'.$m[1].$m[2].'.'.$m[3].'.'.$m[4].'.'.$m[5].'.'.$m[6]) {
                    $referral = $newcomer->referral;
                    $referral->phone = '0'.$m[1].$m[2].'.'.$m[3].'.'.$m[4].'.'.$m[5].'.'.$m[6];
                    $referral->save();
                }
            }
        }

        return View::make('dashboard.newcomers.letter', [ 'newcomers' => $newcomers, 'i' => $id, 'count' => Newcomer::count() ]);
    }
}
