<?php

namespace App\Http\Controllers;

use App\Models\Newcomer;
use App\Models\Student;
use \Carbon\Carbon;
use App\Models\WEIRegistration;
use App\Models\Payment;
use Request;
use Redirect;
use Config;
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
        $newscomers = Student::NewcomersFilter()->with('weiPayment', 'sandwichPayment', 'guaranteePayment')
            ->get();

        $students = Student::with('weiPayment', 'sandwichPayment', 'guaranteePayment')
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

        $graphFood = Payment::select(DB::raw('DATE_FORMAT(created_at,\'%d-%m-%Y\') as day'), DB::raw('COUNT(id) as sum'))
            ->where('type', 'payment')
            ->where(DB::raw('`amount`%500'), 325)
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
    public function newcomersHome()
    {
        $sandwich = ((Auth::user()->sandwichPayment && in_array(Auth::user()->sandwichPayment->state, ['paid', 'returned', 'refunded']))?1:0);
        $wei = ((Auth::user()->weiPayment && in_array(Auth::user()->weiPayment->state, ['paid', 'returned', 'refunded']))?1:0);
        $guarantee = ((Auth::user()->guaranteePayment && in_array(Auth::user()->guaranteePayment->state, ['paid', 'returned', 'refunded']))?1:0);
        $underage = (Auth::user()->birth->add(new \DateInterval('P18Y')) >= (new \DateTime(Config::get('services.wei.start'))));
        $count = Student::NewcomersFilter()->where('wei', 1)->count();

        Auth::user()->updateWei();

        return View::make('newcomer.wei.home', [
            'sandwich' => $sandwich,
            'wei' => $wei,
            'guarantee' => $guarantee,
            'underage' => $underage,
            'count' => $count,
        ]);
    }

    /**
     *
     * @return Response
     */
    public function newcomersPay()
    {
        $weiCount = 1;
        $sandwichCount = 1;
        if (Auth::user()->sandwichPayment && in_array(Auth::user()->sandwichPayment->state, ['paid', 'refunded'])) {
            $sandwichCount = 0;
        }
        if (Auth::user()->weiPayment && in_array(Auth::user()->weiPayment->state, ['paid', 'refunded'])) {
            $weiCount = 0;
        }
        if ($weiCount == 0 && $sandwichCount == 0) {
            return Redirect(route('newcomer.wei.guarantee'))->withSuccess('Vous avez déjà payé le week-end et le sandwich !');
        }
        return View::make('newcomer.wei.pay', ['weiCount' => $weiCount, 'sandwichCount' => $sandwichCount]);
    }

    /**
     *
     * @return Response
     */
    public function newcomersPaySubmit()
    {
        $input = Request::only(['wei', 'sandwich', 'cgv']);

        // Check errors
        $oldSandwich = ((Auth::user()->sandwichPayment && in_array(Auth::user()->sandwichPayment->state, ['paid', 'returned', 'refunded']))?1:0);
        $oldWei = ((Auth::user()->weiPayment && in_array(Auth::user()->weiPayment->state, ['paid', 'returned', 'refunded']))?1:0);
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
        if (!$input['cgv']) {
            return Redirect::back()->withError('Vous devez accepter les conditions générales de vente')->withInput();
        }

        // Calculate amount
        $amount = ($sandwich * Config::get('services.wei.sandwichPrice') + $wei * Config::get('services.wei.price'))*100;

        // Create payment
        $payment = new Payment([
            'type' => 'payment',
            'mean' => 'etupay',
            'amount' => $amount,
            'state' => 'started'
        ]);
        $payment->save();

        // Save paiement in user object
        $user = Auth::user();
        if ($wei) {
            $user->wei_payment = $payment->id;
        }
        if ($sandwich) {
            $user->sandwich_payment = $payment->id;
        }
        $user->save();

        // Calculate EtuPay Payload
        $crypt = new Encrypter(base64_decode(Config::get('services.etupay.key')), 'AES-256-CBC');
        $payload = $crypt->encrypt(json_encode([
            'type' => 'checkout',
            'amount' => $amount,
            'client_mail' => $user->email,
            'firstname' => $user->first_name,
            'lastname' => $user->last_name,
            'description' => 'Formulaire de paiement du weekend d\'intégration et du panier repas',
            'articles' => [
                ['name' => 'Week-end d\'intégration', 'price' => Config::get('services.wei.price')*100, 'quantity' => $wei],
                ['name' => 'Panier repas du vendredi midi', 'price' => Config::get('services.wei.sandwichPrice')*100, 'quantity' => $sandwich],
            ],
            'service_data' => $payment->id
        ]));
        return Redirect(Config::get('services.etupay.uri.initiate').'?service_id='.Config::get('services.etupay.id').'&payload='.$payload);
    }

    /**
     *
     * @return Response
     */
    public function newcomersGuarantee()
    {
        if (Auth::user()->guaranteePayment && in_array(Auth::user()->guaranteePayment->state, ['paid', 'returned', 'refunded'])) {
            return Redirect(route('newcomer.wei.authorization'))->withSuccess('Vous avez déjà donné votre caution !');
        }
        return View::make('newcomer.wei.guarantee');
    }


    /**
     *
     * @return Response
     */
    public function newcomersGuaranteeSubmit()
    {
        $input = Request::only(['guarantee', 'cgv']);
        // Check errors
        $oldGuarantee = ((Auth::user()->guaranteePayment && in_array(Auth::user()->guaranteePayment->state, ['paid', 'returned', 'refunded']))?1:0);
        $guarantee = ($input['guarantee'])?1:0;
        if ($input['guarantee'] && $oldGuarantee) {
            return Redirect::back()->withError('Vous ne pouvez pas payer deux fois la caution')->withInput();
        }
        if (!$input['cgv']) {
            return Redirect::back()->withError('Vous devez accepter les conditions générales de vente')->withInput();
        }
        // Calculate amount
        $amount = ($guarantee * Config::get('services.wei.guaranteePrice'))*100;
        // Create payment
        $payment = new Payment([
            'type' => 'guarantee',
            'mean' => 'etupay',
            'amount' => $amount,
            'state' => 'started'
        ]);
        $payment->save();
        // Save paiement in user object
        $user = Auth::user();
        if ($guarantee) {
            $user->guarantee_payment = $payment->id;
        }
        $user->save();

        // Calculate EtuPay Payload
        $crypt = new Encrypter(base64_decode(Config::get('services.etupay.key')), 'AES-256-CBC');
        $payload = $crypt->encrypt(json_encode([
            'type' => 'authorisation',
            'amount' => $amount,
            'client_mail' => $user->email,
            'firstname' => $user->first_name,
            'lastname' => $user->last_name,
            'description' => 'Formulaire de dépôt de la caution du weekend d\'intégration',
            'articles' => [
                ['name' => 'Caution du Week-end d\'intégration', 'price' => Config::get('services.wei.guaranteePrice')*100, 'quantity' => $guarantee],
            ],
            'service_data' => $payment->id
        ]));
        return Redirect(Config::get('services.etupay.uri.initiate').'?service_id='.Config::get('services.etupay.id').'&payload='.$payload);
    }

    /**
     *
     * @return Response
     */
    public function newcomersAuthorization()
    {
        $underage = (Auth::user()->birth->add(new \DateInterval('P18Y')) >= (new \DateTime(Config::get('services.wei.start'))));
        if (!$underage) {
            Auth::user()->setCheck('wei_authorization', true);
            Auth::user()->save();
            return Redirect(route('newcomer.wei'));
        }

        return View::make('newcomer.wei.authorization');
    }



    /**
     *
     * @return Response
     */
    public function etuHome()
    {
        $sandwich = ((EtuUTT::student()->sandwichPayment && in_array(EtuUTT::student()->sandwichPayment->state, ['paid', 'returned', 'refunded']))?1:0);
        $wei = ((EtuUTT::student()->weiPayment && in_array(EtuUTT::student()->weiPayment->state, ['paid', 'returned', 'refunded']))?1:0);
        $guarantee = ((EtuUTT::student()->guaranteePayment && in_array(EtuUTT::student()->guaranteePayment->state, ['paid', 'returned', 'refunded']))?1:0);
        $validated = (EtuUTT::student()->wei_validated?1:0);

        return View::make('dashboard.wei.home', [
            'sandwich' => $sandwich,
            'wei' => $wei,
            'guarantee' => $guarantee,
            'validated' => $validated,
        ]);
    }

    /**
     *
     * @return Response
     */
    public function etuPay()
    {
        $weiCount = 1;
        $sandwichCount = 1;
        if (EtuUTT::student()->sandwichPayment && in_array(EtuUTT::student()->sandwichPayment->state, ['paid', 'refunded'])) {
            $sandwichCount = 0;
        }
        if (EtuUTT::student()->weiPayment && in_array(EtuUTT::student()->weiPayment->state, ['paid', 'refunded'])) {
            $weiCount = 0;
        }
        if ($weiCount == 0 && $sandwichCount == 0) {
            return Redirect(route('dashboard.wei.guarantee'))->withSuccess('Vous avez déjà payé le week-end et le sandwich !');
        }

        //calculate price
        $price = Config::get('services.wei.price-other');
        $priceName = 'Ancien/Autre';
        if (EtuUTT::student()->ce && EtuUTT::student()->team_accepted && EtuUTT::student()->team_id) {
            $price = Config::get('services.wei.price-ce');
            $priceName = 'Chef d\'équipe';
        } elseif (EtuUTT::student()->orga) {
            $price = Config::get('services.wei.price-orga');
            $priceName = 'Orga';
        }

        return View::make('dashboard.wei.pay', ['weiCount' => $weiCount, 'sandwichCount' => $sandwichCount, 'weiPrice' => $price, 'weiPriceName' => $priceName]);
    }

    /**
     *
     * @return Response
     */
    public function etuPaySubmit()
    {
        $input = Request::only(['wei', 'sandwich', 'cgv']);

        // Check errors
        $oldSandwich = ((EtuUTT::student()->sandwichPayment && in_array(EtuUTT::student()->sandwichPayment->state, ['paid', 'returned', 'refunded']))?1:0);
        $oldWei = ((EtuUTT::student()->weiPayment && in_array(EtuUTT::student()->weiPayment->state, ['paid', 'returned', 'refunded']))?1:0);
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
        if (!$input['cgv']) {
            return Redirect::back()->withError('Vous devez accepter les conditions générales de vente')->withInput();
        }

        //calculate price
        $price = Config::get('services.wei.price-other');
        if (EtuUTT::student()->ce && EtuUTT::student()->team_accepted && EtuUTT::student()->team_id) {
            $price = Config::get('services.wei.price-ce');
        } elseif (EtuUTT::student()->orga) {
            $price = Config::get('services.wei.price-orga');
        }

        // Calculate amount
        $amount = ($sandwich * Config::get('services.wei.sandwichPrice') + $wei * $price)*100;

        // Create payment
        $payment = new Payment([
            'type' => 'payment',
            'mean' => 'etupay',
            'amount' => $amount,
            'state' => 'started'
        ]);
        $payment->save();

        // Save paiement in user object
        $user = EtuUTT::student();
        if ($wei) {
            $user->wei_payment = $payment->id;
        }
        if ($sandwich) {
            $user->sandwich_payment = $payment->id;
        }
        $user->save();

        // Calculate EtuPay Payload
        $crypt = new Encrypter(base64_decode(Config::get('services.etupay.key')), 'AES-256-CBC');
        $payload = $crypt->encrypt(json_encode([
            'type' => 'checkout',
            'amount' => $amount,
            'client_mail' => $user->email,
            'firstname' => $user->first_name,
            'lastname' => $user->last_name,
            'description' => 'Formulaire de paiement du weekend d\'intégration et du panier repas',
            'articles' => [
                ['name' => 'Week-end d\'intégration', 'price' => $price*100, 'quantity' => $wei],
                ['name' => 'Panier repas du vendredi midi', 'price' => Config::get('services.wei.sandwichPrice')*100, 'quantity' => $sandwich],
            ],
            'service_data' => $payment->id
        ]));
        return Redirect(Config::get('services.etupay.uri.initiate').'?service_id='.Config::get('services.etupay.id').'&payload='.$payload);
    }

    /**
     *
     * @return Response
     */
    public function etuGuarantee()
    {
        if (EtuUTT::student()->guaranteePayment && in_array(EtuUTT::student()->guaranteePayment->state, ['paid', 'returned', 'refunded'])) {
            return Redirect(route('dashboard.wei.authorization'))->withSuccess('Vous avez déjà donné votre caution !');
        }
        return View::make('dashboard.wei.guarantee');
    }


    /**
     *
     * @return Response
     */
    public function etuGuaranteeSubmit()
    {
        $input = Request::only(['guarantee', 'cgv']);

        // Check errors
        $oldGuarantee = ((EtuUTT::student()->guaranteePayment && in_array(EtuUTT::student()->guaranteePayment->state, ['paid', 'returned', 'refunded']))?1:0);
        $guarantee = ($input['guarantee'])?1:0;

        if ($input['guarantee'] && $oldGuarantee) {
            return Redirect::back()->withError('Vous ne pouvez pas payer deux fois la caution')->withInput();
        }
        if (!$input['cgv']) {
            return Redirect::back()->withError('Vous devez accepter les conditions générales de vente')->withInput();
        }

        // Calculate amount
        $amount = ($guarantee * Config::get('services.wei.guaranteePrice'))*100;

        // Create payment
        $payment = new Payment([
            'type' => 'guarantee',
            'mean' => 'etupay',
            'amount' => $amount,
            'state' => 'started'
        ]);
        $payment->save();

        // Save paiement in user object
        $user = EtuUTT::student();
        if ($guarantee) {
            $user->guarantee_payment = $payment->id;
        }
        $user->save();

        // Calculate EtuPay Payload
        $crypt = new Encrypter(base64_decode(Config::get('services.etupay.key')), 'AES-256-CBC');
        $payload = $crypt->encrypt(json_encode([
            'type' => 'authorisation',
            'amount' => $amount,
            'client_mail' => $user->email,
            'firstname' => $user->first_name,
            'lastname' => $user->last_name,
            'description' => 'Formulaire de dépôt de la caution du weekend d\'intégration',
            'articles' => [
                ['name' => 'Caution du Week-end d\'intégration', 'price' => Config::get('services.wei.guaranteePrice')*100, 'quantity' => $guarantee],
            ],
            'service_data' => $payment->id
        ]));
        return Redirect(Config::get('services.etupay.uri.initiate').'?service_id='.Config::get('services.etupay.id').'&payload='.$payload);
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
        $students = Student::select([DB::raw('student_id AS id'), 'first_name', 'last_name', 'surname', DB::raw('1 AS student'),
        DB::raw('(ce AND team_accepted) AS ce'), DB::raw('volunteer AS volunteer'), DB::raw('orga AS orga'),
        'wei', 'wei_payment', 'sandwich_payment', 'guarantee_payment', 'wei_validated']);
        if (count($words) <= 1 && is_numeric($input['search'])) {
            $students = $students->where('student_id', $input['search']);
        }
        foreach ($words as $word) {
            $students = $students->orWhere('first_name', 'like', '%'.$word.'%');
            $students = $students->orWhere('last_name', 'like', '%'.$word.'%');
            $students = $students->orWhere('surname', 'like', '%'.$word.'%');
            $students = $students->orWhere('email', 'like', '%'.$word.'%');
        }

        // Find newcomers
        $newcomer = Student::NewcomersFilter()->select(['id', 'first_name', 'last_name', DB::raw('"" AS surname'), DB::raw('0 AS student'),
        DB::raw('0 AS ce'), DB::raw('0 AS volunteer'), DB::raw('0 AS orga'),
        'wei', 'wei_payment', 'sandwich_payment', 'guarantee_payment', DB::raw('1 AS wei_validated')]);
        if (count($words) <= 1 && is_numeric($input['search'])) {
            $newcomer = $newcomer->where('id', $input['search']);
        }
        foreach ($words as $word) {
            $newcomer = $newcomer->orWhere('first_name', 'like', '%'.$word.'%');
            $newcomer = $newcomer->orWhere('last_name', 'like', '%'.$word.'%');
            $newcomer = $newcomer->orWhere('email', 'like', '%'.$word.'%');
            $newcomer = $newcomer->orWhere('login', 'like', '%'.$word.'%');
        }

        // Union between newcomers and students
        $users = $students->union($newcomer)->get();

        return View::make('dashboard.wei.search', ['users' => $users, 'search' => $input['search']]);
    }


    /**
     *
     * @return Response
     */
    public function studentEdit($id)
    {
        $student = Student::findOrFail($id);

        //calculate price
        $price = Config::get('services.wei.price-other');
        $priceName = 'Ancien/Autre';
        if ($student->ce && $student->team_accepted && $student->team_id) {
            $price = Config::get('services.wei.price-ce');
            $priceName = 'Chef d\'équipe';
        } elseif ($student->orga) {
            $price = Config::get('services.wei.price-orga');
            $priceName = 'Orga';
        }

        // Calculate count
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
        return View::make('dashboard.wei.edit-student', ['user' => $student, 'price' => $price, 'priceName' => $priceName, 'weiCount' => $weiCount, 'sandwichCount' => $sandwichCount, 'guaranteeCount' => $guaranteeCount]);
    }


    /**
     *
     * @return Response
     */
    public function newcomerEdit($id)
    {
        $newcomer =Student::NewcomersFilter()->findOrFail($id);
        $newcomer->updateWei();

        $underage = ($newcomer->birth->add(new \DateInterval('P18Y')) >= (new \DateTime(Config::get('services.wei.start'))));
        $count = Student::NewcomersFilter()->where('wei', 1)->count();

        // Calculate count
        $weiCount = 1;
        $sandwichCount = 1;
        $guaranteeCount = 1;
        if ($newcomer->sandwichPayment && in_array($newcomer->sandwichPayment->state, ['paid'])) {
            $sandwichCount = 0;
        }
        if ($newcomer->weiPayment && in_array($newcomer->weiPayment->state, ['paid'])) {
            $weiCount = 0;
        }
        if ($newcomer->guaranteePayment && in_array($newcomer->guaranteePayment->state, ['paid'])) {
            $guaranteeCount = 0;
        }
        return View::make('dashboard.wei.edit-newcomer', ['user' => $newcomer, 'weiCount' => $weiCount, 'sandwichCount' => $sandwichCount, 'guaranteeCount' => $guaranteeCount, 'underage' => $underage, 'count' => $count]);
    }


    /**
     *
     * @return Response
     */
    public function newcomerEditSubmit($id)
    {
        $newcomer = Student::NewcomersFilter()->findOrFail($id);
        $newcomer->updateWei();

        $underage = ($newcomer->birth->add(new \DateInterval('P18Y')) >= (new \DateTime(Config::get('services.wei.start'))));

        // Profil form
        $list = ['email', 'phone', 'parent_name', 'parent_phone', 'medical_allergies', 'medical_treatment', 'medical_note'];
        if (Request::has('fullName')) {
            $input = Request::only($list);
            $this->validate(Request::instance(), [
                'email' => 'email|required',
                'phone' => [
                    'regex:/^(?:0([0-9])|(?:00|\+)33[\. -]?([0-9]))[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?$/',
                    'required',
                ],
                'parent_name' => 'required',
                'parent_phone' => 'required',
            ],
            [
                'phone.regex' => 'Le champ téléphone doit contenir un numéro de téléphone français valide.'
            ]);

            $newcomer->update($input);

            if (preg_match('/^(?:0([0-9])|(?:00|\+)33[\. -]?([0-9]))[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?$/', Request::get('phone'), $m)) {
                $newcomer->phone = '0'.$m[1].$m[2].'.'.$m[3].'.'.$m[4].'.'.$m[5].'.'.$m[6];
            } elseif (Request::get('phone') == '') {
                $newcomer->phone = '';
            }

            $newcomer->setCheck('profil_email', !empty($newcomer->email));
            $newcomer->setCheck('profil_phone', !empty($newcomer->phone));
            $newcomer->setCheck('profil_parent_name', !empty($newcomer->parent_name));
            $newcomer->setCheck('profil_parent_phone', !empty($newcomer->parent_phone));

            $newcomer->save();

            return Redirect(route('dashboard.wei.newcomer.edit', ['id' => $newcomer->id]))->withSuccess('Vos modifications ont été enregistrées.');
        }

        // WEI payment form
        if (Request::has(['wei', 'sandwich', 'wei-total'])) {
            $input = Request::only(['wei', 'sandwich', 'wei-total', 'mean', 'cash-number', 'cash-color', 'check-number', 'check-bank', 'check-name', 'check-write', 'card-write']);
            $rules = [
                'wei' => 'required',
                'sandwich' => 'required',
                'wei-total' => 'required',
                'mean' => 'required|in:card,check,cash',
            ];
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
            $oldSandwich = (($newcomer->sandwichPayment && in_array($newcomer->sandwichPayment->state, ['paid']))?1:0);
            $oldWei = (($newcomer->weiPayment && in_array($newcomer->weiPayment->state, ['paid']))?1:0);
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

            // Calculate amount
            $amount = ($sandwich * Config::get('services.wei.sandwichPrice') + $wei * Config::get('services.wei.price'))*100;

            if ($amount/100 != $input['wei-total']) {
                return Redirect::back()->withError('Erreur interne sur le calcul des montants, contactez un administrateur')->withInput();
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
            $user = $newcomer;
            if ($wei) {
                $user->wei_payment = $payment->id;
            }
            if ($sandwich) {
                $user->sandwich_payment = $payment->id;
            }
            $user->updateWei();
            $user->save();


            return Redirect(route('dashboard.wei.newcomer.edit', ['id' => $newcomer->id]))->withSuccess('Vos modifications ont été enregistrées.');
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
            $oldGuarantee = (($newcomer->guaranteePayment && in_array($newcomer->guaranteePayment->state, ['paid']))?1:0);
            $guarantee = ($input['guarantee'])?1:0;

            if ($input['guarantee'] && $oldGuarantee) {
                return Redirect::back()->withError('Vous ne pouvez pas payer deux fois la caution')->withInput();
            }

            // Calculate amount
            $amount = ($guarantee * Config::get('services.wei.guaranteePrice'))*100;

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
                $newcomer->guarantee_payment = $payment->id;
            }
            $newcomer->updateWei();
            $newcomer->save();

            return Redirect(route('dashboard.wei.newcomer.edit', ['id' => $newcomer->id]))->withSuccess('Vos modifications ont été enregistrées.');
        }

        // Else : Authorization form
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
        $newcomer->parent_authorization = true;
        $newcomer->updateWei();
        $newcomer->save();


        return Redirect(route('dashboard.wei.newcomer.edit', ['id' => $newcomer->id]))->withSuccess('Vos modifications ont été enregistrées.');
    }

    public function checkIn($type, $id)
    {
        if($type == "newcomers")
        {
            $user = Student::NewcomersFilter()->findOrFail($id);
        }elseif($type == "students")
        {
            $user = Student::findOrFail($id);
        }else abort(404, 'Inconnu type');

        $user->checkin = true;
        $user->save();

        return \Illuminate\Support\Facades\Redirect::back();
    }

    /**
     *
     * @return Response
     */
    public function studentEditSubmit($id)
    {
        $student = Student::findOrFail($id);

        // WEI payment form
        if (Request::has(['wei', 'sandwich', 'wei-total'])) {
            $input = Request::only(['wei', 'sandwich', 'wei-total', 'mean', 'cash-number', 'cash-color', 'check-number', 'check-bank', 'check-name', 'check-write', 'card-write']);
            $rules = [
                'wei' => 'required',
                'sandwich' => 'required',
                'wei-total' => 'required',
                'mean' => 'required|in:card,check,cash',
            ];
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
            if ($student->ce && $student->team_accepted && $student->team_id) {
                $price = Config::get('services.wei.price-ce');
            } elseif ($student->orga) {
                $price = Config::get('services.wei.price-orga');
            }

            // Calculate amount
            $amount = ($sandwich * Config::get('services.wei.sandwichPrice') + $wei * $price)*100;

            if ($amount/100 != $input['wei-total']) {
                return Redirect::back()->withError('Erreur interne sur le calcul des montants, contactez un administrateur')->withInput();
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


            return Redirect(route('dashboard.wei.student.edit', ['id' => $student->student_id]))->withSuccess('Vos modifications ont été enregistrées.');
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
            $amount = ($guarantee * Config::get('services.wei.guaranteePrice'))*100;

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

            return Redirect(route('dashboard.wei.student.edit', ['id' => $student->student_id]))->withSuccess('Vos modifications ont été enregistrées.');
        }

        return Redirect(route('dashboard.wei.student.edit', ['id' => $student->student_id]))->withError('Y\'a un soucis !');
    }


    /**
     *
     * @return Response
     */
    public function list()
    {
        // Find students
        $students = Student::select([DB::raw('student_id AS id'), 'first_name', 'last_name', 'phone', DB::raw('1 AS student'), DB::raw('1 AS parent_authorization'),
        'wei_payment', 'sandwich_payment', 'guarantee_payment',
        DB::raw('(ce AND team_accepted) AS ce'), 'volunteer', 'orga', 'wei_validated'])
        ->where('wei', 1)->with('weiPayment')->with('sandwichPayment')->with('guaranteePayment');
        ;

        // Find newcomers
        $newcomer = Student::NewcomersFilter()->select(['id', 'first_name', 'last_name', 'phone', DB::raw('0 AS student'), 'parent_authorization',
        'wei_payment', 'sandwich_payment', 'guarantee_payment',
        DB::raw('0 AS ce'), DB::raw('0 AS volunteer'), DB::raw('0 AS orga'), DB::raw('1 AS wei_validated')])
        ->where('wei', 1)->with('weiPayment')->with('sandwichPayment')->with('guaranteePayment');

        // Union between newcomers and students
        $users = $students->union($newcomer)->orderBy('last_name')->get();

        return View::make('dashboard.wei.list', ['users' => $users]);
    }
}
