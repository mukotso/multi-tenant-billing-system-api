<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MeterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'tenant' => $this->tenant->name,
            'tenant_id' => $this->tenant_id,
            'meter_type_id' => $this->meter_type_id,
            'meter_type' => $this->meterType->name,
            'timezone' => $this->timezone,
            'previous_reading' => $this->previous_reading,
            'current_reading' => $this->current_reading,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}