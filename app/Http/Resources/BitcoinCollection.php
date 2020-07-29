<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BitcoinCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'url' => $this['data']['url'],
            'price' => $this['data']['price'],
            'currency' => $this['data']['currency'],
            'id' => $this['data']['id'],
        ];
    }
}
