<?php

namespace App\Http\Controllers\All;

use App\Http\Controllers\Controller;
use Request;
use Auth;
use Response;
use App\Classes\EtuPay;

/**
 */
class EtupayController extends Controller
{

    /**
     * Called by user when he return from EtuPay
     * @return Response
     */
    public function etupayReturn()
    {
        $payment = EtuPay::readCallback(Request::get('payload'));
        $route = route('index');
        if (Auth::user()->isNewcomer()) {
            if ($payment->userWei) {
                if ($payment->state == 'refused') {
                    $route = route('newcomer.wei.pay');
                } else {
                    $route = route('newcomer.wei.guarantee');
                }
            } elseif ($payment->userSandwich) {
                if ($payment->state == 'refused') {
                    $route = route('newcomer.wei.pay');
                } else {
                    $route = route('newcomer.wei');
                }
            } elseif ($payment->userGuarantee) {
                if ($payment->state == 'refused') {
                    $route = route('newcomer.wei.guarantee');
                } else {
                    $route = route('newcomer.wei.authorization');
                }
            }
        }
        else {
            if ($payment->userWei) {
                if ($payment->state == 'refused') {
                    $route = route('dashboard.wei.pay');
                } else {
                    $route = route('dashboard.wei.guarantee');
                }
            } elseif ($payment->userSandwich) {
                if ($payment->state == 'refused') {
                    $route = route('dashboard.wei.pay');
                } else {
                    $route = route('dashboard.wei');
                }
            } elseif ($payment->userGuarantee) {
                if ($payment->state == 'refused') {
                    $route = route('dashboard.wei.guarantee');
                } else {
                    $route = route('dashboard.wei');
                }
            }
        }

        $message = false;
        $transaction = 'Le paiement';
        if ($payment->type == 'guarantee') {
            $transaction = 'La caution';
        }
        switch ($payment->state) {
            case 'paid':
                return Redirect($route)->withSuccess($transaction.' a été validé');
            case 'refused':
                return Redirect($route)->withError($transaction.' a été annulé');
            case 'returned':
                return Redirect($route)->withError($transaction.' a été envoyé');
            case 'refunded':
                return Redirect($route)->withSuccess($transaction.' a été remboursé');
            default:
                return Redirect($route);
        }
    }

    /**
     * Called directly by EtuPay
     * @return Response
     */
    public function etupayCallback()
    {
        $payment = EtuPay::readCallback(Request::get('payload'));
        if ($payment->user) {
            $payment->user->updateWei();
        }

        if ($payment) {
            return Response::make('OK', 200);
        } else {
            return Response::make('Payment not found', 500);
        }
    }
}
