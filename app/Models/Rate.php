<?php

namespace App\Models;

use App\Traits\PaginationsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rate extends Model
{
    use HasFactory;
    use PaginationsTrait;
    use SoftDeletes;
    protected $fillable = [
        'note',
        'name',
        'to',
        'from',
        'cost',
        'created',
        'deleted',
        'updated',
    ];

    /**
     * Get the business that owns the Rate
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
  

    public function filterRate($id = null, $filter_from, $filter_to)
    {
        $rates = Rate::select('id')
            ->where('id', '!=', $id)

            ->where('from', '>=', $filter_from)
            ->where('from', '<=', $filter_to)

            ->where('to', '>=', $filter_from)
            ->where('to', '<=', $filter_to)
            ->first();

        return $rates;
    }

    public function getCost($usage)
    {
        $rates = Rate::select('cost')
     
            ->where('to', '>=', $usage)
            ->where('from', '<=', $usage)
            ->first();
       

      
       return $rates;

    }

 function calculate_amount($usage)
    {
 
        // Check if the usage greater than zero
        if ($usage > 0) {
            $rate = new Rate();
            $amount = 0;

            $rate_data = $rate->getCost($usage);

            $cost = $rate_data->cost ?? 0;

            $data = [];
         
            if ($cost > 0) {
                $amount = $usage * $cost;
                $data['amount'] = $amount;
                $data['rate'] = $cost;
            }
        
            return $data;
        }
    }


}
