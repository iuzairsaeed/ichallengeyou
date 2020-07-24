<?php

function btcAuth(){
    $resourceUrl = 'https://test.bitpay.com/tokens';

    $postData = json_encode([
    //    'id' => 'TfALHhgU5duM4PAtFWgNqNgYZkLhfwnf2Tj',
       'facade' => 'merchant'
    ]);
    
    $curlCli = curl_init($resourceUrl);
    
    curl_setopt($curlCli, CURLOPT_HTTPHEADER, [
       'x-accept-version: 2.0.0',
       'Content-Type: application/json'
    ]);
    
    curl_setopt($curlCli, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curlCli, CURLOPT_POSTFIELDS, stripslashes($postData));
    
    $result = curl_exec($curlCli);
    $resultData = json_decode($result, TRUE);
    curl_close($curlCli);
    
    echo $resultData;
}

function btcInvoice(){

    $resourceUrl = 'https://test.bitpay.com/invoices';

    $postData = json_encode([
    'currency' => 'EUR',
    'price' => 10,
    'orderId' => 'MerchantOrder123',
    'fullNotifications' => true,
    'extendedNotifications' => true,
    'transactionSpeed' => 'medium',
    'notificationURL' => 'https://yournotificationurl.com',
    'notificationEmail' => 'merchant@email.com',
    'redirectURL' => 'https://yourredirecturl.com',
    'buyer' => [
        'email' => 'fox.mulder@trustno.one',
        'name' => 'Fox Mulder',
        'phone' => '555-123-456',
        'address1' => '2630 Hegal Place',
        'address2' => 'Apt 42',
        'locality' => 'Alexandria',
        'region' => 'VA',
        'postalCode' => '23242',
        'country' => 'US',
        'notify' => true
    ],
    'posData' => 'tx1234',
    'itemDesc' => 'Item XYZ',
    'token' => '6oE1tYw1xMqUjM97dk57cwbda5sxbXTkUgg7UKpwkzEE'
    ]);

    $curlCli = curl_init($resourceUrl);

    curl_setopt($curlCli, CURLOPT_HTTPHEADER, [
    'x-accept-version: 2.0.0',
    'Content-Type: application/json'
    ]);

    curl_setopt($curlCli, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curlCli, CURLOPT_POSTFIELDS, stripslashes($postData));

    $result = curl_exec($curlCli);
    $resultData = json_decode($result, TRUE);
    curl_close($curlCli);

    echo $resultData;

    // $resourceUrl = 'https://test.bitpay.com/invoices';

    // $postData = json_encode([
    // 'currency' => 'USD',
    // 'price' => 10,
    // 'orderId' => 'MerchantOrder123',
    // 'fullNotifications' => true,
    // 'extendedNotifications' => true,
    // 'transactionSpeed' => 'medium',
    // 'notificationURL' => 'https://yournotificationurl.com',
    // 'notificationEmail' => 'merchant@email.com',
    // 'redirectURL' => 'https://yourredirecturl.com',
    // 'buyer' => [
    //     'email' => 'fox.mulder@trustno.one',
    //     'name' => 'Fox Mulder',
    //     'phone' => '555-123-456',
    //     'address1' => '2630 Hegal Place',
    //     'address2' => 'Apt 42',
    //     'locality' => 'Alexandria',
    //     'region' => 'VA',
    //     'postalCode' => '23242',
    //     'country' => 'US',
    //     'notify' => true
    // ],
    // 'posData' => 'tx1234',
    // 'itemDesc' => 'Item XYZ',
    // 'token' => '6oE1tYw1xMqUjM97dk57cwbda5sxbXTkUgg7UKpwkzEE'
    // ]);

    // $curlCli = curl_init($resourceUrl);

    // curl_setopt($curlCli, CURLOPT_CUSTOMREQUEST, 'POST');
    // curl_setopt($curlCli, CURLOPT_HTTPHEADER, [
    // 'x-accept-version: 2.0.0',
    // 'Content-Type: application/json',
    // ]);

    // curl_setopt($curlCli, CURLOPT_POSTFIELDS, stripslashes($postData));

    // $result = curl_exec($curlCli);
    // curl_close($curlCli);

    // echo $result;


    // $resourceUrl = 'https://test.bitpay.com/invoices';
    // $token = '7auwdduckFP7bvnrCwW1JvvEqoXGPcUsbFjqQnDHs7eD';

    // $status = 'complete';
    // $dateStart = '2020-1-24';
    // $dateEnd = '2020-1-28';
    // $limit = 2;
    // $offset = 0;

    // $curlCli = curl_init(
    // $resourceUrl .
    // '?token=' . $token .
    // '&status=' . $status .
    // '&dateStart=' . $dateStart .
    // '&dateEnd=' . $dateEnd .
    // '&limit=' . $limit .
    // '&offset=' . $offset);

    // curl_setopt($curlCli, CURLOPT_CUSTOMREQUEST, 'GET');
    // curl_setopt($curlCli, CURLOPT_HTTPHEADER, [
    // 'x-accept-version: 2.0.0',
    // 'Content-Type: application/json',
    // 'x-identity: tpubDDiaWP4LcQF9qcfAAig6A8KGes6tRjgkC4P3ShctqS288owuAgCSSaFqBXgbzxShgCcbwbLur5bkQBM5cw4PGDXio2VJEprtYkK2zo5H',
    // 'x-signature: xprv9s21ZrQH143K3huNi9f4LpnR4Um99b8STXxcvTgBGoCAKh2uzzpKHCbvE4iwnZxKVvWWbGzxZTPjM2PRziaVMAyKMfpb8XpDT1aV7U4Qnmm'
    // ]);

    // $result = curl_exec($curlCli);
    // $resultData = json_decode($result, TRUE);
    // curl_close($curlCli);

    // echo $resultData;
}