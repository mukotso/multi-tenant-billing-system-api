<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\CommonEditRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\user\UserEditResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class UsersController extends Controller
{

 
    public function index(Request $request)

    {

        $sortBy_columns = [
            'nm' => 'name',
            'em' => 'email',
        ];

        $filters = ['name' => $request->input('name')];

        $searchTerm = $request->input('search');
        $searchColumns = ['name', 'email'];

        $sortBy = $request->get('sortBy');
        //convert
        $sortBy =
            search_multidimensional($sortBy_columns, $sortBy, 'created_at') ??
            'created_at';

        $sortType = $request->input('sortType', 'desc');
        $perPage = $request->input('per_page', '10');
        $currentPage = $request->input('page', '1');

        return UserResource::collection(
            User::filters($filters)
                ->search($searchColumns, $searchTerm)
                ->sort($sortBy, $sortType)
                ->customPaginate($perPage, $currentPage)
        );
    }

    public function store(UserStoreRequest $request)
    {

       // try {
            $validate = $request->validated();
            if (!$validate) {
                return responseWithError(__('message.invalid'), 404);
            } else {
                $inserdb = [];
                $inserdb['name'] = $validate['name'];
                $inserdb['email'] = $validate['email'];
                $inserdb['role_id'] = $validate['role_id'];
                $inserdb['password'] = Hash::make($validate['password']);

                if ($user = User::create($inserdb)) {
                return responseWithSuccess(__('message.save_form'), 200);
            } else {
                return responseWithError(__('message.not_success'), 204);
            }
        }


    }


  
    // edit
    public function edit(CommonEditRequest $request)
    {
        try {
            $validate = $request->validated();
            if (!$validate) {
                return responseWithError(__('message.invalid'), 404);
            } else {
                $user_obj = User::where('id', $request->id)->first();

                // Check if object has value
                if (is_null($user_obj)) {
                    return responseWithError(
                        __('message.record_not_found'),
                        404
                    );
                }
                return new UserResource($user_obj);
            }
        } catch (\Exception $error) {
            return responseWithError(__('message.invalid'), 400);
        }
    }


    //end edit function
    public function update(UserUpdateRequest $request)
    {
         try {
        $validate = $request->validated();
        if (!$validate) {
            return responseWithError(__('message.invalid'), 400);
        } else {
            $user = User::where('id', $request->id)->first();

            if (is_null($user)) {
                return responseWithError(__('message.record_not_found'), 404);
            } else {
                $inserdb = [];
                $inserdb['name'] = $validate['name'];


                if ($user->update($inserdb)) {

                   $user->assignRole($request->input('roles'));
                    return responseWithSuccess(__('message.update_form'), 200);
                } else {
                    return responseWithError(__('message.not_success'), 204);
                }
            }
        }
        } catch (\Exception $error) {
            return responseWithError(__('message.invalid'), 400);
        }
    }

    // destory function
    public function destroy(Request $request)
    {
      
        try {
            $validator = Validator::make($request->only('id'), [
                'id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return responseWithError(__('message.invalid'), 404);
            }
            $user = User::where('id', $request->id)->first();
            if (is_null($user)) {
                return responseWithError(__('message.record_not_found'), 404);
            } else {
                $user->delete();
                return responseWithSuccess(__('message.delete_form'), 200);
            }
        } catch (\Exception $error) {
            return responseWithError(__('message.invalid'), 400);
        }
    }


    public function profile()
    {

        $username = Auth::user()->name;
        $email = Auth::user()->email;
        $branch = Auth::user()->branches->pluck('name');
       // dd($branch);
       $role= Auth::user()->role->pluck('name');

       $user_data=array();
       $user_data['username']=$username;
       $user_data['email']=$email;
       $user_data['role']=$role;

      return $user_data;
    }

    public function changePassword(Request $request)
{
        # Validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);


        #Match The Old Password
        if(!Hash::check($request->old_password, auth()->user()->password)){
            return response()->json("error", "Old Password Doesn't match!");
        }


        #Update the new Password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json("status", "Password changed successfully!");
}
}


