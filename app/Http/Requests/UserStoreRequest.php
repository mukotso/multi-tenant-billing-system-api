<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

      return true;

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|min:5|max:20',

            'email' => [
                'bail',
                'required',
                'min:10',
                'max:50',
                'unique:users,email',
            ],
            'user_password' =>['bail', 'required', 'min:8', 'max:50'],
            'roles' => ['bail', 'required', 'min:1', 'max:50'],
        ];
    }
}
