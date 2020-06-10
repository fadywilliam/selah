<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Session\Store;


class MypropertyController extends Controller
{
    public function index()
    {
        
    }
    public function add(Request $request){
            
        

          $data=$request->all();
//print_r($data);die;
           $input['owner_id']=$data['owner_id'];
           $input['name']=$data['name'];           
           $input['name_ar']=$data['name_ar'];
 //          $input['building_area']=$data['building_area'];
           $input['location']=$data['location'];           
           $input['type']=$data['type'];           
           $input['status']=$data['status'];           
           $input['description']=isset($data['description'])?$data['description']:'';
           $input['description_ar']=isset($data['description_ar'])?$data['description_ar']:'';                   
          
           if($request->hasFile('image')) {
              $image = $request->file('image');         
              $filename_logo =strtolower(trim($image->getClientOriginalName()));      
            
              $type_logo=substr($filename_logo,strrpos($filename_logo,'.')+0);

              $filename_logo=rand(1, 1000000).time().$type_logo;

              $image->move(storage_path('images'), $filename_logo);
              $image = $filename_logo;//$request->file('image')->getClientOriginalName();
          }
        
            $input['image']='images/'.$image;
            $myproperty_model = config('admin.database.myproperty_model');            
 
            $item_Data=$myproperty_model::create($input);              
            
            admin_toastr(trans('admin.Add new property'),'success');   
            return redirect(admin_url('auth/myproperty?owner_id='.$data['owner_id']));        
            return back();        
        }
    public function edit(Request $request){          
           $data=$request->all();
           $id=$data['id'];           
           $name=$data['name'];           
           $name_ar=$data['name_ar'];
           $location=$data['location'];           
           $type=$data['type'];           
           $status=$data['status'];           
           $description=isset($data['description'])?$data['description']:'';
           $description_ar=isset($data['description_ar'])?$data['description_ar']:'';                   
           $image_path=$data['image_path'];
           if($request->hasFile('image')) {
              $image = $request->file('image');         
              $filename_logo =strtolower(trim($image->getClientOriginalName()));      
            
              $type_logo=substr($filename_logo,strrpos($filename_logo,'.')+0);

              $filename_logo=rand(1, 1000000).time().$type_logo;

              $image->move(storage_path('images'), $filename_logo);
              $image = $filename_logo;//$request->file('image')->getClientOriginalName();              
              $image_path='images/'.$image;                                     
          }
                  
          $myproperty_model = config('admin.database.myproperty_model');            
          $myproperty_Data=$myproperty_model::where(['id' =>$id])->update([
              'name' => $name,
              'name_ar' => $name_ar,
              'location' => $location,
              'type'=>$type,
              'status'=>$status,
              'description'=>$description,
              'description_ar'=>$description_ar,
              'image'=>$image_path
          ]);

            admin_toastr(trans('admin.update myproperty'),'success');   
            return redirect(admin_url('auth/myproperty?owner_id='.$data['owner_id']));        
            return back();        
        }
      public function insert_addmoredetails(Request $request){

          $data=$request->all();
//print_r($data);die;
           $renter_id=isset($data['renter_id'])?$data['renter_id']:0;
           $name=$data['name'];           
           $name_ar=$data['name_ar']; 
           $location=$data['location'];  
           $room_count=isset($data['room_count'])?$data['room_count']:0;
        //   $bathroom_count=isset($data['bathroom_count'])?$data['bathroom_count']:0;
      //     $hall_count=isset($data['hall_count'])?$data['hall_count']:0;
          // $kitchen=isset($data['kitchen'])?$data['kitchen']:0;
         //  $furniture=isset($data['furniture'])?$data['furniture']:0;
           //$elevator=isset($data['elevator'])?$data['elevator']:0;

       //    $car_entrance=isset($data['car_entrance'])?$data['car_entrance']:0;
        //   $adaptations=isset($data['adaptations'])?$data['adaptations']:0;
           $area_partment=isset($data['area_partment'])?$data['area_partment']:'';

           $property_age=isset($data['property_age'])?$data['property_age']:'';

           $contract_start_date=isset($data['contract_start_date'])?$data['contract_start_date']:'';
           $contract_end_date=isset($data['contract_end_date'])?$data['contract_end_date']:'';
           $contract_period=isset($data['contract_period'])?$data['contract_period']:'';


          $paid_system=isset($data['paid_system'])?$data['paid_system']:'';
          $rent_money=isset($data['rent_money'])?$data['rent_money']:0;

          $insurance=isset($data['insurance'])?$data['insurance']:'';
          $myproperty_id=isset($data['myproperty_id'])?$data['myproperty_id']:0;
          $rental_type=isset($data['rental_type'])?$data['rental_type']:'';
          $mypropertytype=isset($data['mypropertytype'])?$data['mypropertytype']:'';
        
          $bathroom_count=0;
          $hall_count=0;
          $kitchen=0;
          $furniture=0;
          $elevator=0;
          $car_entrance=0;
          $adaptations=0;
          $contract_file_path='';
          if(isset($data['bathroom_count']) &&  $data['bathroom_count']=='on'){ 
             $bathroom_count=1;
          }
          if(isset($data['hall_count']) &&  $data['hall_count']=='on'){ 
            $hall_count=1;
          }
          if(isset($data['kitchen']) &&  $data['kitchen']=='on'){ 
            $kitchen=1;
          }
          if(isset($data['furniture']) &&  $data['furniture']=='on'){ 
            $furniture=1;
          }
          if(isset($data['elevator']) &&  $data['elevator']=='on'){ 
            $elevator=1;
          }
          if(isset($data['car_entrance']) &&  $data['car_entrance']=='on'){ 
            $car_entrance=1;
          }
          if(isset($data['adaptations']) &&  $data['adaptations']=='on'){ 
            $adaptations=1;
          }
                
         if($request->hasFile('contract_file')) {
        $contract_file = $request->file('contract_file');         
        $filename =strtolower(trim($contract_file->getClientOriginalName()));      

        $image_name_wo_ext = substr($filename, 0, strripos($filename,'.')); 
        $type=substr($filename,strrpos($filename,'.')+0);

        $filename=time().$type;
        $contract_file->move(storage_path('images'), $filename);
        $contract_file = $filename;//$request->file('image')->getClientOriginalName();
       $contract_file_path='images/'.$contract_file;                                     
  }



  $last_myproperty_id=DB::table('myproperty_details')->insertGetId(
  array(
          'renter_id' => $renter_id,
          'myproperty_id' => $myproperty_id,
          'rental_type' => $rental_type,
          'name' => $name,
          'name_ar' => $name_ar,
          'location' => $location,
          'room_count' => $room_count,
          'bathroom_count' => $bathroom_count,
          'hall_count' => $hall_count,
          'kitchen' => $kitchen,
          'furniture' => $furniture,
          'elevator' => $elevator,
          'car_entrance' => $car_entrance,
          'adaptations' => $adaptations,
          'area_partment' => $area_partment,
          'property_age' => $property_age,          
          'contract_period' => $contract_period,
          'paid_system' => $paid_system,
          'rent_money' => $rent_money,
          'insurance' => $insurance,                    
          'contract_file'=>$contract_file_path,
          'contract_start_date' => $contract_start_date,
          'contract_end_date' => $contract_end_date,
  )

);

          if($request->hasFile('files')) {
                $files= $request->file('files');
                for($i=0; $i < count($files); $i++) {                
                    $filename =strtolower(trim($files[$i]->getClientOriginalName()));
                    $image_name_wo_ext = substr($filename, 0, strripos($filename,'.')); 
                    $type=substr($filename,strrpos($filename,'.')+0);
                    $filename=time().rand(1000,9999).$type;
                    $files[$i]->move(storage_path('images'), $filename);          
                    $file_path='images/'.$filename;          
                    DB::table('myproperty_attachment')->insert(array('myproperty_id' => $myproperty_id,'files' =>$file_path));
              }  
            }


          if($request->hasFile('images')) {
                $images= $request->file('images');
                for($j=0; $j < count($images); $j++) {                
                    $filename_img =strtolower(trim($images[$j]->getClientOriginalName()));
                    $image_name_wo_ext = substr($filename_img, 0, strripos($filename_img,'.')); 
                    $typeimg=substr($filename_img,strrpos($filename_img,'.')+0);
                    $filename_img=time().rand(1000,9999).$typeimg;
                    $images[$j]->move(storage_path('images'), $filename_img);          
                    $image_path='images/'.$filename_img;          
                    DB::table('myproperty_images')->insert(array('mypropertydetails_id' => $last_myproperty_id,'image' =>$image_path));
              }  
            }

  /*          $my_prop_images=DB::Table('myproperty_images')->where(['myproperty_id'=>$last_myproperty_id])->get();
            $resimg=array();
            if(count($my_prop_images)>0){
              foreach ($my_prop_files as $rowsimg) {
                $rowsimg->images=url('storage/'.$rowsimg->image);
                $resimg[]=$rowsimg;  
              }
            }
*/


         admin_toastr(trans('admin.Add more details property'),'success');   
         $myproperty_data=DB::table('myproperty')->where(['id'=>$myproperty_id])->first();
         $owner_id=$myproperty_data->owner_id;
         
            if(!empty($mypropertytype)){ // building         
         
return redirect(admin_url('auth/mypropertydetails/'.$last_myproperty_id.'?id='.$last_myproperty_id.'&myproperty_id='.$myproperty_id.'&owner_id='.$owner_id));        
            }else{
return redirect(admin_url('auth/mypropertydetails/'.$myproperty_id.'?type='.$data['rental_type'].'&myproperty_id='.$myproperty_id.'&owner_id='.$owner_id));        
            }
            
            return back();        
        } 
        public function update_addmoredetails(Request $request){
           $data=$request->all();
           $myproperty_id=isset($_GET['myproperty_id'])?$_GET['myproperty_id']:'';
           $id=isset($_GET['id'])?$_GET['id']:'';
           $renter_id=isset($data['renter_id'])?$data['renter_id']:0;
           $name=$data['name'];           
           $name_ar=$data['name_ar']; 
           $location=$data['location'];  
           $room_count=isset($data['room_count'])?$data['room_count']:0;
           $area_partment=isset($data['area_partment'])?$data['area_partment']:'';

           $property_age=isset($data['property_age'])?$data['property_age']:'';

           $contract_start_date=isset($data['contract_start_date'])?$data['contract_start_date']:'';
           $contract_end_date=isset($data['contract_end_date'])?$data['contract_end_date']:'';
           $contract_period=isset($data['contract_period'])?$data['contract_period']:'';


          $paid_system=isset($data['paid_system'])?$data['paid_system']:'';
          $rent_money=isset($data['rent_money'])?$data['rent_money']:0;

          $insurance=isset($data['insurance'])?$data['insurance']:'';
        
          $rental_type=isset($data['rental_type'])?$data['rental_type']:'';
          $mypropertytype=isset($data['mypropertytype'])?$data['mypropertytype']:'';
        
          $bathroom_count=0;
          $hall_count=0;
          $kitchen=0;
          $furniture=0;
          $elevator=0;
          $car_entrance=0;
          $adaptations=0;
          $contract_file_path='';
          if(isset($data['bathroom_count']) &&  $data['bathroom_count']=='on'){ 
             $bathroom_count=1;
          }
          if(isset($data['hall_count']) &&  $data['hall_count']=='on'){ 
            $hall_count=1;
          }
          if(isset($data['kitchen']) &&  $data['kitchen']=='on'){ 
            $kitchen=1;
          }
          if(isset($data['furniture']) &&  $data['furniture']=='on'){ 
            $furniture=1;
          }
          if(isset($data['elevator']) &&  $data['elevator']=='on'){ 
            $elevator=1;
          }
          if(isset($data['car_entrance']) &&  $data['car_entrance']=='on'){ 
            $car_entrance=1;
          }
          if(isset($data['adaptations']) &&  $data['adaptations']=='on'){ 
            $adaptations=1;
          }
                
         if($request->hasFile('contract_file')) {
              $contract_file = $request->file('contract_file');         
              $filename =strtolower(trim($contract_file->getClientOriginalName()));      

              $image_name_wo_ext = substr($filename, 0, strripos($filename,'.')); 
              $type=substr($filename,strrpos($filename,'.')+0);

              $filename=time().$type;
              $contract_file->move(storage_path('images'), $filename);
              $contract_file = $filename;//$request->file('image')->getClientOriginalName();
              $contract_file_path='images/'.$contract_file;                                     
          }


  $mypropertydetails_model = config('admin.database.mypropertydetails_model');                                                           

$mypropertydetails_Data=$mypropertydetails_model::where(['id' =>$id])->update([
          'renter_id' => $renter_id,
         // 'myproperty_id' => $myproperty_id,
          'rental_type' => $rental_type,
          'name' => $name,
          'name_ar' => $name_ar,
          'location' => $location,
          'room_count' => $room_count,
          'bathroom_count' => $bathroom_count,
          'hall_count' => $hall_count,
          'kitchen' => $kitchen,
          'furniture' => $furniture,
          'elevator' => $elevator,
          'car_entrance' => $car_entrance,
          'adaptations' => $adaptations,
          'area_partment' => $area_partment,
          'property_age' => $property_age,          
          'contract_period' => $contract_period,
          'paid_system' => $paid_system,
          'rent_money' => $rent_money,
          'insurance' => $insurance,                    
          'contract_file'=>$contract_file_path,
          'contract_start_date' => $contract_start_date,
          'contract_end_date' => $contract_end_date,

          ]);



          if($request->hasFile('files')) {
                $files= $request->file('files');
                for($i=0; $i < count($files); $i++) {                
                    $filename =strtolower(trim($files[$i]->getClientOriginalName()));
                    $image_name_wo_ext = substr($filename, 0, strripos($filename,'.')); 
                    $type=substr($filename,strrpos($filename,'.')+0);
                    $filename=time().rand(1000,9999).$type;
                    $files[$i]->move(storage_path('images'), $filename);          
                    $file_path='images/'.$filename;          
                    DB::table('myproperty_attachment')->insert(array('myproperty_id' => $myproperty_id,'files' =>$file_path));
              }  
            }


          if($request->hasFile('images')) {
                $images= $request->file('images');
                for($j=0; $j < count($images); $j++) {                
                    $filename_img =strtolower(trim($images[$j]->getClientOriginalName()));
                    $image_name_wo_ext = substr($filename_img, 0, strripos($filename_img,'.')); 
                    $typeimg=substr($filename_img,strrpos($filename_img,'.')+0);
                    $filename_img=time().rand(1000,9999).$typeimg;
                    $images[$j]->move(storage_path('images'), $filename_img);          
                    $image_path='images/'.$filename_img;          
                    DB::table('myproperty_images')->insert(array('mypropertydetails_id' => $id,'image' =>$image_path));
              }  
            }



        admin_toastr(trans('admin.Add more details property'),'success');   
         $myproperty_data=DB::table('myproperty')->where(['id'=>$myproperty_id])->first();
         $owner_id=$myproperty_data->owner_id;

        


            admin_toastr(trans('admin.edit more details property'),'success');   
return redirect(admin_url('auth/mypropertydetails/'.$id.'?id='.$id.'&myproperty_id='.$myproperty_id.'&owner_id='.$owner_id));        
            
              // return redirect(admin_url('auth/mypropertydetails/'.$id));        
            
            
            return back(); 
        } 
       
        public function autocomplete(Request $request)
    {
        $data = DB::table('users')
                ->where("name","LIKE","%{$request->input('query')}%")
                ->where(['type'=>'renter'])->get();
    // $results = array();

    // foreach ($data as $key => $v) {
    //     $results[]=['id' => $v->id, 'value' => $v->name];
    // }

    // return response()->json($results);
       return response()->json($data);
    }
    public function delete(){
    DB::table('myproperty')->where('id', '=', $_GET['myproperty_id'])->delete();
    
    DB::table('myproperty_details')->where('myproperty_id', '=', $_GET['myproperty_id'])->delete();
    
     admin_toastr(trans('admin.delete property success'),'success');   
    return redirect(admin_url('auth/myproperty?owner_id='.$_GET['owner_id']));        
    }
    
    public function deletemoredetails(){
    DB::table('myproperty_details')->where('id', '=', $_GET['id'])->delete();
    admin_toastr(trans('admin.delete flat success'),'success');
    return redirect(admin_url('auth/myproperty/'.$_GET['myproperty_id'].'?myproperty_id='.$_GET['myproperty_id'].'&owner_id='.$_GET['owner_id']));        
    }
    
    
}