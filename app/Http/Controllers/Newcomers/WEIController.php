<?php

namespace App\Http\Controllers\Newcomers;

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
    /**
     *
     * @return Response
     */
    public function newcomersHome()
    {
        $sandwich = ((Auth::user()->sandwichPayment && in_array(Auth::user()->sandwichPayment->state, ['paid', 'refunded']))?1:0);
        $wei = ((Auth::user()->weiPayment && in_array(Auth::user()->weiPayment->state, ['paid', 'refunded']))?1:0);
        $guarantee = ((Auth::user()->guaranteePayment && in_array(Auth::user()->guaranteePayment->state, ['paid', 'refunded']))?1:0);
        $count = User::where('is_newcomer', 1)->where('wei', 1)->count();

        Auth::user()->updateWei();

        return View::make('Newcomers.WEI.home', [
            'sandwich' => $sandwich,
            'wei' => $wei,
            'guarantee' => $guarantee,
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
        return View::make('Newcomers.WEI.pay', ['weiCount' => $weiCount, 'sandwichCount' => $sandwichCount]);
    }

    /**
     *
     * @return Response
     */
    public function newcomersPaySubmit()
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

        // Calculate amount
        $amount = ($sandwich * intval(Config::get('services.wei.sandwichPrice')) + $wei * intval(Config::get('services.wei.price')));

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
                ['name' => 'Week-end d\'intégration', 'price' => intval(Config::get('services.wei.price')), 'quantity' => $wei],
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
    public function newcomersGuarantee()
    {
        if (Auth::user()->guaranteePayment && in_array(Auth::user()->guaranteePayment->state, ['paid', 'refunded'])) {
            return Redirect(route('newcomer.wei.authorization'))->withSuccess('Vous avez déjà donné votre caution !');
        }
        return View::make('Newcomers.WEI.guarantee');
    }


    /**
     *
     * @return Response
     */
    public function newcomersGuaranteeSubmit()
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
                ['name' => 'Caution du Week-end d\'intégration', 'price' => intval(Config::get('services.wei.guaranteePrice')), 'quantity' => $guarantee],
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
        if (!Auth::user()->isUnderage()) {
            Auth::user()->setCheck('wei_authorization', true);
            Auth::user()->save();
            return Redirect(route('newcomer.wei'));
        }

        return View::make('Newcomers.WEI.authorization');
    }
}
