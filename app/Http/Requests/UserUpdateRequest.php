<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return  Gate::allows('user.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(User $user)
    {
        return [
            'id' => 'required|numeric',
            'name' => ['bail', 'required', 'min:5', 'max:50'],
            // 'user_email' => ['bail', 'required', 'min:10', 'max:50'],
        ];
    }
}
