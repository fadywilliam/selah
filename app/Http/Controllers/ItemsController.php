<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Session\Store;


class ItemsController extends Controller
{
    public function index()
    {
        
    }
    public function insert(Request $request){
           $data=$request->all();
           $input['vendor_branch_user_id']=$data['vendor_branch_user_id'];
           $input['cat_id']=$data['cat_id'];
           $input['name']=$data['name'];
           $input['name_ar']=$data['name_ar'];
           $input['price']=$data['price'];
           $input['vendor_id']=$data['vendor_id'];
           $input['available']=$data['available'];
           $input['normal_time']=$data['normal_time_h'].$data['normal_time_m'].'00';
           $input['rush_time']=$data['rush_time_h'].$data['rush_time_m'].'00';
           $input['description']=isset($data['description'])?$data['description']:'';
           $input['description_ar']=isset($data['description_ar'])?$data['description_ar']:'';           
         //  $input['item_logo']=$data['item_logo'];
          // $input['item_logo_details']=$data['item_logo_details'];


        if($request->hasFile('item_logo')) {
            $item_logo = $request->file('item_logo');         
            $filename_logo =strtolower(trim($item_logo->getClientOriginalName()));      

          //  $image_name_wo_ext = substr($filename_logo, 0, strripos($filename_logo,'.')); 
            $type_logo=substr($filename_logo,strrpos($filename_logo,'.')+0);

            $filename_logo=rand(1, 1000000).time().$type_logo;



            $item_logo->move(storage_path('images'), $filename_logo);
            $item_logo = $filename_logo;//$request->file('image')->getClientOriginalName();
        }
//echo $item_logo;die;

      if($request->hasFile('item_logo_details')) {
        $item_logo_details = $request->file('item_logo_details');         
        $filename_logo_details =strtolower(trim($item_logo_details->getClientOriginalName()));      

      //  $image_name_wo_ext = substr($filename_logo_details, 0, strripos($filename_logo_details,'.')); 
        $type_logo_details=substr($filename_logo_details,strrpos($filename_logo_details,'.')+0);

        $filename_logo_details=rand(1, 1000000).time().$type_logo_details;



        $item_logo_details->move(storage_path('images'), $filename_logo_details);
        $item_logo_details = $filename_logo_details;//$request->file('image')->getClientOriginalName();
  }


//echo $item_logo.'>'.$item_logo_details;die;


     $input['item_logo']='images/'.$item_logo;
     $input['item_logo_details']='images/'.$item_logo_details;

    $items_model = config('admin.database.items_model');            
    $item_Data=$items_model::create($input);
$itemid=$item_Data->id;
//$vendor_id=$item_Data->vendor_branch_user_id;
//echo $lastid;die;
//print_r($_POST);die;
//$sizes_count=count(array_filter($_POST['addmore_size_name'][]));

if(isset($_POST['addmore'])){
$addmore_sizes_count=count(array_filter($_POST['addmore']));
$addmoresize=array_values(array_filter($_POST['addmore']));

$itemsize_arr=array();
if($addmore_sizes_count>0){
  for($i=0; $i <$addmore_sizes_count ; $i++) { 
  //  print_r($_POST['addmore']);die;
         $inputsize['name']=$addmoresize[$i]['name'];
         $inputsize['name_ar']=$addmoresize[$i]['name_ar'];
         $inputsize['price']=$addmoresize[$i]['price'];
         $inputsize['item_id']=$itemid;         
         
//echo $inputsize['price'];die;
         $itemsizes_model = config('admin.database.itemsizes_model');      
         $itemsizes_data=$itemsizes_model::create($inputsize);
        
  }

}
}
//die;

        // print_r($data);die;
    //return redirect('items');

    //  return back();        
      //return Redirect::action('ItemsController@index');
        return redirect()->intended('admin/auth/items'); //redirect to admin panel



        } 


}