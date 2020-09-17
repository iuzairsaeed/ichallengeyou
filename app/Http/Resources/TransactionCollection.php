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
            "amount" => $this->amount,
            "type" => $this->type,
            "reason" => $this->reason,
            "month" => $this->month,
            "day" => $this->day,
            "year" => $this->year,
            "challenge_id" => $this->challenge->id ?? 0,
            "title" => $this->challenge->title ?? "",
            "created_at" => $this->created_at->format('Y-m-d h:i A'),
        ];
    }
}
