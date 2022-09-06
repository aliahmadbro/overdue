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
use App\Models\Customer;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\Service;
use App\Models\Banner;
use App\Models\Job;
use App\Models\Support;

use App\Models\NotificationDescription;
use App\Models\DriverNotification;

use App\Helpers\StripeAccount;
use App\Helpers\NotifyDriver;

class CustomerController extends BaseController
{

    public function __construct()
    {
        $this->partial = 'admin.category.';
        $this->category = new Category();
    }
    public function login(Request $request)
    {           
        $rules = [
        'phone_no' => 'required',
        'password'=>'required'
        ];

            $input     = $request->only('phone_no','password');
            $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return $this->sendError('Error', ['error' => $validator->messages()]);
        }
        try{
            if(Auth::attempt(['phone_no' => $request->phone_no,'password'=>$request->password])){
                $user = Auth::user();
                // $success['token'] =  $user->createToken('MyApp')-> accessToken;
                $success['name'] =  $user->name;
                $success['phone_no'] =  $user->phone_no;
                // }
                $customer=Customer::where('user_id',auth::user()->id)->first();

                return $this->sendResponse($customer, 'Customer Login Successfully.');
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
            'email'=> "required",
            'phone_no'=> 'unique:users|required|min:10',
            'address'=> "required",
            'password'=>['required', 
            'min:8', 
            'regex:/^.*(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#$%^&*()\><*~]).*$/'],
            'license_number'=>'unique:customers|required',
            'profile_image'=>'required',
            'social_media'=>'required'
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
            // dd($request->phone_no); 
            
            DB::beginTransaction();
            
            if (!empty($request->profile_image)) {
                $request['image'] = $this->uploadFile('profile_image', 'public/uploads/profiles/');
            }
            $user= User::create(['name' => $request->first_name,'password' => Hash::make($request->password),'phone_no'=>$request->phone_no,
            'type'=>'1','email'=>$request->email]);
            $customer=Customer::create(['first_name'=>$request->first_name,'last_name'=>$request->last_name,'phone_no'=>$request->phone_no,'address'=>$request->address,'user_id'=>$user->id,
                'license_number'=>$request->license_number,'image'=>$request->image,'social_media'=>$request->social_media,'social_security'=>$request->social_security]);
            DB::commit();
            $customer=Customer::where('user_id',$user->id)->first();
            // $success['token'] =  $user->createToken('MyApp')-> accessToken;

            return $this->sendResponse($customer, 'Customer Registerd Successfully.');
        }
        catch (\Exception $e){
            $alert['type'] = 'danger';
            $alert['error'] ='Something Went Wrong';
            return $this->sendError('alert',$alert);
        }
    }
    public function Home(){
        try{
            DB::beginTransaction();
            $Category=$this->category->newQuery()->fetchParent()->get();
            foreach($Category as $cat){
                $cat->subCategory=$this->category->newQuery()->where('parent_id',$cat->id)->get();
            }
            $banner=Banner::get();
            $result['category']=$Category;
            $result['banner']=$banner;
            DB::commit();

            return $this->sendResponse($result, 'Category List');
        
        }
        catch (\Exception $e){
            $alert['type'] = 'danger';
            $alert['error'] ='Something Went Wrong';
            return $this->sendError('alert',$alert);
        }
    }
    public function Profile(Request $request){
        $rules = [
            'cus_id' => 'required',
            ];
    
                $input     = $request->only('cus_id');
                $validator = Validator::make($input, $rules);
    
            if ($validator->fails()) {
                return $this->sendError('error', ['error' => $validator->messages()]);
            }
        try{
            DB::beginTransaction();
            $customer=Customer::where('id',$request->cus_id)->first();
            if(empty($customer)){
                
                    	$response = [
                        'statusCode' => 100,
                            'message'=>'user not exist',
                        ];
    
    
    
                    return response()->json($response, 400);
            }

            DB::commit();
            return $this->sendResponse($customer, 'Profile Info');
        }
        catch (\Exception $e){
            $alert['type'] = 'danger';
            $alert['error'] ='Something Went Wrong';
            return $this->sendError('alert',$alert);
        }
    }
    public function updateProfile(Request $request){
        $rules = [
            'cus_id' => 'required',
            'profile_image'=>'required',
            'email'=>'required',
            'first_name'=>'required',
            'last_name'=>'required'
            ];
    
                $input     = $request->all();
                $validator = Validator::make($input, $rules);
    
            if ($validator->fails()) {
                return $this->sendError('error', ['error' => $validator->messages()]);
            }
        try{
            DB::beginTransaction();
            $customer=Customer::where('id',$request->cus_id)->first();
            if(empty($customer)){
                
                    	$response = [
                        'statusCode' => 100,
                            'message'=>'user not exist',
                        ];
    
    
    
                    return response()->json($response, 400);
            }else{
                
            if (!empty($request->profile_image)) {
                $request['image'] = $this->uploadFile('profile_image', 'uploads/profiles/');
            }
                $customer->update([
                    'image'=>$request->image,
                    'email'=>$request->email,
                    'first_name'=>$request->first_name,
                    'last_name'=>$request->last_name,
                ]);
            }

            DB::commit();
            return $this->sendResponse($customer, 'Profile Updated');
        }
        catch (\Exception $e){
            $alert['type'] = 'danger';
            $alert['error'] ='Something Went Wrong';
            return $this->sendError('alert',$alert);
        }
    }

    public function Category(){
        try{
            DB::beginTransaction();
            $category=$this->category->newQuery()->fetchParent()->get();
            foreach($category as $cat){
                $cat->subCategory=$this->category->newQuery()->where('parent_id',$cat->id)->get();
            }

            DB::commit();

            return $this->sendResponse($category, 'Category List');
        
        }
        catch (\Exception $e){
            $alert['type'] = 'danger';
            $alert['error'] ='Something Went Wrong';
            return $this->sendError('alert',$alert);
        }
    }
    public function Service(Request $request){
        $rules = [
            'sub_cat_id' => 'required',
            ];
    
                $input     = $request->only('sub_cat_id');
                $validator = Validator::make($input, $rules);
    
            if ($validator->fails()) {
                return $this->sendError('error', ['error' => $validator->messages()]);
            }
        try{
            DB::beginTransaction();
            $service=Service::where('sub_id',$request->sub_cat_id)->where('parent_id',0)->get();
            DB::commit();

            return $this->sendResponse($service, 'Service List');
        
        }
        catch (\Exception $e){
            $alert['type'] = 'danger';
            $alert['error'] ='Something Went Wrong';
            return $this->sendError('alert',$alert);
        }
    }
    public function subCategory(Request $request){
        $rules = [
            'cat_id' => 'required',
            ];
    
                $input     = $request->only('cat_id');
                $validator = Validator::make($input, $rules);
    
            if ($validator->fails()) {
                return $this->sendError('error', ['error' => $validator->messages()]);
            }
        try{
            DB::beginTransaction();
            $category=Category::where('parent_id',$request->cat_id)->get();
            DB::commit();

            return $this->sendResponse($category, 'Sub Category List');
        
        }
        catch (\Exception $e){
            $alert['type'] = 'danger';
            $alert['error'] ='Something Went Wrong';
            return $this->sendError('alert',$alert);
        }
    }
    public function subService(Request $request){
        $rules = [
            'ser_id' => 'required',
            ];
    
                $input     = $request->only('ser_id');
                $validator = Validator::make($input, $rules);
    
            if ($validator->fails()) {
                return $this->sendError('error', ['error' => $validator->messages()]);
            }
        try{
            DB::beginTransaction();
            $sub_service=Service::where('parent_id',$request->ser_id)->get();
            DB::commit();

            return $this->sendResponse($sub_service, 'Sub Service List');
        
        }
        catch (\Exception $e){
            $alert['type'] = 'danger';
            $alert['error'] ='Something Went Wrong';
            return $this->sendError('alert',$alert);
        }
    }
    public function Banner(){
        try{
            DB::beginTransaction();
            $banner=Banner::get();
            DB::commit();

            return $this->sendResponse($banner, 'Banner List');
        
        }
        catch (\Exception $e){
            $alert['type'] = 'danger';
            $alert['error'] ='Something Went Wrong';
            return $this->sendError('alert',$alert);
        }
    }
    public function postJob(Request $request){
        $rules = [
            'cus_id' => 'required',
            'service_id'=>'required',
            'description'=>'required',
            'address'=>'required',
            'budget'=>'required',
            'post_image'=>'required',
            'date'=>'required',
            ];
    
                $input     = $request->all();
                $validator = Validator::make($input, $rules);
    
            if ($validator->fails()) {
                return $this->sendError('error', ['error' => $validator->messages()]);
            }
        try{
            DB::beginTransaction();
            
            if (!empty($request->post_image)) {
                $request['image'] = $this->uploadFile('post_image', 'uploads/posts/');
            }
            
            $job=new Job();
            $job->fill($request->all());
            $job->save();
            DB::commit();

            return $this->sendResponse($job, 'Job Posted');
        
        }
        catch (\Exception $e){
            $alert['type'] = 'danger';
            $alert['error'] ='Something Went Wrong';
            return $this->sendError('alert',$alert);
        }
    }

    public function searchCategory(Request $request){
        $rules = [
            'search' => 'required',
            ];
    
                $input     = $request->all();
                $validator = Validator::make($input, $rules);
    
            if ($validator->fails()) {
                return $this->sendError('error', ['error' => $validator->messages()]);
            }
            try{
            $category=Category::where('name','LIKE','%'.$request->search."%")->where('parent_id',0)->get();
            return $this->sendResponse($category, 'Search Categories');
            }
            catch (\Exception $e){
                $alert['type'] = 'danger';
                $alert['error'] ='Something Went Wrong';
                return $this->sendError('alert',$alert);
            }
    }

    public function searchSubCategory(Request $request){
        $rules = [
            'search' => 'required',
            ];
    
                $input     = $request->all();
                $validator = Validator::make($input, $rules);
    
            if ($validator->fails()) {
                return $this->sendError('error', ['error' => $validator->messages()]);
            }
            try{
            $category=Category::where('name','LIKE','%'.$request->search."%")->whereNot('parent_id',0)->get();
            return $this->sendResponse($category, 'Search Sub Categories');
            }
            catch (\Exception $e){
                $alert['type'] = 'danger';
                $alert['error'] ='Something Went Wrong';
                return $this->sendError('alert',$alert);
            }
    }

    public function Support(){
        
        try{
            $support=Support::where('support','user')->get();
            return $this->sendResponse($support, 'Support List');
            }
            catch (\Exception $e){
                $alert['type'] = 'danger';
                $alert['error'] ='Something Went Wrong';
                return $this->sendError('alert',$alert);
            }
    }
  

}
