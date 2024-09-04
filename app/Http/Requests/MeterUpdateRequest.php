<?php

namespace App\Http\Requests;

use App\Models\MeterType;
use App\Rules\UniqueConditions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class MeterTypeUpdateRequest extends FormRequest
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
            'id'=>'required',
            'name' => [
                'bail',
                'required','min:3','max:5',
                new UniqueConditions(Meter::class,$this->id)

            ],
            'tenant_id' => 'required',
        ];
    }
}