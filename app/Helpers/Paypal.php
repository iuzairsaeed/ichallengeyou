<?php

function paypalAuth(){

    $curl = curl_init();
    $clientId = config('global.PAYPAL_CLIENT_ID');
    $clientSecret = config('global.PAYPAL_CLIENT_SECRET');
    curl_setopt($curl, CURLOPT_USERPWD, $clientId . ":" . $clientSecret);
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.sandbox.paypal.com/v1/oauth2/token",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "grant_type=client_credentials",
    CURLOPT_HTTPHEADER => array(
        "Content-Type: application/x-www-form-urlencoded"
    ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $json = json_decode($response, true);
    return $json;
}

function paypalDetail($access_token,$pay_id)
{
    $curl = curl_init();
    $clientId = config('global.PAYPAL_CLIENT_ID');
    $clientSecret = config('global.PAYPAL_CLIENT_SECRET');
    curl_setopt($curl, CURLOPT_USERPWD, $clientId . ":" . $clientSecret);
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.sandbox.paypal.com/v1/payments/payment/".$pay_id,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_XOAUTH2_BEARER => $access_token,
    CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json",
        "Accept: application/json",
    ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    $json = json_decode($response, true);
    return $json;
}

function sendMoney($email,$amount,$token)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.sandbox.paypal.com/v1/payments/payouts",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => '{
    "sender_batch_header": {
        "email_subject": "You have a payment",
        "sender_batch_id": "batch-'.rand(9999999999999,13).'"
    },
    "items": [
        {
        "recipient_type": "EMAIL",
        "amount": {
            "value": "'.$amount.'",
            "currency": "'.config('global.CURRENCY').'"
        },
        "receiver": "'.$email.'",
        "note": "Payouts sample transaction",
        "sender_item_id": "item-2-1596118834446"
        }
    ]
    }',
    CURLOPT_HTTPHEADER => array(
        'accept: application/json',
        'authorization: Bearer '.$token,
        'content-type: application/json'
    ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return "cURL Error #:" . $err;
    } else {
        $json = json_decode($response, true);
        return $json;
    }
}


function checkPayment($pay_id,$token)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.sandbox.paypal.com/v1/payments/payouts/".$pay_id,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        'authorization: Bearer '.$token,
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return "cURL Error #:" . $err;
    } else {
        $json = json_decode($response, true);
        return $json;
    }
}