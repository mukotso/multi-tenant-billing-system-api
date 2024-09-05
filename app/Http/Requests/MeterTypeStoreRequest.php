<?php

namespace App\Http\Requests;

use App\Models\MeterType;
use App\Rules\UniqueConditions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class MeterTypeStoreRequest extends FormRequest
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
            'code' => [
                'bail',
                'required',
                new UniqueConditions(MeterType::class, 'code')
            ],
            'format' => 'required',
            'tenant_id' => ['bail', 'required', 'exists:tenants,id'],
        ];
    }
}