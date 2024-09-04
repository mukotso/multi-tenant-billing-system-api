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
        return Gate::allows('meter_type.update');
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
            'short' => [
                'bail',
                'required','min:3','max:5',
                new UniqueConditions(MeterType::class, 'code',$this->id)

            ],
            'format' => 'required|min:3|max:10',
        ];
    }
}