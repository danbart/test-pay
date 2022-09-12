<?php

namespace App\Http\Controllers;

use Omnipay\Omnipay;

use Illuminate\Http\Request;

class TestOmnipay extends Controller
{
    //test
    public function test()
    {
        $gateway = Omnipay::create('Stripe');
        $gateway->setApiKey($_ENV['SECRET_APY_KEY_OMNIPAY']);

        $formData = array('number' => '4242424242424242', 'expiryMonth' => '6', 'expiryYear' => '2030', 'cvv' => '123');

        $response = $gateway->purchase(array('amount' => '11.00', 'currency' => 'USD', 'card' => $formData))->send();

        if ($response->isRedirect()) {
            // redirect to offsite payment gateway
            $response->redirect();
        } else if ($response->isSuccessful()) {
            // payment was successful: update database
            print_r($response);
        } else {
            // payment failed: display message to customer
            echo $response->getMessage();
        }
    }
}
