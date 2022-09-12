<?php

namespace App\Http\Controllers;

use Omnipay\Omnipay;

use Illuminate\Http\Request;

class TestApiController extends Controller
{
    //
    public function payment(Request $request)
    {
        $gateway = Omnipay::create('Cybersource');

        $gateway->initialize(array(
            'profileId' => $_ENV['PROFILE_ID'],
            'secretKey'   => $_ENV['SECRET_PUBLIC_KEY'],
            'accessKey' => $_ENV['SECRET_PRIVATE_KEY'],
            'testMode' => true, // Or false when you are ready for live transactions
        ));

        // $gateway->setProfileId($_ENV['PROFILE_ID']);
        // $gateway->setSecretKey($_ENV['SECRET_PUBLIC_KEY']);
        // $gateway->setAccessKey($_ENV['SECRET_PRIVATE_KEY']);

        $formData = array('number' =>  $request->input('number'), 'expiryMonth' =>  $request->input('expiryMonth'), 'expiryYear' => $request->input('expiryYear'), 'cvv' => $request->input('cardCv'));
        $formCard = array('amount' => $request->input('amount'), 'currency' => $request->input('currency'), 'card' => $formData);

        // $response = $gateway->authorize($formCard)->send();

        $response = $gateway->purchase(
            [
                'amount' => '10.00',
                'currency' => 'USD',
                'card' => [
                    'firstName' => 'test'
                ]
            ]
        )->send();

        // if ($response->isSuccessful()) {
        //     // payment was successful: update database
        //     echo "Transaction ID: " . $response->getTransactionReference();
        // } elseif ($response->isRedirect()) {
        //     // redirect to offsite payment gateway
        //     $response->redirect();
        // } else {
        //     // payment failed: display message to customer
        //     echo $response->getMessage();
        // }

        // $response = $gateway->purchase($formCard)->send();

        if ($response->isSuccessful()) {
            echo "Transaction was successful!\n";
        } else if ($response->isRedirect()) {
            $response->redirect();
        } else {
            // Payment failed
            echo $response->getMessage();
        }


        // if ($response->isRedirect()) {
        //     // redirect to offsite payment gateway
        //     $response->redirect();
        //     // return response()->json(['ok redirect:' => $response->isRedirect(), "message" => $response->getMessage()], 200);
        // } elseif ($response->isSuccessful()) {
        //     // payment was successful: update database
        //     print_r($response);
        //     // return response()->json(['ok' => $response->getMessage()], 200);
        // } else {
        //     // payment failed: display message to customer
        //     echo $response->getMessage();
        //     // return response()->json(['message' => $response->getMessage()], 200);
        // }
    }
}
