<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Mail;
use Validator;

class UserController extends Controller 
{
public $successStatus = 200;
public $fails=0;
/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(Request $request){    
    $request_data=$request->all();    
    $url=str_replace('public','',url(""));
    $login_name=$request_data['login_name'];
        if(Auth::attempt(['national_id' => request('login_name'), 'password' => request('password'),'type'=>request('type')] ) ){
            $user = Auth::user();           
            $success['token']=(int)$user->id;              
            $success['type']=$user->type; 
            $success['name']=$user->name;               
            $success['national_id']=$user->national_id; 
            //$success['unit_count']=$user->unit_count; 
            $success['phone']=$user->phone;
            $success['verification_phone_code']=(int)$user->verification_phone_code;
            $success['verification_phone_status']=(int)$user->verification_phone_status;

if($user->image!=''){
    $success['image']=url('/storage/'.$user->image);
  }else{
    $success['image']='';
  }

            return response()->json(['data'=>['result' => $success],'msg'=>'success','status_code'=>$this->successStatus]);
        }else{           
            return response()->json(['msg'=>'Unauthorised', 'status_code'=>401]); 
        }
    }
/** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $input = $request->all();   
        $validator = Validator::make($request->all(), [ 
            'type' => 'required', 
            'name' => 'required', 
            'national_id' => 'required',
            'phone'=> 'required|min:9',
            'password' => 'required|min:6',             
            //'c_password' => 'required|same:password', 
        ]);
     //   $check_exit=DB::table('users')->where(['national_id'=>$input['national_id'],'type'=>$input['type']])->count();

if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
              
     //   print_r($input);die;
        $input['verification_phone_code']=rand(1000,9999);
        $input['type']=$input['type'];
        $input['name']=$input['name'];
        $input['national_id']=$input['national_id'];
        $input['phone']=$input['phone'];
        $input['password'] = bcrypt($input['password']);

        $usersModel = config('admin.database.users_front_model');
        $result = $usersModel::select('*')->where(['national_id'=>$input['national_id'],'type'=>$input['type']])->get();
        if(count($result)>0){                    
          return response()->json(['msg'=>'this national id already exist','status_code'=>401]);
        }else{
        //  $input['token']=rand();
        //  print_r($input);die;
          $user = User::create($input); 
          //$success['token'] =  $user->createToken('MyApp')->accessToken; 
        //  $success['token']=$input['token'];
          
        }
        // $user->user_id=$user->id;
        // $user->token=$user->id;
/*$to      = $user->email;
$subject = 'verification email code';
$message = 'verification email code is:'.$user->verification_email_code;
$headers = 'From: info@creativitysol.com' . "\r\n" .
    'Reply-To: webmaster@creativitysol.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
*/
$success['token']=(int)$user->id;
$success['type']=$user->type;
$success['name'] =  $user->name;
$success['name']=$user->name;
$success['national_id']=$user->national_id;
$success['phone']=$user->phone;
$success['verification_phone_code']=(int)$user->verification_phone_code;
$success['verification_phone_status']=0;
 if($user->image!=''){
    $success['image']='/storage/'.$user->image;
  }else{
    $success['image']='';
  }
  return response()->json(['data'=>['result' => $success],'msg'=>'success','status_code'=>$this->successStatus]);

}
    
public function verification_phone_code(Request $request){     
      $request_data=$request->all();      
      $verification_phone_code=isset($request_data['verification_phone_code'])?$request_data['verification_phone_code']:'';
      $national_id=isset($request_data['national_id'])?$request_data['national_id']:'';
      $type=isset($request_data['type'])?$request_data['type']:'';
      $user_data=DB::table('users')->where(['national_id'=>$national_id,'verification_phone_code'=>$verification_phone_code,'type'=>$type])->get();
      if(count($user_data)>0){
              User::where(['national_id' =>$national_id])->update(['verification_phone_status'=>1]);
              return response()->json(['msg'=>'this phone verified success','status_code'=>$this->successStatus]);
      }else{
          return response()->json(['msg'=>'this verification phone code not correct','status_code'=>404]);
      }
      
    }
/** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
public function details(Request $request){  
  $token=$request->header('token');
  $user_data=DB::table('users')->where('id','=',$token)->first();
  $userid=(int)$user_data->id;
  $success['token']=$userid;
  $success['type']=$user_data->type;
  $success['name']=$user_data->name;
  $success['national_id']=$user_data->national_id;
  $success['phone']=$user_data->phone;
  $success['verification_phone_code']=(int)$user_data->verification_phone_code;
  $success['verification_phone_status']=(int)$user_data->verification_phone_status;
  if($user_data->image!=''){
    $success['image']=url('/storage/'.$user_data->image);
  }else{
    $success['image']='';
  }
  return response()->json(['data'=>['result' => $success],'msg'=>'success','status_code'=>$this->successStatus]);
}

public function update_profile(Request $request){ 
    $token=$request->header('token');
    $request_data=$request->all();

    if($request->hasFile('image')) {
        $image = $request->file('image');         
        $filename =strtolower(trim($image->getClientOriginalName()));      

        $image_name_wo_ext = substr($filename, 0, strripos($filename,'.')); 
        $type=substr($filename,strrpos($filename,'.')+0);

        $filename=time().$type;



        $image->move(storage_path('images'), $filename);
        $image = $filename;//$request->file('image')->getClientOriginalName();
  }

             
    if($request->hasFile('image')){
             $image_path='images/'.$filename;                                      
            User::where(['id' =>$token])->update(['image'=>$image_path]);             
          }


  $user_data=DB::table('users')->where('id','=',$token)->first();
  $userid=(int)$user_data->id;
  $success['token']=$userid;
  $success['type']=$user_data->type;
  $success['name']=$user_data->name;
  $success['national_id']=$user_data->national_id;
  $success['phone']=$user_data->phone;
  $success['verification_phone_code']=(int)$user_data->verification_phone_code;
  $success['verification_phone_status']=(int)$user_data->verification_phone_status;
  if($user_data->image!=''){
    $success['image']=url('/storage/'.$user_data->image);
  }else{
    $success['image']='';
  }


return response()->json(['data'=>['result' => $success],'msg'=>'success','status_code'=>$this->successStatus]);

}   

public function change_password(Request $request){
 // $token=$request->header('token');
  $request_data=$request->all();
  $national_id=$request_data['national_id'];
  $new_password=isset($request_data['new_password'])?$request_data['new_password']:'';
  $user_data =DB::table('users')->where(['national_id'=>$national_id])->first();
  $validator = Validator::make($request->all(), [ 
            'password' => 'required', 
            'new_password'=> 'required',
        ]);
      if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);
      }
      if(!Auth::attempt(['national_id' =>$national_id, 'password' => request('password')])){  
            return response()->json(['msg'=>'password not correct', 'status_code'=>404]); 
      }       
        $new_password=Hash::make($new_password); 
        User::where(['national_id' =>$national_id])->update(['password'=>$new_password]);
        return response()->json(['msg'=>'changed password success', 'status_code'=>$this->successStatus]); 

}
public function resend_code_phone(Request $request){
    $token=$request->header('token');
    //$basic  = new \Nexmo\Client\Credentials\Basic('c4c5c8f9', 'RBqIOeVmU0xCgMBo');      
//$client = new \Nexmo\Client($basic);
      $verification_phone_code=rand(1000,9999);
      $verification_phone_status=0;
      /*$message = $client->message()->send([
        'to' =>    $phone,
        'from' => 'Nexmo',
        'text' => 'verification code is:'.$verification_phone_code*/    



User::where(['id' =>$token])->update(['verification_phone_code'=>$verification_phone_code,'verification_phone_status'=>0]);
    return response()->json(['msg'=>'verification phone code sent success '.$verification_phone_code, 'status_code'=>$this->successStatus]);
}

public function forget_password(Request $request){
  //$token=$request->header('token');
  $request_data=$request->all();
  $national_id=isset($request_data['national_id'])?$request_data['national_id']:'';
  $type=isset($request_data['type'])?$request_data['type']:'';
  
  $validator = Validator::make($request->all(), [ 
            'national_id' => 'required',            
        ]);
      if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);
      }     
$user_data_info =DB::table('users')->where(['national_id'=>$national_id,'type'=>$request_data['type']])->first();
//print_r($user_data_info);die;
if(empty($user_data_info)){
              return response()->json(['msg'=>'this national id or type not exist', 'status_code'=>404]);
}
$verification_phone_code=$user_data_info->verification_phone_code;
$phone=$user_data_info->phone;
//$password=rand();
//$bcrypt_password=bcrypt($password);
/*$to      = $email;
$subject = 'Forget password';
$message = 'New password:'.$password;
$headers = 'From: info@creativitysol.com' . "\r\n" .
    'Reply-To: webmaster@creativitysol.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
 mail($to, $subject, $message, $headers);*/

 //User::where(['national_id' =>$national_id])->update(['password'=>$bcrypt_password]);
 $result=array();
 $result['verification_phone_code']=$verification_phone_code;
 $result['phone']=$phone;

        return response()->json(['data'=>['result' => $result],'msg'=>'we will sent to you verification code via SMS', 'status_code'=>$this->successStatus]);
}
public function reset_password(Request $request){  
  $request_data=$request->all();
  $national_id=$request_data['national_id'];
  $type=isset($request_data['type'])?$request_data['type']:'';
  
  $validator = Validator::make($request->all(), [ 
            'password' => 'required',                  
            'confirm_password' => 'required|same:password', 
            'national_id'=>'required',
            'type'=>'required',
        ]);
      if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);
      }
      $password=$request_data['password'];
      $bcrypt_password=bcrypt($password);
      $user_data=User::where(['national_id' =>$national_id,'type'=>$type])->first();
      if(empty($user_data)){
        return response()->json(['msg'=>'not exit', 'status_code'=>$this->successStatus]);   
      }
      User::where(['national_id' =>$national_id,'type'=>$type])->update(['password'=>$bcrypt_password]);
      return response()->json(['msg'=>'success', 'status_code'=>$this->successStatus]);
}
public function addproperty(Request $request){
  $token = $request->header('token');
  $request_data=$request->all();
  //print_r($request_data);die;
  $inputs['owner_id']=$token;
  $inputs['name']=$request_data['type'];
  $inputs['name_ar']=$request_data['type'];
  $inputs['location']=$request_data['location'];
  $inputs['type']=$request_data['type'];
  $inputs['status']=$request_data['status'];
  $inputs['description']=$request_data['description'];
  $mypropertyModel = config('admin.database.myproperty_model');
  $mypropertyModel = config('admin.database.myproperty_model');
  $result=$mypropertyModel::create($inputs);
  $mypropertyid=$result->id;
  
  
   if($request->hasFile('files')) {
        $files= $request->file('files');
       for($i=0; $i < count($files); $i++) {                
          $filename =strtolower(trim($files[$i]->getClientOriginalName()));
          $image_name_wo_ext = substr($filename, 0, strripos($filename,'.')); 
          $type=substr($filename,strrpos($filename,'.')+0);
          $filename=time().rand(1000,9999).$type;
          $files[$i]->move(storage_path('images'), $filename);          
          $file_path='images/'.$filename;          
          DB::table('myproperty_attachment')->insert(array('myproperty_id' => $mypropertyid,'files' =>$file_path));
      }  
  }
   
  $my_prop_files=DB::Table('myproperty_attachment')->where(['myproperty_id'=>$mypropertyid])->get();
  $res=array();
  if(count($my_prop_files)>0){
    foreach ($my_prop_files as $rows) {
    $rows->files=url('storage/'.$rows->files);
    $res[]=$rows;  
    }


  }
  return response()->json(['data'=>['result' => $res],'msg'=>'success','status_code'=>$this->successStatus]);
}
public function myproperty(Request $request){
        $token = $request->header('token');
        $lang=$request->header('lang');          
        $result=DB::table('myproperty')->where(['owner_id'=>$token,'status'=>'rent'])->get();
        $res=array();
        foreach ($result as $rows) {
          if($rows->image!=''){
          $rows->image=url('/storage/'.$rows->image);
        }else{
          $rows->image='';
        }
        $name=$rows->name;
        if($lang=='ar'){
          $name=$rows->name_ar;
        }
        $rows->name=$name;
        
        unset($rows->name_ar);

          $res[]=$rows;
        
        
        }
        return response()->json(['data'=>['result' => $res],'msg'=>'success','status_code'=>$this->successStatus]);
}
function myproperty_details(Request $request,$id)
{
        $token = $request->header('token');      
        $lang=$request->header('lang');      
        $result=DB::table('myproperty_details')->where(['myproperty_id'=>$id])->get();
        $res=array();
        foreach ($result as $rows) {
         
        if($rows->renter_id!=0){
            $rows->rent_status=1;
        }else{
            $rows->rent_status=0;
        }
        $rows->contract_file=url('storage/'.$rows->contract_file);

        $name=$rows->name;
        if($lang=='ar'){
          $name=$rows->name_ar;
        }
        $rows->name=$name;
        
        unset($rows->name_ar);
      //  unset($rows->renter_id);

   $myproperty_data=DB::table('myproperty')->where(['id'=>$rows->myproperty_id])->first();     
   $rows->type=$myproperty_data->type;
   $rows->price=$myproperty_data->price;
   $myproperty_details_images=DB::table('myproperty_images')->select('image')->where(['mypropertydetails_id'=>$rows->id])->get();


$res_images=array();
        foreach ($myproperty_details_images as $rowsimg){
            if($rowsimg->image!=''){
              $rowsimg->image=url('/storage/'.$rowsimg->image);
            }else{
              $rowsimg->image='';
            }
            $res_images[]=$rowsimg;

        }


      $rows->myproperty_images=$myproperty_details_images;
          

    $myproperty_installments=DB::table('invoice')->where(['myproperty_id'=>$rows->id,'invoice_type'=>'installment'])->get();
    $myproperty_gass_bill=DB::table('invoice')->where(['myproperty_id'=>$rows->id,'invoice_type'=>'gass'])->get();
    $myproperty_phone_internet_bill=DB::table('invoice')->where(['myproperty_id'=>$rows->id,'invoice_type'=>'internet'])->get();
$myproperty_installments_arr=array();
foreach($myproperty_installments as $mypro_ins){
    $mypro_ins->money=(double)$mypro_ins->money;
    $mypro_ins->paid_status=(int)$mypro_ins->paid_status;
    $myproperty_installments_arr[]=$mypro_ins;
}

$myproperty_gass_bill_arr=array();
foreach($myproperty_gass_bill as $mypro_gas){
    $mypro_gas->money=(double)$mypro_gas->money;
    $mypro_gas->paid_status=(int)$mypro_gas->paid_status;
    $myproperty_gass_bill_arr[]=$mypro_gas;
}
$myproperty_phone_internet_bill_arr=array();
foreach($myproperty_phone_internet_bill as $mypro_internet){
    $mypro_internet->money=(double)$mypro_internet->money;
    $mypro_internet->paid_status=(int)$mypro_internet->paid_status;
    $myproperty_phone_internet_bill_arr[]=$mypro_internet;
}
    $rows->myproperty_installments=$myproperty_installments_arr;
    $rows->myproperty_gass_bill=$myproperty_gass_bill_arr;
    $rows->myproperty_phone_internet_bill=$myproperty_phone_internet_bill_arr;


      
      $renter_data=DB::table('users')->select('name','national_id','phone')->where(['id'=>$rows->renter_id])->get();
      $rows->renter_data=$renter_data;
      // $rows->id=(int)$rows->id;      
      // $rows->token=(int)$rows->token;
      $rows->renter_id=(int)$rows->renter_id;      
      // $rows->myproperty_id=(int)$rows->myproperty_id;
      $rows->unit_num=(int)$rows->unit_num;
      $rows->apartment_count=(int)$rows->apartment_count;
      $rows->room_count=(int)$rows->room_count;
      $rows->bathroom_count=(int)$rows->bathroom_count;
      $rows->hall_count=(int)$rows->hall_count;
      $rows->kitchen=(int)$rows->kitchen;
      $rows->furniture=(int)$rows->furniture;
      $rows->elevator=(int)$rows->elevator;
      $rows->car_entrance=(int)$rows->car_entrance;
      $rows->adaptations=(int)$rows->adaptations;      
      $rows->rent_money=(double)$rows->rent_money;


      $res[]=$rows;
        }
        return response()->json(['data'=>['result' => $res],'msg'=>'success','status_code'=>$this->successStatus]);
}
function myproperty_moredetails(Request $request,$id)
{
        $token = $request->header('token');      
        $lang=$request->header('lang');      
        $result=DB::table('myproperty_details')->where(['id'=>$id])->get();
        $res=array();
        foreach ($result as $rows) {
         
        if($rows->renter_id!=0){
            $rows->rent_status=1;
        }else{
            $rows->rent_status=0;
        }
        $rows->contract_file=url('storage/'.$rows->contract_file);

        $name=$rows->name;
        if($lang=='ar'){
          $name=$rows->name_ar;
        }
        $rows->name=$name;
        
        unset($rows->name_ar);
      //  unset($rows->renter_id);

   $myproperty_data=DB::table('myproperty')->where(['id'=>$rows->myproperty_id])->first();     
   $rows->type=$myproperty_data->type;
   $myproperty_details_images=DB::table('myproperty_images')->select('image')->where(['mypropertydetails_id'=>$rows->id])->get();


$res_images=array();
        foreach ($myproperty_details_images as $rowsimg){
            if($rowsimg->image!=''){
              $rowsimg->image=url('/storage/'.$rowsimg->image);
            }else{
              $rowsimg->image='';
            }
            $res_images[]=$rowsimg;

        }


      $rows->myproperty_images=$myproperty_details_images;
          


$myproperty_installments=DB::table('invoice')->where(['myproperty_id'=>$rows->id,'invoice_type'=>'installment'])->get();
    $myproperty_gass_bill=DB::table('invoice')->where(['myproperty_id'=>$rows->id,'invoice_type'=>'gass'])->get();
    $myproperty_phone_internet_bill=DB::table('invoice')->where(['myproperty_id'=>$rows->id,'invoice_type'=>'internet'])->get();



$myproperty_installments_arr=array();
foreach($myproperty_installments as $mypro_ins){
    $mypro_ins->money=(double)$mypro_ins->money;
    $mypro_ins->paid_status=(int)$mypro_ins->paid_status;
    $myproperty_installments_arr[]=$mypro_ins;
}
    $myproperty_gass_bill_arr=array();
foreach($myproperty_gass_bill as $mypro_gas){
    $mypro_gas->money=(double)$mypro_gas->money;
    $mypro_gas->paid_status=(int)$mypro_gas->paid_status;
    $myproperty_gass_bill_arr[]=$mypro_gas;
}
$myproperty_phone_internet_bill_arr=array();
foreach($myproperty_phone_internet_bill as $mypro_internet){
    $mypro_internet->money=(double)$mypro_internet->money;
    $mypro_internet->paid_status=(int)$mypro_internet->paid_status;
    $myproperty_phone_internet_bill_arr[]=$mypro_internet;
}
    $rows->myproperty_installments=$myproperty_installments_arr;
    $rows->myproperty_gass_bill=$myproperty_gass_bill_arr;
    $rows->myproperty_phone_internet_bill=$myproperty_phone_internet_bill_arr;

      
      $renter_data=DB::table('users')->select('name','national_id','phone')->where(['id'=>$rows->renter_id])->get();
      $rows->renter_data=$renter_data;
      // $rows->id=(int)$rows->id;      
      // $rows->token=(int)$rows->token;
      $rows->renter_id=(int)$rows->renter_id;      
      // $rows->myproperty_id=(int)$rows->myproperty_id;
      $rows->unit_num=(int)$rows->unit_num;
      $rows->apartment_count=(int)$rows->apartment_count;
      $rows->room_count=(int)$rows->room_count;
      $rows->bathroom_count=(int)$rows->bathroom_count;
      $rows->hall_count=(int)$rows->hall_count;
      $rows->kitchen=(int)$rows->kitchen;
      $rows->furniture=(int)$rows->furniture;
      $rows->elevator=(int)$rows->elevator;
      $rows->car_entrance=(int)$rows->car_entrance;
      $rows->adaptations=(int)$rows->adaptations;      
      $rows->rent_money=(double)$rows->rent_money;
      $res[]=$rows;
        }
        return response()->json(['data'=>['result' => $res],'msg'=>'success','status_code'=>$this->successStatus]);
}
function renter_details(Request $request){
        $token = $request->header('token');      
        $lang=$request->header('lang');      
        $result=DB::table('myproperty_details')->select('id','renter_id','myproperty_id','rental_type','unit_num','contract_start_date','contract_end_date','contract_period','rent_money','paid_system','insurance','contract_file')->where(['renter_id'=>$token])->get();
        $res=array();
        foreach ($result as $rows) {
           //   $rows->rent_money=(string)$rows->rent_money;
              //$rows->paid_system=(int)$rows->paid_system;
      /*  if($rows->renter_id!=0){
            $rows->rent_status=1;
        }else{
            $rows->rent_status=0;
        }*/
        $rows->contract_file=url('storage/'.$rows->contract_file);
/*
        $name=$rows->name;
        if($lang=='ar'){
          $name=$rows->name_ar;
        }
        $rows->name=$name;
        
        unset($rows->name_ar);
      //  unset($rows->renter_id);
*/
   // $myproperty_data=DB::table('myproperty')->select('unit_num','contract_start_date','contracr_end_date','contract_period','rent_money','insurance')->where(['id'=>$rows->myproperty_id])->first();     
   $rows->type='flat';
    $myproperty_data=DB::table('myproperty')->where(['id'=>$rows->myproperty_id])->first();     
   
    $name=$myproperty_data->name;
        if($lang=='ar'){
          $name=$myproperty_data->name_ar;
        }
        $rows->building=$name;
        
        unset($rows->name_ar);
 

 /*  $myproperty_details_images=DB::table('myproperty_images')->select('image')->where(['mypropertydetails_id'=>$rows->id])->get();


$res_images=array();
        foreach ($myproperty_details_images as $rowsimg){
            if($rowsimg->image!=''){
              $rowsimg->image=url('/storage/'.$rowsimg->image);
            }else{
              $rowsimg->image='';
            }
            $res_images[]=$rowsimg;

        }


      $rows->myproperty_images=$myproperty_details_images;
          */
    $myproperty_installments=DB::table('invoice')->where(['myproperty_id'=>$rows->id,'invoice_type'=>'installment'])->get();
    $myproperty_gass_bill=DB::table('invoice')->where(['myproperty_id'=>$rows->id,'invoice_type'=>'gass'])->get();
    $myproperty_phone_internet_bill=DB::table('invoice')->where(['myproperty_id'=>$rows->id,'invoice_type'=>'internet'])->get();

    $rows->myproperty_installments=$myproperty_installments;
    $rows->myproperty_gass_bill=$myproperty_gass_bill;
    $rows->myproperty_phone_internet_bill=$myproperty_phone_internet_bill;


      

$myproperty_installments_arr=array();
foreach($myproperty_installments as $mypro_ins){
    $mypro_ins->money=(double)$mypro_ins->money;
    $mypro_ins->paid_status=(int)$mypro_ins->paid_status;
    $myproperty_installments_arr[]=$mypro_ins;
}
    $myproperty_gass_bill_arr=array();
foreach($myproperty_gass_bill as $mypro_gas){
    $mypro_gas->money=(double)$mypro_gas->money;
    $mypro_gas->paid_status=(int)$mypro_gas->paid_status;
    $myproperty_gass_bill_arr[]=$mypro_gas;
}
$myproperty_phone_internet_bill_arr=array();
foreach($myproperty_phone_internet_bill as $mypro_internet){
    $mypro_internet->money=(double)$mypro_internet->money;
    $mypro_internet->paid_status=(int)$mypro_internet->paid_status;
    $myproperty_phone_internet_bill_arr[]=$mypro_internet;
}
    $rows->myproperty_installments=$myproperty_installments_arr;
    $rows->myproperty_gass_bill=$myproperty_gass_bill_arr;
    $rows->myproperty_phone_internet_bill=$myproperty_phone_internet_bill_arr;


      $renter_data=DB::table('users')->select('id','name','phone','national_id','image')->where(['id'=>$rows->renter_id])->first();
      if($renter_data->image!=''){
        $image=url('/storage/'.$renter_data->image);
      }else{
        $image='';
      }
      $renter_data->id=$renter_data->id;
      $renter_data->national_id=$renter_data->national_id;
      $renter_data->phone=$renter_data->phone;
      $renter_data->image=$image;

      $rows->renter_data=$renter_data;
      // $rows->id=(int)$rows->id;      
      // $rows->token=$rows->renter_id;
    //  $rows->renter_id=(int)$rows->renter_id;      
      // $rows->myproperty_id=(int)$rows->myproperty_id;
      $rows->unit_num=(int)$rows->unit_num;
      $rows->rent_money=(double)$rows->rent_money;


      $res[]=$rows;
        }
        return response()->json(['data'=>['result' => $res],'msg'=>'success','status_code'=>$this->successStatus]);
}
public function saleoffers(Request $request){
        $token = $request->header('token');
        $lang=$request->header('lang'); 
        if(empty($token)){
         return response()->json(['msg'=>'empty token','status_code'=>$this->successStatus]);         
        }         
        $result=DB::table('myproperty')->where(['status'=>'sale'])->orderBy('id','desc')->get();

        $res=array();
        foreach ($result as $rows) {
          if($rows->image!=''){
          $rows->image=url('/storage/'.$rows->image);
        }else{
          $rows->image='';
        }
        $name=$rows->name;
        if($lang=='ar'){
          $name=$rows->name_ar;
        }
        $rows->name=$name;
        
        unset($rows->name_ar);

          $res[]=$rows;
        
        
        }
        return response()->json(['data'=>['result' => $res],'msg'=>'success','status_code'=>$this->successStatus]);
}
public function myproperty_manager(Request $request){
        $token = $request->header('token');      
        $lang=$request->header('lang');      
        if(empty($token)){
         return response()->json(['msg'=>'empty token','status_code'=>$this->successStatus]);         
        }
        $result=DB::table('users')->select('name','national_id','phone','image')->where(['id'=>1])->get();
        $res=array();
        foreach ($result as $rows) {
          if($rows->image!=''){
          $rows->image=url('/storage/'.$rows->image);
        }else{
          $rows->image='';
        }
       

          $res[]=$rows;
        
        
        }
        return response()->json(['data'=>['result' => $res],'msg'=>'success','status_code'=>$this->successStatus]);

}
public function chat(Request $request){ // good deals         
   $token = $request->header('token');      
  if(empty($token)){
      return response()->json(['msg'=>'empty token','status_code'=>$this->successStatus]);         
  }
    $result=DB::table('chat')->where(['sender_id'=>$token])->orWhere(['reciever_id'=>$token])->orderby('created_at','asc')->get();
    return response()->json(['data'=>['result' => $result],'msg'=>'success','status_code'=>$this->successStatus]);
  }

  public function send_message(Request $request){ // good deals         
    $token = $request->header('token');      
    $request_data=$request->all();  
    $message=$request_data['message'];
 
    $result=DB::table('chat')->insert(array('token' =>$token,'sender_id' =>$token,'reciever_id'=>1,'message'=>$message));
    return response()->json(['msg'=>'success','status_code'=>$this->successStatus]);
  }
  

/*end selah api*/
public function good_deals(Request $request){ // good deals         
        $token = $request->header('token');      
        $lang=$request->header('lang');      
        $url=str_replace('public','',url(""));
        $ItemsModel = config('admin.database.items_model');
        $itemsfavouriteModel = config('admin.database.items_favourite_model');
      if(!empty($token)){
      @session_start();
      $_SESSION['token']=$token;
        $result = $ItemsModel::select('items.*','categories.name as categoryname','categories.name_ar as categoryname_ar','items_favourite.item_id as itemfav',
          DB::raw('CASE WHEN items_favourite.item_id <>"" THEN 1 ELSE 0 END AS itemfav')

      )->leftjoin('categories', 'items.cat_id', '=', 'categories.id')->
      leftJoin('items_favourite', function($join){
          $join->on('items.id', '=', 'items_favourite.item_id');      
          $join->on('token','=',DB::raw($_SESSION['token']));
        })->where(['good_deal'=>1,'available'=>1])->get();                    
      }else{
        $result = $ItemsModel::select('items.*','categories.name as categoryname')->leftjoin('categories', 'items.cat_id', '=', 'categories.id')->where(['good_deal'=>1,'available'=>1])->get();                   
      }
      $result2 = array();
      foreach ($result as $res) {        
          $res['image']=$url.'storage/'.$res['item_logo'];
          
          if($res['item_logo_details']!=''){            
            $res['image_details']=$url.'storage/'.$res['item_logo_details'];
          }else{
            $res['image_details']='';
          }
          unset($res['item_logo']);
          unset($res['item_logo_details']);

$name=$res->name;
$description=$res->description;     
$categoryname=$res->categoryname;     
if($lang=='ar'){
  $name=$res->name_ar;
  $description=$res->description_ar;
  $categoryname=$res->categoryname_ar;
}
  $res->name=$name;
  $res->description=$description;
  $res->categoryname=$categoryname;

unset($res->name_ar);
unset($res->description_ar);
unset($res->categoryname_ar);


      $result2[]=$res;
      }
        return response()->json(['data'=>['result' => $result2],'msg'=>'success','status_code'=>$this->successStatus]); 
    }
    public function wishlist(Request $request){ // good deals         
        $token = $request->header('token');      
        $lang=$request->header('lang');
        $url=str_replace('public','',url(""));
        $ItemsModel = config('admin.database.items_model');
        $itemsfavouriteModel = config('admin.database.items_favourite_model');
      if(!empty($token)){
        $result = $ItemsModel::select('items_favourite.*', 'items_favourite.id as itemfavid','items.*','vendors.vendor_logo')
        ->leftjoin('vendors', 'items.vendor_branch_user_id', '=', 'vendors.id')
        ->leftJoin('items_favourite', function($join){
          $join->on('items.id', '=', 'items_favourite.item_id');      
        })->where(['items_favourite.token'=>$token])->get();                    
      }
      $result2 = array();
      foreach ($result as $res) {        
          $res['image']=$url.'storage/'.$res['item_logo'];
          
          if($res['item_logo_details']!=''){            
            $res['image_details']=$url.'storage/'.$res['item_logo_details'];
          }else{
            $res['image_details']='';
          }
          unset($res['id']);
          $res['id']=$res['itemfavid'];
          unset($res['itemfavid']);
          unset($res['item_logo']);
          unset($res['item_logo_details']);
          $res['vendor_logo']=$url.'storage/'.$res['vendor_logo'];

$name=$res->name;
$description=$res->description;     
if($lang=='ar'){
  $name=$res->name_ar;
  $description=$res->description_ar;
}
  $res->name=$name;
  $res->description=$description;

unset($res->name_ar);
unset($res->description_ar);


      $result2[]=$res;
      }
        return response()->json(['data'=>['result' => $result2],'msg'=>'success','status_code'=>$this->successStatus]); 
    }
    public function delete_wishlist_item(Request $request,$id){
        $token = $request->header('token');        
        $whereArray = array('id'=>$id);
        $res_delete=DB::table('items_favourite')->where($whereArray)->delete();
        return response()->json(['msg'=>'item # '.$id.' deleted from wishlist success','status_code'=>$this->successStatus]); 
    }
   /* public function reservetable(Request $request){
      $token = $request->header('token');

      $inputs=$request->all();

    }*/
    public function list_items(){
        $ItemsModel = config('admin.database.items_model');
        $url=str_replace('public','',url(""));
        $result = $ItemsModel::select('items.*','categories.name as categoryname')->join('categories', 'items.cat_id', '=', 'categories.id')->get();
         $result2 = array();
        foreach ($result as $res) {        
          $res['image']=$url.'storage/'.$res['item_logo'];
          
          if($res['item_logo_details']!=''){            
            $res['image_details']=$url.'storage/'.$res['item_logo_details'];
          }else{
             $res['image_details']='';
          }
          unset($res['item_logo']);
          unset($res['item_logo_details']);
      $result2[]=$res;
      }

        return response()->json(['data'=>['result' => $result2] ,'msg'=>'success','status_code'=>$this->successStatus]); 
    }
    public function get_item_by_catid($catid,Request $request){    
        $token = $request->header('token');   
        $url=str_replace('public','',url(""));
        $ItemsModel = config('admin.database.items_model');
        $itemsfavouriteModel = config('admin.database.items_favourite_model');
      if(!empty($token)){
      @session_start();
      $_SESSION['token']=$token;
        $result = $ItemsModel::select('items.*','categories.name as categoryname','items_favourite.item_id as itemfav',
          DB::raw('CASE WHEN items_favourite.item_id <>"" THEN 1 ELSE 0 END AS itemfav')

      )->leftjoin('categories', 'items.cat_id', '=', 'categories.id')->
      leftJoin('items_favourite', function($join){
          $join->on('items.id', '=', 'items_favourite.item_id');      
          $join->on('token','=',DB::raw($_SESSION['token']));
        })->where(['cat_id'=>$catid,'available'=>1])->get();                    
      }else{
        $result = $ItemsModel::select('items.*','categories.name as categoryname')->leftjoin('categories', 'items.cat_id', '=', 'categories.id')->where(['cat_id'=>$catid,'available'=>1])->get();                   
      }
      $result2 = array();
      foreach ($result as $res) {        
          $res['image']=$url.'storage/'.$res['item_logo'];          
          if($res['item_logo_details']!=''){            
            $res['image_details']=$url.'storage/'.$res['item_logo_details'];
          }else{
             $res['image_details']='';
          }
          unset($res['item_logo']);
          unset($res['item_logo_details']);
      $result2[]=$res;
      }
        return response()->json(['data'=>['result' => $result2],'msg'=>'success','status_code'=>$this->successStatus]);
    }
    public function get_item_by_id($id,Request $request){        
      $token = $request->header('token');      
      $lang=$request->header('lang');  
      $url=str_replace('public','',url(""));
      $ItemsModel = config('admin.database.items_model');
      $itemsfavouriteModel = config('admin.database.items_favourite_model');
      if(!empty($token)){
      @session_start();
      $_SESSION['token']=$token;
        $result = $ItemsModel::select('items.*','categories.name as categoryname','categories.name_ar as categoryname_ar','items_favourite.item_id as itemfav',
          DB::raw('CASE WHEN items_favourite.item_id <>"" THEN 1 ELSE 0 END AS itemfav')

      )->leftjoin('categories', 'items.cat_id', '=', 'categories.id')->
      leftJoin('items_favourite', function($join){
          $join->on('items.id', '=', 'items_favourite.item_id');      
          $join->on('token','=',DB::raw($_SESSION['token']));
        })->where('items.id','=', $id)->get();                    
      }else{
        $result = $ItemsModel::select('items.*','categories.name as categoryname')->leftjoin('categories', 'items.cat_id', '=', 'categories.id')->where('items.id','=',$id)->get();                   
      }
      $result2 = array();
      foreach ($result as $res) {        
          $res['image']=$url.'storage/'.$res['item_logo'];
          
          if($res['item_logo_details']!=''){            
            $res['image_details']=$url.'storage/'.$res['item_logo_details'];
          }else{
            $res['image_details']='';
          }
          unset($res['item_logo']);
          unset($res['item_logo_details']);

$name=$res->name;
$description=$res->description;     
$categoryname=$res->categoryname;     
if($lang=='ar'){
  $name=$res->name_ar;
  $description=$res->description_ar;
  $categoryname=$res->categoryname_ar;
}
  $res->name=$name;
  $res->description=$description;
  $res->categoryname=$categoryname;

unset($res->name_ar);
unset($res->description_ar);
unset($res->categoryname_ar);





      $result2[]=$res;
      }
        return response()->json(['data'=>['result' => $result2],'msg'=>'success','status_code'=>$this->successStatus]);
    }
    public function get_list_vendors(){ // resturants
        $vendorsModel = config('admin.database.vendors_model');
         $url=str_replace('public','',url(""));
        $result=$vendorsModel::all();
         $result2 = array();
      foreach ($result as $res) {        
          $res['vendor_logo']=$url.'storage/'.$res['vendor_logo'];
          $res['vendor_featured_image']=$url.'storage/'.$res['vendor_featured_image'];
          $res['vendor_store_bg_image']=$url.'storage/'.$res['vendor_store_bg_image'];
          $res['vendor_store_bg_video']=$url.'storage/'.$res['vendor_store_bg_video'];

      $result2[]=$res;
      }
          return response()->json(['data'=>['result' => $result2] ,'msg'=>'success','status_code'=>$this->successStatus]);
      }
       public function get_list_vendors_by_vendor_type($name){ // resturants
        $vendorsModel = config('admin.database.vendors_model');
        $url=str_replace('public','',url(""));
        $result=$vendorsModel::all()->where('vendor_type','=',$name);
          $result2 = array();
         foreach ($result as $res) {        
          $res['vendor_logo']=$url.'storage/'.$res['vendor_logo'];
          $res['vendor_featured_image']=$url.'storage/'.$res['vendor_featured_image'];
          $res['vendor_store_bg_image']=$url.'storage/'.$res['vendor_store_bg_image'];
          $res['vendor_store_bg_video']=$url.'storage/'.$res['vendor_store_bg_video'];

      $result2[]=$res;
      }

          return response()->json(['data'=>['result' => $result2] ,'msg'=>'success','status_code'=>$this->successStatus]);
      }
      public function get_items_options_by_item_id(Request $request,$itemid){
                $url=str_replace('public','',url(""));
                $lang=$request->header('lang');
                $itemsoptionsModel = config('admin.database.itemsoptions_model');
                $result=DB::table('itemsoptions')->where('item_id','=',$itemid)->get();
                $result2 = array();
                foreach ($result as $res) {        
                  $itemoptionname=$res->name;
                  if($lang=='ar'){
                    $itemoptionname=$res->name_ar;
                  }
                  $res->name=$itemoptionname;
                  unset($res->name_ar);
                    $res->image=$url.'storage/'.$res->image;
                    //unset($res['image']);
                     $result2[]=$res;
                } 
                
                 return response()->json(['data'=>['result' => $result2] ,'msg'=>'success','status_code'=>$this->successStatus]); 
                
      }      
      public function get_items_sizes_by_item_id(Request $request,$itemid){       
          $lang=$request->header('lang');         
                $result=DB::table('itemsizes')->where('item_id','=',$itemid)->get();
                foreach ($result as $res) {
                  $itemsizename=$res->name;
                  if($lang=='ar'){
                    $itemsizename=$res->name_ar;
                  }
                  $res->name=$itemsizename;
                  unset($res->name_ar);



                }
                  return response()->json(['data'=>['result' => $result] ,'msg'=>'success','status_code'=>$this->successStatus]); 
      
      }
      public function get_categories_by_vendor_branch(Request $request,$branch_id){
      $token = $request->header('token');
      $lang=$request->header('lang');
      $url=str_replace('public','',url(""));

      $categoriesModel = config('admin.database.categories_model');
      $ItemsModel = config('admin.database.items_model');

          
      $branch_data=DB::table('branches')->where('id','=',$branch_id)->first();

      $vendor_branch_id=$branch_data->vendor_id;
      $dnn_table=$branch_data->dnn_table;

      $vendor_data=DB::table('vendors')->where('id','=',$vendor_branch_id)->first();

      $result=DB::table('categories')->where('vendor_branch_user_id','=',$vendor_branch_id)->get();

      $vendor_logo_image=$url.'storage/'.$vendor_data->vendor_logo;      
      
$vendor_name=$vendor_data->vendor_name;
$branch_name=$branch_data->name;
          if($lang=='ar'){
            $vendor_name=$vendor_data->vendor_name_ar;
            $branch_name=$branch_data->name_ar;
          }          
/*unset($rows->vendor_name_ar);
unset($rows->name_ar);
*/




/*      $vendor_name=$vendor_data->vendor_name;
      $branch_name=$branch_data->name;
*/
      $vendor_reservation_table_show=$vendor_data->reservation_table_show;
      $vendor_store_bg_image=$url.'storage/'.$vendor_data->vendor_store_bg_image;
      $vendor_store_bg_video=$url.'storage/'.$vendor_data->vendor_store_bg_video;      
      $vendor_feature_gif_bg=$vendor_data->feature_gif_bg;
      

      $result2 = array();    
      foreach ($result as $res) { 
      if(!empty($token)){
      @session_start();
      $_SESSION['token']=$token;
        $result_itemdata = $ItemsModel::select('items.*','categories.name as categoryname','items_favourite.item_id as itemfav',
          DB::raw('CASE WHEN items_favourite.item_id <>"" THEN 1 ELSE 0 END AS itemfav')

      )->leftjoin('categories', 'items.cat_id', '=', 'categories.id')
       ->leftJoin('items_favourite', function($join){
          $join->on('items.id', '=', 'items_favourite.item_id');      
          $join->on('token','=',DB::raw($_SESSION['token']));
        })->where(['cat_id'=>$res->id,'available'=>1])->get();                    
      }else{
       $result_itemdata = $ItemsModel::select('items.*','categories.name as categoryname','categories.name_ar as categoryname_ar')->leftjoin('categories', 'items.cat_id', '=', 'categories.id')->where(['cat_id'=>$res->id,'available'=>1])->get();                   
      }

      $res->item_data=$result_itemdata;   
      foreach ($res->item_data as $item_data_row) {
          $item_data_row->image=$url.'storage/'.$item_data_row->item_logo;
          if($item_data_row->item_logo_details!=''){
            $item_data_row->image_details=$url.'storage/'.$item_data_row->item_logo_details;
            unset($item_data_row->item_logo_details);
          }else{
            $item_data_row->image_details='';

          }          
          unset($item_data_row->item_logo); 

          $name=$item_data_row->name;
          $description=$item_data_row->description;     
          $categoryname=$item_data_row->categoryname;    
          if($lang=='ar'){
            $name=$item_data_row->name_ar;
            $description=$item_data_row->description_ar;
            $categoryname=$item_data_row->categoryname_ar;
          }
            $item_data_row->name=$name;
            $item_data_row->description=$description;
            $item_data_row->categoryname=$categoryname;

          unset($item_data_row->name_ar);
          unset($item_data_row->description_ar);
          unset($item_data_row->categoryname_ar);


      }    
      
$name=$res->name;     
if($lang=='ar'){
  $name=$res->name_ar;
}
  $res->name=$name;
unset($res->name_ar);
      $result2[]=$res;

      }
        $vendor_data=array('vendor_id' =>$vendor_branch_id,'vendor_name'=>$vendor_name,'branch_id'=>$branch_id,'branch_name'=>$branch_name,'vendor_logo_image'=>$vendor_logo_image,'vendor_reservation_table_show'=>$vendor_reservation_table_show,'dnn_table'=>$dnn_table,'vendor_store_bg_image'=>$vendor_store_bg_image,'vendor_store_bg_video'=>$vendor_store_bg_video,'vendor_feature_gif_bg'=>$vendor_feature_gif_bg );    

      return response()->json(['data'=>['result' => $result2,'vendor_data'=>$vendor_data] ,'msg'=>'success','status_code'=>$this->successStatus]);
      }
      public function add_item_to_my_favorites($itemid,Request $request){       
        $token = $request->header('token');     
        $itemsfavouriteModel = config('admin.database.items_favourite_model');
        $inputs['item_id']=$itemid;
        $inputs['token']=$token;
        $checkexit=$itemsfavouriteModel::all()->where('item_id','=',$itemid)->where('token','=',$token);
        if(count($checkexit)>0){
        return response()->json(['msg'=>'this item added before to favourite list','status_code'=>$this->successStatus]);
        }else{
           $result=$itemsfavouriteModel::create($inputs);
                 return response()->json(['data'=> $result,'msg'=>'success','status_code'=>$this->successStatus]);
        }
       
      }     
       
      public function add_item_to_cart(Request $request){        
        $token=$request->header('token');
        $request_data=$request->all();               
        $token=isset($token)?$token:'';
        $session_code=isset($request_data['session_code'])?$request_data['session_code']:0;
        $itemid=isset($request_data['item_id'])?$request_data['item_id']:0; 
        $branch_id=isset($request_data['branch_id'])?$request_data['branch_id']:0;                  
                           
        $option_id=isset($request_data['option_id'])?$request_data['option_id']:0;
        $option_quantity=isset($request_data['quantity'])?$request_data['quantity']:0;
        $size_id=isset($request_data['size_id'])?$request_data['size_id']:'';  
        $item_options_json=isset($request_data['item_options'])?$request_data['item_options']:''; 
        $quantity=isset($request_data['quantity'])?$request_data['quantity']:1;               
        $note=isset($request_data['note'])?$request_data['note']:'';               
        
        if(!empty($item_options_json)){
            $item_options_json=json_encode($item_options_json,true);        
        }
        $cartModel = config('admin.database.cart_model');
        $itemsModel = config('admin.database.items_model');        
       $size_price='';
       $size_name='';
       if(!empty($size_id)){
        $res_itemsizes=DB::table('itemsizes')->where('id', $size_id)->first();
        $size_price=$res_itemsizes->price;
        $size_name=$res_itemsizes->name;   
        $inputs['size_id']=$size_id;     
        }
        $inputs['item_id']=$itemid;
        if(empty($token)){        // first time            
            if($session_code==0){        
              $inputs['session_code']=rand();        
            }else{
               $inputs['session_code']=$session_code;
            }

      }else{        
        $inputs['token']=$token;
      }
      if(!empty($item_options_json)){
       $inputs['item_options']=$item_options_json;  
      }
      $inputs['branch_id']=$branch_id;
      if(empty($token)){ // not logged and use session_code
        /*start add quantity to same item */
          $result_check_exist_same_item = DB::table('cart')
 ->leftjoin('items', 'cart.item_id', '=', 'items.id')
 ->leftjoin('itemsizes', 'cart.size_id', '=', 'itemsizes.id')
 ->leftjoin('vendors', 'items.vendor_branch_user_id', '=', 'vendors.id')
              ->select('cart.id','cart.item_id','cart.branch_id','quantity','item_options','itemsizes.price','size_id','items.price as default_price',                           
                'items.name','items.name_ar','item_logo','item_logo_details','vendors.id as vendorid','vendors.vendor_name','vendors.vendor_name_ar','vendors.vendor_logo as branchlogo')            
              ->where(['session_code'=> $inputs['session_code'],
              'cart.item_id'=>$itemid,'cart.branch_id'=>$branch_id,'cart.size_id'=>$size_id,'cart.item_options'=>$item_options_json,
                  'confirmed'=>'0'])->first();
               //   print_r($item_options_json);die;
              if(!empty($result_check_exist_same_item)) {
                //echo 'there are same item added before'.$inputs['session_code'];die;
                $new_quantity=$result_check_exist_same_item->quantity+1;
               // echo $result_check_exist_same_item->id;die;
                $res=$cartModel::where(['id' =>$result_check_exist_same_item->id,'session_code'=>$inputs['session_code']])->update(['quantity'=>$new_quantity]);


                $result_f=$cartModel::all()->toArray();
                foreach ($result_f as $result) {                
                  unset($result['token']);
                }

              }else{   // proceed add new one;
                 $inputs['note']=$note;
                 $inputs['quantity']=$quantity;
                 $result=$cartModel::create($inputs);
                 $result['quantity']=$quantity;
            }
        /*end add quantity to same item */
        
       //   $result=$cartModel::create($inputs);
         
          $new_cartid_incart=$result['id'];
          $new_itemid_incart=$result['item_id'];
          $new_sessioncode_incart=$result['session_code'];          
          $res=DB::table('items')->where('id', $itemid)->first();

          $vendor_branch_id_last_added=$res->vendor_branch_user_id;                             

          $all_cart=DB::table('cart')->where(['session_code'=>$new_sessioncode_incart])->where('id','<>',$new_cartid_incart)->get();      

          foreach ($all_cart as $carts){           
            if($branch_id!=$carts->branch_id){
                  $whereArray = array('id'=>$carts->id);
                $res_delete=DB::table('cart')->where($whereArray)->delete(); 
            }
          
          }

      }else{ // logged and have token
        /*start check exit same item */
          $result_check_exist_same_item = DB::table('cart')
 ->leftjoin('items', 'cart.item_id', '=', 'items.id')
 ->leftjoin('itemsizes', 'cart.size_id', '=', 'itemsizes.id')
 ->leftjoin('vendors', 'items.vendor_branch_user_id', '=', 'vendors.id')
              ->select('cart.id','cart.item_id','cart.branch_id','quantity','item_options','itemsizes.price','size_id','items.price as default_price',                           
                'items.name','items.name_ar','item_logo','item_logo_details','vendors.id as vendorid','vendors.vendor_name','vendors.vendor_name_ar','vendors.vendor_logo as branchlogo')            
              ->where(['token'=> $token,
              'cart.item_id'=>$itemid,'cart.branch_id'=>$branch_id,'cart.size_id'=>$size_id,'cart.item_options'=>$item_options_json,
                  'confirmed'=>'0'])->first();
               //   print_r($item_options_json);die;
              if(!empty($result_check_exist_same_item)) {
                //echo 'there are same item added before'.$inputs['session_code'];die;
                $new_quantity=$result_check_exist_same_item->quantity+1;
               // echo $result_check_exist_same_item->id;die;
                $res=$cartModel::where(['id' =>$result_check_exist_same_item->id,'token'=>$token])->update(['quantity'=>$new_quantity]);

                $result_f=$cartModel::all()->toArray();
                foreach ($result_f as $result) {                
                  unset($result['token']);
                }

              }else{   // proceed add new one;
                 $inputs['note']=$note;
                 $inputs['quantity']=$quantity;
                  //return response()->json($inputs);die;

              /*   unset($inputs['item_options']);
                 $test=array();
                 $test=$inputs;
                 $test['item_options']=json_decode($item_options_json);
                 echo json_encode($test);die;*/
                 $result=$cartModel::create($inputs);
                 $result['quantity']=$quantity;
            }
        /*end check exit same item*/
       /* $inputs['item_id']=$itemid;
        $inputs['token']=$token;
        $inputs['quantity']=$quantity;
        $inputs['note']=$note;*/
        //$result=$cartModel::create($inputs);



        $new_cartid_incart=$result['id'];
        $new_itemid_incart=$result['item_id'];                
          $res=DB::table('items')->where('id', $itemid)->first();   
          $vendor_branch_id_last_added=$res->vendor_branch_user_id;                                   
         $all_cart=DB::table('cart')->where(['token'=>$token])->where('id','<>',$new_cartid_incart)->get();
          foreach ($all_cart as $carts){           
            if($branch_id!=$carts->branch_id){
                  $whereArray = array('id'=>$carts->id);
                $res_delete=DB::table('cart')->where($whereArray)->delete(); 
            }
          
          }

       }     if(!empty($size_id)){ 
                $result['size_id']=$size_id;
                $result['size_name']=$size_name;
             }
          if(!empty($item_options_json)){
              $result['item_options']= json_decode($item_options_json,true);
             }

            // print_r($result);die;
              return response()->json(['data'=> $result,'msg'=>'success add to cart','status_code'=>$this->successStatus]);       
      }      
public function check_added_to_another_branch(Request $request){
    $request_data=$request->all();      
    $token=$request->header('token');
    $token=isset($token)?$token:'';
    $session_code=isset($request_data['session_code'])?$request_data['session_code']:0;
    $branch_id=$request_data['branch_id'];    
    if(!empty($token)){
      $res=DB::table('cart')->where(['token'=>$token,'confirmed'=>'0'])->first();
      if(!empty($res) && $res->branch_id!=$branch_id){
          return response()->json(['msg'=>'warning! you added meals for another branch before','status_code'=>404]);
      }      
    }elseif($session_code!=''){
      $res=DB::table('cart')->where(['session_code'=>$session_code,'confirmed'=>'0'])->first();      
      if(!empty($res) && $res->branch_id!=$branch_id){
          return response()->json(['msg'=>'warning! you added meals for another branch before','status_code'=>404]);  
      }      
    }
    return response()->json(['msg'=>'empty cart','status_code'=>$this->successStatus]);
}
public function list_cart(Request $request){
      $request_data=$request->all();
      $token=$request->header('token');
      $lang=$request->header('lang');
      $token=isset($token)?$token:'';
      $branch_id=isset($request_data['branch_id'])?$request_data['branch_id']:'';
      $url=str_replace('public','',url(""));
      $session_code=isset($request_data['session_code'])?$request_data['session_code']:'';
      $cartModel = config('admin.database.cart_model');
$result=array();
if($session_code!=''){
                if($branch_id!=''){
                  $result = DB::table('cart')
                 ->leftjoin('items', 'cart.item_id', '=', 'items.id')
                 ->leftjoin('itemsizes', 'cart.size_id', '=', 'itemsizes.id')
                 ->leftjoin('vendors', 'items.vendor_branch_user_id', '=', 'vendors.id')
                              ->select('cart.id','cart.item_id','cart.branch_id','quantity','item_options','itemsizes.price','size_id','items.price as default_price','note','itemsizes.name as sizename',                           
                                'items.name','items.name_ar','item_logo','item_logo_details','vendors.id as vendorid','vendors.vendor_name','vendors.vendor_name_ar','vendors.vendor_logo as branchlogo')            
                              ->where(['session_code'=>$session_code,'confirmed'=>'0','cart.branch_id'=>$branch_id])
                              ->groupBy('cart.id','cart.item_id','size_id','item_options','quantity')
                              ->get();

                }else{
                  $result = DB::table('cart')
                 ->leftjoin('items', 'cart.item_id', '=', 'items.id')
                 ->leftjoin('itemsizes', 'cart.size_id', '=', 'itemsizes.id')
                 ->leftjoin('vendors', 'items.vendor_branch_user_id', '=', 'vendors.id')
                              ->select('cart.id','cart.item_id','cart.branch_id','quantity','item_options','itemsizes.price','size_id','items.price as default_price','note','itemsizes.name as sizename',                           
                                'items.name','items.name_ar','item_logo','item_logo_details','vendors.id as vendorid','vendors.vendor_name','vendors.vendor_name_ar','vendors.vendor_logo as branchlogo')            
                              ->where(['session_code'=>$session_code,'confirmed'=>'0'])
                              ->groupBy('cart.id','cart.item_id','size_id','item_options','quantity')
                              ->get();
                }
        }elseif(!empty($token)){
                  if($branch_id!=''){
                            $result = DB::table('cart')
                   ->leftjoin('items', 'cart.item_id', '=', 'items.id')
                   ->leftjoin('itemsizes', 'cart.size_id', '=', 'itemsizes.id')
                   ->leftjoin('vendors', 'items.vendor_branch_user_id', '=', 'vendors.id')
                                ->select('cart.id','cart.item_id','cart.branch_id','quantity','item_options','itemsizes.price','size_id','items.price as default_price','note','itemsizes.name as sizename',
                                  'items.name','items.name_ar','item_logo','item_logo_details','vendors.id as vendorid','vendors.vendor_name','vendors.vendor_name_ar','vendors.vendor_logo as branchlogo')
                                ->where(['token'=>$token,'confirmed'=>'0','cart.branch_id'=>$branch_id])
                                ->groupBy('cart.id','cart.item_id','size_id','item_options','quantity')
                                ->get();     
                    }else{
                                  $result = DB::table('cart')
                     ->leftjoin('items', 'cart.item_id', '=', 'items.id')
                     ->leftjoin('itemsizes', 'cart.size_id', '=', 'itemsizes.id')
                     ->leftjoin('vendors', 'items.vendor_branch_user_id', '=', 'vendors.id')
                                  ->select('cart.id','cart.item_id','cart.branch_id','quantity','item_options','itemsizes.price','size_id','items.price as default_price','note','itemsizes.name as sizename',
                                    'items.name','items.name_ar','item_logo','item_logo_details','vendors.id as vendorid','vendors.vendor_name','vendors.vendor_name_ar','vendors.vendor_logo as branchlogo')
                                  ->where(['token'=>$token,'confirmed'=>'0'])
                                  ->groupBy('cart.id','cart.item_id','size_id','item_options','quantity')
                                  ->get();     
                    }
        }

              $total_price=0;
              $itemsnum=0;
              $total_item_option_price=0;
              $result2=array();   
             // $countitems=1;
              $total_num_of_item_incart=0;
              if(count($result)==0){
                  return response()->json(['msg'=>'no items in cart','status_code'=>$this->successStatus]);
              }
              $new_price=0;
              foreach ($result as $res) {                 
                $quantity=$res->quantity;
              //  $countitems=$res->countitems;                
              //  $res->quantity=$res->countitems;
                if(empty($res->size_id)){                  
                  unset($res->price);
                  unset($res->size_id);
                  unset($res->sizename);
                   $res->price=$res->default_price;
                  // unset($res->countitems);
                   //$res->total_item_price=$res->default_price*$countitems*$quantity;                  
                  $new_price=$res->price;
                   unset($res->default_price);
                }else{
                  unset($res->default_price);

                  //$res->total_item_price=$res->price*$countitems*$quantity;
                   $new_price=$res->price;
                }
                $total_num_of_item_incart=$total_num_of_item_incart+$quantity;
                $total_item_option_price=0;
                      $item_options_name='';
                     if(!empty($res->item_options)){
                          $item_options_json=json_decode($res->item_options,true);
                          $res->item_options=$item_options_json;          
                          $sum_one_option=0;                          
                          foreach($item_options_json as $item_option) {                        
                            $total_item_option_price=$total_item_option_price+$item_option['price'];
                            $sum_one_option=$sum_one_option+$item_option['price'];
                            $item_options_name.=$item_option['quantity'].' '.$item_option['name'].',';
                            $res->item_options_name=trim($item_options_name,',');
                          }                      
                          $res->total_option_price=$sum_one_option;                      
                         // $res->total_item_price= $res->total_item_price+$sum_one_option;
                          $res->total_item_price= ($new_price+$sum_one_option)*$res->quantity;  
                          $total_price=$total_price+$res->total_item_price;
                      }else{
                        unset($res->item_options);
                        //  $res->total_item_price= $res->total_item_price;
                          $res->total_option_price=0;
                          $res->total_item_price= $new_price*$res->quantity;
                          $total_price=$total_price+$res->total_item_price;
                      }          
                                                             
                    $itemsnum++;
                
                  $res->branchlogo=$url.'storage/'.$res->branchlogo;

                  $name=$res->name;
                  $vendor_name=$res->vendor_name;
                  if($lang=='ar'){
                    $name=$res->name_ar;
                    $vendor_name=$res->vendor_name_ar;
                  }
                  $res->name=$name;
                  $res->vendor_name=$vendor_name;

                  unset($res->name_ar);
                  unset($res->vendor_name_ar);

                $res_branch_details=DB::table('branches')->where('id', $res->branch_id)->first();                
                $Branch_details['vendor_id']=$res->vendorid;
                $Branch_details['branch_id']=$res->branch_id;                
                $Branch_details['vendor_name']=$vendor_name;
                $Branch_details['dnn_table']=$res_branch_details->dnn_table;
                $Branch_details['longitude']=$res_branch_details->longitude;
                $Branch_details['latitude']=$res_branch_details->latitude;



                $branch_name=$res_branch_details->name;
                if($lang=='ar'){
                  $branch_name=$res_branch_details->name_ar;
                }
                $Branch_details['branch_name']=$branch_name;
                unset($res_branch_details->name_ar);                

                $Branch_details['branch_logo']=$res->branchlogo;

              /*  unset($res->branchid);
                unset($res->branchname);
                unset($res->branchlogo);
              */
                
if($res->item_logo!=''){
        $res->image=$url.'storage/'.$res->item_logo;          

}else{
      $res->image='';
}
          if($res->item_logo_details!=''){            
            $res->image_details=$url.'storage/'.$res->item_logo_details;
          }else{
            $res->image_details='';
          }
          unset($res->item_logo);
          unset($res->item_logo_details);


              }           
$setting_data=DB::table('settings')->where(['id'=>1])->first();
$settings_taxes=$setting_data->taxes;
$settings_fees_cash=$setting_data->fees_cash;
$settings_fees_online=$setting_data->fees_online;
$sub_total=$total_price;
$total_price=$sub_total+($sub_total*($settings_taxes/100));
return response()->json(['data'=>['result' => $result,'branch_details'=>$Branch_details,'total_num_of_item_incart'=>$total_num_of_item_incart,'taxes'=>$settings_taxes,'fees_cash'=>$settings_fees_cash,'fees_online'=>$settings_fees_online,'sub_total'=>$sub_total,
  'total_price'=>$total_price],'msg'=>'success','status_code'=>$this->successStatus]);
                  

      }
      public function update_cart_by_quantity(Request $request){
        $request_data=$request->all();
        $token=$request->header('token');        
        $lang=$request->header('lang');
        $id=$request_data['id'];
        $session_code=isset($request_data['session_code'])?$request_data['session_code']:'';                    
        $cartModel = config('admin.database.cart_model');      
        $quantity=$request_data['quantity'];
        $res='';

        if(!empty($token)){
          $res=$cartModel::where(['id' =>$id,'token'=>$token])->update(['quantity'=>$quantity]);      
        }else{        
          $res=$cartModel::where(['id' =>$id,'session_code'=>$session_code])->update(['quantity'=>$quantity]);
        }        
        
        if(!empty($token)){            
                 $result_update = DB::table('cart')
              ->leftjoin('items', 'item_id', '=', 'items.id')
               ->leftjoin('itemsizes', 'cart.size_id', '=', 'itemsizes.id')
              ->select('cart.id','cart.item_id','items.price','quantity','item_options',
                'items.name','items.name_ar','itemsizes.price','size_id','items.price as default_price')

              ->where(['token'=>$token,'confirmed'=>'0','cart.id'=>$id])              
              ->get();              

        }else{
           $result_update = DB::table('cart')
                ->leftjoin('items', 'item_id', '=', 'items.id')
               ->leftjoin('itemsizes', 'cart.size_id', '=', 'itemsizes.id')
              ->select('cart.id','cart.item_id','items.price','quantity','item_options',
                'items.name','items.name_ar','itemsizes.price','size_id','items.price as default_price')
                ->where(['session_code'=>$session_code,'confirmed'=>'0','cart.id'=>$id])
                ->groupBy('cart.item_id')
                ->get();                
        }



           $result_update2=array();
      //  print_r($result_update);die;
           $new_price_update=0;
        foreach ($result_update as $update_row) {
                  $quantity=$update_row->quantity;
                   if(empty($update_row->size_id)){                  
                  unset($update_row->price);
                  unset($update_row->size_id);
                 // print_r($update_row);die;
                   $update_row->price=$update_row->default_price;
                   $new_price_update=$update_row->price;
                  // unset($res->countitems);
                   //$update_row->total_item_price=$update_row->default_price*$quantity;                  
                   unset($update_row->default_price);
                }else{
                  unset($update_row->default_price);
                 // $update_row->total_item_price=$update_row->price*$quantity;
                   $new_price_update=$update_row->price;
                  
                }


                  $name=$update_row->name;

                  if($lang=='ar'){
                    $name=$update_row->name_ar;
                  }
                  $update_row->name=$name;
                  unset($update_row->name_ar);
                //$update_row->total_item_price=$total_item_price_update;


                        
                        if(!empty($update_row->item_options)){
                          $item_options_json_update=json_decode($update_row->item_options,true);
                          $update_row->item_options=$item_options_json_update;          
                          $sum_one_option_update=0;
                         // $total_item_option_price_update=0;                         
                          foreach($item_options_json_update as $item_option) {                        
                            //$total_item_option_price_update=$total_item_option_price_update+$item_option['price'];
                            $sum_one_option_update=$sum_one_option_update+$item_option['price'];
                          }                      
                       //   $update_row->total_option_price=$sum_one_option_update;                      
                         // $res->total_item_price= $res->total_item_price+$sum_one_option;
                          $update_row->total_item_price= ($new_price_update+$sum_one_option_update)*$update_row->quantity;  
                          //$total_item_price_update=($new_price+$sum_one_option)*$update_row->quantity;
                        //  $total_price=$total_price+$update_row->total_item_price;
                       // unset($update_row->item_options);  
                      }else{
                        unset($update_row->item_options);
                        //  $res->total_item_price= $res->total_item_price;
                        //  $update_row->total_option_price=0;
                          $update_row->total_item_price= $new_price_update*$update_row->quantity;
                        //  $total_item_price_update=$new_price*$update_row->quantity;

                         // $total_price=$total_price+$update_row->total_item_price;
                      }






                $result_update2[]=$update_row;
        }








       
              // to get total item
$url=str_replace('public','',url(""));
if(empty($token)){
  $result = DB::table('cart')
 ->leftjoin('items', 'cart.item_id', '=', 'items.id')
 ->leftjoin('itemsizes', 'cart.size_id', '=', 'itemsizes.id')
 ->leftjoin('vendors', 'items.vendor_branch_user_id', '=', 'vendors.id')
              ->select('cart.id','cart.item_id','cart.branch_id','quantity','item_options','itemsizes.price','size_id','items.price as default_price',                           
                'items.name','item_logo','item_logo_details','vendors.id as vendorid','vendors.vendor_name','vendors.vendor_logo as branchlogo')            
              ->where(['session_code'=>$session_code,'confirmed'=>'0'])
              ->groupBy('cart.id','cart.item_id','size_id','item_options','quantity')
              ->get();
        }else{
             $result = DB::table('cart')
 ->leftjoin('items', 'cart.item_id', '=', 'items.id')
 ->leftjoin('itemsizes', 'cart.size_id', '=', 'itemsizes.id')
 ->leftjoin('vendors', 'items.vendor_branch_user_id', '=', 'vendors.id')
              ->select('cart.id','cart.item_id','cart.branch_id','quantity','item_options','itemsizes.price','size_id','items.price as default_price',
                'items.name','item_logo','item_logo_details','vendors.id as vendorid','vendors.vendor_name','vendors.vendor_logo as branchlogo')
              ->where(['token'=>$token,'confirmed'=>'0'])
              ->groupBy('cart.id','cart.item_id','size_id','item_options','quantity')
              ->get(); 
        }

              $total_price=0;
              $itemsnum=0;
              $total_item_option_price=0;
              $result2=array();   
             // $countitems=1;
              $total_num_of_item_incart=0;
              if(count($result)==0){
                  return response()->json(['msg'=>'no items in cart','status_code'=>$this->successStatus]);
              }
              $new_price=0;
              foreach ($result as $res) {                 
                $quantity=$res->quantity;
                
              //  $res->quantity=$res->countitems;
                if(empty($res->size_id)){                  
                  unset($res->price);
                  unset($res->size_id);
                   $res->price=$res->default_price;
                  // unset($res->countitems);
                   //$res->total_item_price=$res->default_price*$countitems*$quantity;                  
                  $new_price=$res->price;
                   unset($res->default_price);
                }else{
                  unset($res->default_price);
                  //$res->total_item_price=$res->price*$countitems*$quantity;
                   $new_price=$res->price;
                }
                $total_num_of_item_incart=$total_num_of_item_incart+$quantity;
                     if(!empty($res->item_options)){
                          $item_options_json=json_decode($res->item_options,true);
                          $res->item_options=$item_options_json;          
                          $sum_one_option=0;                          
                          foreach($item_options_json as $item_option) {                        
                            $total_item_option_price=$total_item_option_price+$item_option['price'];
                            $sum_one_option=$sum_one_option+$item_option['price'];
                          }                      
                          $res->total_option_price=$sum_one_option;                      
                         // $res->total_item_price= $res->total_item_price+$sum_one_option;
                          $res->total_item_price= ($new_price+$sum_one_option)*$res->quantity;  
                          $total_item_price=($new_price+$sum_one_option)*$res->quantity;
                          $total_price=$total_price+$res->total_item_price;
                      }else{
                        unset($res->item_options);
                        //  $res->total_item_price= $res->total_item_price;
                          $res->total_option_price=0;
                          $res->total_item_price= $new_price*$res->quantity;
                          $total_item_price=$new_price*$res->quantity;

                          $total_price=$total_price+$res->total_item_price;
                      }          
                                                             
                    $itemsnum++;
                //  echo 'dsadasd';die;
                  $res->branchlogo=$url.'storage/'.$res->branchlogo;
            //    echo 'dasdas';die;
                $res_brancbh_details=DB::table('branches')->where('id', $res->branch_id)->first();
//echo 'dasdas';die;
                //print_r($res_brancbh_details);die;
                $Branch_details['vendor_id']=$res->vendorid;
                $Branch_details['branch_id']=$res->branch_id;
                $Branch_details['vendor_name']=$res->vendor_name;
                $Branch_details['branch_name']=$res_brancbh_details->name;
                $Branch_details['branch_logo']=$res->branchlogo;

              /*  unset($res->branchid);
                unset($res->branchname);
                unset($res->branchlogo);
              */
                
if($res->item_logo!=''){
        $res->image=$url.'storage/'.$res->item_logo;          

}else{
      $res->image='';
}
          if($res->item_logo_details!=''){            
            $res->image_details=$url.'storage/'.$res->item_logo_details;
          }else{
            $res->image_details='';
          }
          unset($res->item_logo);
          unset($res->item_logo_details);


              }


            


              //$result_update2['total_item_price']=$total_item_price;           
              $result_update2['total_price']=$total_price;
              $result_update2['total_num_of_item_incart']=$total_num_of_item_incart;
              
        return response()->json(['data'=>['result' => $result_update2],'msg'=>'success','status_code'=>$this->successStatus]);
      }
       public function delete_cart(Request $request){
        $request_data=$request->all();
        $token=$request->header('token');
        $token=isset($token)?$token:'';
        $session_code=isset($request_data['session_code'])?$request_data['session_code']:0;
        /*$itemid=isset($request_data['item_id'])?$request_data['item_id']:0;  */   
            $cart_id=$request_data['cart_id'];
            if(empty($token)){          
                  $whereArray = array('id'=>$cart_id,'session_code'=>$session_code);
                  $result=DB::table('cart')->where($whereArray)->delete();
                  if($result){
                    $status_code=$this->successStatus;
                    $msg='success deleted';
                  }else{
                    $msg='not exit';
                    $status_code=401;
                  }
            }else{
                  $whereArray = array('id'=>$cart_id,'token'=>$token);
                  $result=DB::table('cart')->where($whereArray)->delete();
                  if($result){
                    $status_code=$this->successStatus;
                    $msg='success deleted';
                  }else{
                    $msg='not exit';
                    $status_code=401;
                  }
            }
        return response()->json(['msg'=>$msg,'status_code'=>$status_code]);
}
    public function confirm_order(Request $request){
      $ordersModel = config('admin.database.orders_model');
      $cartModel = config('admin.database.cart_model');

         $request_data=$request->all();
          $token=$request->header('token');
          $token=isset($token)?$token:'';
          $num_of_people=isset($request_data['num_of_people'])?$request_data['num_of_people']:0;
          $payment_method=isset($request_data['payment_method'])?$request_data['payment_method']:'';
          $promocode=isset($request_data['promocode'])?$request_data['promocode']:'';
          $transaction_num=isset($request_data['transaction_num'])?$request_data['transaction_num']:'';
          $url=str_replace('public','',url(""));
        if(!empty($token)){            
              $result = DB::table('cart')
              ->leftjoin('items', 'cart.item_id', '=', 'items.id')
              ->leftjoin('itemsizes', 'cart.size_id', '=', 'itemsizes.id')
              ->leftjoin('vendors', 'items.vendor_branch_user_id', '=', 'vendors.id') 
              ->select('cart.id','cart.item_id','cart.branch_id','cart.quantity','item_options','note','itemsizes.price','size_id','items.price as default_price',DB::raw('TIME_TO_SEC(items.normal_time) as normal_time'),DB::raw('TIME_TO_SEC(items.rush_time) as rush_time'),
                'vendors.vendor_logo as branchlogo',
                'items.name')
              ->where(['token'=>$token,'confirmed'=>'0'])
              ->groupBy('cart.id','cart.size_id','cart.item_options','quantity','note')              
              ->get();     
        }       
              $total_price=0;
              $itemsnum=0;
              $total_item_option_price=0;
              $total_item_price=0;                          
              $sub_total=0;
              $result2=array();            
              if(count($result)==0){
                return response()->json(['msg'=>'order confirmed before','status_code'=>200]);
              }          
              $new_price=0;  
              //print_r($result);die;
              $timer=array();
              foreach ($result as $res) { 
                $branch_timer_data=DB::table('branches')->where(['id'=>$res->branch_id])->first();
                
                if($branch_timer_data->timer_status=='normal time'){
                    array_push($timer,$res->normal_time);      
                }else{
                    array_push($timer,$res->rush_time);
                }
              
              if(empty($res->size_id)){                  
                  unset($res->price);
                  unset($res->size_id);
                   $res->price=$res->default_price;
                   //$res->total_item_price=$res->default_price*$res->countitems*$res->quantity;                  
                    $new_price=$res->default_price;                  
                   unset($res->default_price);
                }else{
                   unset($res->default_price);
                   //$res->total_item_price=$res->price*$res->countitems*$res->quantity;
                   $new_price=$res->price;
                }

                      $item_options_json=json_decode($res->item_options,true);
                      $res->item_options=$item_options_json;          
                      $sum_one_option=0;
                      $item_options_name='';
                      
                      if(!empty($item_options_json)){//echo '1';die;
                      foreach($item_options_json as $item_option) {                        
                          $total_item_option_price=$total_item_option_price+$item_option['price'];
                           
                            $sum_one_option=$sum_one_option+$item_option['price'];
                            $item_options_name.=', '.$item_option['quantity'].' '.$item_option['name'];
                      }                                 
                      $res->total_option_price=$sum_one_option;                      
                      //$res->total_item_price= $res->total_item_price+$sum_one_option;

                      $res->total_item_price= ($new_price+$sum_one_option)*$res->quantity;          
                       
                      $total_price=$total_price+$res->total_item_price;
                    }else{ //echo '2';die;
                      $res->total_option_price=0;
                      //$res->total_item_price= $res->total_item_price+$sum_one_option;
                        $res->total_item_price=$new_price*$res->quantity;

                        $total_price=$total_price+$res->total_item_price;
                      //echo  $total_price;die;
                    }                                        
              $itemsnum++;
              $branch_id=$res->branch_id;

              $result2[]=$res;
                  
              }             
              $total_num_of_item_incart=$itemsnum; 

$user_data_info =DB::table('users')->where(['id'=>$token])->first();

if($user_data_info->verification_email_status==0){
  return response()->json(['msg'=>'please verify your email address then place order', 'status_code'=>401]);
}

//$check_details = DB::table('branches')->where(['id'=>$branch_id])->first();   
/*if($check_details->branch_status=='closed'){
    return response()->json(['msg'=>'sorry branch is closed now,try later', 'status_code'=>$this->successStatus]);
}*/

$branch_data=DB::table('branches')->where(['id'=>$branch_id])->first();
$vendor_data=DB::table('vendors')->where('id', $branch_data->vendor_id)->first();   
//print_r($time_status);die;
date_default_timezone_set('Africa/Cairo');
//date_default_timezone_set('Asia/Riyadh');
$current_datetime=date("Y-m-d G:i:s"); 
$timer=max($timer);
$fees=0;
$setting_data=DB::table('settings')->where(['id'=>1])->first();
$promocode_discount=0;
$sub_total=$total_price;
$fees_with_promo=0;
$total_price_with_discount=0;
$fees_cash_with_discount=0;
$fees_online_discount=0;
$promocode_type='';
if(isset($promocode) && $promocode!=''){
    $check_valid=DB::table('promocode')->where(['promocode'=>$promocode])->where(DB::Raw('expire_date'),'>',DB::Raw('now()'))->first();
    if(count((array)$check_valid)>0){
      
            $discount=$check_valid->discount;
            $promocode_type=$check_valid->type;
            $promocode_discount=$discount;
            $type=$check_valid->type;
            $vendor_id=$check_valid->vendor_id;                        
            if($check_valid->type=='store'){  // store          
              if($check_valid->vendor_id!=$branch_data->vendor_id){
                  return response()->json(['msg'=>'sorry this promocode for another store', 'status_code'=>401]);
                }

                  $total_price_with_discount=$sub_total*($check_valid->discount/100);     
                  $total_price=$sub_total-$total_price_with_discount;
                 // echo $total_price;die;
            }else{ $fees_with_promo=1;  // weasy fees discount                
                if($payment_method=='cash'){  
                  $fees=$setting_data->fees_cash;
                  $fees_cash_with_discount=$setting_data->fees_cash*($check_valid->discount/100); 
                  $fees_cash=$setting_data->fees_cash-$fees_cash_with_discount;                   
                  $total_price=Round($sub_total+$fees_cash,2);                    
                }elseif($payment_method=='online'){                   
                  $fees=$setting_data->fees_online;
                  $fees_online_discount=$setting_data->fees_online*($check_valid->discount/100);
                  $fees_online=$setting_data->fees_online-$fees_online_discount;
                  $total_price=Round($sub_total+$fees_online,2);                    
                }
                                
            }


    }else{
      return response()->json(['msg'=>'expire promo code','status_code'=>401]);
    }
}
//echo $sub_total;die;
$total_with_taxes=Round($sub_total*($setting_data->taxes/100),2);
//echo $total_with_taxes;die; 

$total_price=Round($total_price+$total_with_taxes,2);
  //echo $total_price;die;
              
/*if(isset($request_data['payment_method']) && $request_data['payment_method']!=''){
  if($request_data['payment_method']=='cash'){
    $total_price=$total_price+$vendor_data->reservation_table_pay_cash;
  }else{
    $total_price=$total_price+$vendor_data->reservation_table_pay_online;;
  }
}*/
//echo $sub_total;die;
$orders_list_Model = config('admin.database.orders_list_model');
$input_orders_list['token']=$token;
$input_orders_list['vendor_id']=$branch_data->vendor_id;
$input_orders_list['branch_id']=$branch_id;
$input_orders_list['total_price']=$total_price;
$input_orders_list['status']='';
$input_orders_list['num_of_people']=$num_of_people;
$input_orders_list['timer']=$timer;
$input_orders_list['created_at']=$current_datetime;
$orders_list_data=$orders_list_Model::create($input_orders_list);     
$order_id=$orders_list_data['id'];
$order_datetime=$orders_list_data['created_at'];
$dnn_table_fees=0;
$money=0;
/* start create reservation table*/       
if(isset($request_data['payment_method']) && $request_data['payment_method']!=''){
  
        $orderslistModel=config('admin.database.orders_list_model');

        $paymentsmodel=config('admin.database.payments_model');
        $order_list_data=DB::table('orders_list')->where('id', $order_id)->first();                    
        $payment_method=$request_data['payment_method'];
      //  $money=$order_list_data->total_price;
        $branch_id=$order_list_data->branch_id;
        $vendor_id=$order_list_data->vendor_id;

        $user_data=DB::table('users')->where('id', $token)->first();
        $setting_data=DB::table('settings')->where(['id'=>1])->first();
        $setting_point=$setting_data->point;
        $setting_money=$setting_data->money;
        $userpoints=$user_data->points;
        $money_points=($userpoints*$setting_money)/$setting_point;   // $money_points=$userpoints/10;
      //  echo $money_points;die;        
          $customer_Model = config('admin.database.users_front_model');



        if($payment_method=='points'){
              if($money_points<$total_price){
                    return response()->json(['msg'=>'sorry you points less than total price of order to ship','status_code'=>401]);
              }
              if($userpoints<100){
                    return response()->json(['msg'=>'sorry you points less than 100 point','status_code'=>401]); 
              }
                // deduction from points to pay     
               // echo  $money_points;die;
                $new_money_points=$money_points-$total_price;   // 200-33=
               // echo $new_money_points;die;
                if($new_money_points>0){
                    $points=($new_money_points*$setting_point)/$setting_money; // convert money to points              
                }else{
                    $points=0;              
                } //echo $points;die;
                //echo $total_price;die;
                    $customer_Model::where(['id' =>$token])->update(['points'=>$points]);
        }elseif($payment_method=='cash'){// echo $total_price;die;
            if($fees_with_promo==0){
                $fees=$setting_data->fees_cash;
                $money=$total_price+$setting_data->fees_cash;
              }else{
                $money=$total_price;
              }
            //echo $money;die;
          //  $orderslistModel::where(['id' =>$order_id])->update(['total_price'=>$money]);

        }elseif($payment_method=='online') {
          if($fees_with_promo==0){
            $fees=$setting_data->fees_online;
            $money=Round($total_price+$setting_data->fees_online,2);
          }else{
            $money=Round($total_price,2);
          }
           //echo $money;die;
          
        }
        //echo $new_money_points;die;
         $orderslistModel::where(['id' =>$order_id])->update(['total_price'=>$money]);
        //echo $money;die;
        //echo $fees;die;
        if(isset($num_of_people) && $num_of_people>0 && $request_data['payment_method']!='points'){            
            if($request_data['payment_method']=='cash'){
              $dnn_table_fees=$vendor_data->reservation_table_pay_cash;
            }elseif($request_data['payment_method']=='online'){
              $dnn_table_fees=$vendor_data->reservation_table_pay_online;
            }
              $money=$money+$dnn_table_fees;
             $orderslistModel::where(['id' =>$order_id])->update(['total_price'=>$money]);

        }
//echo $money;die;
        $input['order_id']=$order_id;
        $input['token']=$token;
        $input['payment_type']=$payment_method;        
        $input['fees']=$fees;
        $input['commission']=$vendor_data->fees;
        $input['taxes']=$setting_data->taxes;
        $input['promocode_discount']=$promocode_discount;
        $input['promocode_type']=$promocode_type;
        $input['money']=$money;
        $input['sub_total']=$sub_total;
        $input['vendor_id']=$vendor_id;
        $input['branch_id']=$branch_id;
        $input['transaction_num']=$transaction_num;
        $input['dnn_table_fees']=$dnn_table_fees;        
        $result=$paymentsmodel::create($input);

// reward points
        $user_data=DB::table('users')->where('id', $token)->first();
        $new_points=($user_data->points)+1;
        $customer_Model::where(['id' =>$token])->update(['points'=>$new_points]);
}
       
/*end reservation table*/
$item_options_name='';
foreach ($result2 as $cart_row) {
              $input['item_id']=$cart_row->item_id;
              $size_id=isset($cart_row->size_id)?$cart_row->size_id:'';
              if(!empty($size_id)){
                $input['size_id']=$size_id;                  
              }else{
              unset($input['size_id']);
              }
              
              $input['size_id']=$size_id;
              $input['price']= $cart_row->price;
              $input['quantity']=$cart_row->quantity;  

   if($cart_row->item_options!=''){           
        foreach($cart_row->item_options as $item_option_row) {                                              
        $item_options_name.=', '.$item_option_row['quantity'].' '.$item_option_row['name'];
                      }  
      }
              $input['item_options']=ltrim($item_options_name,',');
              
              $input['total_option_price']=$cart_row->total_option_price;
              $input['total_item_price']=$cart_row->total_item_price;                              
              $input['token']=$token;
              
              $input['cart_id']=$cart_row->id;              
              $input['order_id']=$order_id;
              $input['vendor_id']=$branch_data->vendor_id;
              $input['note']=$cart_row->note;              
              $result_data[]=$input;
              $ordersModel::create($input);

      $cartModel::where(['id' =>$cart_row->id])->update(['confirmed'=>'1']);
              } 
              
              $last_added_order_data=DB::table('orders_list')->where('id', $order_id)->first();
              $order_status=$last_added_order_data->status;
        return response()->json(['data'=>['order_id'=>$order_id,'order_status'=>$order_status],'msg'=>'success','status_code'=>$this->successStatus]);
          
    }
    public function check_order_status(Request $request,$id){
        $request_data=$request->all();              
        $token=$request->header('token');        
        $order_list_data=DB::table('orders_list')->where(['token'=>$token,'id'=>$id])->first();        
        $order_status=$order_list_data->status;
        $comments=$order_list_data->comments;
        return response()->json(['data'=>['order_status'=>$order_status,'comments'=>$comments],'msg'=>'success','status_code'=>$this->successStatus]);
                                                               
    }
    public function payment(Request $request){
        $request_data=$request->all();              
        $token=$request->header('token');
        $order_id=$request_data['order_id'];
        $orderslistModel=config('admin.database.orders_list_model');

        $paymentsmodel=config('admin.database.payments_model');
        $order_list_data=DB::table('orders_list')->where('id', $order_id)->first();        
        $payment_method=$request_data['payment_method'];
        $money=$order_list_data->total_price;
        $branch_id=$order_list_data->branch_id;
        $vendor_id=$order_list_data->vendor_id;

        $user_data=DB::table('users')->where('id', $token)->first();
        $setting_data=DB::table('settings')->where(['id'=>1])->first();
        $setting_point=$setting_data->point;
        $userpoints=$user_data->points;
        $money_points=$userpoints/$setting_point;   // $money_points=$userpoints/10;
        
        
          $customer_Model = config('admin.database.users_front_model');

        if($payment_method=='points'){
          if($money_points<$money){
            return response()->json(['msg'=>'sorry you points less than total price of order to ship','status_code'=>404]);
          }
            // deduction from points to pay          
            $new_money_points=$money_points-$money;
            if($new_money_points>0){
              $points=$new_money_points*$setting_point; // convert money to points              
            }else{
              $points=0;              
            }
            $customer_Model::where(['id' =>$token])->update(['points'=>$points]);
        }
        
        $settings_data=DB::table('settings')->where('id', 1)->first();
        if($payment_method=='cash'){
          $money=$money+$settings_data->fees_cash;
          $orderslistModel::where(['id' =>$order_id])->update(['total_price'=>$money]);
        }elseif($payment_method=='online'){
          $money=$money+$settings_data->fees_online;
          $orderslistModel::where(['id' =>$order_id])->update(['total_price'=>$money]);
        }

        $input['order_id']=$order_id;
        $input['token']=$token;
        $input['payment_type']=$payment_method;        
        $input['money']=$money;
        $input['vendor_id']=$vendor_id;
        $input['branch_id']=$branch_id;
        $result=$paymentsmodel::create($input);

// reward points
        $user_data=DB::table('users')->where('id', $token)->first();
        $new_points=($user_data->points)+1;
        $customer_Model::where(['id' =>$token])->update(['points'=>$new_points]);

       /*$reservation_model = config('admin.database.reservation_model');
        $check_reservatio_table_for_order=DB::table('reservation')->where(['order_id'=>$order_id])->first();
        if(!empty($check_reservatio_table_for_order)){
            $reservation_model::where(['order_id' =>$order_id])->update(['payment_method'=>$payment_method]);

        }*/
        return response()->json(['data'=>['result' => $result] ,'msg'=>'success','status_code'=>$this->successStatus]); 
    }
    public function order_history(Request $request)
    {
        $request_data=$request->all();
        $orders_listModel = config('admin.database.orders_list_model');
        $token=$request->header('token');
        $lang=$request->header('lang');        
                                   
        $token=isset($token)?$token:'';                             
        if(!empty($token)){                    
         $result = $orders_listModel::select('id','branch_id','total_price','num_of_people','created_at')->where(['token'=>$token])
         ->orderby('id','desc')->get();
        }      
        $url=str_replace('public','',url(""));

       // print_r($result);die;
        $total_price=0;
        $result2=array();
        foreach ($result as $res){
          $branches_data=DB::table('branches')->where('id', $res->branch_id)->first();
          $vendors_data=DB::table('vendors')->where('id', $branches_data->vendor_id)->first();
          $vendor_id=$branches_data->vendor_id;
          $branchname=$branches_data->name;
          $vendorname=$vendors_data->vendor_name;
          if($lang=='ar'){
              $vendorname=$vendors_data->vendor_name_ar;
              $branchname=$branches_data->name_ar;
          }


          $res->vendor_name=$vendorname;
          $res->vendor_id=$vendor_id;
          $res->branch_id=$res->branch_id;
          $res->branch_name=$branchname;
          $res->longitude=$branches_data->longitude;
          $res->latitude=$branches_data->latitude;
          
          $total_price=$total_price+$res->total_price;

          $res->branchlogo=$url.'storage/'.$vendors_data->vendor_logo;         
          $result2[]=$res;
        }                   
     return response()->json(['data'=>['result' => $result2,'total_price'=>$total_price],'msg'=>'success','status_code'=>$this->successStatus]);
    } 
    public function order_history_details(Request $request,$order_id)
    {
        $request_data=$request->all();
        $ordersModel = config('admin.database.orders_model');
        $token=$request->header('token');
        $lang=$request->header('lang'); 
        $token=isset($token)?$token:'';                             
        if(!empty($token)){                    
         $result = $ordersModel::select('orders.*','items.name as itemname','items.name_ar as itemname_ar','items.item_logo','items.item_logo_details','itemsizes.name as sizename','itemsizes.name_ar as sizename_ar',
          'vendors.vendor_logo as branchlogo','vendors.vendor_name','vendors.vendor_name_ar','vendors.id as vendor_id','orders.quantity','orders.total_item_price')
         ->leftjoin('items', 'orders.item_id', '=', 'items.id')         
          ->leftjoin('vendors', 'items.vendor_branch_user_id', '=', 'vendors.id')
         ->leftjoin('itemsizes', 'orders.size_id', '=', 'itemsizes.id')->where(['token'=>$token,'order_id'=>$order_id])->groupBy('orders.item_id','size_id','item_options','quantity')->get();

        }
        $total_price=0;
        $itemsnum=0;              
        $result2=array(); 
        $total_num_of_item_order=0;
        $url=str_replace('public','',url(""));
        //print_r($result);die;
        foreach ($result as $res){
        //  $res->quantity=$res->countitems;



          $orders_list_data=DB::table('orders_list')->where('id', $res->order_id)->first();
          $branch_id=$orders_list_data->branch_id;
          $branch_data=DB::table('branches')->where('id', $branch_id)->first();          
          $itemsnum++;             
          
          $res->total_item_price=$res->total_item_price;
          $total_price=$total_price+$res->total_item_price;
          $total_num_of_item_order=$total_num_of_item_order+$res->quantity;
        
     //    $res->vendor_id=$res->$vendorid->bra;
        //  $res->vendor_id=$res->vendorid;
          if($res->sizename==NULL){
            $res->sizename='';
          }
          $res->branch_id=$branch_id;
          //$branch_name=$branch_data->name;
          //$res->branch_name=$branch_name;
          
          $vendorname=$res->vendor_name;
          $branchname=$branch_data->name;
          if($lang=='ar'){
            $vendorname=$res->vendor_name_ar;
            $branchname=$branch_data->name_ar;
          }
          $res->vendor_name=$vendorname;
          $res->branch_name=$branchname;
          $res->longitude=$branch_data->longitude;
          $res->latitude=$branch_data->latitude;
        unset($res->vendor_name_ar);

          $itemname=$res->itemname;
          $sizename=$res->sizename;

          if($lang=='ar'){
             $itemname=$res->itemname_ar;
             $sizename=$res->sizename_ar;
          }
          $res->itemname=$itemname;
          $res->sizename=$sizename;
        unset($res->itemname_ar);
        unset($res->sizename_ar);





//unset($res->branch_name_ar);



          $res->branchlogo=$url.'storage/'.$res->branchlogo;


   if($res->item_logo!=''){
          $res->image=$url.'storage/'.$res->item_logo;
        }else{
          $res->image='';
        }
          if($res->item_logo_details!=''){
            $res->image_details=$url.'storage/'.$res->item_logo_details;           
          }else{
            $res->image_details='';         
          }
          unset($res->item_logo);
          unset($res->item_logo_details);
          $result2[]=$res;
        }             
      
return response()->json(['data'=>['result' => $result2,'total_item_to_order'=>$total_num_of_item_order,'total_price'=>$total_price],'msg'=>'success','status_code'=>$this->successStatus]);
    } 
public function order_status(Request $request,$id){     
        $request_data=$request->all();
        $token=$request->header('token');
        $result=array();
        $result = DB::Table('orders_list')
          ->leftjoin('payments', 'orders_list.id', '=', 'payments.order_id')    
         ->select('status','comments','payment_type','timer','loading_timer')->where(['orders_list.token'=>$token,'orders_list.id'=>$id])->get();        
        
        return response()->json(['data'=>['result' => $result],'msg'=>'success','status_code'=>$this->successStatus]);
}
public function branch_details(Request $request,$id){
    $url=str_replace('public','',url(""));
    $lang=$request->header('lang');       
    $result = DB::Table('branches') 
    ->leftjoin('vendors', 'branches.vendor_id', '=', 'vendors.id')
    ->select('branches.*','vendors.vendor_name','vendors.vendor_name_ar','vendors.vendor_logo')->where('branches.id','=',$id)->get();                  
       foreach ($result as $rows) {
          $vendorname=$rows->vendor_name;
          $branchname=$rows->name;
          if($lang=='ar'){
            $vendorname=$rows->vendor_name_ar;
            $branchname=$rows->name_ar;
          }
          $rows->vendor_name=$vendorname;
          $rows->name=$branchname;
unset($rows->vendor_name_ar);
unset($rows->name_ar);

         $rows->branch_logo= $url.'storage/'.$rows->vendor_logo;        
         unset($rows->vendor_logo);
       }
        return response()->json(['data'=>['result' => $result],'msg'=>'success','status_code'=>$this->successStatus]);
}
    public function search(Request $request){ -
        date_default_timezone_set('Asia/Riyadh');      
        $current_time=date('H:i:s'); //echo $current_time;die;
        $current_time_stamp=strtotime($current_time);
        $request_data=$request->all();
        $lang=$request->header('lang');
        $branchesModel = config('admin.database.branches_model');      
        $url=str_replace('public','',url(""));
        $latitude=isset($request_data['latitude'])?$request_data['latitude']:'';
        $longitude=isset($request_data['longitude'])?$request_data['longitude']:'';
         $vendor_type=isset($request_data['vendor_type'])?$request_data['vendor_type']:'';
         $search_word=isset($request_data['search_word'])?$request_data['search_word']:'';
        //$vendor_type='Cafe';
        //$search_word='Mac';       
       // $latitude ='29.956165';
       // $longitude ='31.262523';       
          $gr_circle_radius = 6371;
          $max_distance = 3; // kilometers
          // 1 mile=1067 kilometres         
            if($vendor_type!=''){
          $result = DB::table('branches')
          ->leftjoin('vendors', 'branches.vendor_id', '=', 'vendors.id')
              ->select('vendors.id as vendor_id','vendors.vendor_name','vendors.vendor_name_ar','reservation_table_show','branches.id as branch_id','branches.latitude','branches.longitude','branches.name','branches.name_ar','branches.opening_time','closing_time','vendors.vendor_logo as branchlogo','vendors.vendor_type',DB::raw(sprintf(
            '(6371 * acos(cos(radians(%1$.7f)) * cos(radians(latitude)) * cos(radians(longitude) - radians(%2$.7f)) + sin(radians(%1$.7f)) * sin(radians(latitude)))) AS distance',
            $latitude,
            $longitude
        )))
              ->where(['vendors.vendor_type'=>$vendor_type])
              ->Where('name', 'like', '%' . $search_word. '%')
              //->where('opening_time','>=',$current_time)
             // ->where('closing_time','=<',$current_time)
              ->having('distance', '<=',  $max_distance)
              ->orderBy('distance', 'asc')
              ->get();
}else{
  $result = DB::table('branches')
          ->leftjoin('vendors', 'branches.vendor_id', '=', 'vendors.id')
              ->select('vendors.id as vendor_id','vendors.vendor_name','vendors.vendor_name_ar','reservation_table_show','branches.id as branch_id','branches.latitude','branches.longitude','branches.name','branches.name_ar','branches.opening_time','closing_time','vendors.vendor_logo as branchlogo','vendors.vendor_type',DB::raw(sprintf(
            '(6371 * acos(cos(radians(%1$.7f)) * cos(radians(latitude)) * cos(radians(longitude) - radians(%2$.7f)) + sin(radians(%1$.7f)) * sin(radians(latitude)))) AS distance',
            $latitude,
            $longitude
        )))
              // ->where(['vendors.vendor_type'=>$vendor_type])
              ->Where('name', 'like', '%' . $search_word. '%')
              //->where(DB::raw('strtotime(opening_time)'),'>=',$current_time_stamp)
              //->where(DB::raw('strtotime(closing_time)'),'=<',$current_time_stamp)
              ->having('distance', '<=',  $max_distance)
              ->orderBy('distance', 'asc')
              ->get();
}
        // $result->branch_logo= $url.'storage/'.$result->branch_logo;    
         $result2=array();
//echo $current_time;die;
foreach ($result as $result_row) {
$vendorname=$result_row->vendor_name;
$branchname=$result_row->name;
if($lang=='ar'){
$vendorname=$result_row->vendor_name_ar;
$branchname=$result_row->name_ar;
}

$result_row->vendor_name=$vendorname;
$result_row->name=$branchname;

unset($result_row->vendor_name_ar);
unset($result_row->name_ar);
  $opening_time_stamp=strtotime($result_row->opening_time);
  $closing_time_stamp=strtotime($result_row->closing_time);
 // echo $current_time_stamp.','.$closing_time_stamp.'<br>';
  if(($current_time_stamp >= $opening_time_stamp) && ($current_time_stamp <= $closing_time_stamp)){
      $result_row->branch_logo=$url.'storage/'.$result_row->branchlogo;        
      $result_row->current_time=$current_time;

  $result_row->distance=number_format($result_row->distance,2);
      $result2[]=$result_row;

      unset($result_row->branchlogo);
    }
}
        return response()->json(['data'=>['result' => $result2],'msg'=>'success',
          'status_code'=>$this->successStatus]);
    }

public function listvendors(Request $request){
      $request_data=$request->all();      

      $lang=$request->header('lang');
      $vendor_type=isset($request_data['vendor_type'])?$request_data['vendor_type']:'';
      $search_word=isset($request_data['search_word'])?$request_data['search_word']:'';
      $result = DB::table('vendors')->select('id','vendor_name','vendor_name_ar','reservation_table_show','vendor_logo')
              ->Where('vendor_name', 'like', '%' . $search_word. '%')
              ->where(['vendor_type'=>$vendor_type])->get();
     $url=str_replace('public','',url(""));
     foreach ($result as $rows) {
         $rows->vendor_logo=$url.'storage/'.$rows->vendor_logo;

          $vendorname=$rows->vendor_name;          
          if($lang=='ar'){
            $vendorname=$rows->vendor_name_ar;            
          }
          $rows->vendor_name=$vendorname;         
          unset($rows->vendor_name_ar);


     }
return response()->json(['data'=>['result' => $result],'msg'=>'success','status_code'=>$this->successStatus]);
}
public function listspecialvendors(Request $request){
      $lang=$request->header('lang');
      $vendor_type=isset($request_data['vendor_type'])?$request_data['vendor_type']:'';
      $search_word=isset($request_data['search_word'])?$request_data['search_word']:'';
      
        $result = DB::table('vendors')->select('id','vendor_name','vendor_name_ar','reservation_table_show','vendor_logo')
              ->get();
      
     $url=str_replace('public','',url(""));
     foreach ($result as $rows) {
         $rows->vendor_logo=$url.'storage/'.$rows->vendor_logo;

          $vendorname=$rows->vendor_name;          
          if($lang=='ar'){
            $vendorname=$rows->vendor_name_ar;            
          }
          $rows->vendor_name=$vendorname;         
          unset($rows->vendor_name_ar);


     }
return response()->json(['data'=>['result' => $result],'msg'=>'success','status_code'=>$this->successStatus]);
}

public function vendor_details(Request $request,$id){
      $request_data=$request->all();
      $token = $request->header('token');     
      if(empty($token)){
return response()->json(['msg'=>'empty token','status_code'=>401]);        
      }
      $result = DB::table('vendors')->select('*')->where(['id'=>$id])->get();
      $url=str_replace('public','',url(""));
      foreach ($result as $rows) {
        $rows->vendor_logo=$url.'storage/'.$rows->vendor_logo;
        $rows->vendor_featured_image=$url.'storage/'.$rows->vendor_featured_image;
        $rows->vendor_store_bg_image=$url.'storage/'.$rows->vendor_store_bg_image;
        $rows->vendor_store_bg_video=$url.'storage/'.$rows->vendor_store_bg_video;
      }
return response()->json(['data'=>['result' => $result],'msg'=>'success','status_code'=>$this->successStatus]);
}

public function search_vendors(Request $request){
    $request_data=$request->all();      
    $lang=$request->header('lang');
    $search_word=isset($request_data['search_word'])?$request_data['search_word']:'';
    $vendor_type=$request_data['vendor_type'];
    $result = DB::table('vendors')
              ->select('*')
              ->Where('vendor_name', 'like', '%' . $search_word. '%')
              ->Where('vendor_type', '=',$vendor_type)
              ->get();  
              if(count($result)==0){
                  return response()->json(['msg'=>'no data found','status_code'=>401]);
              }
               $url=str_replace('public','',url(""));
        foreach ($result as $rows) {
            $rows->vendor_logo=$url.'storage/'.$rows->vendor_logo;
            $rows->vendor_featured_image=$url.'storage/'.$rows->vendor_featured_image;
            $rows->vendor_store_bg_image=$url.'storage/'.$rows->vendor_store_bg_image;
            $rows->vendor_store_bg_video=$url.'storage/'.$rows->vendor_store_bg_video;

            $vendorname=$rows->vendor_name;          
          if($lang=='ar'){
            $vendorname=$rows->vendor_name_ar;            
          }
          $rows->vendor_name=$vendorname;         
        unset($rows->vendor_name_ar);



       }  
return response()->json(['data'=>['result' => $result],'msg'=>'success','status_code'=>$this->successStatus]);
}

public function search_branches(Request $request){
    $request_data=$request->all();      
    $lang=$request->header('lang');       
    $search_word=isset($request_data['search_word'])?$request_data['search_word']:'';
    $result = DB::table('branches')
              ->select('*')->Where('name', 'like', '%' . $search_word. '%')->get();  
              if(count($result)==0){
                  return response()->json(['msg'=>'no result found','status_code'=>401]);
              }  
              foreach ($result as $rows) {
                $name=$rows->name;
                if($lang=='ar'){
                  $name=$rows->name_ar;
                }
                $rows->name=$name;
                unset($rows->name_ar);
              }  
return response()->json(['data'=>['result' => $result],'msg'=>'success','status_code'=>$this->successStatus]);
}

public function listbranches_by_vendorid(Request $request){
  $request_data=$request->all();
  $lang=$request->header('lang');
  $vendor_id=$request_data['vendor_id'];
  $latitude=isset($request_data['latitude'])?$request_data['latitude']:'';
  $longitude=isset($request_data['longitude'])?$request_data['longitude']:'';
  $search_word=isset($request_data['search_word'])?$request_data['search_word']:'';
  $url=str_replace('public','',url(""));
  $result = DB::table('branches')
          ->leftjoin('vendors', 'branches.vendor_id', '=', 'vendors.id')
              ->select('vendors.id as vendor_id','vendors.vendor_name','vendors.vendor_name_ar','reservation_table_show','branches.id as branch_id','branches.latitude','branches.longitude','branches.name','branches.name_ar','branches.opening_time','closing_time','vendors.vendor_logo as branchlogo','vendors.vendor_type',DB::raw(sprintf(
            '(6371 * acos(cos(radians(%1$.7f)) * cos(radians(latitude)) * cos(radians(longitude) - radians(%2$.7f)) + sin(radians(%1$.7f)) * sin(radians(latitude)))) AS distance',
            $latitude,
            $longitude
        )))
              ->Where('branches.name', 'like', '%' . $search_word. '%')
              ->where('branches.vendor_id','=',$vendor_id)
              ->orderBy('distance', 'asc')
              ->get();
  foreach ($result as  $result_row) {
          $result_row->distance=number_format($result_row->distance,2);
          $result_row->branch_logo=$url.'storage/'.$result_row->branchlogo;
          $result2[]=$result_row;
          unset($result_row->branchlogo);


$vendorname=$result_row->vendor_name;
          $branchname=$result_row->name;
          if($lang=='ar'){
            $vendorname=$result_row->vendor_name_ar;
            $branchname=$result_row->name_ar;
          }
          $result_row->vendor_name=$vendorname;
          $result_row->name=$branchname;
unset($result_row->vendor_name_ar);
unset($result_row->name_ar);


      }    
  return response()->json(['data'=>['result' => $result],'msg'=>'success','status_code'=>$this->successStatus]);
}
    public function reservation_table(Request $request){
       $request_data=$request->all();
       $token=$request->header('token');
       $reservation_model = config('admin.database.reservation_model');
       $input['token']=$token;
       $input['vendor_id']=$request_data['vendor_id'];
       $input['branch_id']=$request_data['branch_id'];
     
       $input['area_phone_code']=$request_data['area_phone_code'];
       $input['phone_without_code']=$request_data['phone'];      
       $input['phone']=$request_data['area_phone_code'].$request_data['phone'];
       $input['num_of_people']=$request_data['num_of_people'];
       $input['name']=$request_data['name'];
       $input['date']=$request_data['date'];
       $input['time']=$request_data['time'];  
       //$input['time']= date("g:i A", strtotime($request_data['time']." UTC"));    

       $input['payment_method']=$request_data['payment_method'];
      
       $input['transaction_num']=isset($request_data['transaction_num'])?$request_data['transaction_num']:'';

       $vendor_data=DB::table('vendors')->where('id','=',$input['vendor_id'])->first();
       $vendorname=$vendor_data->vendor_name;

       $branch_data=DB::table('branches')->where('id','=',$input['branch_id'])->first();
       $branchname=$branch_data->name; 

       $user_data=DB::table('users')->where('id','=',$token)->first();


       $user_branch_data=DB::table('admin_users')->where('branch_id','=',$branch_data->id)->first();
      /*if(!empty($user_branch_data->device_token)){          
          $notification_msg_vendor=['title' => 'Reservation table','body'=>'you have reserved the table at '.$vendorname.' ('.$branchname.')  for '. $input['num_of_people'].' people at '.$input['time'].'.'];
          $data_vendor=[
          'notification'=>$notification_msg_vendor,
          'to'=>$user_branch_data->device_token,
          ];          
          $ch_vendor = curl_init();
          curl_setopt($ch_vendor, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
          curl_setopt($ch_vendor, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch_vendor, CURLOPT_POST, 1);
          curl_setopt($ch_vendor, CURLOPT_POSTFIELDS,json_encode($data_vendor) );
          $headers_vendor = array();
          $headers_vendor[] = 'Authorization: key=AIzaSyDwOO0Y3bdQqQkIyHq0AYcHq00rLoCqOg4';
          $headers_vendor[] = 'Content-Type: application/json';
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_vendor);

          $result_vendor = curl_exec($ch_vendor);
          if (curl_errno($ch_vendor)) {
          echo 'Error:' . curl_error($ch_vendor);
          }
          curl_close($ch_vendor);
        }*/
  /*    
$basic  = new \Nexmo\Client\Credentials\Basic('c4c5c8f9', 'RBqIOeVmU0xCgMBo');      
$client = new \Nexmo\Client($basic);
      $message = $client->message()->send([
        'to' =>    $input['phone'],
        'from' => 'Nexmo',
        'text' => 'you have reserved the table at '.$vendorname.' ('.$branchname.')  for '. $input['num_of_people'].' people at '.$input['time'].'.'
]);
*/

  $to      = $user_data->email;
  $subject = 'Reservation table';
  $message = 'you have reserved the table at '.$vendorname.' ('.$branchname.')  for '. $input['num_of_people'].' people at '.$input['time'].'.';
  $headers = 'From: info@creativitysol.com' . "\r\n" .
      'Reply-To: webmaster@creativitysol.com' . "\r\n" .
      'X-Mailer: PHP/' . phpversion();
  mail($to, $subject, $message, $headers);

if($user_data->notification==1){
      $ch = curl_init();
      $notification_msg=['title' => 'Reservation table','body'=>'you have reserved the table at '.$vendorname.' ('.$branchname.')  for '. $input['num_of_people'].' people at '.$input['time'].'.'];
      $data=[
      'notification'=>$notification_msg,
      'to'=>$user_data->device_token,
      ];
      curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data) );

      $headers = array();
      $headers[] = 'Authorization: key=AIzaSyBmVXUeWEEbCHYjXQ9VTSjaYjnXQjy7-wk';
      $headers[] = 'Content-Type: application/json';
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      $result = curl_exec($ch);
      if (curl_errno($ch)) {
          echo 'Error:' . curl_error($ch);
      }
      curl_close($ch);  
}

       $result=$reservation_model::create($input);
       return response()->json(['data'=>['result' => $result],'msg'=>'your table has been booked success, a notification has been sent to your phone number with the details',
          'status_code'=>$this->successStatus]);
    }
    public function check_token_device_reservation_tbl(Request $request){
      $usersModel = config('admin.database.users_front_model');
      $request_data=$request->all();
      $token=$request->header('token');
      $device_token=$request_data['device_token'];    
      $res_data=DB::table('users')->where('id','=',$token)->first();
      $username=$res_data->name;
      if($res_data->device_token!=$device_token){
        $usersModel::where(['id'=>$token])->update(array('device_token' =>$device_token));
        $res_data=DB::table('users')->where('id','=',$token)->first();
        $msg='success! token device for user '.$username.' has been updated';
      }else{
        $msg='success! no change token device for user '.$username;
      }
     return response()->json(['data'=>['result' => $res_data],'msg'=>$msg,
          'status_code'=>$this->successStatus]);
    }
    public function limit_num_of_people_fees_reservation_table(Request $request,$vendorid){
      $request_data=$request->all();
      $token=$request->header('token');
      if(empty($token)){
          return response()->json(['msg'=>'empty token','status_code'=>401]);                     
      }
      $res_data=DB::table('vendors')->where('id','=',$vendorid)->first();   
      $minimum_charge_cash=$res_data->reservation_table_pay_cash;
      $minimum_charge_online=$res_data->reservation_table_pay_online;
      
      $limit_num_of_people=$res_data->limit_num_of_people;
      return response()->json(['data'=>['minimum_charge_cash'=>$minimum_charge_cash,'minimum_charge_online'=>$minimum_charge_online,'limit_num_of_people'=>$limit_num_of_people]
        ,'msg'=>'success','status_code'=>$this->successStatus]);                  
    }
    public function list_reservation_table(Request $request){
      $request_data=$request->all();
      $lang=$request->header('lang');
      $token=$request->header('token');      
      $result = DB::table('reservation')
              ->leftjoin('branches', 'reservation.branch_id', '=', 'branches.id')
              ->leftjoin('vendors', 'reservation.vendor_id', '=', 'vendors.id')
              ->select('reservation.*','vendors.vendor_name','vendors.vendor_name_ar','branches.name as branch_name','branches.name_ar as branch_name_ar','vendors.vendor_logo as branchlogo','reservation_table_pay_cash','reservation_table_pay_online')
              ->where(['token'=>$token])
              ->orderBy('id','desc')->get();
              $result2=array();
                  $url=str_replace('public','',url(""));
              foreach ($result as $rows){
                  if($rows->payment_method=='cash'){
                      $rows->pay_money=$rows->reservation_table_pay_cash;
                  }elseif($rows->payment_method=='online'){
                      $rows->pay_money=$rows->reservation_table_pay_online;
                  }elseif($rows->payment_method=='points'){
                      $rows->pay_money=$rows->reservation_table_pay_online;
                  }else{
                      $rows->pay_money=0;
                  }
                  unset($rows->reservation_table_pay_cash);
                  unset($rows->reservation_table_pay_online);

                  unset($rows->created_at);
                  unset($rows->updated_at);
                  $rows->branchlogo=$url.'storage/'.$rows->branchlogo;
                  $rows->time=date("g:i A", strtotime($rows->time." UTC"));


          $vendorname=$rows->vendor_name;
          $branchname=$rows->branch_name;
          if($lang=='ar'){
            $vendorname=$rows->vendor_name_ar;
            $branchname=$rows->branch_name_ar;
          }
          $rows->vendor_name=$vendorname;
          $rows->branch_name=$branchname;          
          unset($rows->vendor_name_ar);
          unset($rows->branch_name_ar);

                  $result2[]=$rows;                     
              }
             
               return response()->json(['data'=>['result' => $result2],'msg'=>'success',
          'status_code'=>$this->successStatus]);
    }
    public function reservation_table_details(Request $request,$id){
      $request_data=$request->all();
      $token=$request->header('token');
      $result = DB::table('reservation')
              ->leftjoin('branches', 'reservation.branch_id', '=', 'branches.id')
              ->leftjoin('vendors', 'reservation.vendor_id', '=', 'vendors.id')
              ->select('reservation.*','vendors.vendor_name','branches.name as branch_name','reservation_table_pay_cash','reservation_table_pay_online')
              ->where(['reservation.id'=>$id])
              ->get();
               return response()->json(['data'=>['result' => $result],'msg'=>'success',
          'status_code'=>$this->successStatus]);
    }
    public function logout_remove_device_token(Request $request){
      $request_data=$request->all();
      $token=$request->header('token');
      $usersModel = config('admin.database.users_front_model');
      $usersModel::where(['id'=>$token])->update(array('device_token' =>''));
      return response()->json(['msg'=>'success','status_code'=>$this->successStatus]);
    }
public function notification(Request $request){
      $request_data=$request->all();
      $token=$request->header('token');
      $notification=$request_data['notification'];

      $usersModel = config('admin.database.users_front_model');
      $usersModel::where(['id'=>$token])->update(['notification' =>$notification]);
      return response()->json(['msg'=>'success','status_code'=>$this->successStatus]);
    }

public function contactus(Request $request){
    $token=$request->header('token');
    //if(empty($token)){
      //        return response()->json(['msg'=>'error','status_code'=>401]);
    //}else{

        $setting_data=DB::table('settings')->where(['id'=>1])->first();
        $setting_email=$setting_data->email;  
        $setting_phone=$setting_data->phone; 
        $settings_fees_cash=$setting_data->fees_cash;
        $settings_fees_online=$setting_data->fees_online;   
        $settings_taxes=$setting_data->taxes;   
//    }
    $result=array();
    $result['email']=$setting_email;
    $result['phone']=$setting_phone;
    $result['fees_cash']=$settings_fees_cash;
    $result['fees_online']=$settings_fees_online;
    $result['taxes']=$settings_taxes;
    
    return response()->json(['data'=>['result'=>$result],'msg'=>'success','status_code'=>$this->successStatus]);

}
public function points_info(Request $request){
    $token=$request->header('token');
    $result=array();
    if(empty($token)){
              return response()->json(['msg'=>'error','status_code'=>401]);
    }else{

      $lang=$request->header('lang');     
      $setting_data=DB::table('settings')->where(['id'=>1])->first();
    if($lang=='ar'){
      $result['points_info']=$setting_data->points_info_ar;
    }else{
      $result['points_info']=$setting_data->points_info;
    }
  }
    return response()->json(['data'=>['result'=>$result],'msg'=>'success','status_code'=>$this->successStatus]);
}
public function promocode(Request $request){
    $token=$request->header('token');
    if(empty($token)){
            return response()->json(['msg'=>'error','status_code'=>401]);
    }
    $request_data=$request->all();
    $promocode=$request_data['promocode'];    
    $check_valid=DB::table('promocode')->where(['promocode'=>$promocode])->where(DB::Raw('expire_date'),'>',DB::Raw('now()'))->first();

    $result=array();
    if(count((array)$check_valid)>0){
        $discount=$check_valid->discount;
        $type=$check_valid->type;
        $vendor_id=$check_valid->vendor_id;
        
              $total_price=0;
              $itemsnum=0;
              $total_item_option_price=0;
              $result2=array();   
             // $countitems=1;
              $total_num_of_item_incart=0;             
              $new_price=0;
     
      $result = DB::table('cart')
                     ->leftjoin('items', 'cart.item_id', '=', 'items.id')
                     ->leftjoin('itemsizes', 'cart.size_id', '=', 'itemsizes.id')
                     ->leftjoin('vendors', 'items.vendor_branch_user_id', '=', 'vendors.id')
                                  ->select('cart.id','cart.item_id','cart.branch_id','quantity','item_options','itemsizes.price','size_id','items.price as default_price',
                                    'items.name','items.name_ar','item_logo','item_logo_details','vendors.id as vendorid','vendors.vendor_name','vendors.vendor_name_ar','vendors.vendor_logo as branchlogo')
                                  ->where(['token'=>$token,'confirmed'=>'0'])
                                  ->groupBy('cart.id','cart.item_id','size_id','item_options','quantity')
                                  ->get();
              if(count($result)==0){
                  return response()->json(['msg'=>'no items in cart','status_code'=>$this->successStatus]);
              }     
              $sub_total=0;
              $setting_data=DB::table('settings')->where(['id'=>1])->first();  

              foreach ($result as $res) {                 
                $quantity=$res->quantity;              
                if(empty($res->size_id)){                  
                  unset($res->price);
                  unset($res->size_id);
                   $res->price=$res->default_price;
                  // unset($res->countitems);
                   //$res->total_item_price=$res->default_price*$countitems*$quantity;                  
                  $new_price=$res->price;
                   unset($res->default_price);
                }else{
                  unset($res->default_price);
                  //$res->total_item_price=$res->price*$countitems*$quantity;
                   $new_price=$res->price;
                }
                $total_num_of_item_incart=$total_num_of_item_incart+$quantity;
                $total_item_option_price=0;
                     if(!empty($res->item_options)){
                          $item_options_json=json_decode($res->item_options,true);
                          $res->item_options=$item_options_json;          
                          $sum_one_option=0;                          
                          foreach($item_options_json as $item_option) {                        
                            $total_item_option_price=$total_item_option_price+$item_option['price'];
                            $sum_one_option=$sum_one_option+$item_option['price'];
                          }                      
                          $res->total_option_price=$sum_one_option;                      
                         // $res->total_item_price= $res->total_item_price+$sum_one_option;
                          $res->total_item_price= ($new_price+$sum_one_option)*$res->quantity;  
                          $total_price=$total_price+$res->total_item_price;
                      }else{
                        unset($res->item_options);
                        //  $res->total_item_price= $res->total_item_price;
                          $res->total_option_price=0;
                          $res->total_item_price= $new_price*$res->quantity;
                          $total_price=$total_price+$res->total_item_price;
                      }

                    }


    }else{
      return response()->json(['msg'=>'expire promo code','status_code'=>401]);
    }
    
    $res_branch_details=DB::table('branches')->where('id', $res->branch_id)->first();                
    $vendor_id=$res_branch_details->vendor_id;

    $sub_total=$total_price;
    $result2=array();
    if($check_valid->type=='store'){       
       if($check_valid->vendor_id!=$vendor_id){
                  return response()->json(['msg'=>'sorry this promocode for another store', 'status_code'=>401]);
                }

      $final_after_discount=$total_price*($check_valid->discount/100);
      $total_price=$total_price-$final_after_discount;
    }else{
             
      $result2['fees_cash']=$setting_data->fees_cash-($setting_data->fees_cash*($check_valid->discount/100));  
      $result2['fees_online']=$setting_data->fees_online-($setting_data->fees_online*($check_valid->discount/100));  
    }    
    


    $total_with_taxes=Round($sub_total*($setting_data->taxes/100),2);
    $total_price=$total_price+$total_with_taxes;
   // $result2['sub_total']=$sub_total;    
    $result2['discount']=$check_valid->discount;
    $result2['total_price']=$total_price;
    $result2['type']=$check_valid->type;
    return response()->json(['data'=>['result'=>$result2],'msg'=>'success','status_code'=>$this->successStatus]);
}
public function terms(Request $request){
  $token=$request->header('token');
  $result=array();
  $result['terms']=url('terms');
    return response()->json(['data'=>['result'=>$result],'msg'=>'success','status_code'=>$this->successStatus]);

}
// vendor app apis
public function vendor_login(Request $request){
    $request_data=$request->all();    
    $url=str_replace('public','',url(""));   
    $validator = Validator::make($request->all(), [ 
            'username' => 'required',            
            'password' => 'required', 
        ]);
    if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
    }
    if (Auth::guard('admin')->attempt(['username' =>$request->username,'password'=>$request->password])) {
          //$user_data=DB::table('users')->where('id','=',$user->id)->first();        
          $user_data=Auth::guard('admin')->authenticate();
          $success['token']=$user_data->id;    
          $success['user_id']=$user_data->id;
          $success['username']=$user_data->username;          
          $success['name']=$user_data->name;
          $success['device_token']=$user_data->device_token;                       
          if(isset($request_data['device_token'])){
            if(empty($user_data->device_token)){
                  $usersModel = config('admin.database.users_model');
                  $usersModel::where(['id'=>$user_data->id])->update(array('device_token' =>$request_data['device_token']));
                  $success['device_token']=$request_data['device_token'];          
            }    
        }
          //$success['branch_id']=$user_data->branch_id;
   
   /* if($user_data->avatar!=''){
        //  $success['avatar']=$url.'storage/'.$user_data->avatar;
    }else{
      //$success['avatar']='';
    }*/
     //   $success['created_at']=$user_data->created_at;
         return response()->json(['data'=>['result' => $success],'msg'=>'success','status_code'=>$this->successStatus]);
    }else{          
          return response()->json(['msg'=>'Unauthorised', 'status_code'=>401]); 
    } 
  }
  
public function vendor_items(Request $request){
    $request_data=$request->all();
    $token=$request->header('token');
    $lang=$request->header('lang');
    $user_data=DB::table('admin_users')->where('id','=',$token)->first();    
    $branch_id=$user_data->branch_id;
    $url=str_replace('public','',url(""));
    $ItemsModel = config('admin.database.items_model');
    $url=str_replace('public','',url(""));
 $result = DB::table('items')
              ->leftjoin('branches', 'items.vendor_branch_user_id', '=', 'branches.vendor_id')
              ->select('items.*')      
              ->where(['branches.id'=>$branch_id])
              ->get();
       $result2 = array();
    //   print_r($result);die;
    foreach ($result as $res) {        
          $res->image=$url.'storage/'.$res->item_logo;          
          if($res->item_logo_details!=''){            
            $res->image_details=$url.'storage/'.$res->item_logo_details;
          }else{
             $res->image_details='';
          }
          unset($res->item_logo);
          unset($res->item_logo_details);

          $name=$res->name;
          $description=$res->description;         
          if($lang=='ar'){
            $name=$res->name_ar;
            $description=$res->description_ar;        
          }
          $res->name=$name;
          $res->description=$description;          
          unset($res->name_ar);
          unset($res->description_ar);



          $result2[]=$res;
    }
        return response()->json(['data'=>['result' => $result2] ,'msg'=>'success','status_code'=>$this->successStatus]); 
  }
  public function vendor_available_items(Request $request){
        $request_data=$request->all();
        $item_id=$request_data['item_id'];
        $available=$request_data['available'];                
        $itemsModel = config('admin.database.items_model');
        
        $token=$request->header('token');
        $token=isset($token)?$token:'';                             
        
        $user_data=DB::table('admin_users')->where('id','=',$token)->first();    
        $branch_id=$user_data->branch_id;
        $msg='';
        if($available==1){
        $msg=" is available now";
        }else{
          $msg=" is not available now";
        }
        if(!empty($token)){                  
                $itemsModel::where(['id'=>$item_id])->update(array('available' =>$available));

        }
        return response()->json(['msg'=>'item # '.$item_id.$msg.' success','status_code'=>$this->successStatus]);
  }
  public function vendor_orders(Request $request)
    {
        $current_date = date('Y-m-d', time());
        $request_data=$request->all();
        $orders_listModel = config('admin.database.orders_list_model');
        $token=$request->header('token');
        $lang=$request->header('lang');                        
        $user_data=DB::table('admin_users')->where('id','=',$token)->first();    
        $branch_id=$user_data->branch_id;
        if(!empty($token)){                    
      /*   $result = $orders_listModel::select('id','branch_id','total_price','status','created_at')
         ->where(['branch_id'=>$branch_id])
         ->whereDate('created_at','=',$current_date)
         ->orderBy('created_at', 'desc')
         ->get();*/

           $result = $orders_listModel::select('orders_list.id','orders_list.branch_id','orders_list.total_price','orders_list.status','payment_type','users.name as customername','orders_list.num_of_people','payments.paid_money','orders_list.created_at')
                   ->leftjoin('payments', 'orders_list.id', '=', 'payments.order_id')    
                   ->leftjoin('users', 'orders_list.token', '=', 'users.id')    
            ->where(['orders_list.branch_id'=>$branch_id])
            ->whereNotIn('orders_list.status', ['declined'])
            ->whereDate('orders_list.created_at','=',$current_date)
            ->orderBy('orders_list.created_at', 'desc')

            ->get();


        }      
        $url=str_replace('public','',url(""));

       // print_r($result);die;
        $total_price=0;
        $result2=array();
        foreach ($result as $res){
          if($res->payment_type==null){
            $res->payment_type='';
          }
          if($res->paid_money==null){
            $res->paid_money=0;
          }
          $branches_data=DB::table('branches')->where('id', $res->branch_id)->first();
          $vendors_data=DB::table('vendors')->where('id', $branches_data->vendor_id)->first();
          $vendor_id=$branches_data->vendor_id;
        //  $vendor_name=$vendors_data->vendor_name;
          $res->vendor_id=$vendors_data->id;
          //$res->vendor_name=$vendor_name;
          $res->vendor_id=$vendor_id;
          $res->branch_id=$res->branch_id;
          //$res->branch_name=$branches_data->name;
          $total_price=$total_price+$res->total_price;

          $res->branchlogo=$url.'storage/'.$vendors_data->vendor_logo;         

          $originalDate = $res->created_at;
          $newDate = date("j M Y", strtotime($originalDate));
          $res->ordered_date=$newDate;
          $newtime = date("g:i a", strtotime($originalDate));
          $res->ordered_at=$newtime;



          $vendor_name=$vendors_data->vendor_name;
          $branch_name=$branches_data->name;
          if($lang=='ar'){
            $vendor_name=$vendors_data->vendor_name_ar;
            $branch_name=$branches_data->name_ar;
          }
          $res->vendor_name=$vendor_name;
          $res->branch_name=$branch_name;





          $result2[]=$res;
        }                   
     return response()->json(['data'=>['result' => $result2,'total_price'=>$total_price],'msg'=>'success','status_code'=>$this->successStatus]);
    }
    public function vendor_orders_pending(Request $request)
    {
        $current_date = date('Y-m-d', time());
        //echo $current_date;die;
        $request_data=$request->all();
        $lang=$request->header('lang');
        $orders_listModel = config('admin.database.orders_list_model');
        $token=$request->header('token');
        $token=isset($token)?$token:'';                             
        $user_data=DB::table('admin_users')->where('id','=',$token)->first();    
        $branch_id=$user_data->branch_id;
        if(!empty($token)){                    
         $result = $orders_listModel::select('orders_list.id','orders_list.branch_id','orders_list.total_price','orders_list.status','payment_type','users.name as customername','orders_list.timer','orders_list.num_of_people','payments.paid_money','orders_list.created_at')
                   ->leftjoin('payments', 'orders_list.id', '=', 'payments.order_id')    
                   ->leftjoin('users', 'orders_list.token', '=', 'users.id')    
         ->where(['orders_list.branch_id'=>$branch_id])
            ->whereIn('orders_list.status', ['','order placed','preparing', 'ready to pickup','payment done'])
            ->whereDate('orders_list.created_at','=',$current_date)
            ->orderBy('orders_list.created_at', 'desc')

            ->get();
        }      
         



        $url=str_replace('public','',url(""));

      //  print_r($result);die;
        $total_price=0;
        $result2=array();
        foreach ($result as $res){
          if($res->payment_type==null){
            $res->payment_type='';
          }
          if($res->paid_money==null){
            $res->paid_money=0;
          }
          $branches_data=DB::table('branches')->where('id', $res->branch_id)->first();
          $vendors_data=DB::table('vendors')->where('id', $branches_data->vendor_id)->first();
          $vendor_id=$branches_data->vendor_id;
          
          $res->vendor_id=$vendors_data->id;
          

        /*  $vendor_name=$vendors_data->vendor_name;
          $res->vendor_name=$vendor_name;
          $res->branch_name=$branches_data->name;
          */


          $vendor_name=$vendors_data->vendor_name;
          $branch_name=$branches_data->name;
          if($lang=='ar'){
            $vendor_name=$vendors_data->vendor_name_ar;
            $branch_name=$branches_data->name_ar;
          }
          $res->vendor_name=$vendor_name;
          $res->branch_name=$branch_name;






          $res->vendor_id=$vendor_id;
          $res->branch_id=$res->branch_id;          
          $total_price=$total_price+$res->total_price;

          $res->branchlogo=$url.'storage/'.$vendors_data->vendor_logo;         

          $originalDate = $res->created_at;
          $newDate = date("j M Y", strtotime($originalDate));
          $res->ordered_date=$newDate;
          $newtime = date("g:i a", strtotime($originalDate));
          $res->ordered_at=$newtime;

          $result2[]=$res;
        }                   
     return response()->json(['data'=>['result' => $result2,'total_price'=>$total_price],'msg'=>'success','status_code'=>$this->successStatus]);
    }
    public function order_information(Request $request,$order_id)
    {
        $request_data=$request->all();
        $ordersModel = config('admin.database.orders_model');
        $token=$request->header('token');
        $lang=$request->header('lang');
        if(!empty($token)){                    
         $result = $ordersModel::select('orders.*','items.name as itemname','items.name_ar as itemname_ar','items.item_logo','items.item_logo_details','itemsizes.name as sizename','itemsizes.name_ar as sizename_ar',
          'vendors.vendor_logo as branchlogo','vendors.vendor_name','vendors.vendor_name_ar','vendors.id as vendor_id','orders.quantity')
         ->leftjoin('items', 'orders.item_id', '=', 'items.id')         
          ->leftjoin('vendors', 'items.vendor_branch_user_id', '=', 'vendors.id')
         ->leftjoin('itemsizes', 'orders.size_id', '=', 'itemsizes.id')->where(['order_id'=>$order_id])->groupBy('orders.item_id','size_id','item_options','quantity')->get();

        }
        $total_price=0;
        $itemsnum=0;              
        $result2=array(); 
        $total_num_of_item_order=0;
        $url=str_replace('public','',url(""));      
        foreach ($result as $res){
         // $res->quantity=$res->countitems;
          $orders_list_data=DB::table('orders_list')
          ->leftjoin('payments', 'orders_list.id', '=', 'payments.order_id')
          ->select('orders_list.*','payments.payment_type','payments.paid_money')
          ->where('orders_list.id', $res->order_id)->first();
          $branch_id=$orders_list_data->branch_id;
          $branch_data=DB::table('branches')->where('id', $branch_id)->first();
          //$branch_name=$branch_data->name;
          $itemsnum++;      
          $res->status=$orders_list_data->status;    
      if($orders_list_data->paid_money==null){
            $res->paid_money=0;
          }else{
            $res->paid_money=$orders_list_data->paid_money;
          }
          if($orders_list_data->payment_type==null){
            $res->payment_type='';
          }else{
            $res->payment_type=$orders_list_data->payment_type;
          }
          $res->timer=$orders_list_data->timer;
          $result2[]=$res;
          $res->total_item_price=$res->total_item_price;
          $total_price=$total_price+$res->total_item_price;
          $total_num_of_item_order=$total_num_of_item_order+$res->quantity;          
          if($res->sizename==NULL){
            $res->sizename='';
          }
          $res->branch_id=$branch_id;
          //$res->branch_name=$branch_name;
         // $res->vendor_name=$vendor_name;



          $res->branchlogo=$url.'storage/'.$res->branchlogo;
   if($res->item_logo!=''){
          $res->image=$url.'storage/'.$res->item_logo;
        }else{
          $res->image='';
        }
          if($res->item_logo_details!=''){
            $res->image_details=$url.'storage/'.$res->item_logo_details;           
          }else{
            $res->image_details='';         
          }
          unset($res->item_logo);
          unset($res->item_logo_details);
$originalDate = $res->created_at;
$newDate = date("j M Y", strtotime($originalDate));
$res->ordered_date=$newDate;
$newtime = date("g:i a", strtotime($originalDate));
$res->ordered_at=$newtime;



          $vendorname=$res->vendor_name;
          $branchname=$branch_data->name;
          if($lang=='ar'){
            $vendorname=$res->vendor_name_ar;
            $branchname=$branch_data->name_ar;
          }
          $res->vendor_name=$vendorname;
          $res->branch_name=$branchname;
          unset($res->vendor_name_ar);

          $itemname=$res->itemname;
          $sizename=$res->sizename;

          if($lang=='ar'){
             $itemname=$res->itemname_ar;
             $sizename=$res->sizename_ar;
          }
          $res->itemname=$itemname;
          $res->sizename=$sizename;
        unset($res->itemname_ar);
        unset($res->sizename_ar);
$res->num_of_people=$orders_list_data->num_of_people;





        }             
      
return response()->json(['data'=>['result' => $result2,'order_status'=>$res->status,'payment_type'=>$res->payment_type,'timer'=>$res->timer,'paid_money'=>$res->paid_money,'total_item_to_order'=>$total_num_of_item_order,'total_price'=>$total_price],'msg'=>'success','status_code'=>$this->successStatus]);
    } 

public function vendor_timer_status(Request $request){     
      $branches_Model=config('admin.database.branches_model');    
      $request_data=$request->all();
      $timer_status=$request_data['timer_status'];
      $token=$request->header('token');

      $user_data=DB::table('admin_users')->where('id','=',$token)->first();
      $branch_id=$user_data->branch_id;
      
      $branches_Model::where(['id'=>$branch_id])->update(array('timer_status' =>$timer_status));
      return response()->json(['msg'=>'success','status_code'=>$this->successStatus]);
    }
    public function vendor_dnn_table(Request $request){     
      $branches_Model=config('admin.database.branches_model');    
      $request_data=$request->all();
      $dnn_table=$request_data['dnn_table'];
      $token=$request->header('token');

      $user_data=DB::table('admin_users')->where('id','=',$token)->first();
      $branch_id=$user_data->branch_id;
      
      $branches_Model::where(['id'=>$branch_id])->update(array('dnn_table' =>$dnn_table));
      return response()->json(['msg'=>'success','status_code'=>$this->successStatus]);
    }

public function vendor_get_dnn_table(Request $request){
      $request_data=$request->all();
      $token=$request->header('token');
      $user_data=DB::table('admin_users')->where('id','=',$token)->first();
      $branch_id=$user_data->branch_id;      
      $branches_data=DB::table('branches')->where('id','=',$branch_id)->first();
      $result['dnn_table']=$branches_data->dnn_table;
return response()->json(['data'=>['result' => $result],'msg'=>'success',
          'status_code'=>$this->successStatus]);
   
}

public function vendor_get_timer_status(Request $request){
      $request_data=$request->all();
      $token=$request->header('token');
      $user_data=DB::table('admin_users')->where('id','=',$token)->first();
      $branch_id=$user_data->branch_id;      

        $branches_data=DB::table('branches')->where('id','=',$branch_id)->first();
        $result['timer_status']=$branches_data->timer_status;

return response()->json(['data'=>['result' => $result],'msg'=>'success',
          'status_code'=>$this->successStatus]);
   
}
public function vendor_accept_order(Request $request,$orderid){      
      $current_time = date("Y-m-d G:i:s");
      $order_list_Model=config('admin.database.orders_list_model');            
      $request_data=$request->all();
      $token=$request->header('token');                        
      $order_list_Model::where(['id'=>$orderid])->update(['status' =>'order placed']);
return response()->json(['msg'=>'order #'.$orderid.' accepted and preparing now success',
  'status_code'=>$this->successStatus]);
    }

public function vendor_preparing_order(Request $request,$orderid){
      $order_list_Model=config('admin.database.orders_list_model');
      $request_data=$request->all();
      $token=$request->header('token');      
      $order_list_Model::where(['id'=>$orderid])->update(['status' =>'preparing']);              
return response()->json(['msg'=>'order #'.$orderid.' is ready to preparing now',
  'status_code'=>$this->successStatus]);

}

public function vendor_ready_to_pick_order(Request $request,$orderid){
      $order_list_Model=config('admin.database.orders_list_model');
      $request_data=$request->all();
      $token=$request->header('token');      
      $order_list_Model::where(['id'=>$orderid])->update(['status' =>'ready to pickup','timer'=>0]);              
return response()->json(['msg'=>'order #'.$orderid.' is ready to pickup now',
  'status_code'=>$this->successStatus]);

}
public function vendor_confirm_order(Request $request,$orderid){
      $order_list_Model=config('admin.database.orders_list_model');
      $payment_Model=config('admin.database.payments_model');
      $request_data=$request->all();      
      $token=$request->header('token');      
      $order_list_Model::where(['id'=>$orderid])->update(array('status' =>'completed'));     

      $payment_Model::where(['order_id'=>$orderid])->update(['paid_money' =>1]);


return response()->json(['msg'=>'order #'.$orderid.' is completed now',
  'status_code'=>$this->successStatus]);


}

public function vendor_reject_order(Request $request){
      $order_list_Model=config('admin.database.orders_list_model');            
      $request_data=$request->all();
      $orderid=$request_data['order_id'];
      $note=$request_data['note'];
      $token=$request->header('token');      
      $order_list_Model::where(['id'=>$orderid])->update(array('status' =>'declined','comments'=>$note));
return response()->json(['msg'=>'order #'.$orderid.' declined success',
  'status_code'=>$this->successStatus]);

}


public function vendor_paid_order(Request $request){
      $payment_Model=config('admin.database.payments_model');            
      $request_data=$request->all();

      $orderid=$request_data['order_id'];      
      $token=$request->header('token');  
      
      $user_data=DB::table('admin_users')->where('id','=',$token)->first();
      $branch_id=$user_data->branch_id;      
      $payment_Model::where(['order_id'=>$orderid,'branch_id'=>$branch_id])->update(['paid_money' =>1]);
      return response()->json(['msg'=>'order #'.$orderid.' paid success',
      'status_code'=>$this->successStatus]);

}


public function account(Request $request){
      $order_list_Model=config('admin.database.orders_list_model');
      $request_data=$request->all();
      $token=$request->header('token');
      $user_data=DB::table('admin_users')->where('id','=',$token)->first();
      $branch_id=$user_data->branch_id;      
      $result_orders_today=DB::table('orders_list')
      ->where(['branch_id'=>$branch_id])->whereDay('orders_list.created_at', '=', date('d'))->get();

      $result_orders_completed_today=DB::table('orders_list')
      ->where(['branch_id'=>$branch_id,'status'=>'completed'])->whereDay('orders_list.created_at', '=', date('d'))->get(); 
      
      $result_orders_rejected_today=DB::table('orders_list')
      ->where(['branch_id'=>$branch_id,'status'=>'declined'])->whereDay('orders_list.created_at', '=', date('d'))->get();                   

      $res_earns=db::table('orders_list')
            ->select(DB::raw('SUM(total_price) as totalprice'))
            ->where(['branch_id'=>$branch_id,'status'=>'completed'])->whereDay('orders_list.created_at', '=', date('d'))->first();
      $total_orders_today=count($result_orders_today);      
      $total_orders_completed_today=count($result_orders_completed_today);      
      $total_orders_rejected_today=count($result_orders_rejected_today);

     // $total_cash_payment=0;
     // $total_credit_cart_payment=0;
      /*if($res_earns->totalprice!=NULL){
       $total_cash_payment= $res_earns->totalprice;
      }*/
      $res_cash_payment=db::table('payments')
            ->select(DB::raw('SUM(money) as money'))
            ->where(['branch_id'=>$branch_id,'payment_type'=>'cash'])->whereDay('created_at', '=', date('d'))->first();

      $res_credit_cart_payment=db::table('payments')
            ->select(DB::raw('SUM(money) as money'))
            ->where(['branch_id'=>$branch_id,'payment_type'=>'online'])->whereDay('created_at', '=', date('d'))->first();

      $total_cash_payment=$res_cash_payment->money;
      $total_credit_cart_payment=$res_credit_cart_payment->money;
      if($total_cash_payment=='' || $total_cash_payment==null){
        $total_cash_payment=0;
      }
      if($total_credit_cart_payment=='' || $total_credit_cart_payment==null){
        $total_credit_cart_payment=0;
      }
      $total_price=$total_cash_payment+$total_credit_cart_payment;      
      $today = date("j M Y");
      $day=date("D");
      return response()->json(['today_date'=>$today,'day'=>$day,'total_orders'=>$total_orders_today,'orders_completed'=>$total_orders_completed_today,'orders_rejected'=>$total_orders_rejected_today,'total_cash_payment'=>$total_cash_payment,'total_credit_card_payment'=>$total_credit_cart_payment,'total_price'=>$total_price,'msg'=>'success','status_code'=>$this->successStatus]);
}
public function earnings(Request $request){
      $order_list_Model=config('admin.database.orders_list_model');
      $request_data=$request->all();
      $token=$request->header('token');
      $user_data=DB::table('admin_users')->where('id','=',$token)->first();
      $branch_id=$user_data->branch_id;      
      $result_orders_today=DB::table('orders_list')
      ->where(['branch_id'=>$branch_id])->whereDay('orders_list.created_at', '=', date('d'))->get();

      $result_orders_completed_today=DB::table('orders_list')
      ->where(['branch_id'=>$branch_id,'status'=>'completed'])->whereDay('orders_list.created_at', '=', date('d'))->get(); 
      
      $result_orders_rejected_today=DB::table('orders_list')
      ->where(['branch_id'=>$branch_id,'status'=>'declined'])->whereDay('orders_list.created_at', '=', date('d'))->get();                   

      $res_earns=db::table('orders_list')
            ->select(DB::raw('SUM(total_price) as totalprice'))
            ->where(['branch_id'=>$branch_id,'status'=>'completed'])->whereDay('orders_list.created_at', '=', date('d'))->first();
      $total_orders_today=count($result_orders_today);      
      $total_orders_completed_today=count($result_orders_completed_today);      
      $total_orders_rejected_today=count($result_orders_rejected_today);

     // $total_cash_payment=0;
     // $total_credit_cart_payment=0;
      /*if($res_earns->totalprice!=NULL){
       $total_cash_payment= $res_earns->totalprice;
      }*/
      $res_cash_payment=db::table('payments')
            ->select(DB::raw('SUM(money) as money'))
            ->where(['branch_id'=>$branch_id,'payment_type'=>'cash'])->whereDay('created_at', '=', date('d'))->first();

      $res_credit_cart_payment=db::table('payments')
            ->select(DB::raw('SUM(money) as money'))
            ->where(['branch_id'=>$branch_id,'payment_type'=>'online'])->whereDay('created_at', '=', date('d'))->first();

      $total_cash_payment=$res_cash_payment->money;
      $total_credit_cart_payment=$res_credit_cart_payment->money;
      if($total_cash_payment=='' || $total_cash_payment==null){
        $total_cash_payment=0;
      }
      if($total_credit_cart_payment=='' || $total_credit_cart_payment==null){
        $total_credit_cart_payment=0;
      }
      $total_price=$total_cash_payment+$total_credit_cart_payment;      
      $today = date("j M Y");
      $day=date("D");
      // return response()->json(['today_date'=>$today,'day'=>$day,'total_orders'=>$total_orders_today,'orders_completed'=>$total_orders_completed_today,'orders_rejected'=>$total_orders_rejected_today,'total_cash_payment'=>$total_cash_payment,'total_credit_card_payment'=>$total_credit_cart_payment,'total_price'=>$total_price,'msg'=>'success','status_code'=>$this->successStatus]);
      // $result=array();
      
      $result['today_date']=$today;
      $result['day']=$day;      
      $result['total_orders']=$total_orders_today;
      $result['orders_completed']=$total_orders_completed_today;
      $result['orders_rejected']=$total_orders_rejected_today;
      $result['total_cash_payment']=$total_cash_payment;      
      $result['total_credit_card_payment']=$total_credit_cart_payment;
      $result['total_price']=$total_price;
      

                  
              return response()->json(['data'=>['result' => $result] ,'msg'=>'success','status_code'=>$this->successStatus]); 

}
public function vendor_list_reservation_table(Request $request){
     // date_default_timezone_set('Asia/Riyadh');
      $current_date = date('Y-m-d', time());   
      $current_time=date('H:i');      
      $currenttime_timestamp = strtotime($current_time);
      $add_10minutes = strtotime("+10 minutes", $currenttime_timestamp);
      $current_time_with_minutes = date("H:i", $add_10minutes);

      $request_data=$request->all();      
      $token=$request->header('token');      
      $lang=$request->header('lang');
      $user_data=DB::table('admin_users')->where('id','=',$token)->first();
      $branch_id=$user_data->branch_id;
      $result = DB::table('reservation')
              ->leftjoin('branches', 'reservation.branch_id', '=', 'branches.id')
              ->leftjoin('vendors', 'reservation.vendor_id', '=', 'vendors.id')
              ->select('reservation.*','vendors.vendor_name','vendors.vendor_name_ar','branches.name as branch_name','branches.name_ar as branch_name_ar','vendors.vendor_logo as branchlogo','reservation_table_pay_cash','reservation_table_pay_online')
              ->where(['branch_id'=>$branch_id])
              ->whereDate('date','=',$current_date)
              ->where('time','>=',$current_time_with_minutes)
              
              ->orderBy('id','desc')->get();
              $result2=array();
                  $url=str_replace('public','',url(""));
              foreach ($result as $rows){
                  if($rows->payment_method=='cash'){
                      $rows->pay_money=$rows->reservation_table_pay_cash;
                  }elseif($rows->payment_method=='online'){
                      $rows->pay_money=$rows->reservation_table_pay_online;
                  }elseif($rows->payment_method=='points'){
                      $rows->pay_money=$rows->reservation_table_pay_online;
                  }else{
                      $rows->pay_money=0;
                  }
                  unset($rows->reservation_table_pay_cash);
                  unset($rows->reservation_table_pay_online);

                  unset($rows->created_at);
                  unset($rows->updated_at);
                  $rows->branchlogo=$url.'storage/'.$rows->branchlogo;                       
                  $rows->time=date("g:i A", strtotime($rows->time." UTC"));



          $vendorname=$rows->vendor_name;
          $branchname=$rows->branch_name;
          if($lang=='ar'){
            $vendorname=$rows->vendor_name_ar;
            $branchname=$rows->branch_name_ar;
          }
          $rows->vendor_name=$vendorname;
          $rows->branch_name=$branchname;
unset($rows->vendor_name_ar);
unset($rows->branch_name_ar);

                  $result2[]=$rows;                     
              }
             
               return response()->json(['data'=>['result' => $result2],'msg'=>'success',
          'status_code'=>$this->successStatus]);
    }
public function vendor_support(Request $request){      
      $request_data=$request->all();
      $message=$request_data['message'];
      $token=$request->header('token');
      $user_data=DB::table('admin_users')->where('id','=',$token)->first();
      $branchid=$user_data->branch_id;

      $branch_data=DB::table('branches')
                   ->leftjoin('vendors', 'branches.vendor_id', '=', 'vendors.id')
                   ->select('branches.*','vendor_name')
                   ->where('branches.id','=',$branchid)->first();

      $branch_name=$branch_data->name;
      $vendor_name=$branch_data->vendor_name;


        $setting_data=DB::table('settings')->where(['id'=>1])->first();
        $setting_email=$setting_data->email;  
      
        $to      = $setting_email;
        $subject = 'Weasy Vendor app Support';
        $msg     ='
        <html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<p><b>Branch name:</b>'.$branch_name.'</p>
<p><b>Vendor name:</b>'.$vendor_name.'</p>
<p><b>Message:</b>'.$message.'</p>

<p>Thank you</p>
<p><b>Weasy team</b></p>
<p><a href="https://www.weasy.sa">Click here to visit website</a></p>
</body>
</html>
';
       
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <info@weasy.sa>' . "\r\n";

        mail($to, $subject, $msg, $headers);


          return response()->json(['msg'=>'success','status_code'=>$this->successStatus]);             
}

public function vendor_profile(Request $request){
          $token=$request->header('token');
          $user_data=DB::table('admin_users')->where(['id'=>$token])->first();
          $success['token']=$user_data->id;    
          $success['user_id']=$user_data->id;
          $success['username']=$user_data->username;          
          $success['name']=$user_data->name;
         return response()->json(['data'=>['result' => $success],'msg'=>'success','status_code'=>$this->successStatus]);                    
}
    
public function vendor_logout_remove_device_token(Request $request){
      $request_data=$request->all();
      $token=$request->header('token');
      $device_token=$request_data['device_token'];
      $usersModel = config('admin.database.users_model');
      $usersModel::where(['device_token'=>$device_token])->update(array('device_token' =>''));
      return response()->json(['msg'=>'success','status_code'=>$this->successStatus]);
    }

}