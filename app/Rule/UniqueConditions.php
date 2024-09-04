<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UniqueConditions implements Rule
{
    private $modelOrTable;
    private $column;
    private $current_id;
    // 'short' => ['required', new UniqueAcrossConditions('buildings', 'code')],
    // 'short' => ['required', 'min:1', 'max:5', new UniqueConditions(Building::class, 'code')],

    public function __construct($modelOrTable, $column, $current_id = null)
    {
        $this->modelOrTable = $modelOrTable;
        $this->column = $column;
        $this->current_id = $current_id;
    }

    public function passes($attribute, $value)
    {

        try {
            $query = null;
            if (is_subclass_of($this->modelOrTable, Model::class)) {
                $query = $this->modelOrTable::query();
            } elseif (is_string($this->modelOrTable)) {
                $query = DB::table($this->modelOrTable);
            }

            if (!$query) {
                // Invalid model or table type
                return false;
            }
            //dd($this->current_id);
            if (!empty($this->current_id)) {
                $query = $query->where('id', '<>', $this->current_id);
            }

            $exists = $query->where($this->column, $value)->exists();

            return !$exists;
        } catch (\Exception $exception) {
            // You might want to log the exception message here for debugging
            // Log::error($exception->getMessage());
            return false;
        }
    }

    public function message()
    {
        return 'The :Attribute has already been taken.';
    }
}