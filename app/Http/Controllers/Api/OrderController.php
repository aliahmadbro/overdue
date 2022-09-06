<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Driver;
use App\Models\Store;

use App\Models\WithdrawAmount;

use App\Helpers\StripeAccount;
use App\Helpers\NotifyDriver;

use App\Models\NotificationDescription;
use App\Models\DriverNotification;
use Carbon\Carbon;
use App\Models\rating;
use App\Models\Order;
use App\Models\OrderCancelation;
use App\Models\cancelReason;
use App\Notifications\UserFollowed;
use Exception;

class OrderController extends BaseController
{

    public function addOrder(Request $request)
    {

            $rules = [
                'order_amount'=> "required",
                'order_type'=> "required",
                'pickup_from'=> "required",
                'pickup_instruction'=>'required',
                'cus_name'=> "required",
                'cus_phone'=> "required",
                'cus_address'=> "required",
                'd_latitude'=> "required",
                'd_longitude'=> "required",
                'delivery_instruction'=> "required",
            ];

            $input     = $request->only('pickup_from', 'cus_address','d_latitude','d_longitude','pickup_instruction',
            'cus_name','cus_phone','cus_address','order_type','order_amount','delivery_instruction');
            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return $this->sendError('Unauthorised.', ['error' => $validator->messages()]);
            }


        try{
            $store=Store::where('id',$request->pickup_from)->first();




            // dd($online_drivers);



            //add order
            $id=$request->pickup_from;
            $ins=$request->pickup_instruction;
            // $request->request->remove('pickup_from');
            $request->request->remove('pickup_instruction');
            $order=Order::create($request->all());
            $rand = substr("00".$order->id, -3);
            $order_no='BL-'.$rand.'-'.date('d-m-y');
            $order->order_no=$order_no;
            $order->save();
            Store::where('id',$id)->update([
                'pickup_instruction'=>$ins
            ]);
            // dd($order);
            $id=NotifyDriver::getOnlineDrivers($store->id,$order->id);
            $driver=Driver::where('user_id',$id[0])->first();

            $title='New Order';
            $key='new';
            $order_id=$order->id;
            $body='this is your new order';
            $device_token=$driver->token;
            // dd($device_token);
            $result=[];
            $result['notification_result']=NotifyDriver::notification($body,$title,$device_token,$key,$order_id);
            $result['order_id']=$order_id;

            
            $order=Order::where('id',$order->id)->first();
            $order->driver_id=$driver->id;
            $user=User::where('id',$store->user_id)->first();
            $user->notify(new UserFollowed($order));
            return $this->sendResponse($result, 'notification sent.');
        }
        catch (\Exception $e){
            $alert['type'] = 'danger';
            $alert['error'] ='Something Went Wrong';
            return $this->sendError('alert',$alert);
        }


    }

    public function updateOrderStatus(Request $req){
                $rules = [
                'order_id'=> "required",
                'driver_id'=> "required",
                'status'=>'required',
            ];

            $input     = $req->only('order_id', 'driver_id','status');
            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return $this->sendError('Unauthorised.', ['error' => $validator->messages()]);
            }
        try{
            DB::beginTransaction();
            $order=Order::where('id',$req->order_id)->first();
            $driver=Driver::where('id',$req->driver_id)->first();
            // dd($order);
            $store=Store::where('id',$order->pickup_from)->first();
            $user=User::where('id',$store->user_id)->first();
            $order=Order::where('id',$order->id)->first();
            $order->driver=$driver->first_name;
            $order_id=$req->order_id;
            $msg='';
            $bool='true';
            if($order->driver_id == $driver->id){
                if($req->status == 2){
                    if($order->status > 2 ){
                        $bool='true';
                        $msg ='Order has been Moved into next stage';
                    }else{
                        $today = Carbon::now();
                        Order::where('id',$req->order_id)->update(['status'=>$req->status,'order_accept'=>$today]);
                        $msg='Order Accepted and driver is going towards store';
                        $des=NotificationDescription::where('notification_type',2)->first();
                        if(empty($des->description)){
                            $description ='no description';
                        }else{
                            $description=$des->description;
                        }
                        $not=new DriverNotification();
                        $not->driver_id=$driver->id;
                        $not->title='Accept Order';
                        $not->description=$description;
                        $not->save();
                    }
                }
                elseif($req->status == 3){
                    if($order->status >3 ){
                        $bool='true';
                        $msg ='Order has been Moved into next stage';
                    }else{
                        
                        $today = Carbon::now();
                        Order::where('id',$req->order_id)->update(['status'=>$req->status,'order_pickup'=>$today]);
                        $msg='Order pickup and driver is going towards customer';
                        $des=NotificationDescription::where('notification_type',3)->first();
                        if(empty($des->description)){
                            $description ='no description';
                        }else{
                            $description=$des->description;
                        }
                        $not=new DriverNotification();
                        $not->driver_id=$driver->id;
                        $not->title='Pickup Order';
                        $not->description=$description;
                        $not->save();
                    }
                }else{
                    $bool='false';
                    $msg ='Order status is wrong';
                }
            }else{
                $bool='false';
                $msg ='You are not the right driver';
            }
            if($bool == 'true'){
                $user->notify(new UserFollowed($order));
                DB::commit();
                return $this->sendResponse($order_id, $msg);
            }else{
                $alert['type'] = 'danger';
                $alert['error'] =$msg;
                return $this->sendError('alert',$alert);
            }
        }
        catch (\Exception $e){
            $alert['type'] = 'danger';
            $alert['error'] ='Something Went Wrong';
            return $this->sendError('alert',$alert);
        }
    }
    // orderlist
    public function orderList(Request $req){
            $rules = [
                'id'=> "required",
            ];

            $input     = $req->only('id');
            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return $this->sendError('Unauthorised.', ['error' => $validator->messages()]);
            }
        try{
            $driver = Driver::where('id',$req->id)->first();
            if($driver){
                $ongoing=Order::where('driver_id',$req->id)->where('status',2)->orwhere('status',3)->get();
                foreach($ongoing as $order){
                    $store=Store::where('id',$order->pickup_from)->first();
                    $order->store_lat=$store->latitude;
                    $order->store_long=$store->longitude;
                    $order->store_name=$store->company_name;
                    $order->pickup_instruction=$store->pickup_instruction;
                    $order->store_phone_number=$store->phone_number;
                    $order->store_address=$store->address;  
                }
                $data['ongoing']=$ongoing;
                $orders=Order::where('driver_id',$req->id)->where('status',4)->get();
                foreach($orders as $order){
                    $store=Store::where('id',$order->pickup_from)->first();
                    $order->store_lat=$store->latitude;
                    $order->store_long=$store->longitude;
                    $order->store_name=$store->company_name;
                    $order->pickup_instruction=$store->pickup_instruction;
                    $order->store_phone_number=$store->phone_number;
                    $order->store_address=$store->address;  
                    $order->delivery_proof=env('APP_URL').'images/delivery_proof/'.$order->delivery_proof;
                }
                $data['completed']=$orders;
                if(!empty($data)){
                return $this->sendResponse($data, 'Order List.');
                }else{
                    $alert['type'] = 'danger';
                    $alert['error'] ='Order List Not Existed';
                    return $this->sendError('alert',$alert);
                }
            }else{
                    $alert['type'] = 'danger';
                    $alert['error'] ='Driver Not Existed';
                    return $this->sendError('alert',$alert);
            }

        }
        catch (\Exception $e){
            $alert['type'] = 'danger';
            $alert['error'] ='Something Went Wrong';
            return $this->sendError('alert',$alert);
        }
    }


    // order submited started
    public function orderSubmit(Request $req){
            $rules = [
                'id'=> "required",
                'delivery_proof'=> "required",
                'status'=>'required',
            ];

            $input     = $req->only('id', 'delivery_proof','status');
            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return $this->sendError('Unauthorised.', ['error' => $validator->messages()]);
            }
        try{
            DB::beginTransaction();
            // dd($req);
            
                $order=Order::where('id',$req->id)->first();
                if(!empty($order)){
                    if($order->status == 4){
                        $alert['type'] = 'danger';
                        $alert['error'] ='Order already submited';
                        return $this->sendError('alert',$alert);
                    }
                    if($req->file('delivery_proof'))
                    {
                        $image = $req->file('delivery_proof');
                        $filename = time().Str::slug('').'.'.$image->getClientOriginalExtension();
                        $destinationPath = public_path('/images/delivery_proof');
                        $req->delivery_proof->move($destinationPath, $filename);
                        $req->image=$filename;
                    }else{
                        $alert['type'] = 'danger';
                        $alert['error'] ='Image must be submited';
                        return $this->sendError('alert',$alert);
                    }
                    $today = Carbon::now();
                    // dd($req->image);
                        $orderSubmit=Order::where('id',$req->id)->update(['status'=>$req->status,'delivery_proof'=>$req->image,'order_complete'=>$today]);
                            // dd($order);
                            $des=NotificationDescription::where('notification_type',4)->first();
                            if(empty($des->description)){
                                $description ='no description';
                            }else{
                                $description=$des->description;
                            }
                            $not=new DriverNotification();
                            $not->driver_id=$driver->id;
                            $not->title='Complete Order';
                            $not->description=$description;
                            $not->save();
                    $order=Order::where('id',$req->id)->first();
                    DB::commit();
                                return $this->sendResponse($order, 'Order submited successfully');
                }else{
                    $alert['type'] = 'danger';
                    $alert['error'] ='order not existed';
                    return $this->sendError('alert',$alert);
                }

        }
        catch (\Exception $e){
            $alert['type'] = 'danger';
            $alert['error'] ='Something Went Wrong';
            return $this->sendError('alert',$alert);
        }
    }


    // order submited code ended

    //order get cancels reasons
    public function cancelOrderData(Request $request){
                $rules = [
                'order_id'=> "required",
            ];

            $input     = $request->only('order_id');
            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return $this->sendError('Unauthorised.', ['error' => $validator->messages()]);
            }
        try{
            $cancel['cancelData']=cancelReason::select('id','name')->get();
            $cancel['order_id']=$request->order_id;
            return $this->sendResponse($cancel, 'Canceled Data');
        }
        catch (\Exception $e){
            $alert['type'] = 'danger';
            $alert['error'] ='Something Went Wrong';
            return $this->sendError('alert',$alert);
        }
    }

    // get order details
    public function orderdetails(Request $req){
            $rules = [
                'id'=> "required",
            ];

            $input     = $req->only('id');
            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return $this->sendError('Unauthorised.', ['error' => $validator->messages()]);
            }
        try{

            $order=Order::where('id',$req->id)->first();
            $store=Store::where('id',$order->pickup_from)->first();
            $order->store_lat=$store->latitude;
            $order->store_long=$store->longitude;
            $order->store_name=$store->company_name;
            $order->pickup_instruction=$store->pickup_instruction;
            $order->store_phone_number=$store->phone_number;
            $order->store_address=$store->address;  
            $order->delivery_proof=env('APP_URL').'images/delivery_proof/'.$order->delivery_proof;
            if(!empty($order)){
            return $this->sendResponse($order, 'order details.');
            }else{
                $alert['type'] = 'danger';
                $alert['error'] ='Order Not Existed';
                return $this->sendError('alert',$alert);
            }
        }
        catch (\Exception $e){
            $alert['type'] = 'danger';
            $alert['error'] ='Something Went Wrong';
            return $this->sendError('alert',$alert);
        }
    }

    public function orderCancelation(Request $request){
            $rules = [
                'order_id'=> "required",
                'driver_id'=> "required",
                'reason_id'=>'required',
            ];

            $input     = $request->only('order_id', 'driver_id','reason_id');
            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return $this->sendError('Unauthorised.', ['error' => $validator->messages()]);
            }
        try{
            DB::beginTransaction();
            OrderCancelation::create($request->all());
            $des=NotificationDescription::where('notification_type',7)->first();
            if(empty($des->description)){
                $description ='no description';
            }else{
                $description=$des->description;
            }
            $not=new DriverNotification();
            $not->driver_id=$request->driver_id;
            $not->title='Cancel Order';
            $not->description=$description;
            $not->save();
            $cancel=OrderCancelation::where('order_id',$request->order_id)->get();
            $order=Order::where('id',$request->order_id)->first();
            // dd($cancel);
            $id=NotifyDriver::getOnlineDrivers($order->pickup_from,$order->id);
            if(empty($id[0])){
                 $today = Carbon::now();
                    $order->driver_id=null;
                    $order->status='0';
                    $order->save();
                    DB::commit();
                   return $this->sendResponse('alert','Order Canceled Successfully');
            }
            

            // for checking already cancel order by specific driver
            $cancels=[];
            $k=0;
            foreach($cancel as $can){
                foreach($id as $id1){
                    $driver=Driver::where('user_id',$id1)->first();
                    if((int)$can->driver_id == (int)$driver->id){
                        // echo $can->driver_id;
                        $cancels[$k]=$driver->id;
                        $k++;
                    }
                }
            }
            $bool=false;
            if($order->order_type =='0'){
                
                
                foreach($id as $id1){
                    $driver1=Driver::where('user_id',$id1)->first();
                    // dd($driver1,$can);
                        if($order->order_amount <= $driver1->credit_amount){
                            foreach($cancels as $c){
                                if($driver1->id == $c){
                                    $bool=false;
                                    break;
                                }else{
                                    $bool=true;
                                }
                            }
                            if($bool == true){
                               break;
                            }
                        }
                }
            }else{
                foreach($id as $id1){
                    $driver1=Driver::where('user_id',$id1)->first();
                    // dd($driver1,$can);
                    foreach($cancels as $c){
                        if($driver1->id == $c){
                            $bool=false;
                            break;
                        }else{
                            $bool=true;
                        }
                        
                    }
                    if($bool == true){
                        break;
                        }
                }
            }
            if($bool == true){
                if(!empty($driver1)){
                    $today = Carbon::now();
                    $order->driver_id=$driver1->id;
                    $order->order_assign=$today;
                    $order->status='1';
                    $order->save();
                }else{
                    $today = Carbon::now();
                    $order->driver_id=null;
                    $order->status='0';
                    $order->save();
                    DB::commit();
                   return $this->sendResponse('alert','Order Canceled Successfully');
                }
            }else{
                
                $today = Carbon::now();
                $order->driver_id=null;
                $order->status='0';
                $order->save();
                DB::commit();
                return $this->sendResponse('alert','Order Canceled Successfully');
            }

            $title='New Order';
            $key='new';
            $order_id=$order->id;
            $body='this is your new order';
            $device_token=$driver1->token;
            // dd($device_token);
            $result=[];
            $result['notification_result']=NotifyDriver::notification($body,$title,$device_token,$key,$order->id);
            $result['order_id']=$order->id;

            
            
            DB::commit();
            return $this->sendResponse($result, 'Order Canceled Successfully');
        
        }
        catch (\Exception $e){
            $alert['type'] = 'danger';
            $alert['error'] ='Something Went Wrong';
            return $this->sendError('alert',$alert);
        }
    }


    public function rating(Request $request){
            $rules = [
                'order_id'=> "required",
                'driver_id'=> "required",
                'store_id'=>'required',
                'rating'=>'required',
                'review'=>'required',
            ];

            $input     = $request->only('order_id', 'driver_id','store_id','rating','review');
            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return $this->sendError('Unauthorised.', ['error' => $validator->messages()]);
            }
        rating::create($request->all());
        return $this->sendResponse($request->order_id, 'Store Rated Successfully');
    }

    public function driverEarning(Request $request){
        
        try{
        $orders=Order::where('driver_id',$request->driver_id)->get();
        $fullPriceWithBTW=0.0;
        $fullPrice=0.0;
        $total=0.0;
        $distance=0.0;
        foreach($orders as $order){
            $fullPrice=$order->driver_earning*10;
            $fullPrice=$fullPrice/100;
            $order->driver_earning_excluding_adminComm=$fullPrice;
            $total+=(double)$order->driver_earning;
            $store=Store::where('id',$order->pickup_from)->first();
            $distance+=NotifyDriver::getDistance($store->latitude,$store->longitude,$order->d_latitude,$order->d_longitude);
        }

        $driver=Driver::where('id',$request->driver_id)->first();
        $result['Total_orders']=count($orders);
        $result['distance']=$distance;
        $result['total_earning']=$total;
        $ongoing=Order::where('driver_id',$request->driver_id)->where('status',2)->orwhere('status',3)->get();
        $result['total_online']=count($ongoing);


        $orders=Order::where('driver_id',$request->driver_id)->get();
        $amount=0.0;
        foreach($orders as $order){
            $amount+=$order->driver_earning;
        }
        $withdrawn=withdrawAmount::where('driver_id',$request->driver_id)->get();
        foreach($withdrawn as $w){
            $amount-=$w->amount;
        }

        $result['driver_earning']=$amount;
        $result['connectedAccount']['account']=$driver->connect_account;

        return $this->sendResponse($result, 'Driver Earning.');
    
        }
        catch (\Exception $e){
            $alert['type'] = 'danger';
            $alert['error'] ='Something Went Wrong';
            return $this->sendError('alert',$alert);
        }
    }

}
