<div class="row"><div class="col-md-12"><div class="row">
    <div class="col-md-12">
        <div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{trans('admin.Special sale offers')}}</h3>
<a href='{{admin_url("auth/saleoffers/create")}}' class="btn-group pull-left addsaleoffer">+</a>
        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 5px">    
</div>

        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <div class="form-horizontal">

        <div class="box-body">

            <div class="fields-group">                                
                <div class="form-group ">
<div class="col-md-12">                    
                        <div class="col-lg-2 col-12"><b>{{trans('admin.name')}}</b></div>

                        <div class="col-lg-2 col-12"><b>{{trans('admin.Price')}}</b></div>

                        <div class="col-lg-2 col-12"><b>{{trans('admin.Building area')}}</b></div>

                        <div class="col-lg-4 col-12"><b>{{trans('admin.Location')}}</b></div>
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Building cover')}}</b></div>

</div>          
<div class="col-md-12">                    
</div>
<div class="col-md-12">                    
</div>
<div class="col-md-12">                    
</div>
<div class="col-md-12">                    
</div>           
                    @foreach ($res_data as $row)

                     <div class="col-md-12">                    
                        <div class="bordersale">
                        <?php
        $res_details_data=DB::table('myproperty_details')->where(['myproperty_id'=>$row->id])->first();
                         ?>
                        <div class="col-lg-2 col-12">
                        <?php
                            if(empty($res_details_data)){?>
                                <a href='{{admin_url("auth/saleoffers/$row->id?myproperty_id=$row->id")}}'>{{$row->name}}</a>
                            <?php }else{ ?>
                                <a href='{{admin_url("auth/saleoffers/$row->id")}}'>{{$row->name}}</a>
                            <?php } ?>
                        </div>
                        
                        <div class="col-lg-2 col-12">{{$row->price}}</div>
                        <div class="col-lg-2 col-12">{{$row->building_area}}</div>
                        
                        
                        <div class="col-lg-4 col-12">{{$row->location}}</div>

                        


<div class="col-lg-2 col-12">
    <img width="100px" height="100px;" src="{{url('storage')}}/{{$row->image}}">
    <a href='{{url("saleofferdelete/?id=$row->id")}}' class="btn-group pull-left removesaleoffer">-</a>
    
</div>

</div>
                    </div>

                    @endforeach


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
    .bordersale {
            border: 1px solid #000;
            height: 126px;
            padding: 10px;
            margin-bottom:1%;
            border-radius: 25px;
   }

.addsaleoffer{
    background-color:#fff;color: white;text-align: center;margin-bottom: 5px;
  height: 25px;
  width: 25px;
  border-radius: 50%;
  border: 2px solid #000000;
  color: #000000;
  font-weight: bold;
  text-align: center;
}
.removesaleoffer{
    height: 25px;
    width: 25px;
    border-radius: 50%;
    border: 2px solid #000000;
    color: #000000;
    font-weight: bold;
    text-align: center;
    margin: 24% 0 auto; 
}
    
@media (max-width: 768px) {
  .bordersale {
    height: 100%;
  }
  .removesaleoffer{
    height: 25px;
    width: 25px;
    border-radius: 50%;
    border: 2px solid #000000;
    color: #000000;
    font-weight: bold;
    text-align: center;
    margin:auto;
}
}

    </style>

