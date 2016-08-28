<?php

namespace App\Http\Controllers;

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
        $underage = (Auth::user()->birth >= (new \DateTime(Config::get('services.wei.start'))));

        Auth::user()->updateWei();

        return View::make('newcomer.wei.home', [
            'sandwich' => $sandwich,
            'wei' => $wei,
            'guarantee' => $guarantee,
            'underage' => $underage,
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
        $underage = (Auth::user()->birth >= (new \DateTime(Config::get('services.wei.start'))));
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
}
