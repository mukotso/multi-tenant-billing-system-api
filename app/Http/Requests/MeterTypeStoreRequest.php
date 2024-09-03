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
        return Gate::allows('meter_type.access');

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'short' => [
                'bail',
                'required','min:3','max:5',
                new UniqueConditions(MeterType::class, 'code')

            ],
            'format' => 'required|min:3|max:10',
        ];
    }
}