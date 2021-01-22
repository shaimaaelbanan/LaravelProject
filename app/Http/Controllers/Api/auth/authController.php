<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\traits\generalTrait;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class authController extends Controller
{
    use generalTrait;
    public function register(Request $request)
    {
        $rules = [
            'name'=>'required|string|min:2',
            'phone'=>'required|numeric|min:10|uniqe:users,phone',
            'email'=>'required|uniqe:users,email',
            'password'=>'required|min:8'
        ];
        $validator = validator::make($request->all(),$rules);
        if($validator->fails()){
            return $this->returnValidationError($validator);
        }

        $data = $request->except('password');
        $data['password'] = bcrypt($request->password);
        // $data['code']= rand(11111,99999);
        $id = User::insertGetId($data);
        $user = User::find($id);

        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            // return response()->json(['error' => 'Unauthorized'], 401);
            return $this->returnError(401,"Unauthorized");
        }
        $user->token = 'bearer'.$token;

        // return $this->respondWithToken($token);

        return $this->returnData('user',$user);
    }
    public function profile()
    {
        return $this->returnData('user',auth('api')->user());
    }
    public function checkCode(Request $request)
    {
        $rules = [
           'code'=>'required|integer|digits:5|exists:users,code'
        ];
        $validator = validator::make($request->all(),$rules);
        if($validator->fails()){
            return $this->returnValidationError($validator);
        }
        $id = auth('api')->user()->id;
        $check = User::where('code','=', $request->code)->where('id','=', $id)->first();

        if($check){
            $user = User::find($id);
            $user->status = 1;
            $user->save();
            $user->token = $request->header('authortication');
            return $this->returnData('user',$user);
        }
        return $this->returnError(403,"Wrong Code!");
    }
    public function login(Request $request)
    {
        $rules = [
            'email'=>'required|exists:users',
            'password'=>'required|min:8'
        ];
        $validator = validator::make($request->all(),$rules);
        if($validator->fails()){
            return $this->returnValidationError($validator);
        }

        $credentials = $request->all();

        if (! $token = auth('api')->attempt($credentials)) {
            return $this->returnError(401,"Unauthorized");
        }
        $user = auth('api')->user();
        if($user->status == 2){
            return $this->returnError(401,"You must verify your account!");
        }
        $user->token = 'bearer'.$token;
        return $this->returnData('user', $user);
    }
    public function sendCode(Request $request)
    {
        $user = Auth::gaurd('api')->user();
        $email = $user->email;
        $code = rand(11111,99999);

        $updatedUser = User::find($user->id);
        $updatedUser->code = $code;
        $updatedUser->save();

        $updatedUser->token = $request->header('authorization');
        return $this->returnData('user',$updatedUser);
    }


    public function logout(Request $request)
    {
        auth('api')->logout($request);
        return $this->returnSuccessMessage("You have successfully logged out");
    }

    public function updateProfile(Request $request)
    {
        $rules = [
            'name'=>'string|min:2',
            'phone'=>'numeric|digits:10',
        ];
        $validator = validator::make($request->all(),$rules);
        if($validator->fails()){
            return $this->returnValidationError($validator);
        }
        $user = auth('api')->user();
        try{
           User::where('id',$user->id)->update($request->all());
           if($request->has('name')){
            $user->name = $request->name;
            }
            if($request->has('phone')){
                $user->phone = $request->phone;
            }
            $user->token = $request->header('authorization');
            return $this->returnData('user',$user);
        }catch(QueryException $x){
            $message = $x->getMessage();
            if(strpos($message, 'users_phone_unique') !== false){
                return $this->returnError(403,"Phone is already exists");
            }else{
                return $this->returnError(403,"Email is already exists");
            }
        }
    }
    // public function refresh()
    // {
    //     return $this->responseWithToken(auth()->refresh());
    // }
}
