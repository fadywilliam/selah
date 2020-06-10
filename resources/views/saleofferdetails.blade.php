
        
                        
        <div class="row"><div class="col-md-12"><div class="row">
    <div class="col-md-12">
        <div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{trans('admin.Details')}}</h3>

        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 5px">    
</div>
<a href="{{admin_url('auth/saleoffers/')}}/{{$res_data->id}}/edit?id={{$res_data->id}}&myproperty_id=<?php echo $myproperty_data->id; ?>" class="btn btn-sm btn-primary" title="Edit">
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
                                        
                        <div class="col-md-12">                    
                        
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Property name')}}:</b></div>   
                        <div class="col-lg-2 col-12">{{$myproperty_data->name}}</div>
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Property name arabic')}}:</b></div> 
                        <div class="col-lg-2 col-12">{{$myproperty_data->name_ar}}
                        </div>                     

            </div>

                        <div class="col-md-12">       
                                                <div class="col-lg-2 col-12"><b>{{trans('admin.Location')}}</b></div>   
             
                        <div class="col-lg-6 col-12">
                            {{$res_data->location}}
                        </div>   
                                          
</div>
   
            

                    
                    <div class="col-md-12">                    
                            <h4>{{trans('admin.Property Specifications')}}</h4>
                        </div>  

                    <div class="col-md-12">                    
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Apartment count')}}:</b></div>   
                        <div class="col-lg-2 col-12">{{$res_data->apartment_count}}</div>
                       <!--  <div class="col-lg-2 col-12"><b>{{trans('admin.Bathroom count')}}:</b></div>   
                        <div class="col-lg-2 col-12"><input type="checkbox" name="bathroom_count"></div>
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Hall count')}}:</b></div> 
                        <div class="col-lg-2 col-12"><input type="checkbox" name="hall_count"></div> -->
                     <div class="col-lg-2 col-12"><b>{{trans('admin.Building area')}}:</b></div>   
                     <div class="col-lg-2 col-12">{{$myproperty_data->building_area}}</div>
                      
                    </div>

                    <div class="col-md-12">
                         <div class="col-lg-2 col-12"><b>{{trans('admin.Property age')}}:</b></div>   
                        <div class="col-lg-2 col-12">{{$res_data->property_age}}</div>

                        <div class="col-lg-2 col-12"><b>{{trans('admin.Price')}}:</b></div>   
                        <div class="col-lg-2 col-12">{{$myproperty_data->price}}</div>
                    
                    </div>
                    
                    
                    <div class="col-md-12">
                        <div class="col-lg-2 col-12"></div>   
                        <?php
                            if($res_data->car_entrance==1){
                                $checked_car_entrance='checked';
                            }else{
                                $checked_car_entrance='';
                            }
                            if($res_data->elevator==1){
                                $checked_elevator='checked';
                            }else{
                                $checked_elevator='';
                            }
                         ?>
                        <div class="col-lg-2 col-12"><input type="checkbox" {{$checked_car_entrance}}   name="car_entrance"><b> {{trans('admin.Car entrance')}}</b></div>    
                                            
                        <div class="col-lg-2 col-12"></div>   
                        <div class="col-lg-2 col-12"><input type="checkbox" {{$checked_elevator}} name="elevator"><b> {{trans('admin.Elevator')}}</b></div>     
                    </div> 

                    <div class="col-md-12">                    

                        <h4>{{trans('admin.Properity cover')}}:</h4> 
                        <div class="col-lg-2 col-12">
                            <div id="preview">
                            <img width="200px;" height="200px;" src="{{url('storage')}}/{{$myproperty_data->image}}" ></div>   </div>   
                    </div> 


                    @if(count($property_images)>0)
                    <div class="col-md-12">                    
                            <h4>{{trans('admin.Properity images')}}</h4>
                       
                        @foreach($property_images as $property_rows)                    
                            <div class="col-lg-2 col-12">
                                <div id="preview">
                                <img width="200px;" height="200px;" src="{{url('storage')}}/{{$property_rows->image}}" ></div> </div>  
                        @endforeach
                         </div>  
                        @endif


                                

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

<div class="col-md-12">                    

                        <h4>{{trans('admin.Description')}}:</h4> 
                        <div class="col-lg-2 col-12">{{$myproperty_data->description}}</div>   

                    </div> 

<div class="col-md-12">                    

                        <h4>{{trans('admin.Description arabic')}}:</h4> 
                        <div class="col-lg-2 col-12">{{$myproperty_data->description_ar}}</div>   

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
        #preview img{
            padding: 15px;
        }
        
    </style>