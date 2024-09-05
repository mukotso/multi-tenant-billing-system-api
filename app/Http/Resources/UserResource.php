<?php

namespace App\Http\Resources;

use App\Http\Resources\roles\BranchUserResource;
use App\Http\Resources\roles\RoleUserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Role;
use App\Http\Resources\user\ZoneUserResource;
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

         $user = [];
        $user['id'] = $this->id;
        $user['name'] = $this->name;
        $user['email'] = $this->email;
        // $user['roles'] = [RoleUserResource::collection($this->roles)]; 

        return $user;


    }

}

