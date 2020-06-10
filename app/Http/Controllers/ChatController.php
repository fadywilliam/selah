<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Session\Store;


class ChatController extends Controller
{
    public function index()
    {
        
    }
    public function sendmessage(Request $request){            
           $data=$request->all();
           $input['sender_id']=1;
           $input['reciever_id']=$data['reciever_id'];
           $input['token']=1;
           $input['message']=$data['message'];
                     
           $chat_model = config('admin.database.chat_model');            
 
           $chat_model_Data=$chat_model::create($input);              
            
           admin_toastr(trans('admin.message sent success'),'success');   
              
           return redirect(admin_url('auth/chat?sender_id='.$data['reciever_id']));        

            return back();        
        }
     
    
}