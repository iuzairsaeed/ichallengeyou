<?php

function btcAuth(){
    $resourceUrl = 'https://test.bitpay.com/tokens';

    $postData = json_encode([
       'id' => 'TfALHhgU5duM4PAtFWgNqNgYZkLhfwnf2Tj',
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
    'price' => 10,
    'currency' => 'EUR',
    'token' => 'F9rQvjRToyqGFbEy81kM4xFFAeGLScVZARBRBcvruVwm'
    ]);

    // dd($resourceUrl.$postData);

    $curlCli = curl_init($resourceUrl);

    curl_setopt($curlCli, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curlCli, CURLOPT_HTTPHEADER, [
    'x-accept-version: 2.0.0',
    'Content-Type: application/json',
    'x-identity: mhDKBwmTe4iXeArnSZXxdgsqe5ZQNXC2sp',
    'x-signature: 304502207b625ff22530a7aeb8f63d417039b83106e25ce4db1c68ad8dcffd90f35a04a9022100b73bf23116b3e767eef68615e089cd11594431c93ccd5cf9024fade2ff358530'
    ]);

    curl_setopt($curlCli, CURLOPT_POSTFIELDS, stripslashes($postData));

    $result = curl_exec($curlCli);
    curl_close($curlCli);

    echo $result;

}