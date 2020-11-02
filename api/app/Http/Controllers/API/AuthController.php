<?php

namespace App\Http\Controllers\API;

use App\Models\User;

use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController as BaseController;



class AuthController extends BaseController
{
    /**

     * Register api

     *

     * @return \Illuminate\Http\Response

     */

    public function register(Request $request)
    {

        if(!config('custom.front_registration_enabled')){
            return $this->sendError('Validation Error.', 'Registration not allowed.',401);
        }


        $validator = Validator::make($request->all(), [

            'name' => 'required',

            'email' => 'required|email|unique:users',

            'password' => 'required',

            'device_name' => 'required'
        ]);



        if($validator->fails()){

            return $this->sendError('Validation Error.', $validator->errors(),400);

        }



        $input = $request->all();
        $user = User::create($input);

        $roleDefaultUser = Role::firstOrCreate(['name' => config('custom.profil_default_name'),'guard_name'=>'api']);
        $user->assignRole($roleDefaultUser);
        $right=$user->getAllPermissions();
        $requestDevice = Str::slug($request->device_name);
        $success['token'] =  $user->createToken($requestDevice,$right)->plainTextToken;
        $success['email'] =  $user->email;
        $success['name'] =  $user->name;
        $success['device_name'] =  $requestDevice;



        return $this->sendResponse($success, 'User register successfully.');

    }

    /**

     * Login api

     *

     * @return \Illuminate\Http\Response

     */

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'email' => 'required|email',

            'password' => 'required',

            'device_name' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        //die($request->password);
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){

            $requestDevice = Str::slug($request->device_name);
            $user = Auth::user();
            $roles = $user->getRoleNames()->all();
            if(is_array($roles) && count($roles)){

                $right=[];
                if(in_array(config('custom.profil_admin_name'),$roles)){
                  $right[]='*';
                }
                else
                {
                    $right=$user->getAllPermissions();
                }
                $success['token'] =  $user->createToken($requestDevice,$right)->plainTextToken;
                $success['name'] =  $user->name;
                $success['email'] =  $user->email;
                $success['device_name']=$requestDevice;

                return $this->sendResponse($success, 'User login successfully.');
            }
            else{
                return $this->sendError('Unauthorised',  ['error'=>'Unauthorised'],403);
            }

        }
        else{
            return $this->sendError('Unauthorised', ['error'=>'Unauthorised'],403);
        }

    }


    public function actualUser(Request $request)
    {
        $user = Auth::user();
        return $this->sendResponse($user, 'info user');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        return $this->sendResponse(null,'User logout successfully.');
    }

    public function notFound(){
        return $this->sendError('resource', ['error'=>'Resource not found'],404);
    }
}
