<?php

namespace App\Http\Resources\roles;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $role = [];
        $role['id'] = $this->id;
        $role['role_name'] = $this->name;
        return $role;
    }
}
