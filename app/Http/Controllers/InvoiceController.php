<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Session\Store;


class InvoiceController extends Controller
{
    public function index()
    {
        
    }
    public function addinvoice(Request $request){            
          $data=$request->all();

          $id=$data['id'];
          $myproperty_id=$data['myproperty_id'];
          $owner_id=$data['owner_id'];

          $input['invoice_type']=$data['invoice_type'];
          $input['renter_id']=$data['renter_id'];           
          $input['myproperty_id']=$id;
          $input['account_num']=$data['account_num'];           
          $input['month']=$data['month'];           
          $input['money']=$data['money'];           
          $input['paid_status']=$data['paid_status'];
          $myproperty_type=isset($data['myproperty_type'])?$data['myproperty_type']:'';
          
          $invoice_model = config('admin.database.invoice_model');            

          $invoice_Data=$invoice_model::create($input);              

          admin_toastr(trans('admin.Add new Invoice'),'success');   
          if(empty($myproperty_type)){
          return redirect(admin_url('auth/mypropertydetails/'.$id.'?id='.$id.'&myproperty_id='.$myproperty_id.'&owner_id='.$owner_id));          
          }else{
          return redirect(admin_url('auth/mypropertydetails/'.$id.'?type='.$myproperty_type.'&myproperty_id='.$myproperty_id.'&owner_id='.$owner_id));        
          }
          
          return back();        
        }
       
      public function update_invoice(Request $request){
          $data=$request->all();
          $id=$data['id'];          
          $input['paid_status']=$data['paid_status'];
          
          
          $invoice_model = config('admin.database.invoice_model');

          $invoice_Data=$invoice_model::where(['id' =>$id])->update([                
          'paid_status'=> $data['paid_status'],          
          ]);


          
        $invoce_data=DB::table('invoice')->where(['id'=>$id])->first();
        $myproperty_id=$invoce_data->myproperty_id; 
        $invoice_type=$invoce_data->invoice_type; 
        $renter_id=$invoce_data->renter_id; 
        admin_toastr(trans('admin.Update invoice'),'success');
        
      

          return redirect(admin_url('auth/invoice?myproperty_id='.$myproperty_id.'&invoice_type='.$invoice_type.'&renter_id='.$renter_id));          

        //  return back();
        } 
       
    
    
    
}