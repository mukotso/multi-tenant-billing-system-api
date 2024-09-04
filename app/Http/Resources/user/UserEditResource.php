<?php

namespace App\Http\Resources\user;

use App\Models\Branch;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Role;

class UserEditResource extends JsonResource
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
        $user['fullname'] = $this->name;
        $user['user_email'] = $this->email;


     $user['branches'] = [Branch::all()->pluck('name')];
     $user['roles'] = [Role::all()->pluck('name')];



       // dd( $user['roles']);
        return $user;
    }
}
