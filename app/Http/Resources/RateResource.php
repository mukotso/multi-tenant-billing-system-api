<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
       
        $rate['rate'] = $this->name;
        $rate['minimum'] = $this->from;
        $rate['maximum'] = $this->to;
        $rate['status'] = $this->status== 1 ? 'active': 'inactive';
        $rate['amount'] = $this->cost;
        $rate['remark'] = $this->note ?? 'N/A';
        $rate['id'] = $this->id;
        return $rate;
    }
}
