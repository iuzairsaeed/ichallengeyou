<?php

function btcAuth(){
    $resourceUrl = 'https://test.bitpay.com/tokens';

    $postData = json_encode([
       'id' => 'TfALHhgU5duM4PAtFWgNqNgYZkLhfwnf2Tj',
       'facade' => 'pos'
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
        'token' => '2Z1oKcdDjMDBgUFHMJDcUoVaoa5oc7iFaF7G7RbuYu98'
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