<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Provider;
use Carbon\Carbon;
use App\Models\Support;
use App\Models\WithdrawAmount;
use App\Models\Order;
use App\Models\appRating;
use App\Models\driverStripeaccount;

use App\Models\NotificationDescription;
use App\Models\DriverNotification;

use App\Helpers\StripeAccount;
use App\Helpers\NotifyDriver;

class ProviderController extends BaseController
{
    public function login(Request $request)
    {           
        $rules = [
        'phone_no' => 'required',
        'password'=>'required',
        ];

            $input     = $request->only('phone_no','password');
            $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return $this->sendError('Error', ['error' => $validator->messages()]);
        }
        
        try{
            if(Auth::attempt(['phone_no' => $request->phone_no,'password'=>$request->password])){
                $user = Auth::user();
                $provider=Provider::where('user_id',auth::user()->id)->first();

                return $this->sendResponse($provider, 'Provider Login Successfully.');
            }
            else{
                return $this->sendError('Invalid Credentials', ['error'=>'Invalid Credentials']);
            }
        
        }
        catch (\Exception $e){
            $alert['type'] = 'danger';
            $alert['error'] ='Something Went Wrong';
            return $this->sendError('alert',$alert);
        }
    }

    public function register(Request $request)
    {
        // $image_validation = "required|mimes:jpg,jpeg,png";
        $rules = [
            'first_name'=> "required",
            'last_name'=> "required",
            'phone_no'=> 'unique:users|required|min:10',
            'address'=> "required",
            'password'=>['required', 
            'min:8', 
            'regex:/^.*(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#$%^&*()\><*~]).*$/'],
        ];
        $vali=[
            'phone_no.min'=>'phone number must have 10 digits',
        ];
            $input     = $request->all();
            $validator = Validator::make($input, $rules,$vali);

        if ($validator->fails()) {
            return $this->sendError('Unauthorised.', ['error' => $validator->messages()]);
        }
    	try{
     
            
            DB::beginTransaction();
            
            $user= User::create(['name' => $request->first_name,'password' => Hash::make($request->password), 'phone_no'=>$request->phone_no,
            'type'=>'1','email'=>$request->phone_no]);
            $provider=Provider::create(['first_name'=>$request->first_name,'last_name'=>$request->last_name,'phone_no'=>$request->phone_no,'address'=>$request->address,'user_id'=>$user->id,
                ]);
            DB::commit();

            return $this->sendResponse($provider, 'Provider Registerd Successfully.');
        }
        catch (\Exception $e){
            $alert['type'] = 'danger';
            $alert['error'] ='Something Went Wrong';
            return $this->sendError('alert',$alert);
        }
    }

    public function Profile(Request $request){
        $rules = [
            'pro_id' => 'required',
            ];
    
                $input     = $request->only('pro_id');
                $validator = Validator::make($input, $rules);
    
            if ($validator->fails()) {
                return $this->sendError('error', ['error' => $validator->messages()]);
            }
        try{
            DB::beginTransaction();
            $pro=Provider::where('id',$request->pro_id)->first();
            if(empty($pro)){
                
                    	$response = [
                        'statusCode' => 100,
                            'message'=>'user not exist',
                        ];
    
    
    
                    return response()->json($response, 400);
            }

            DB::commit();
            return $this->sendResponse($pro, 'Profile Info');
        }
        catch (\Exception $e){
            $alert['type'] = 'danger';
            $alert['error'] ='Something Went Wrong';
            return $this->sendError('alert',$alert);
        }
    }
    public function updateProfile(Request $request){
        $rules = [
            'pro_id' => 'required',
            'profile_image'=>'required',
            'email'=>'required',
            ];
    
                $input     = $request->only('pro_id','profile_image','email');
                $validator = Validator::make($input, $rules);
    
            if ($validator->fails()) {
                return $this->sendError('error', ['error' => $validator->messages()]);
            }
        try{
            DB::beginTransaction();
            $pro=Provider::where('id',$request->pro_id)->first();
            if(empty($pro)){
                
                    	$response = [
                        'statusCode' => 100,
                            'message'=>'user not exist',
                        ];
    
    
    
                    return response()->json($response, 400);
            }else{
                
            if (!empty($request->profile_image)) {
                $request['image'] = $this->uploadFile('profile_image', 'uploads/profiles/');
            }
                $pro->update([
                    'image'=>$request->image,
                    'email'=>$request->email,
                ]);
            }

            DB::commit();
            return $this->sendResponse($pro, 'Profile Updated');
        }
        catch (\Exception $e){
            $alert['type'] = 'danger';
            $alert['error'] ='Something Went Wrong';
            return $this->sendError('alert',$alert);
        }
    }

    public function Support(){
        
        try{
            $support=Support::where('support','provider')->get();
            return $this->sendResponse($support, 'Support List');
            }
            catch (\Exception $e){
                $alert['type'] = 'danger';
                $alert['error'] ='Something Went Wrong';
                return $this->sendError('alert',$alert);
            }
    }
  
}
