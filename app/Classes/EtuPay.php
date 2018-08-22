<?php

namespace App\Classes;

use App\Models\Payment;
use Illuminate\Encryption\Encrypter;
use Config;

/**
 * EtuPay helper
 */
class EtuPay
{
    /*
     * Decrypt and do action according to crypted payload content send by a callback
     * @param $payload crypted payload given by a callback from EtuPay
     * @return Payment object
     */
    public static function readCallback($payload)
    {
        $crypt = new Encrypter(base64_decode(Config::get('services.etupay.key')), 'AES-256-CBC');
        $payload = json_decode($crypt->decrypt($payload));
        if ($payload && is_numeric($payload->service_data)) {
            $paymentId = $payload->service_data;
            $payment = Payment::findOrFail($paymentId);

            switch ($payload->step) {
                case 'INITIALISED':
                    $payment->state = 'returned';
                    break;
                case 'PAID':
                case 'AUTHORISATION':
                    $payment->state = 'paid';
                    break;
                case 'REFUSED':
                case 'CANCELED':
                    $payment->state = 'refused';
                    break;
                case 'REFUNDED':
                    $payment->state = 'refunded';
                    break;
            }
            $payment->informations = ['transaction_id' => $payload->transaction_id];
            $payment->save();

            if ($payment->user) {
                $payment->user->updateWei();
            }

            return $payment;
        }
        return null;
    }
}
