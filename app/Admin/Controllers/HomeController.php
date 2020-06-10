<?php
namespace App\Admin\Controllers;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Support\Facades\View;
use Encore\Admin\Admin;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
{
    public function index(Content $content)
    {  
      $user_data=new Admin();
        
        // $user_id=$user_data->user()->id;
        // $user_data_role=DB::table('admin_role_users')->select('role_id')->where(['user_id'=>$user_id])->first(); // for company
        // $user_role_id=$user_data_role->role_id;

        // if($user_role_id==1){

        return $content
           // ->title('Dashboard')
           // ->description('Description...')
          //  ->row(Dashboard::title())
            ->row(function (Row $row) {

                $row->column(12, function (Column $column) {
                    $column->append(Dashboard::info());
                });            

            $row->column(12, function (Column $column) {
                    $column->append(Dashboard::units());
                });
              // $row->column(6, function (Column $column) {
              //       $column->append(Dashboard::best_sale_items());
              //   });
           //  $row->column(6, function (Column $column) {
           //          $column->append(Dashboard::orders_report());
           //  });
           // $row->column(6, function (Column $column) {
           //          $column->append(Dashboard::sale_branches());
           //      });

           // $row->column(6, function (Column $column) {
            
           //          $column->append(Dashboard::top_stores());

           //      });
           // $row->column(6, function (Column $column) {
            
           //          $column->append(Dashboard::top_customers());

           //      });
          
           

            });  
        }    
}