<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Consumption;
use App\Models\Bill;
use Carbon\Carbon;

class GenerateMonthlyBills extends Command
{
    protected $signature = 'bills:generate';
    protected $description = 'Generate monthly bills based on consumption data';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Get the current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Get all consumptions that have not been billed yet
        $consumptions = Consumption::where('status', 'pending')->get();

        $consumptions->chunk(100)->each(function ($chunk) use ($currentMonth, $currentYear) {
            foreach ($chunk as $consumption) {
                // Calculate bill details
                $usage = $consumption->total_consumption;
                $subtotal = $consumption->total_consumption * $consumption->rate;
                $discount = 0; 
                $tax = $subtotal * 0.16; // add 16% tax rate
                $grandTotal = $subtotal - $discount + $tax;

                // Create a new bill
                Bill::create([
                    'meter_id' => $consumption->meter_id,
                    'tenant_id' => $consumption->tenant_id,
                    'usage' => $usage,
                    'subtotal' => $subtotal,
                    'amount_paid'=>0,
                    'discount_percent' => $discount,
                    'tax_amount' => $tax,
                    'grand_total' => $grandTotal,
                    'bill_month' => $currentMonth,
                    'bill_year' => $currentYear,
                    'bill_date' => Carbon::now(),
                ]);

                // Mark the consumption as billed
                $consumption->status = 'billed';
                $consumption->save();
            }
        });

        $this->info('Monthly bills generated successfully.');
    }
}
