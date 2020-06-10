
        
                        
        <div class="row"><div class="col-md-12"><div class="row">
    <div class="col-md-12">
        <div class="box box-info">
    <div class="box-header with-border">
        <!-- <h3 class="box-title">{{trans('admin.Details')}}</h3> -->
      <?php 
        $mypropertyname='';
        if(isset($_GET['myproperty_id'])){
           $myproperty_data=DB::table('myproperty')->where(['id'=>$_GET['myproperty_id']])->first();
           if(isset($_GET['type'])){
                $mypropertyname=$myproperty_data->name;
            }else{
                $mypropertyname=$myproperty_data->name.' -> ';
            }
        }
        $ownername='';
        if(isset($_GET['owner_id'])){
           $user_data=DB::table('users')->where(['id'=>$_GET['owner_id']])->first();
           $ownername=$user_data->name.' -> ';
        }
        $mypropertymoredetailsname='';
        if(isset($_GET['id'])){
           $mypropertymoredetails_data=DB::table('myproperty_details')->where(['id'=>$_GET['id']])->first();
           $mypropertymoredetailsname=$mypropertymoredetails_data->name;
        }


        ?>
        
                <h3 class="box-title">{{$ownername}}{{trans('admin.Myproperty')}} -> {{$mypropertyname}} {{$mypropertymoredetailsname}}</h3>


        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 5px">    
</div>
<?php if($res_data->renter_id!=0){ 
    if(isset($_GET['id'])){
        $id=$_GET['id'];
    }else{
        $id=$_GET['myproperty_id'];
    }
    $type='';
    if (isset($_GET['type'])) {
        $type='&type='.$_GET['type'];
    }
    ?>
<a href="{{admin_url('auth/invoice') }}/create?id=<?php echo $id; ?>&renter_id=<?php echo $res_data->renter_id; ?>&myproperty_id=<?php echo $_GET['myproperty_id'] ?>&owner_id=<?php echo $_GET['owner_id'] ?><?php echo $type; ?>"  class="btn btn-sm btn-primary" title="{{trans('admin.Add invoice')}}">
        <i class="fa fa-plus"></i><span class="hidden-xs">{{trans('admin.Add invoice')}}</span>
    </a>
<?php } ?>

<a href="{{admin_url('auth/mypropertydetails/') }}/<?php echo $res_data->myproperty_id; ?>/edit?myproperty_id=<?php echo $res_data->myproperty_id ?>&id=<?php echo $res_data->id; ?>"  class="btn btn-sm btn-primary" title="Edit">
        <i class="fa fa-edit"></i><span class="hidden-xs">{{trans('admin.Edit')}}</span>
    </a>

    


        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <div class="form-horizontal">

        <div class="box-body">

            <div class="fields-group">                                
                <div class="form-group ">
                     
                    @if(isset($res_data->renter_id) && $res_data->renter_id!=0)
                        <div class="col-md-12">                    
                            <h4>{{trans('admin.Renter detials')}}</h4>
                        </div>                    
                        <div class="col-md-12">                    
                        <div class="col-lg-2 col-12"><b>{{trans('admin.name')}}:</b></div>   
                        <div class="col-lg-2 col-12">{{$res_data->renter_name}}</div>
                        <div class="col-lg-2 col-12"><b>{{trans('admin.National id')}}:</b></div>   
                        <div class="col-lg-2 col-12">{{$res_data->national_id}}</div>
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Phone')}}:</b></div> 
                        <div class="col-lg-2 col-12">{{$res_data->phone}}</div>                     
                    @endif    

            </div>

@if(isset($res_data->location) && $res_data->location!='')
<div class="col-md-12">                    
                            <h4>{{trans('admin.Location')}}</h4>
                        </div>                    
                        <div class="col-md-12">                    
                        <div class="col-lg-12 col-12">{{$res_data->location}}</div>   
                                          
</div>
@endif
                    
                    
                    <div class="col-md-12">                    
                            <h4>{{trans('admin.Property Specifications')}}</h4>
                        </div>  

                    <div class="col-md-12">                    
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Room count')}}:</b></div>   
                        <div class="col-lg-2 col-12">{{isset($res_data->room_count)?$res_data->room_count:0}}</div>
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Bathroom count')}}:</b></div>   
                        <div class="col-lg-2 col-12">{{isset($res_data->bathroom_count)?$res_data->bathroom_count:0}}</div>
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Hall count')}}:</b></div> 
                        <div class="col-lg-2 col-12">{{isset($res_data->hall_count)?$res_data->hall_count:0}}</div>
                    </div>

                    <div class="col-md-12">
                    <div class="col-lg-2 col-12"><b>{{trans('admin.Area partment')}}:</b></div>   
                        <div class="col-lg-2 col-12">{{isset($res_data->area_partment)?$res_data->area_partment:''}}</div>
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Kitchen')}}:</b></div>   
                        <div class="col-lg-2 col-12">{{isset($res_data->kitchen)?$res_data->kitchen:0}}</div>
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Furniture')}}:</b></div> 
                        <div class="col-lg-2 col-12">{{isset($res_data->furniture)?$res_data->furniture:0}}</div>                        
                    </div>
                    <div class="col-md-12">
                    <div class="col-lg-2 col-12"><b>{{trans('admin.Elevator')}}:</b></div>   
                        <div class="col-lg-2 col-12">{{isset($res_data->elevator)?$res_data->elevator:0}}</div>
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Car entrance')}}:</b></div>   
                        <div class="col-lg-2 col-12">{{isset($res_data->car_entrance)?$res_data->car_entrance:''}}</div>
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Adaptations')}}:</b></div> 
                        <div class="col-lg-2 col-12">{{isset($res_data->adaptations)?$res_data->adaptations:0}}</div>                        
                    </div>
                    <div class="col-md-12">
                         <div class="col-lg-2 col-12"><b>{{trans('admin.Property age')}}:</b></div>   
                        <div class="col-lg-2 col-12">{{isset($res_data->property_age)?$res_data->property_age:''}}</div>
                    </div>

                    @if(count($property_images)>0)
                    <div id="preview">
                    <div class="col-md-12">                    
                            <h4>{{trans('admin.Properity images')}}</h4>
                        </div>  
                        @foreach($property_images as $property_rows)                    
                            <div class="col-lg-2 col-12"><img width="200px;" height="200px;" src="{{url('storage')}}/{{$property_rows->image}}" ></div>   
                        @endforeach
                    </div>
                        @endif


                    <div class="col-md-12">                    
                            <h4>{{trans('admin.Contract')}}</h4>
                        </div>  

                    <div class="col-md-12">                    
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Contract start date')}}:</b></div>   
                        <div class="col-lg-2 col-12">{{isset($res_data->contract_start_date)?$res_data->contract_start_date:''}}</div>
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Contract end date')}}:</b></div>   
                        <div class="col-lg-2 col-12">{{isset($res_data->contracr_end_date)?$res_data->contracr_end_date:''}}</div>
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Contract period')}}:</b></div>   
                        <div class="col-lg-2 col-12">{{isset($res_data->contract_period)?$res_data->contract_period:''}}</div>
                    </div>
 

                    <div class="col-md-12">                    
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Paid system')}}:</b></div>   
                        <div class="col-lg-2 col-12">{{isset($res_data->paid_system)?trans('admin.'.$res_data->paid_system):''}}</div>
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Rent money')}}:</b></div>   
                        <div class="col-lg-2 col-12">{{isset($res_data->rent_money)?$res_data->rent_money:''}}</div> 
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Insurance')}}:</b></div>   
                        <div class="col-lg-2 col-12">{{isset($res_data->insurance)?$res_data->insurance:''}}</div>

                       @if(isset($res_data->contract_file) && $res_data->contract_file!='' )                         
                                    <div class="col-lg-2 col-12"><b>{{trans('admin.Contract file')}}:</b></div>   
                                    <div class="col-lg-2 col-12">
                         
                            <a href="{{url('storage')}}/{{$res_data->contract_file}}" download target="_blank">{{trans('admin.Click to download')}}</a>
            
                        </div>    
                         @endif                    
                    </div>


 @if(count($myproperty_attachment)>0)
                    <div class="col-md-12">                    
                            <h4>{{trans('admin.attachment files')}}</h4>
                        </div>

                         <ul style="list-style:decimal;">                                                      
                        @foreach($myproperty_attachment as $property_file_rows)                    
                        <div class="col-lg-2 col-12">    
                               
                                    <li>
                                        <a href="{{url('storage')}}/{{$property_file_rows->files}}" download target="_blank">{{trans('admin.Click to download')}}</a>

                                    </li>
                                
</div>                                              
                                  
                        @endforeach
                        
                        </ul>
 
                        @endif


<?php if($res_data->renter_id!=0){ ?>
    <div class="col-md-12">                    
                            <h4>{{trans('admin.Invoices')}}</h4>
                        </div>  

                    <div class="col-md-12">                    
                            
                        <div class="col-lg-2 col-12"><a href="{{admin_url('auth/invoice')}}?myproperty_id=<?php echo $id; ?>&invoice_type=installment&renter_id=<?php echo $res_data->renter_id; ?>"><b>{{trans('admin.Installment')}}</b></a></div>                     

                        <div class="col-lg-2 col-12"><a href="{{admin_url('auth/invoice')}}?myproperty_id=<?php echo $id; ?>&invoice_type=gass&renter_id=<?php echo $res_data->renter_id; ?>"><b>{{trans('admin.Gass')}}</b></a></div> 


                        <div class="col-lg-2 col-12"><a href="{{admin_url('auth/invoice')}}?myproperty_id=<?php echo $id; ?>&invoice_type=internet&renter_id=<?php echo $res_data->renter_id; ?>"><b>{{trans('admin.Internet')}}</b></a></div> 

                    </div>

<?php } ?>


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
#preview img{
    padding: 15px;
}
        
    </style>