<?php

namespace App\Http\Controllers\Students;

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
use App\Http\Controllers\Controller;

/**
 * Handle registrations for the "WEI".
 */
class WEIController extends Controller
{
    /**
     *
     * @return Response
     */
    public function etuHome()
    {
        $sandwich = ((Auth::user()->sandwichPayment && in_array(Auth::user()->sandwichPayment->state, ['paid', 'refunded']))?1:0);
        $wei = ((Auth::user()->weiPayment && in_array(Auth::user()->weiPayment->state, ['paid', 'refunded']))?1:0);
        $guarantee = ((Auth::user()->guaranteePayment && in_array(Auth::user()->guaranteePayment->state, ['paid', 'refunded']))?1:0);
        $validated = (Auth::user()->wei_validated?1:0);
        $health = Auth::user()->parent_name && Auth::user()->parent_phone;

        return View::make('dashboard.wei.home', [
            'health' => $health,
            'sandwich' => $sandwich,
            'wei' => $wei,
            'guarantee' => $guarantee,
            'validated' => $validated,
        ]);
    }



    /**
     * Display the health profil edition form
     *
     * @return Response
     */
    public function healthForm()
    {
        return View::make('dashboard.wei.health');
    }

    /**
     * Submit the health profil form
     *
     * @return Response
     */
    public function healthFormSubmit()
    {
        $this->validate(Request::instance(), [
            'parent_name' => 'required',
            'parent_phone' => 'required',
        ]);

        $user = Auth::user();
        $user->update(Request::only([
            'parent_name',
            'parent_phone',
            'medical_allergies',
            'medical_treatment',
            'medical_note',
        ]));

        $user->save();

        return Redirect(route('dashboard.wei.pay'))->withSuccess('Vos modifications ont été enregistrées.');
    }


    /**
     *
     * @return Response
     */
    public function etuPay()
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
            return Redirect(route('dashboard.wei.guarantee'))->withSuccess('Vous avez déjà payé le week-end et le sandwich !');
        }

        //calculate price
        $price = Config::get('services.wei.price-other');
        $priceName = 'Ancien/Autre';
        if (Auth::user()->orga) {
            $price = Config::get('services.wei.price-orga');
            $priceName = 'Orga';
        }
        elseif (Auth::user()->ce && Auth::user()->team_accepted && Auth::user()->team_id) {
            $price = Config::get('services.wei.price-ce');
            $priceName = 'Chef d\'équipe';
        }

        return View::make('dashboard.wei.pay', ['weiCount' => $weiCount, 'sandwichCount' => $sandwichCount, 'weiPrice' => $price/100, 'weiPriceName' => $priceName]);
    }

    /**
     *
     * @return Response
     */
    public function etuPaySubmit()
    {
        $input = Request::only(['wei', 'sandwich', 'cgv']);

        // Check errors
        $oldSandwich = ((Auth::user()->sandwichPayment && in_array(Auth::user()->sandwichPayment->state, ['paid', 'refunded']))?1:0);
        $oldWei = ((Auth::user()->weiPayment && in_array(Auth::user()->weiPayment->state, ['paid', 'refunded']))?1:0);
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
        if (!isset($input['cgv'])) {
            return Redirect::back()->withError('Vous devez accepter les conditions générales de vente')->withInput();
        }

        //calculate price
        $price = Config::get('services.wei.price-other');
        if (Auth::user()->orga) {
            $price = Config::get('services.wei.price-orga');
        } elseif (Auth::user()->ce && Auth::user()->team_accepted && Auth::user()->team_id) {
            $price = Config::get('services.wei.price-ce');
        }

        $price = intval($price);
        // Calculate amount
        $amount = ($sandwich * intval(Config::get('services.wei.sandwichPrice')) + $wei * $price);

        if ($amount == 0) {
            return Redirect(route('dashboard.wei.guarantee'))->withSuccess('Wei payé !');
        }

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
                ['name' => 'Week-end d\'intégration', 'price' => $price, 'quantity' => $wei],
                ['name' => 'Panier repas du vendredi midi', 'price' => intval(Config::get('services.wei.sandwichPrice')), 'quantity' => $sandwich],
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
        if (Auth::user()->guaranteePayment && in_array(Auth::user()->guaranteePayment->state, ['paid', 'refunded'])) {
            return Redirect(route('dashboard.wei'))->withSuccess('Vous avez déjà donné votre caution !');
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
        $oldGuarantee = ((Auth::user()->guaranteePayment && in_array(Auth::user()->guaranteePayment->state, ['paid', 'refunded']))?1:0);
        $guarantee = ($input['guarantee'])?1:0;

        if ($input['guarantee'] && $oldGuarantee) {
            return Redirect::back()->withError('Vous ne pouvez pas payer deux fois la caution')->withInput();
        }
        if (!$input['cgv']) {
            return Redirect::back()->withError('Vous devez accepter les conditions générales de vente')->withInput();
        }

        // Calculate amount
        $amount = ($guarantee * intval(Config::get('services.wei.guaranteePrice')));

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
            'type' => 'checkout',
            'amount' => $amount,
            'client_mail' => $user->email,
            'firstname' => $user->first_name,
            'lastname' => $user->last_name,
            'description' => 'Formulaire de dépôt de la caution du weekend d\'intégration',
            'articles' => [
                ['name' => 'Caution du Week-end d\'intégration', 'price' => $amount, 'quantity' => $guarantee],
            ],
            'service_data' => $payment->id
        ]));
        return Redirect(Config::get('services.etupay.uri.initiate').'?service_id='.Config::get('services.etupay.id').'&payload='.$payload);
    }
}
