<?php

namespace App\Http\Requests;

use App\Models\MeterType;
use App\Rules\UniqueConditions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class MeterStoreRequest extends FormRequest
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
            'name' => [
                'bail',
                'required','min:3','max:5',
                new UniqueConditions(Meter::class, 'code')

            ],
            'tenant_id' => 'required|min:3|max:10',
        ];
    }
}