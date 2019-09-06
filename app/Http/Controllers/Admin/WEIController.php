<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checkin;
use App\Models\Newcomer;
use App\Models\User;
use App\Models\Team;
use \Carbon\Carbon;
use App\Models\WEIRegistration;
use App\Models\Payment;
use Redirect;
use Config;
use Illuminate\Support\Facades\Request;
use View;
use Auth;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\DB;
use EtuUTT;

/**
 * Handle registrations for the "WEI".
 */
class WEIController extends Controller
{

    public function adminTeamAssignation(\Illuminate\Http\Request $request)
    {
        if(Request::isMethod('POST'))
        {
            $count = 0;
            foreach ($request->request as $team_id => $bus_id)
            {
                $team = Team::find($team_id);
                if($team && !empty($bus_id))
                {
                    foreach ($team->newcomers->where('wei', 1) as $newcomers)
                    {
                        $newcomers->bus_id = $bus_id;
                        $newcomers->save();
                        $count++;
                    }

                    foreach ($team->ce->where('wei', 1) as $ce)
                    {
                        $ce->bus_id = $bus_id;
                        $ce->save();
                        $count++;
                    }
                }
            }
            $request->session()->flash('success', $count.' personnes mis à jour.');
        }
        $teams = Team::all();

        return view('dashboard.wei.bus-team-assign', compact('teams'));

    }

    public function adminBusList(Request $request)
    {
        $buses = User::where('wei', 1)->get()->groupBy('bus_id')->sort();
        // $buses->put(0,$buses->get(0)->merge($buses->get("")));
        // $buses->forget("");
        return view('dashboard.wei.bus-list', compact('buses'));
    }

    public function adminBusGenerateChecklist(Request $request)
    {
        $buses = User::where('wei', 1)->get()->groupBy('bus_id')->sort();
        // $buses->put(0,$buses->get(0)->merge($buses->get("")));
        // $buses->forget("");

        foreach ($buses as $bus_id=>$students)
        {
            if(!empty($bus_id) && $bus_id>0)
            {
                $bus_check = new Checkin([
                    'name'=> 'Bus #'.$bus_id.' checkin',
                    'prefilled'=> true
                ]);
                $bag_check = new Checkin([
                    'name'=> 'Bag #'.$bus_id.' checkin',
                    'prefilled'=> true
                ]);
                $bus_check->save();
                $bag_check->save();

                $bus_check->users()->attach($students->pluck('id')->toArray());
                $bag_check->users()->attach($students->pluck('id')->toArray());
            }
        }
        return redirect()->route('dashboard.checkin');
    }

    public function adminGraph()
    {
        $result = [
            'newcomers' => [

            ],
            'orga'      => [
                'wei'       => 0,
                'sandwitch' => 0,
                'guarantee' => 0,
            ],
            'ce'        => [
                'wei'       => 0,
                'sandwitch' => 0,
                'guarantee' => 0,
            ],
            'vieux'        => [
                'wei'       => 0,
                'sandwitch' => 0,
                'guarantee' => 0,
            ],
        ];
        $newscomers = User::newcomer()->with('weiPayment', 'sandwichPayment', 'guaranteePayment')
            ->get();

        $students = User::student()->with('weiPayment', 'sandwichPayment', 'guaranteePayment')
            ->get();

        foreach ($newscomers as $newcomer) {
            if (!isset($result['newcomers'][$newcomer->branch])) {
                $result['newcomers'][$newcomer->branch] = [];
                $result['newcomers'][$newcomer->branch]['wei'] = 0;
                $result['newcomers'][$newcomer->branch]['sandwitch'] = 0;
                $result['newcomers'][$newcomer->branch]['guarantee'] = 0;
            }

            if (isset($newcomer->weiPayment) && $newcomer->weiPayment->state == 'paid') {
                $result['newcomers'][$newcomer->branch]['wei'] += 1;
            }

            if (isset($newcomer->sandwichPayment) && $newcomer->sandwichPayment->state == 'paid') {
                $result['newcomers'][$newcomer->branch]['sandwitch'] += 1;
            }

            if (isset($newcomer->guaranteePayment) && $newcomer->guaranteePayment->state == 'paid') {
                $result['newcomers'][$newcomer->branch]['guarantee'] += 1;
            }
        }

        foreach ($students as $student) {
            $ret = &$result['vieux'];
            if ($student->ce && $student->team_accepted && $student->team_id) {
                $ret = &$result['ce'];
            } elseif ($student->orga) {
                $ret = &$result['orga'];
            }

            if (isset($student->weiPayment) && $student->weiPayment->state == 'paid') {
                $ret['wei'] += 1;
            }

            if (isset($student->sandwichPayment) && $student->sandwichPayment->state == 'paid') {
                $ret['sandwitch'] += 1;
            }

            if (isset($student->guaranteePayment) && $student->guaranteePayment->state == 'paid') {
                $ret['guarantee'] += 1;
            }
        }


        $graphPaid = Payment::select(DB::raw('DATE_FORMAT(created_at,\'%d-%m-%Y\') as day'), DB::raw('COUNT(id) as sum'))
            ->where('type', 'payment')
            ->where('state', 'paid')
            ->where('amount', '>', 325)
            ->orderBy('created_at')
            ->groupBy(DB::raw('DATE_FORMAT(created_at,\'%d-%m-%Y\')'))->get();

        $graphCaution = Payment::select(DB::raw('DATE_FORMAT(created_at,\'%d-%m-%Y\') as day'), DB::raw('COUNT(id) as sum'))
            ->where('type', 'guarantee')
            ->where('state', 'paid')
            ->orderBy('created_at')
            ->groupBy(DB::raw('DATE_FORMAT(created_at,\'%d-%m-%Y\')'))->get();

        //TODO NUL A JETER
        $graphFood = Payment::select(DB::raw('DATE_FORMAT(created_at,\'%d-%m-%Y\') as day'), DB::raw('COUNT(id) as sum'))
            ->where('type', 'payment')
            ->where(DB::raw('`amount`%500'), Config::get('services.wei.sandwichPrice'))
            ->where('state', 'paid')
            ->orderBy('created_at')
            ->groupBy(DB::raw('DATE_FORMAT(created_at,\'%d-%m-%Y\')'))->get();

        $sum = [
            'paid' => array_sum(array_column($graphPaid->toArray(), 'sum')),
            'caution' => array_sum(array_column($graphCaution->toArray(), 'sum')),
            'food'  => array_sum(array_column($graphFood->toArray(), 'sum'))
        ];

        return View::make('dashboard.wei.graph', [
            'graphPaid' => $graphPaid,
            'graphCaution' => $graphCaution,
            'graphFood' => $graphFood,
            'sum'       => $sum,
            'global'    => $result,
        ]);
    }

    /**
     *
     * @return Response
     */
    public function userSearch()
    {
        return View::make('dashboard.wei.search');
    }

    /**
     *
     * @return Response
     */
    public function userSearchSubmit()
    {
        $input = Request::only(['search']);
        $this->validate(Request::instance(), [
            'search' => 'max:60|min:2|required',
        ]);

        $words = explode(' ', $input['search']);

        // Find students
        $students = User::select(['id', 'student_id', 'first_name', 'last_name',
            'surname', 'is_newcomer', 'ce', 'volunteer', 'orga',
            'wei', 'wei_payment', 'sandwich_payment', 'guarantee_payment', 'wei_validated']);
        if (count($words) <= 1 && is_numeric($input['search'])) {
            $students = $students->where('id', $input['search']);
            $students = $students->orWhere('student_id', $input['search']);
        }
        foreach ($words as $word) {
            $students = $students->orWhere('first_name', 'like', '%'.$word.'%');
            $students = $students->orWhere('last_name', 'like', '%'.$word.'%');
            $students = $students->orWhere('email', 'like', '%'.$word.'%');
            $students = $students->orWhere('login', 'like', '%'.$word.'%');
        }

        // Union between newcomers and students
        $users = $students->get();

        return View::make('dashboard.wei.search', ['users' => $users, 'search' => $input['search']]);
    }


    /**
     *
     * @return Response
     */
    public function studentEdit($id)
    {
        $student = User::where('id',$id)->firstOrFail();
        $student->updateWei();

        $newcomerCount = User::where('is_newcomer', 1)->where('wei', 1)->count();

        //calculate price
        $price = Config::get('services.wei.price-other');
        $priceName = 'Ancien/Autre';
        if ($student->is_newcomer) {
            $price = Config::get('services.wei.price');
            $priceName = 'Nouveau';
        } elseif ($student->ce && $student->team_accepted && $student->team_id) {
            $price = Config::get('services.wei.price-ce');
            $priceName = 'Chef d\'équipe';
        } elseif ($student->orga) {
            $price = Config::get('services.wei.price-orga');
            $priceName = 'Orga';
        }

        // Calculate counts
        $weiCount = 1;
        $sandwichCount = 1;
        $guaranteeCount = 1;
        if ($student->sandwichPayment && in_array($student->sandwichPayment->state, ['paid'])) {
            $sandwichCount = 0;
        }
        if ($student->weiPayment && in_array($student->weiPayment->state, ['paid'])) {
            $weiCount = 0;
        }
        if ($student->guaranteePayment && in_array($student->guaranteePayment->state, ['paid'])) {
            $guaranteeCount = 0;
        }
        return View::make('dashboard.wei.edit-student', [
            'user' => $student,
            'price' => round($price/100,2),
            'priceName' => $priceName,
            'weiCount' => $weiCount,
            'sandwichCount' => $sandwichCount,
            'guaranteeCount' => $guaranteeCount,
            'newcomerCount' => $newcomerCount,
        ]);
    }
    /**
     *
     * @return Response
     */
    public function studentEditSubmit($id)
    {
        $student = User::where('id', $id)->firstOrFail();
        $student->updateWei();

        // Profil form
        $list = ['email', 'phone', 'parent_name', 'parent_phone', 'medical_allergies', 'medical_treatment', 'medical_note'];
        if ($student->is_newcomer && Request::has('email')) {
            $input = Request::only($list);
            $this->validate(Request::instance(), [
                'email' => 'email|required',
                'phone' => 'required',
                'parent_name' => 'required',
                'parent_phone' => 'required',
            ],
            [
                'phone.regex' => 'Le champ téléphone doit contenir un numéro de téléphone français valide.'
            ]);

            $student->update($input);

            $student->setCheck('profil_email', !empty($student->email));
            $student->setCheck('profil_phone', !empty($student->phone));
            $student->setCheck('profil_parent_name', !empty($student->parent_name));
            $student->setCheck('profil_parent_phone', !empty($student->parent_phone));

            $student->save();

            return Redirect(route('dashboard.wei.student.edit', ['id' => $student->id]))->withSuccess('Vos modifications ont été enregistrées.');
        }

        // WEI payment form
        if (Request::has(['wei', 'sandwich', 'wei-total'])) {
            $input = Request::only(['wei', 'sandwich', 'wei-total', 'mean', 'cash-number', 'cash-color', 'check-number', 'check-bank', 'check-name', 'check-write', 'card-write']);
            if(Auth::user()->isAdmin())
            {
                $rules = [
                    'wei' => 'required',
                    'sandwich' => 'required',
                    'wei-total' => 'required',
                    'mean' => 'required|in:card,check,cash,free',
                ];
            } else {
                $rules = [
                    'wei' => 'required',
                    'sandwich' => 'required',
                    'wei-total' => 'required',
                    'mean' => 'required|in:card,check,cash',
                ];
            }
            $informations = [];
            switch ($input['mean']) {
                case 'card':
                    $rules['card-write'] = 'accepted';
                    break;
                case 'cash':
                    $rules['cash-number'] = 'required';
                    $rules['cash-color'] = 'required';
                    $informations = Request::only(['cash-number', 'cash-color']);
                    break;
                case 'check':
                    $rules['check-number'] = 'required';
                    $rules['check-bank'] = 'required';
                    $rules['check-name'] = 'required';
                    $rules['check-write'] = 'accepted';
                    $informations = Request::only(['check-number', 'check-bank', 'check-name']);
                    break;
            }
            $this->validate(Request::instance(), $rules,
            [
                'mean.required' => 'Le champ Moyen de paiement est obligatoire.',
                'card-write.accepted' => 'Vous devez avoir écris le numéro indiqué derrière le ticket de CB',
                'check-write.accepted' => 'Vous devez avoir écris le numéro indiqué derrière le chèque',
                'check-number.required' => 'Le champ Numéro de chèque est obligatoire.',
                'check-bank.required' => 'Le champ Banque du chèque est obligatoire.',
                'check-name.required' => 'Le champ Émetteur du chèque est obligatoire.',
                'cash-number.required' => 'Le champ Numéro de caisse est obligatoire.',
                'cash-color.required' => 'Le champ Couleur de caisse est obligatoire.',
            ]);

            // Check errors
            $oldSandwich = (($student->sandwichPayment && in_array($student->sandwichPayment->state, ['paid']))?1:0);
            $oldWei = (($student->weiPayment && in_array($student->weiPayment->state, ['paid']))?1:0);
            $sandwich = ($input['sandwich'])?1:0;
            $wei = ($input['wei'])?1:0;

            if ($input['sandwich'] && $oldSandwich) {
                return Redirect::back()->withError('Vous ne pouvez pas prendre un deuxieme panier repas')->withInput();
            }
            if ($input['wei'] && $oldWei) {
                return Redirect::back()->withError('Vous ne pouvez pas prendre un deuxieme weekend d\'intégration')->withInput();
            }
            if (!$input['wei'] && !$oldWei && $input['sandwich']) {
                return Redirect::back()->withError('Vous ne pouvez pas prendre un panier repas sans prendre le weekend')->withInput();
            }

            //calculate price
            $price = Config::get('services.wei.price-other');
            if ($student->is_newcomer) {
                $price = Config::get('services.wei.price');
            }
            if ($student->ce && $student->team_accepted && $student->team_id) {
                $price = Config::get('services.wei.price-ce');
            } elseif ($student->orga) {
                $price = Config::get('services.wei.price-orga');
            }
            $price = intval($price);
            // Calculate amount
            $amount = ($sandwich * intval(Config::get('services.wei.sandwichPrice')) + $wei * $price);

            if ($amount/100 != $input['wei-total']) {
                return Redirect::back()->withError('Erreur interne sur le calcul des montants, contactez un administrateur')->withInput();
            }

            if($input['mean'] == 'free') {
                $amount = 0;
            }

            // Create payment
            $payment = new Payment([
                'type' => 'payment',
                'mean' => $input['mean'],
                'amount' => $amount,
                'state' => 'paid',
                'informations' => $informations
            ]);
            $payment->save();

            // Save paiement in user object
            $user = $student;
            if ($wei) {
                $user->wei_payment = $payment->id;
            }
            if ($sandwich) {
                $user->sandwich_payment = $payment->id;
            }
            $user->updateWei();
            $user->save();

            return Redirect(route('dashboard.wei.student.edit', ['id' => $student->id]))->withSuccess('Vos modifications ont été enregistrées.');
        }


        // Guarantee payment form
        if (Request::has(['guarantee', 'guarantee-total'])) {
            $input = Request::only(['guarantee', 'guarantee-total', 'check2-number', 'check2-bank', 'check2-name', 'check2-write']);
            $informations = Request::only(['check-number', 'check-bank', 'check-name']);
            $this->validate(Request::instance(), [
                'guarantee' => 'required',
                'guarantee-total' => 'required',
                'check2-number' => 'required',
                'check2-bank' => 'required',
                'check2-name' => 'required',
                'check2-write' => 'accepted',
            ],
            [
                'check2-write.accepted' => 'Vous devez avoir écris le numéro indiqué derrière le chèque',
                'check2-number.required' => 'Le champ Numéro de chèque est obligatoire.',
                'check2-bank.required' => 'Le champ Banque du chèque est obligatoire.',
                'check2-name.required' => 'Le champ Émetteur du chèque est obligatoire.',
            ]);

            // Check errors
            $oldGuarantee = (($student->guaranteePayment && in_array($student->guaranteePayment->state, ['paid']))?1:0);
            $guarantee = ($input['guarantee'])?1:0;

            if ($input['guarantee'] && $oldGuarantee) {
                return Redirect::back()->withError('Vous ne pouvez pas payer deux fois la caution')->withInput();
            }

            // Calculate amount
            $amount = ($guarantee * intval(Config::get('services.wei.guaranteePrice')));

            if ($amount/100 != $input['guarantee-total']) {
                return Redirect::back()->withError('Erreur interne sur le calcul des montants, contactez un administrateur')->withInput();
            }

            // Create payment
            $payment = new Payment([
                'type' => 'guarantee',
                'mean' => 'check',
                'amount' => $amount,
                'state' => 'paid',
                'informations' => $informations
            ]);
            $payment->save();

            // Save paiement in user object
            if ($guarantee) {
                $student->guarantee_payment = $payment->id;
            }
            $student->updateWei();
            $student->save();

            return Redirect(route('dashboard.wei.student.edit', ['id' => $student->id]))->withSuccess('Vos modifications ont été enregistrées.');
        }

        // Authorization form
        if (Request::has(['authorization1'])) {
            $input = Request::only(['authorization1', 'authorization2']);
            $this->validate(Request::instance(), [
                'authorization1' => 'accepted',
                'authorization2' => 'accepted',
            ],
            [
                'authorization1.accepted' => 'Vous devez récupérer l\'autorisation parentale',
                'authorization2.accepted' => 'Vous devez avoir écris le numéro indiqué derrière l\'autorisation parentale',
            ]);

            // Save paiement in user object
            $student->parent_authorization = true;
            $student->updateWei();
            $student->save();
            return Redirect(route('dashboard.wei.student.edit', ['id' => $student->id]))->withSuccess('Vos modifications ont été enregistrées.');
        }


        return Redirect(route('dashboard.wei.student.edit', ['id' => $student->id]))->withError('Formulaire non remplis !');
    }

    public function checkIn($id)
    {
        $input = Request::only(['sleeping_bag']);

        $user = User::findOrFail($id);
        /**
        $date = new \DateTime(config('services.wei.start'));
        if((new \DateTime('now')) > $date)
        {
            return Redirect(route('dashboard.wei.student.edit', ['id' => $user->id]))->withError('Impossible de checkin avant la date de début du wei !');
        }**/

        $user->checkin = true;
        $user->sleeping_bag = $input['sleeping_bag'] == "1";
        $user->save();

        // Gestion du repas
        if($user->sandwichPayment && in_array($user->sandwichPayment->state, ['paid']))
        {
            $checkin = Checkin::where('name','Repas WEI')->first();
            if(!$checkin) {
                $checkin = new Checkin([
                    'name' => 'Repas WEI',
                    'prefilled' => true,
                ]);
                $checkin->save();
            }

            $checkin->users()->attach($user->id);
        }

        // Generate bus checkin
        if ($user->bus_id) {
            $checkin = Checkin::where('name','Check-in bus ' . $user->bus_id)->first();
            if(!$checkin) {
                $checkin = new Checkin([
                    'name' => 'Check-in bus ' . $user->bus_id,
                    'prefilled' => true,
                ]);
                $checkin->save();
            }
    
            $checkin->users()->attach($user->id);
        }

        return Redirect(route('dashboard.wei.search'))->withSuccess('Le checkin a bien été enregistré.');
    }


    /**
     *
     * @return Response
     */
    public function list($filter = '')
    {
        // Find students
        $students = User::select(['*', DB::raw('(ce AND team_accepted) AS ce')])
        ->where('wei', 1)->with('weiPayment')->with('sandwichPayment')->with('guaranteePayment')->get();


        return View::make('dashboard.wei.list', ['users' => $students, 'filter' => $filter]);
    }

    /**
     * Display bus GPS Map
     */

    public function displayMap()
    {
        $buses = User::where('wei', 1)->whereNotNull('latitude')->whereNotNull('longitude')->orderBy('updated_at')->groupBy('bus_id')->get();

        $pts = [];
        foreach ($buses as $bus) {
            $pts [] = [
                'title' => 'Bus '.$bus->bus_id,
                'lat' => $bus->latitude,
                'lng' => $bus->longitude,
                ];
        }

        return view('dashboard.maps.index', compact('pts'));

    }
}
