<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Session\Store;


class UserController extends Controller
{
    public function index()
    {
        
    }
    public function insert(Request $request){
           $data=$request->all();
           $user_data_count=DB::table('admin_users')->select('*')->where(['username'=>$data['username']])->count(); // for company
           if($user_data_count>0){
              admin_toastr(trans('admin.Username already exit'),'warning');   
              return redirect(admin_url('auth/users/create'));
           }
           $input['name']=$data['name'];
           $input['username']=$data['username'];
           $input['password']=bcrypt($data['password']);
           $input['job_title']=$data['job_title'];
           $input['mobile']=$data['mobile'];           
           $input['permissions']=isset($data['permissions'])?$data['permissions']:'';        
           $users_model = config('admin.database.users_model');                                
           $user_Data=$users_model::create($input);
           
           $user_id=$user_Data->id;
           if(isset($input['permissions']) && $input['permissions']!='' ){
            foreach ($input['permissions'] as $permissionname) {
             DB::table('admin_employee_permissions')->insert(
              ['user_id' => $user_id, 'permission' =>$permissionname]
            );   
            }
             

        }
           admin_toastr(trans('admin.Add employee successful'));
           return redirect(admin_url('auth/users'));          
        } 

public function update(Request $request){
           $data=$request->all();      

           $id=$data['id'];    
           $input['name']=$data['name'];           
           $input['job_title']=$data['job_title'];
           $input['mobile']=$data['mobile'];           
           $input['permissions']=isset($data['permissions'])?$data['permissions']:'';        
           $users_model = config('admin.database.users_model');                                                     
           $user_Data=$users_model::where(['id' =>$id])->update([
            'name' =>$data['name'],
            'job_title'=>$data['job_title'],
            'mobile'=>$data['mobile']]);
           $whereArray = array('user_id'=>$id);
           $res_delete=DB::table('admin_employee_permissions')->where($whereArray)->delete();
           if(isset($input['permissions']) && $input['permissions']!='' ){
                    
            foreach ($input['permissions'] as $permissionname) {
             DB::table('admin_employee_permissions')->insert(
              ['user_id' => $id, 'permission' =>$permissionname]
            );   
            }
             

        }
           admin_toastr(trans('admin.Update employee successful'));
           return redirect(admin_url('auth/users'));          
        } 

}