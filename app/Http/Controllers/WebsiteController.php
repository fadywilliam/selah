<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebsiteController extends Controller
{
    public function index()
    {
        $url=str_replace('public','',url(""));
    	$res_data=DB::table('website')->where(['id'=>1])->first();
    	$data['section_one_title'] =$res_data->section_one_title;
		$data['section_one'] =$res_data->section_one;
		$data['section_two_title'] = $res_data->section_two_title;
		$data['section_two'] = $res_data->section_two;
		$data['section_three_title'] =$res_data->section_three_title;
		$data['section_three'] =$res_data->section_three;
		$data['about_us'] =$res_data->about_us;
        $data['address'] = $res_data->address;
        $data['phone'] = $res_data->phone;
        $data['email'] = $res_data->email;
        $data['slider_image']=$url.'storage/'.$res_data->slider_image;
        $data['about_us_image']=$url.'storage/'.$res_data->about_us_image;
        
        $data['success_msg']='';
        return view('welcome')->with($data);    
    }
    public function sendmail(Request $request){
        $setting_data=DB::table('settings')->where(['id'=>1])->first();
        $setting_email=$setting_data->email;  

        $data=$request->all();
        
        $user_name=$data['name'];
        $user_email=$data['email'];
        $user_msg=$data['message'];
        
        $to      =$setting_email;
        $subject = 'Weasy Contact us';
        $message='
        <html>
<head>
</head>
<body>
<p><b>Name:</b>'.$user_name.'</p>
<p><b>Email:</b>'.$user_email.'</p>
<p><b>Message:</b>'.$user_msg.'</p>
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
$headers .= 'From: <'.$user_email.'>' . "\r\n";

        mail($to, $subject, $message, $headers);

$res_data=DB::table('website')->where(['id'=>1])->first();
        $data['section_one_title'] =$res_data->section_one_title;
        $data['section_one'] =$res_data->section_one;
        $data['section_two_title'] = $res_data->section_two_title;
        $data['section_two'] = $res_data->section_two;
        $data['section_three_title'] =$res_data->section_three_title;
        $data['section_three'] =$res_data->section_three;
        $data['about_us'] =$res_data->about_us;
        $data['address'] = $res_data->address;
        $data['phone'] = $res_data->phone;
        $data['email'] = $res_data->email;
        $data['success_msg']='Thank you! we will contact with you soon';

        return view('welcome')->with($data);    


    }
 public function terms(){
    return view('terms');        
 }
}