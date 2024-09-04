<?php

namespace App\Http\Controllers\admin;

use App\Enums\BillingStatus;
use App\Http\Controllers\API\v1\Controller;
use App\Http\Requests\BillingUpdateRequest;
use App\Http\Requests\UsageRequest;
use App\Http\Resources\BillingAndHouseResource;
use App\Http\Resources\BillingResource;
use App\Http\Resources\commanResource;
use App\Http\Resources\HousePayment;
use App\Models\Billing;
use App\Models\BillingPayment;
use App\Models\Discount;
use App\Models\Group;
use App\Models\House;
use App\Models\Rate;
use App\Models\SpecialRate;
use App\Services\BillingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class BillingController extends Controller
{

    protected $billingService;

    public function __construct(BillingService $billingService)
    {
        $this->billingService = $billingService;
    }

    public function index()
    {
        return returnResponseAndStop(__('message.404'), 404);
    }

    //TODO datatable convert to get and create index retrun 404
    public function get(Request $request)
    {
        abort_if(
            Gate::denies('billing.access'),
            Response::HTTP_FORBIDDEN,
            '403 Forbidden'
        );
        $sortBy_columns = [
            'ph' => 'phone',
            'dt' => 'discount_type',
            'bm' => 'bill_month',
            'un' => 'units',
        ];

        $filters = [
            'rate' =>$request->input('units'),
            'branch_id' =>$request->input('branch'),
            'zone_id' =>$request->input('zone'),
            'site_id' =>$request->input('site'),
            ];


        $searchTerm = $request->input('search');
        // FIXME THERE IS NO COLUMN SEARCH IN THE RATHER THEN ID
        $searchColumns = ['id'];
        $sortBy = $request->get('sortBy');
        //convert
        $sortBy = search_multidimensional($sortBy_columns, $sortBy, 'created_at') ??
            'created_at';

        $sortType = $request->input('sortType', 'desc');
        $perPage = $request->input('per_page', '10');
        $currentPage = $request->input('page', '1');

        return BillingResource::collection(
            Billing::filters($filters)
                ->search($searchColumns, $searchTerm)
                ->sort($sortBy, $sortType)
                ->customPaginate($perPage, $currentPage)
        );
    }

    public function load(Request $request)
    {
        try{
            $validator = Validator::make($request->only('house','unit','search','block','building','site','zone','branch'), [
               // 'unit' => 'required|integer',
                //'search'=>'required|min:3'
            ]);
            if($validator->fails()){

                return response($validator->messages(), 200);
            }else{

                $filters = [
                    'house_id' => $request->input('house'),
                    'unit_id' => $request->input('unit'),
                    'block_id' => $request->input('block'),
                    'building_id' => $request->input('building'),
                    'site_id' => $request->input('site'),
                    'zone_id' => $request->input('zone'),
                    'branch_id' => $request->input('branch')
                ];
                $searchTerm = $request->input('search');
                $searchColumns = ['previous_r'];

              $data= Billing::filters($filters)->search($searchColumns, $searchTerm)->take(10)->get();

               if($data->isEmpty()){

               return responseWithError(
                       __('message.record_not_found'),
                       404
                   );
           }
               return BillingAndHouseResource::collection($data);
            }
        }
        catch (\Exception $e) {
            return responseWithError(__('message.invalid'.$e), 400);
        }


    }



    function generateBills()
    {

        $houses = House::all();


        $currentDate = Carbon::now();
        $billingMonth = $currentDate->month; // Current month (1-12)
        $billingYear = $currentDate->year; // Current year (e.g., 2023)




        $houses->each(function ($house) use ($billingMonth, $billingYear) {
            //   BillingService::generateBill($house, $currentMonth, $currentYear);
            BillingService::generateBillsForAllHouses($house, $billingMonth, $billingYear);
        });
    }


    public function post_bill(UsageRequest $request)
    {
        try {
            $validate = $request->validated();
            if (!$validate) {
                return returnResponseAndStop(__('message.invalid'), 400);
            }

            $bill_id = $request->bill_id;
            $current_r = $request->usage;

            $billingService = new BillingService(); //updateBill

            $response = $billingService->PostBill($bill_id, $current_r);
            if (!empty($response)) {

                return $response;
            } else {

                return responseWithError(__('message.invalid'), 500);
            }
        } catch (\Exception $error) {


            return responseWithError(__('message.invalid'), 500);
        }
    }


    public function update_bill(UsageRequest $request)
    {


        try {
            $validate = $request->validated();
            if (!$validate) {
                return returnResponseAndStop(__('message.invalid'), 400);
            }

            $bill_id = $request->bill_id;
            $current_r = $request->usage;


            $billingService = new BillingService(); //updateBill

            $response = $billingService->updateBill($bill_id, $current_r);
            if (!empty($response)) {

                return $response;
            } else {

                return responseWithError(__('message.invalid'), 500);
            }
        } catch (\Exception $error) {


            return responseWithError(__('message.invalid'), 500);
        }
    }

//TODO UPDATE ALL RELATED -  WAA IN LA BADALA DHAMAAN WIXI LA SOCODA SIDA (MATCH COLUMNS )

    //edit function
    public function edit(Request $request)
    {
        abort_if(Gate::denies('billing.edit'),  Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $validator = Validator::make($request->only('id'), [
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return responseWithError(__('message.invalid'), 404);
            } else {
                $billings = Billing::where('id', $request->id)->first();
                // Check if object has value
                if (is_null($billings)) {
                    return responseWithError(
                        __('message.record_not_found'),
                        404
                    );
                }
                return new BillingResource($billings);
            }
        } catch (\Exception $error) {
            return responseWithError(__('message.invalid'), 400);
        }
    }


    public function status(Request $request)
    {
        abort_if(Gate::denies('billing.status'),  Response::HTTP_FORBIDDEN,'403 Forbidden' );
        try {
            $validator = Validator::make($request->only('id','status'), [
                'id' => 'required',
                'status' => 'required|in:true,false',
            ]);
            if ($validator->fails()) {
                return responseWithError(__('message.invalid'), 404);
            }  else {
                $billing = Billing::where('id', $request->id)->first();
                if (is_null($billing)) {
                    return responseWithError(
                        __('message.record_not_found'),
                        404
                    );
                } else {
                    $inserdb = [];

                    $inserdb['status'] = $request->status == 'true' ? 1 : 0;

                    if ($billing->update($inserdb)) {
                        return responseWithSuccess(
                            __('message.update_form'),
                            200
                        );
                    } else {
                        return responseWithError(
                            __('message.not_success'),
                            204
                        );
                    }
                }
            }
        } catch (\Exception $error) {
            return responseWithError(__('message.invalid'), 400);
        }
    }


    public function house_payment(Request $request)
    {
         try {
            $validator = Validator::make($request->only('id'), [
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return responseWithError(__('message.400'), 400);
            } else {
                $house = House::where('id', $request->id)
                    ->first();

                // Check if object has value
                if (is_null($house)) {
                    return responseWithError(
                        __('message.404'),
                        404
                    );
                }
                $house_payments = [];
                $house_payments['house_info'] = $house;
                // $house_payments['house_payments'] = $payments;

                return new HousePayment($house);
            }
        } catch (\Exception $error) {
            return responseWithError(__('message.invalid'), 400);
        }
    }
}
