<?php

namespace App\Http\Requests;

use App\Rules\UniqueConditions;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniqueRateRules;
use Illuminate\Support\Facades\Gate;

class RateStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('rate.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'rate'=>['bail','required','min:3','max:50',new UniqueConditions(Rate::class, 'name')],
            'remark' => 'required|max:50|min:3',

            'minimum' => ['required', 'integer', 'lte:maximum', 'between:0,9999999999', new UniqueRateRules(null, 'from', $this->minimum, $this->maximum)],

            'maximum' => ['required', 'integer', 'gte:minimum', 'between:0,9999999999', new UniqueRateRules(null, 'to', $this->minimum, $this->maximum)],

            'amount' => 'required|numeric|between:0,99999999999',
        ];
    }
}
