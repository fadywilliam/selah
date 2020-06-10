
        
                        
        <div class="row"><div class="col-md-12"><div class="row">
    <div class="col-md-12">
        <div class="box box-info">
    <div class="box-header with-border">
        
        
                <h3 class="box-title"></h3>


        <div class="box-tools">
<a href="" onclick="print()" class="btn btn-sm btn-primary" title="Edit">
        <i class="fa fa-print"></i><span class="hidden-xs">{{trans('admin.Print')}}</span>
    </a>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <div class="form-horizontal">

        <div class="box-body">

            <div class="fields-group">                                
                <div class="form-group ">
                     

                  

                    <div class="col-md-12">                    
                        <div class="col-lg-3 col-12"><b>{{trans('admin.Month')}}</b></div>   
                        <div class="col-lg-3 col-12">{{$res_data->month}}</div>
                        <div class="col-lg-3 col-12"><b>{{trans('admin.Money')}}</b></div>   
                        <div class="col-lg-3 col-12">{{$res_data->money}}</div>                        
                    </div>
            



                    <div class="col-md-12">                    
                        <div class="col-lg-3 col-12"><b>{{trans('admin.Invoice type')}}</b></div>  
                        <?php if($res_data->invoice_type=='installment'){ ?>
                                <div class="col-lg-3 col-12">{{trans('admin.Installment')}}</div>
                        <?php }elseif($res_data->invoice_type=='gass'){ ?>
                                <div class="col-lg-3 col-12">{{trans('admin.Gass')}}</div>
                         <?php }else{ ?>
                                <div class="col-lg-3 col-12">{{trans('admin.Internet')}}</div>
                         <?php } ?>
                        <div class="col-lg-3 col-12"><b>{{trans('admin.Renter')}}</b></div>   
                        <div class="col-lg-3 col-12">{{$res_data->rentername}}</div>                        
                    </div>




<div class="col-md-12">                    
                        <div class="col-lg-3 col-12"><b>{{trans('admin.Account num')}}</b></div>   
                        <div class="col-lg-3 col-12">{{$res_data->account_num}}</div>
                        <div class="col-lg-3 col-12"><b>{{trans('admin.Paid status')}}</b></div>  
                        <?php if($res_data->paid_status==1){?>
                            <div class="col-lg-3 col-12">{{trans('admin.Paid')}}</div>                        
                        <?php }else{ ?>
                            <div class="col-lg-3 col-12">{{trans('admin.Not paid')}}</div>                        
                        <?php } ?>
                        
                    
                    </div>






             



            </div>

        </div>
        <!-- /.box-body -->
    </div>
</div>
    </div>

    <div class="col-md-12">
            </div>
</div></div></div>

    <style type="text/css">
        .myowners{
    border: none;
    /* text-decoration: none; */
    border: 1px solid #354B92;
    /* display: inline-block; */
    margin: 50px 0px;
    width: 100%;
    height: 100px;
    font-size: 32px;
    background-color: #F1F5F9;

        }
        
        .addnewproperty {
    background-color: #fff;
    color: white;
    text-align: center;
    margin-bottom: 5px;
    height: 25px;
    width: 25px;
    border-radius: 50%;
    border: 2px solid #000000;
    color: #000000;
    font-weight: bold;
    text-align: center;
}
.collectionbox {
    border-radius: 5px;    
    background-color: #fff;    
    margin-bottom: 7%;
    width: 220px;
    height: 180px;
    -webkit-box-shadow: 10px 10px 5px -4px rgba(40,52,117,0.67);
-moz-box-shadow: 10px 10px 5px -4px rgba(40,52,117,0.67);
box-shadow: 10px 10px 5px -4px rgba(40,52,117,0.67);
margin-bottom: 10px;
}
.mypropertyname{
    text-align: center;
    padding-bottom: 5%;
    font-weight:bold;
}
    </style>

        <script>$(document).ready(function(){function print(){window.print();return false;}});</script></div></div>
