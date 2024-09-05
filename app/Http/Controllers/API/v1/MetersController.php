<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\Controller;
use App\Http\Requests\CommonEditRequest;
use App\Http\Requests\CommonStatusRequest;
use App\Http\Requests\meterStoreRequest;
use App\Http\Requests\meterUpdateRequest;
use App\Http\Resources\commanResource;
use App\Http\Resources\meterResource;
use App\Models\meter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response as HttpResponse;

class metersController extends Controller
{
   
    public function index(){
        return responseWithError(__('message.404'), 404);
    }


    // get get meter type function
    public function get(Request $request)
    {

    

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

        return meterResource::collection(
            Meter::filters($filters)
                ->search($searchColumns, $searchTerm)
                ->sort($sortBy, $sortType)
                ->customPaginate($perPage, $currentPage)
        );
    }



    // store function
    public function store(meterStoreRequest $request)
    {
        try {
            $validate = $request->validated();

            if (!$validate) {
                return responseWithError(__('message.invalid'), 400);
            } else {
                $inserdb = [];

                $inserdb['code'] = $validate['short'];
                $inserdb['format'] = $validate['format'];
            

                if (Meter::create($inserdb)) {
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
       

        try {
            $validator = Validator::make($request->only('id'), [
                'id' => 'required',

            ]);
            if ($validator->fails()) {
                return responseWithError(__('message.invalid'), 400);
            }  else {
                $meter = Meter::where('id', $request->id)->first();
                if (is_null($meter)) {
                    return responseWithError(
                        __('message.record_not_found'),
                        404
                    );
                }
                // Resource
                return new meterResource($meter);
            }
        } catch (\Exception $e) {
            return responseWithError(__('message.invalid'), 400);
        }
    }

  
    // update function
    public function update(meterUpdateRequest $request)
    {
        try {
            $validator = $request->validated();
            // dd($validator);
            if (!$validator) {
                return responseWithError(__('message.invalid'), 400);
            } else {
                $meter = Meter::where('id', $request->id)->first();
                if (is_null($meter)) {
                    return responseWithError(
                        __('message.record_not_found'),
                        404
                    );
                } else {
                    $inserdb = [];
                    $inserdb['code'] = $validator['short'];
                    $inserdb['format'] = $validator['format'];
                    if ($meter->update($inserdb)) {
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
      

        try {
            $validator = Validator::make($request->only('id'), [
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return responseWithError(__('message.invalid'), 400);
            } else {
                $meter = Meter::where('id', $request->id)->first();
                if (is_null($meter)) {
                    return responseWithError(
                        __('message.record_not_found'),
                        404
                    );
                }

                $meter->delete();
                return responseWithSuccess(__('message.delete_form'), 200);
            }
        } catch (\Exception $error) {
            return responseWithError(__('message.invalid'), 400);
        }
    }
}