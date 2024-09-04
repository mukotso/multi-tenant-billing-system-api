<?php

namespace App\Services;


use App\Exceptions\CustomException;
use App\Models\Billing;
use App\Enums\BillingStatus;
use App\Models\Rate;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class BillingService
{


    private function processBill($bill_data, $current_r, $statusCheck)
    {
        $user_id = Auth::user()->id;
   
        $user_id = $bill_data->user_id;
        $user_data = User::where('id', $user_id)->first();
        if (is_null($user_data)) {

            throw new CustomException(__('message.record_not_found'), 404);
        }


        $invoice = $this->calculateInvoice($user_id, $bill_data->current_reading);

        $amount_paid = $statusCheck ? $bill_data->amount_paid : 0;
        

        $status = $this->determineStatus($$invoice['balance'], $invoice['grand_total']);
        $invoice['status'] = $status;

        

        $inserdb = [
            'status' => $invoice['status'],
            'current_usage' => $invoice['usage'],
            'amount_paid' => $amount_paid,
            'grand_total' => $invoice['grand_total'],
            'subtotal' => $invoice['subtotal'],
            'rate' => $invoice['rate'],
            'transaction_date' => now(),
            'updated_by' => $user_id,
        ];

        if ($bill_data->update($inserdb)) {
            $balance = $bill_data->balance;
            $user_data->update([
                'balance' => $balance,
                'previous_r' => $current_r,
            ]);

            $name = $user_data->fullname;
            $invoice_data = [];
            $invoice_data = $bill_data;
            $invoice_data['name'] = $name;

            return $this->printInvoice($invoice_data);
        } else {
            throw new CustomException(__('message.not_success'), 204);
        }
    }





    private function calculateInvoice($user_id, $usage)
    {

       
        $rates = new Rate();

        $rate_data = $rates->calculate_amount($usage);

        $rate = $rate_data['rate'] ?? 0;
        $total_amount_usage = $rate_data['amount'] ?? 0;
        $additionalCharges = 0;


        $subtotal =  $total_amount_usage; // + $additionalCharges;
        $subtotal = numberFormat($subtotal);

        $taxAmount = 0; // $grandTotal * ($taxPercent / 100);
        $taxPercent = 0;
        $grandTotal = $subtotal + $additionalCharges;

        $grandTotal = numberFormat($grandTotal);


        return [
            'usage' => $usage,
            'subtotal' => $subtotal,
            'rate' => $rate,
            'additional_charges' => $additionalCharges,
            'grand_total' => $grandTotal,
        ];
    }

    private function determineStatus($balance, $grandTotal)
    {
        if ($balance == 0) {
            return BillingStatus::PAID;
        } elseif ($balance > 0 && $balance < $grandTotal) {
            return BillingStatus::PARTIALLY_PAID;
        } elseif ($balance < 0) {
            //  return BillingStatus::CANCELLED;
        }



        if ($grandTotal > 0) {
            //    return  BillingStatus::UNPAID;
        }
        if ($grandTotal  <=  0) {
            return  BillingStatus::FAILED;
        }

        return billingStatus::UNPAID;
    }

    private function printInvoice($invoice)
    {

        $name = Auth::check() ? Auth::user()->name : 'System';


        $Reading_Date = Carbon::parse($invoice['updated_at'])->format('Y-m-d');
        $formattedInvoice = [
            //   'Invoice Details' => [


            'Served By:' => $name,
            'Bill id' => $invoice['id'] ?? '', //Bill #
            'Name' => $invoice['name'] ?? '', //BILLED TO
            'Previous Reading' => $invoice['previous_r'] ?? 0,
            'Current Reading' => $invoice['current_r'] ?? 0,

            'Usage' => $invoice['usage'] ?? 0,
            'rate' => $invoice['rate'] ?? 0,
            'subtotal' => $invoice['subtotal'] ?? 0,
            //  'Additional Charges' => $invoice['additional_charges'],
            'discount_amount' => $invoice['discount_amount'] ?? 0,
            'discount' => $invoice['discount_percent'] ?? 0,

            //'Tax Percent' => $invoice['tax_percent'],
            //'Tax Amount' => $invoice['tax_amount'],
            'Total' => $invoice['grand_total'] ?? 0, //Total Bill
            'old_Balance' => $invoice['old_balance'] ?? 0, //Bal B/F
            'balance' => $invoice['balance'] ?? 0,

            'Reading Date' => $Reading_Date ?? now(),


            //     ],

        ];
        // Generate invoice and send notification to the tenant
        //  InvoiceService::generateInvoice($bill);
        //NotificationService::sendBillNotification($house, $bill);

        return response()->json($formattedInvoice, 200);
    }







    public static function generateBillsForAllHouses(user $user, int $billingMonth, int $billingYear)
    {
        // Check if a bill has already been generated for the specific month and year
        $existingBill = Billing::where('user_id', $user->id)
            ->where('bill_month', $billingMonth)
            ->where('bill_year', $billingYear)
            ->first();

        if ($existingBill) {
            // Skip generating the bill for this house
            return;
        }



        $billDate = now();

        $billingData[] = [
            'usage' => 0,
            'amount_paid' => 0,
            'subtotal' => 0,
            'rate' => 0,
            'discount_percent' => null,
            'tax_amount' => null,
            'grand_total' => 0,
            'balance' => 0,
            'old_balance' => $house->balance,
            'bill_month' => $billingMonth,
            'bill_year' => $billingYear,
            'bill_date' => $billDate,
            'status' => BillingStatus::UNBILLED,
            'transaction_date' => null,
            'discount_type' => 0,
            'notes' => null,
            'refund_date' => null,
            'business_id' => $house->business_id,
            'branch_id' => $house->branch_id,
            'zone_id' => $house->zone_id,
            'site_id' => $house->site_id,
            'house_id' => $house->id,
            'created_by' => null,
        ];



        DB::table('billings')->insertOrIgnore($billingData);
    }


}
