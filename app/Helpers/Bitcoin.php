<?php

function btcInvoice($price){
   
   $curl = curl_init();

   curl_setopt_array($curl, array(
   CURLOPT_URL => "https://test.bitpay.com/invoices",
   CURLOPT_RETURNTRANSFER => true,
   CURLOPT_ENCODING => "",
   CURLOPT_MAXREDIRS => 10,
   CURLOPT_TIMEOUT => 0,
   CURLOPT_FOLLOWLOCATION => true,
   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
   CURLOPT_CUSTOMREQUEST => "POST",
   CURLOPT_POSTFIELDS => "token=2Z1oKcdDjMDBgUFHMJDcUoVaoa5oc7iFaF7G7RbuYu98&currency=".config('global.CURRENCY')."&price=".$price,
   CURLOPT_HTTPHEADER => array(
      "Content-Type: application/x-www-form-urlencoded",
      "x-accept-version: 2.0.0",
   ),
   ));

   $response = curl_exec($curl);
   curl_close($curl);
   $json = json_decode($response, true);
   return $json;
}

function btcInfo($request)
{
   $curl = curl_init();

   curl_setopt_array($curl, array(
   CURLOPT_URL => "https://test.bitpay.com/invoices/".$request->invoice_id,
   CURLOPT_RETURNTRANSFER => true,
   CURLOPT_ENCODING => "",
   CURLOPT_MAXREDIRS => 10,
   CURLOPT_TIMEOUT => 0,
   CURLOPT_FOLLOWLOCATION => true,
   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
   CURLOPT_CUSTOMREQUEST => "GET",
   CURLOPT_HTTPHEADER => array(
      "content-type: application/json",
      "x-accept-version: 2.0.0",
   ),
   ));

   $response = curl_exec($curl);

   curl_close($curl);
   $json = json_decode($response, true);
   return $json;
}