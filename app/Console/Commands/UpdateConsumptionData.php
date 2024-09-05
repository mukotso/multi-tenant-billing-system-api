<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Meter;
use App\Models\Consumption;
use Carbon\Carbon;
use App\Models\Rate;
class UpdateConsumptionData extends Command
{
    protected $signature = 'consumption:update';
    protected $description = 'Update the consumptions table based on meter readings';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Get all meters and their readings
        $meters = Meter::all();
        $currentDate = Carbon::now();

        $meters->chunk(100)->each(function ($meterChunk) use ($currentDate) {
            foreach ($meterChunk as $meter) {
                $totalConsumption = $meter->current_reading - $meter->previous_reading;

                // Insert data into the consumptions table
                Consumption::create([
                    'meter_id' => $meter->id,
                    'tenant_id' => $meter->tenant_id,
                    'consumption_date' => $currentDate,
                    'total_consumption' => $totalConsumption,
                    'rate' => $this->calculateRate($totalConsumption,$meter), 
                    'status' => 'pending',
                ]);

                // update the previous reading for the meter
                $meter->previous_reading = $meter->current_reading;
                $meter->save();
            }
        });
        

        $this->info('Consumption data updated successfully.');
    }

    private function calculateRate($totalConsumption,$meter)
    {
        $rate = Rate::where('meter_type_id', $meter->meter_type_id)
            ->where('tenant_id', $meter->tenant_id)
            ->where('from', '<=', $totalConsumption)
            ->where('to', '>=', $totalConsumption)
            ->first();
        
            return $rate->cost;
           
    }
}
