<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class TenantStoreRequest extends FormRequest
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
                'unique:tenants,email',
            ],
            'password' =>['bail', 'required', 'min:8', 'max:50'],
            'role_id' => ['bail', 'required', 'exists:roles,id'],
            'customer_id' => ['bail', 'required', 'exists:customers,id'],
        ];
    }
}
