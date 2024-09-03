<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommonEditRequest;
use App\Http\Requests\CommonStatusRequest;
use App\Http\Requests\MeterTypeStoreRequest;
use App\Http\Requests\MeterTypeUpdateRequest;
use App\Http\Resources\commanResource;
use App\Http\Resources\MeterTypeResource;
use App\Models\MeterType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response as HttpResponse;

class MeterTypesController extends Controller
{
    public $business_id = 1;
    public function index(){
        return responseWithError(__('message.404'), 404);
    }


    // get get meter type function
    public function get(Request $request)
    {

     abort_if(Gate::denies('meter_type.access'),  Response::HTTP_FORBIDDEN,'403 Forbidden' );

        $sortBy_columns = [
            'id' => 'id',
            'cd' => 'code',
        ];

        $filters = ['status' => $request->input('status')];

        $searchTerm = $request->input('search');
        $searchColumns = ['code'];

        $sortBy = $request->get('sortBy');
        //convert
        $sortBy =
            search_multidimensional($sortBy_columns, $sortBy, 'created_at') ??
            'created_at';

        $sortType = $request->input('sortType', 'desc');
        $perPage = $request->input('per_page', '10');
        $currentPage = $request->input('page', '1');

        return MeterTypeResource::collection(
            MeterType::filters($filters)
                ->search($searchColumns, $searchTerm)
                ->sort($sortBy, $sortType)
                ->customPaginate($perPage, $currentPage)
        );
    }

    // end get meter type function

    public function load(Request $request){
        try{


            $searchTerm = $request->input('search');
            $searchColumns = ['format'];

           $data= MeterType::search($searchColumns, $searchTerm)->get(['id as value','format as label']);
           //dd($data);

           if($data->isEmpty()){

           return responseWithError(
                   __('message.record_not_found'),
                   404
               );
       }

           return response()->json($data);

    }
    catch (\Exception $e) {
        return responseWithError(__('message.invalid'), 400);
    }
    }

    // store function
    public function store(MeterTypeStoreRequest $request)
    {
        try {
            $validate = $request->validated();

            if (!$validate) {
                return responseWithError(__('message.invalid'), 400);
            } else {
                $inserdb = [];

                $inserdb['code'] = $validate['short'];
                $inserdb['format'] = $validate['format'];
                $inserdb['business_id'] = $this->business_id;

                if (MeterType::create($inserdb)) {
                    return responseWithSuccess(__('message.save_form'), 200);
                } else {
                    return responseWithError(__('message.not_success'), 204);
                }
            }
        } catch (\Exception $e) {
            return responseWithError(__('message.invalid'), 400);
        }
    }
    // edit function
    public function edit(Request $request)
    {
        abort_if(Gate::denies('meter_type.edit'), HttpResponse::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $validator = Validator::make($request->only('id'), [
                'id' => 'required',

            ]);
            if ($validator->fails()) {
                return responseWithError(__('message.invalid'), 400);
            }  else {
                $meterType = MeterType::where('id', $request->id)->first();
                if (is_null($meterType)) {
                    return responseWithError(
                        __('message.record_not_found'),
                        404
                    );
                }
                // Resource
                return new MeterTypeResource($meterType);
            }
        } catch (\Exception $e) {
            return responseWithError(__('message.invalid'), 400);
        }
    }

    // switch function
    public function status(Request $request)
    {
        abort_if(Gate::denies('meter_type.status'), HttpResponse::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $validator = Validator::make($request->only('id','status'), [
                'id' => 'required',
                'status' => 'required|in:true,false',
            ]);
            if ($validator->fails()) {
                return responseWithError(__('message.invalid'), 400);
            } else {
                $meterType = MeterType::where('id', $request->id)->first();
                if (is_null($meterType)) {
                    return responseWithError(
                        __('message.record_not_found'),
                        404
                    );
                } else {
                    $inserdb = [];

                    $inserdb['status'] = $request->status == 'true' ? 1 : 0;

                    if ($meterType->update($inserdb)) {
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
    // update function
    public function update(MeterTypeUpdateRequest $request)
    {
        try {
            $validator = $request->validated();
            // dd($validator);
            if (!$validator) {
                return responseWithError(__('message.invalid'), 400);
            } else {
                $meterType = MeterType::where('id', $request->id)->first();
                if (is_null($meterType)) {
                    return responseWithError(
                        __('message.record_not_found'),
                        404
                    );
                } else {
                    $inserdb = [];
                    $inserdb['code'] = $validator['short'];
                    $inserdb['format'] = $validator['format'];
                    if ($meterType->update($inserdb)) {
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
        } catch (\Exception $e) {
            return responseWithError(__('message.invalid'), 400);
        }
    }
    // destroy function
    public function destroy(Request $request)
    {
       abort_if(Gate::denies('meter_type.delete'),  Response::HTTP_FORBIDDEN,'403 Forbidden');

        try {
            $validator = Validator::make($request->only('id'), [
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return responseWithError(__('message.invalid'), 400);
            } else {
                $meterType = MeterType::where('id', $request->id)->first();
                if (is_null($meterType)) {
                    return responseWithError(
                        __('message.record_not_found'),
                        404
                    );
                }

                $meterType->delete();
                return responseWithSuccess(__('message.delete_form'), 200);
            }
        } catch (\Exception $error) {
            return responseWithError(__('message.invalid'), 400);
        }
    }
}