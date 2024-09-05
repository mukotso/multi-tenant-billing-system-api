<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Rate;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Contracts\Validation\DataAwareRule;

class UniqueRateRules implements Rule
{

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $table;
    protected $column;
    protected $filter_to;
    protected $filter_from;
    protected $id;

    private $message;

    public function __construct($id = null, $column, $filter_from, $filter_to)
    {
        // $this->table = $table;
        $this->column = $column;
        $this->filter_to = $filter_to;
        $this->filter_from = $filter_from;

        $this->id = $id;
    }
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        if (is_null($this->filter_from)) {
            $this->message = "this  minimum is empty";
            return false;
        } else if (is_null($this->filter_to)) {
            $this->message = "this  maximum is empty";
            return false;
        }



        if (Rate::where('id', '!=',  $this->id ?? null)->where(function ($query) {
                $query->whereBetween('from', [$this->filter_from, $this->filter_to])
                    ->orwhereBetween('to', [$this->filter_from, $this->filter_to]);



                // ->where(function ($query) {
                //     $query->where('id', '!=',  $this->id)->orWhereNull('id');                  
                // })

            })
            ->exists()
        ) {

            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message ?? "this :attribute between {$this->filter_from} and {$this->filter_to} already exists";
        // return 'Rate for this :attribute already exists';
        // "Rate for this '{$attribute}'. already exists";
    }
}