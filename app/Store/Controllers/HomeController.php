<?php
namespace App\Admin\Controllers;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Support\Facades\View;
class HomeController extends Controller
{
    public function index(Content $content)
    {  

        return $content
           // ->title('Dashboard')
           // ->description('Description...')
          //  ->row(Dashboard::title())
            ->row(function (Row $row) {

                $row->column(12, function (Column $column) {
                    $column->append(Dashboard::info());
                });            

            $row->column(12, function (Column $column) {
                    $column->append(Dashboard::earns());
                });
            $row->column(12, function (Column $column) {
                    $column->append(Dashboard::orders_report());
            });
           $row->column(12, function (Column $column) {
                    $column->append(Dashboard::sale_branches());
                });

            $row->column(12, function (Column $column) {
                    $column->append(Dashboard::best_sale_items());
                });
           

            });  

        }    
}
