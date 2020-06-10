<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Session\Store;


class SaleoffersController extends Controller
{
    public function index()
    {
        
    }
    public function delete(){
            
        
              $id=$_REQUEST['id'];
              $whereArray = array('id'=>$id);
              DB::table('myproperty')->where($whereArray)->delete();            
            admin_toastr(trans('admin.Remove sale offer'),'success');   
            return redirect(admin_url('auth/saleoffers'));
        
        return back();        
        } 
         public function insert_saleofferdetails(Request $request){
        //  echo $_GET['myproperty_id'];die;
           $data=$request->all();           
           $name=$data['name'];           
           $name_ar=$data['name_ar']; 
           $location=$data['location'];  
           $apartment_count=isset($data['apartment_count'])?$data['apartment_count']:0;
           
           $description=isset($data['description'])?$data['description']:'';
           $description_ar=isset($data['description_ar'])?$data['description_ar']:'';      
           
           $area_partment=isset($data['area_partment'])?$data['area_partment']:'';
           $property_age=isset($data['property_age'])?$data['property_age']:'';
           $price=isset($data['price'])?$data['price']:'';
           $building_area=isset($data['area_partment'])?$data['area_partment']:'';
           $elevator=0;
           $car_entrance=0;
           $myproperty_id=isset($_GET['myproperty_id'])?$_GET['myproperty_id']:'';

           $image_path='';
           
          if(isset($data['elevator']) &&  $data['elevator']=='on'){ 
            $elevator=1;
          }
          if(isset($data['car_entrance']) &&  $data['car_entrance']=='on'){ 
            $car_entrance=1;
          }                          
          if($request->hasFile('image_cover')) {
                    $image_cover= $request->file('image_cover');                              
                    $filename_cover_img =strtolower(trim($image_cover->getClientOriginalName()));
//                    $image_name_wo_ext = substr($filename_cover_img, 0, strripos($filename_cover_img,'.')); 
                    $typeimg=substr($filename_cover_img,strrpos($filename_cover_img,'.')+0);
                    $filename_cover_img=time().rand(1000,9999).$typeimg;
                    //echo $filename_cover_img;die;
                    $image_cover->move(storage_path('images'), $filename_cover_img);                             
                    $image_path='images/'.$filename_cover_img;     
            }
if(isset($myproperty_id) && $myproperty_id!='' ){  
            $myproperty_model = config('admin.database.myproperty_model');   
            $myproperty_data=DB::table('myproperty')->where(['id'=>$myproperty_id])->first();
                    if(empty($image_path)){
                      $image_path=$myproperty_data->image;
                    }                              
           $myproperty_Data=$myproperty_model::where(['id' =>$myproperty_id])->update([
              'name' => $name,
              'name_ar' => $name_ar,
              'location' => $location,
              'type'=>'building',
              'status'=>'sale',
              'description'=>$description,  
              'description_ar'=>$description_ar,    
              'price'=>$price,
              'building_area'=>$area_partment,  
              'image'=>$image_path
          ]);
}else{
    $myproperty_id=DB::table('myproperty')->insertGetId(
    array(
            'name' => $name,
            'name_ar' => $name_ar,
            'location' => $location,
            'type'=>'building',
            'status'=>'sale',
            'description'=>$description,  
            'description_ar'=>$description_ar,    
            'price'=>$price,
            'building_area'=>$area_partment,  
            'image'=>$image_path
    )

  );

}

  $last_myproperty_id=DB::table('myproperty_details')->insertGetId(
  array(
          
          'myproperty_id' => $myproperty_id,          
          'name' => $name,
          'name_ar' => $name_ar,
          'location' => $location,
          'elevator' => $elevator,
          'car_entrance' => $car_entrance, 
          'apartment_count' => $apartment_count,                 
          'area_partment'=>$area_partment,        
          'property_age'=>$property_age,
          'rental_type'=>'building'
  )

);


          if($request->hasFile('files')) {
                $files= $request->file('files');
                for($i=0; $i < count($files); $i++) {                
                    $filename =strtolower(trim($files[$i]->getClientOriginalName()));
                 //   $image_name_wo_ext = substr($filename, 0, strripos($filename,'.')); 
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
                 //   $image_name_wo_ext = substr($filename_img, 0, strripos($filename,'.')); 
                    $typeimg=substr($filename_img,strrpos($filename_img,'.')+0);
                    $filename_img=time().rand(1000,9999).$typeimg;
                    $images[$j]->move(storage_path('images'), $filename_img);          
                    $image_path='images/'.$filename_img;          
                    DB::table('myproperty_images')->insert(array('mypropertydetails_id' => $last_myproperty_id,'image' =>$image_path));
              }  
            }







            admin_toastr(trans('admin.Add sale offer'),'success');   
            
              return redirect(admin_url('auth/saleoffers/'));        
            
            
            return back(); 
        }

public function update_saleofferdetails(Request $request){
           $data=$request->all();
//           print_r($data);die;

           $id=$_GET['id'];
           $myproperty_id=$_GET['myproperty_id'];

           $name=$request->input('name');          
           $name_ar=$request->input('name_ar');
           $location=$request->input('location');  
           $apartment_count=$request->input('apartment_count');
           
           $description=$request->input('description');
           $description_ar=$request->input('description_ar');      
           
           $area_partment=$request->input('area_partment');
           $property_age=$request->input('property_age');
           $price=$request->input('price');
           $building_area=$request->input('area_partment');
           $image_path=$request->input('image_path');
           $elevator=0;
           $car_entrance=0;

          if(isset($data['elevator']) &&  $data['elevator']=='on'){ 
            $elevator=1;
          }
          if(isset($data['car_entrance']) &&  $data['car_entrance']=='on'){ 
            $car_entrance=1;
          }
                          
          if($request->hasFile('image_cover')) {
                    $image_cover= $request->file('image_cover');                              
                    $filename_cover_img =strtolower(trim($image_cover->getClientOriginalName()));

//                    $image_name_wo_ext = substr($filename_cover_img, 0, strripos($filename_cover_img,'.')); 
                    $typeimg=substr($filename_cover_img,strrpos($filename_cover_img,'.')+0);
                    $filename_cover_img=time().rand(1000,9999).$typeimg;
                    //echo $filename_cover_img;die;
                    $image_cover->move(storage_path('images'), $filename_cover_img);                             
                    $image_path='images/'.$filename_cover_img;     
                 //   echo $image_path;die;                   
            }

if(isset($myproperty_id) && $myproperty_id!='' ){  
            $myproperty_model = config('admin.database.myproperty_model');   
            $myproperty_data=DB::table('myproperty')->where(['id'=>$myproperty_id])->first();
                    if(empty($image_path)){
                      $image_path=$myproperty_data->image;
                    }   
                  }
          $myproperty_model = config('admin.database.myproperty_model');    
          $myproperty_Data=$myproperty_model::where(['id' =>$myproperty_id])->update([
              'name' => $name,
              'name_ar' => $name_ar,
              'location' => $location,
              'type'=>'building',
              'status'=>'sale',
              'description'=>$description,  
              'description_ar'=>$description_ar,    
              'price'=>$price,
              'building_area'=>$area_partment,  
              'image'=>$image_path
          ]);
$mypropertydetails_model = config('admin.database.mypropertydetails_model');                                                           

$mypropertydetails_Data=$mypropertydetails_model::where(['id' =>$id])->update([
          //'myproperty_id' => $myproperty_id,          
          'name' => $name,
          'name_ar' => $name_ar,
          'location' => $location,
          'elevator' => $elevator,
          'car_entrance' => $car_entrance, 
          'apartment_count' => $apartment_count,                 
          'area_partment'=>$area_partment,        
          'property_age'=>$property_age,
          'rental_type'=>'building'

          ]);



          if($request->hasFile('files')) {
                $files= $request->file('files');
                for($i=0; $i < count($files); $i++) {                
                    $filename =strtolower(trim($files[$i]->getClientOriginalName()));
                 //   $image_name_wo_ext = substr($filename, 0, strripos($filename,'.')); 
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
                 //   $image_name_wo_ext = substr($filename_img, 0, strripos($filename,'.')); 
                    $typeimg=substr($filename_img,strrpos($filename_img,'.')+0);
                    $filename_img=time().rand(1000,9999).$typeimg;
                    $images[$j]->move(storage_path('images'), $filename_img);          
                    $image_path='images/'.$filename_img;          
                    DB::table('myproperty_images')->insert(array('mypropertydetails_id' => $id,'image' =>$image_path));
              }  
            }







            admin_toastr(trans('admin.edit sale offer'),'success');   
            
              return redirect(admin_url('auth/saleoffers/'));        
            
            
            return back(); 
        }
        

}