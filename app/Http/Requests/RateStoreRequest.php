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
            'rate'=>['bail','required'],
            'remark' => 'required',

            'minimum' => ['required', 'integer', 'lte:maximum', 'between:0,9999999999', new UniqueRateRules(null, 'from', $this->minimum, $this->maximum)],

            'maximum' => ['required', 'integer', 'gte:minimum', 'between:0,9999999999', new UniqueRateRules(null, 'to', $this->minimum, $this->maximum)],

            'amount' => 'required|numeric|between:0,99999999999',
            'tenant_id' => ['bail', 'required', 'exists:tenants,id'],
            'meter_type_id' => ['bail', 'required', 'exists:meter_types,id'],
        ];
    }
}
