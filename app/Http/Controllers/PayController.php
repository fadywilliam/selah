<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Session\Store;


class PayController extends Controller
{
    public function index()
    {
        
    }
    public function debpay(Request $request){
           $data=$request->all();    
           $vendor_id=$data['vendor_id'];
           $deptor_from=$data['deptor_from'];
           $deptor_to=$data['deptor_to'];       
            $paymentsModel = config('admin.database.payments_model');
            $paymentsModel::where(['vendor_id'=>$vendor_id])->whereRaw("date(created_at) >='".$deptor_from."' and date(created_at) <= '".$deptor_to."'" )->update(array('paid_fees_cash' =>'Yes'));        
        //return back()->with('message', 'Debtor pad');    
        return back();        
        } 

    public function creditorpay(Request $request){
           $data=$request->all();    
           $vendor_id=$data['vendor_id'];
           $creditor_from=$data['creditor_from'];
           $creditor_to=$data['creditor_to'];       
            $paymentsModel = config('admin.database.payments_model');
            $paymentsModel::where(['vendor_id'=>$vendor_id])->whereRaw("date(created_at) >='".$creditor_from."' and date(created_at) <= '".$creditor_to."'" )->update(array('paid_fees_online' =>'Yes'));        
        return back();        
        } 


    public function commissionpay(Request $request){
           $data=$request->all();    
           $vendor_id=$data['vendor_id'];
           $commission_from=$data['commission_from'];
           $commission_to=$data['commission_to'];       
            $paymentsModel = config('admin.database.payments_model');
            $paymentsModel::where(['vendor_id'=>$vendor_id])->whereRaw("date(created_at) >='".$commission_from."' and date(created_at) <= '".$commission_to."'" )->update(array('paid' =>'Yes'));        
        return back();        
        } 

}