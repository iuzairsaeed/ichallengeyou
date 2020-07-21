<?php

function btcAuth(){

    $resourceUrl = 'https://test.bitpay.com/currencies';

    $curlCli = curl_init($resourceUrl);
    
    curl_setopt($curlCli, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curlCli, CURLOPT_HTTPHEADER, [
       'x-accept-version: 2.0.0',
       'Content-Type: application/json'
    ]);
    
    $result = curl_exec($curlCli);
    $resultData = json_decode($result, TRUE);
    curl_close($curlCli);
    
    echo $resultData;
}
