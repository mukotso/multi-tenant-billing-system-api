<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\Controller;
use App\Http\Requests\RateStoreRequest;
use App\Http\Requests\RateUpdateRequest;
use App\Http\Resources\RateResource;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;


class RateController extends Controller
{
    public $business_id = 1;

    public function index(){
        return responseWithError(__('message.404'), 404);
    }

    // get rate function
    public function get(Request $request)
     {
       abort_if(Gate::denies('rate.access'), HttpResponse::HTTP_FORBIDDEN, '403 Forbidden');


         $sortBy_columns = [
             'nm'=>'name',
             'to' => 'to',
             'fr' => 'from',
             'cs' => 'cost',
         ];

         $filters = ['cost' => $request->input('cost')];
         $filters = ['status' => $request->input('status')];

         $searchTerm = $request->input('search');
         $searchColumns = ['name', 'cost'];

         $sortBy = $request->get('sortBy');
         //convert
         $sortBy = search_multidimensional($sortBy_columns, $sortBy, 'created_at') ?? 'created_at';

         $sortType = $request->input('sortType','desc');
         $perPage = $request->input('per_page', '10');
         $currentPage = $request->input('page', '1');


         return RateResource::collection(
             Rate::filters($filters)
                 ->search($searchColumns, $searchTerm)
                 ->sort($sortBy, $sortType)
                 ->customPaginate($perPage, $currentPage)

         );

     }

     // end get rate function

    // store function
    public function store(RateStoreRequest $request)
    {
        try{
        $validate = $request->validated();

        if (!$validate) {
            return responseWithError(__('message.invalid'), 404);
        } else {
            $inserdb = array();
            $inserdb['name'] = $validate['rate'];
            $inserdb['from'] = $validate['minimum'];
            $inserdb['to'] = $validate['maximum'];
            $inserdb['cost'] = $validate['amount'];
            $inserdb['note'] = $validate['remark'];
            $inserdb['business_id'] = $this->business_id;

            $rate = Rate::create($inserdb);

            if ($rate) {
                return   responseWithSuccess(__('message.save_form'), 200);
            } else {
                return responseWithError(__('message.not_success'), 204);
            }
        }
        }catch(\Exception $e){
            return responseWithError(__('message.invalid'),400);
        }
    }
    // edit function
    public function edit(Request $request)
    {
    abort_if(Gate::denies('rate.edit'), HttpResponse::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $validator = Validator::make($request->only('id'), [
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return responseWithError(__('message.invalid'), 422);
            }else {
                $rate = Rate::where('id', $request->id)->first();
                if (is_null($rate)) {
                    return responseWithError(__('message.record_not_found'), 404);
                }
                // Resource
                return new RateResource($rate);
            }
        } catch (\Exception $e) {
            return responseWithError(__('message.invalid'), 400);
        }
    }

    // switch function
    public function status(Request $request)
    {
         abort_if(Gate::denies('rate.status'), HttpResponse::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $validator = Validator::make($request->only('id','status'), [
                'id' => 'required',
                'status' => 'required|in:true,false',

            ]);
            if ($validator->fails()) {
                return responseWithError(__('message.invalid'), 400);
            } else {
            $rate = Rate::where('id', $request->id)->first();
            if (is_null($rate)) {
                return responseWithError(__('message.record_not_found'), 404);
            } else {
                $inserdb = array();
                $inserdb['status'] = ($request->status  == "true") ? 1 : 0;

                if ($rate->update($inserdb)) {
                    return   responseWithSuccess(__('message.update_form'), 200);
                } else {
                    return responseWithError(__('message.not_success'), 204);
                }
            }
            }
        } catch (\Exception $error) {
            return responseWithError(__('message.invalid'), 400);
        }
    }
    // update function
    public function update(RateUpdateRequest $request)
    {


        try {
            $validate = $request->validated();
            if (!$validate) {
                return responseWithError(__('message.invalid'), 400);
            } else {

                $rate = Rate::where('id', $request->id)->first();

                if (is_null($rate)) {
                    return responseWithError(__('message.record_not_found'), 404);
                } else {

                    $filter_from = $request->minimum ?? 0;
                    $filter_to = $request->maximum ?? 0;

                    $ratemodel = new Rate();


                    $inserdb = array();
                    $inserdb['name'] = $validate['rate'];
                    $inserdb['from'] = $validate['minimum'];
                    $inserdb['to'] = $validate['maximum'];
                    $inserdb['business_id'] = $this->business_id;
                    $inserdb['cost'] = $validate['amount'];
                    $inserdb['note'] = $validate['remark'];

                    if ($rate->update($inserdb)) {
                        return   responseWithSuccess(__('message.update_form'), 200);
                    } else {
                        return responseWithError(__('message.not_success'), 206);
                    }
                }
            }
        } catch (\Exception $e) {

            return responseWithError(__('message.invalid'), 400);
        }
    }
    // destroy function
    public function destroy(Request $request)

    {
      abort_if(Gate::denies('rate.delete'), HttpResponse::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $validator = Validator::make($request->only('id'), [
                'id' => 'required'
            ]);
            if ($validator->fails()) {
                return responseWithError(__('message.invalid'), 400);
            } else {
                $rate = Rate::where('id', $request->id)->first();
                if (is_null($rate)) {
                    return responseWithError(__('message.record_not_found'), 404);
                }

                $rate->delete();
                return   responseWithSuccess(__('message.delete_form'), 200);
            }
        } catch (\Exception $error) {
            return responseWithError(__('message.invalid'), 400);
        }
    }
}
