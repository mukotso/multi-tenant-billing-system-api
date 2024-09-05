<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\Controller;
use App\Http\Requests\CommonEditRequest;
use App\Http\Requests\CommonStatusRequest;
use App\Http\Requests\meterStoreRequest;
use App\Http\Requests\meterUpdateRequest;
use App\Http\Resources\commanResource;
use App\Http\Resources\meterResource;
use App\Models\Meter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response as HttpResponse;

class metersController extends Controller
{
   


    // get get meter type function
    public function index(Request $request)
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

                $inserdb['name'] = $validate['name'];
                $inserdb['tenant_id'] = $validate['tenant_id'];
                $inserdb['meter_type_id'] = $validate['meter_type_id'];
                $inserdb['timezone'] = $validate['timezone'] ?? auth()->user()->timezone ?? 'Asia/Jakarta';
                $inserdb['previous_reading'] = 0;
                $inserdb['current_reading'] = 0;

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


}