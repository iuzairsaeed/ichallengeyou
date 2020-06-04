<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionCollection extends JsonResource
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
            "id" => $this->id,
            "user_id" => $this->user_id,
            "amount" => $this->amount,
            "type" => $this->type,
            "month" => $this->month,
            "date" => $this->date,
            "year" => $this->year,
            "challenge" => [
                "challenge_id" => $this->challenge->id ?? "",
                "title" => $this->challenge->title ?? "",
            ],
        ];
    }
}
