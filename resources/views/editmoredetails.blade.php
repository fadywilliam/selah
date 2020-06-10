
        
                        
        <div class="row"><div class="col-md-12"><div class="row">
    <div class="col-md-12">
        <div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{trans('admin.edit property details')}}</h3>

        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 5px">    
</div>

        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <div class="form-horizontal">

    <form action="{!! action('MypropertyController@update_addmoredetails') !!}?id=<?php echo $_GET['id'] ?>&myproperty_id=<?php echo $_GET['myproperty_id']; ?>" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" pjax-container="">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
    
        <div class="box-body">

            <div class="fields-group">                                
                <div class="form-group ">
                     
                        <div class="col-md-12">                    
                            <h4>{{trans('admin.Renter detials')}}</h4>
                        </div>                    
                        <div class="col-md-12">                    
                        <div class="col-lg-2 col-12"><b>{{trans('admin.name')}}:</b></div>   
                        <div class="col-lg-2 col-12">    
                                
                         <input class="typeahead form-control"  type="text" value="{{$rentername}}">                            
                        <input type="hidden" id="renterid" name="renter_id" value="{{$renterid}}">  
                


                        </div>
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Property name')}}:</b></div>   
                        <div class="col-lg-2 col-12"> <input type="text" name="name" value="{{$res_data->name}}" autocomplete="off" class="form-control" required></div>
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Property name arabic')}}:</b></div> 
                        <div class="col-lg-2 col-12"><input type="text" name="name_ar" value="{{$res_data->name_ar}}" autocomplete="off" class="form-control" required>
</div>                     

            </div>

<div class="col-md-12">                    
                            

                        </div>                    
                        <div class="col-md-12">       
                                                <div class="col-lg-2 col-12"><b>{{trans('admin.Location')}}</b></div>   
             
                        <div class="col-lg-6 col-12">
                            <input type="text" name="location" autocomplete="off" value="{{$res_data->location}}" class="form-control" required>
                        </div>   
                                          
</div>

   <!-- <div class="col-md-12">                    
                            <h4>{{trans('admin.Property name')}}</h4>

                        </div>                    
                        <div class="col-md-12">       
                                                <div class="col-lg-2 col-12"></div>   
             
                        <div class="col-lg-6 col-12">
                            <input type="text" name="name" autocomplete="off" class="form-control" required>
                        </div>   
                                          
</div>
 -->
   
            
                    
                    <div class="col-md-12">                    
                            <h4>{{trans('admin.Property Specifications')}}</h4>
                        </div>  
                    <div class="col-md-12">                    

                        <div class="col-lg-2 col-12"><b>{{trans('admin.Room count')}}:</b></div>   
                        <div class="col-lg-2 col-12"><input type="text" class="form-control" name="room_count" value="{{$res_data->room_count}}" autocomplete="off" required></div>
                        
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Area partment')}}:</b></div>   
                        <div class="col-lg-2 col-12"><input type="textbox" name="area_partment" value="{{$res_data->area_partment}}" class="form-control" autocomplete="off" required></div>
                        
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Property age')}}:</b></div>   
                        <div class="col-lg-2 col-12"><input type="textbox" name="property_age" value="{{$res_data->property_age}}" class="form-control" autocomplete="off" required></div>
                    </div>
                        
                    

                    <?php
if($res_data->bathroom_count){
    $bathroom_count_checked="checked";
}else{
    $bathroom_count_checked="";

}
                         
if($res_data->hall_count){
    $hall_count_checked="checked";
}else{
    $hall_count_checked="";

}

if($res_data->kitchen){
    $kitchen_checked="checked";
}else{
    $kitchen_checked="";

}
if($res_data->furniture){
    $furniture_checked="checked";
}else{
    $furniture_checked="";

}
if($res_data->elevator){
    $elevator_checked="checked";
}else{
    $elevator_checked="";

}

if($res_data->car_entrance){
    $car_entrance_checked="checked";
}else{
    $car_entrance_checked="";

}

if($res_data->adaptations){
    $adaptations_checked="checked";
}else{
    $adaptations_checked="";

}

                         ?>


                    <div class="col-md-12">
                        <div class="col-lg-2 col-12"></div>                           
                        <div class="col-lg-2 col-12">
                            <input type="checkbox" {{$bathroom_count_checked}} name="bathroom_count">&nbsp;<b>{{trans('admin.Bathroom count')}}</b>
                        </div>
                        <div class="col-lg-2 col-12"></div> 
                        <div class="col-lg-2 col-12">
                            <input type="checkbox" {{$hall_count_checked}} name="hall_count">&nbsp;<b>{{trans('admin.Hall count')}}</b></div>
                        <div class="col-lg-2 col-12"></div>   
                        <div class="col-lg-2 col-12">
                            <input type="checkbox" {{$kitchen_checked}} name="kitchen">&nbsp;<b>{{trans('admin.Kitchen')}}</b></div>
                        <div class="col-lg-2 col-12"></div> 
                        <div class="col-lg-2 col-12">
                            <input type="checkbox" {{$furniture_checked}} name="furniture">&nbsp;<b>{{trans('admin.Furniture')}}</b></div>                       
                    <div class="col-lg-2 col-12"></div>   
                        <div class="col-lg-2 col-12">
                            <input type="checkbox" {{$elevator_checked}} name="elevator">&nbsp;<b>{{trans('admin.Elevator')}}</b></div>  
                        <div class="col-lg-2 col-12"></div>   
                        <div class="col-lg-2 col-12">
                            <input type="checkbox" {{$car_entrance_checked}} name="car_entrance">&nbsp;<b>{{trans('admin.Car entrance')}}</b></div>   
                    </div>
                    <div class="col-md-12">
                    
                        
                        <div class="col-lg-2 col-12"></div> 
                        <div class="col-lg-2 col-12">
                            <input type="checkbox" {{$adaptations_checked}} name="adaptations">&nbsp;<b>{{trans('admin.Adaptations')}}</b></div>                        
                    </div>
                    <div class="col-md-12">
                       
                    </div>
                    

                        <div class="col-md-12">
                             <div class="col-lg-2 col-12"><b>{{trans('admin.Properity images')}}:</b></div>   
                            <!-- <div class="col-lg-2 col-12"><input type="file" name="images" multiple></div> -->
<div class="col-lg-6 col-12">
<div id="preview">
    

 @if(count($property_images)>0)
                           
        @foreach($property_images as $property_rows)                    
                <img width="100px;" height="100px;" src="{{url('storage')}}/{{$property_rows->image}}" >  
         @endforeach
@endif



</div>
          <div class="input-group file-caption-main">
  <div class="file-caption form-control  kv-fileinput-caption" tabindex="500">
  <span class="file-caption-icon"></span>
  <input class="file-caption-name" onkeydown="return false;" onpaste="return false;" placeholder="Select image">
</div>
<div class="input-group-btn input-group-append">
      
      
      
      <div tabindex="500" class="btn btn-primary btn-file"><i class="glyphicon glyphicon-folder-open"></i>&nbsp;  <span class="hidden-xs">{{trans('admin.Browse')}}</span><input type="file" class="image" name="images[]" id="file-input" multiple></div>
    </div>
</div>
</div>


                         
                    </div>



                    <div class="col-md-12">                    
                            <h4>{{trans('admin.Contract')}}</h4>
                        </div>  

                    <div class="col-md-12">                    
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Contract start date')}}:</b></div>   
                        <div class="col-lg-2 col-12"><input type="textbox" value="{{$res_data->contract_start_date}}" name="contract_start_date" autocomplete="off" id="contract_start_date" class="form-control" required></div>
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Contract end date')}}:</b></div>   
                        <div class="col-lg-2 col-12"><input type="textbox" value="{{$res_data->contract_end_date}}" name="contract_end_date" autocomplete="off" id="contract_end_date" class="form-control" required></div>
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Contract period')}}:</b></div>   
                        <div class="col-lg-2 col-12"><input type="textbox" name="contract_period" value="{{$res_data->contract_period}}" autocomplete="off" class="form-control" required></div>
                    </div>
 

                    <div class="col-md-12">                    
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Paid system')}}:</b></div>   
                        <div class="col-lg-2 col-12">
                            <?php
                                if($res_data->paid_system=='Monthly'){
                                    $Monthly_selected="selected";
                                }else{
                                    $Monthly_selected="";
                                }
                                 if($res_data->paid_system=='Yearly'){
                                    $Yearly_selected="selected";
                                }else{
                                    $Yearly_selected="";
                                }
                                
                             ?>
                            <select class="form-control" name="paid_system" required><option>Choose</option>
                               <option value="Monthly" {{$Monthly_selected}} >{{trans('admin.Monthly')}}</option>
                                <option value="Yearly" {{$Yearly_selected}} >{{trans('admin.Yearly')}}</option>
                            </select>
                            </div>
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Rent money')}}:</b></div>   
                        <div class="col-lg-2 col-12"><input type="textbox" name="rent_money" value="{{$res_data->rent_money}}" autocomplete="off" class="form-control" required></div>
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Insurance')}}:</b></div>   
                        <div class="col-lg-2 col-12"><input type="textbox" autocomplete="off" name="insurance" value="{{$res_data->insurance}}" class="form-control" required></div>
                    </div>



                     <div class="col-md-12">  
                                        <div class="col-lg-2 col-12"></div>       
                                        <div class="col-lg-2 col-12">                
                          @if(isset($res_data->contract_file) && $res_data->contract_file!='' )                         
                         
                            <a href="{{url('storage')}}/{{$res_data->contract_file}}" download target="_blank">{{trans('admin.Click to download')}}</a>
            
                         @endif 
                    </div>
                                        <div class="col-lg-2 col-12"></div>       
                                        <div class="col-lg-6 col-12"></div>  
                                      
     

                </div>
                    <div class="col-md-12">                                            
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Contract file')}}:</b></div>   
                        <div class="col-lg-2 col-12">

                            <!-- <input type="file" class="form-control" name="file" multiple > -->


          <div class="input-group file-caption-main">
  <div class="file-caption form-control  kv-fileinput-caption" tabindex="500">
  <span class="file-caption-icon"></span>
  <input class="file-caption-name" onkeydown="return false;" onpaste="return false;" placeholder="Select image">
</div>
<div class="input-group-btn input-group-append">
      
      
      
      <div tabindex="500" class="btn btn-primary btn-file"><i class="glyphicon glyphicon-folder-open"></i>&nbsp;  <span class="hidden-xs">{{trans('admin.Browse')}}</span><input type="file" class="image" name="contract_file"  id="contract_file"></div>
    </div>
</div>

                        </div>
                        <div class="col-lg-2 col-12"><b>{{trans('admin.attachment files')}}:</b></div>   
                        <div class="col-lg-2 col-12">

                            <!-- <input type="file" class="form-control" name="attachmentfiles[]" multiple > -->


          <div class="input-group file-caption-main">
  <div class="file-caption form-control  kv-fileinput-caption" tabindex="500">
  <span class="file-caption-icon"></span>
  <input class="file-caption-name" onkeydown="return false;" onpaste="return false;" placeholder="Select image">
</div>
<div class="input-group-btn input-group-append">
      
      
      
      <div tabindex="500" class="btn btn-primary btn-file"><i class="glyphicon glyphicon-folder-open"></i>&nbsp;  <span class="hidden-xs">{{trans('admin.Browse')}}</span><input type="file" class="attachmentfiles" name="files[]" multiple   id="attachmentfiles" ></div>
    </div>
</div>



                        </div>
                                                                        <div class="col-lg-2 col-12">

</div>
                                                                        <div class="col-lg-2 col-12">

 <input type="submit" name="save" class="btn btn-sm btn-primary" value="{{trans('admin.edit')}}">
                    </div>                                                
                    </div>
                    <div class="col-md-12">                                            
                        
                        
                    </div>
                    
        </div>
        <!-- /.box-body -->
    </div>
</div>
<input type="hidden" value="{{isset($mypropertyid)?$mypropertyid:$_GET[myproperty_id]}}" name="myproperty_id">
<input type="hidden" name="rental_type" value="{{isset($_GET['type'])?$_GET['type']:'flat'}}">
{{ csrf_field() }}
</form>
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
            padding: 5px;
        }
        select{
         margin: 2% 0px 2% 0px;
        }
        input{
            margin-top: 1%;
        }
        div#preview{
            padding: 2px;
        }
        #ui-datepicker-div{
            z-index: 9999!important;
        }
        select{
            height: auto!important;
        }
         ul.typeahead.dropdown-menu{
            right: 4px!important;
            left: 0px!important;
            width: 100px!important;
            padding: 0px!important;
            margin: 0px!important;
        }
    </style>
    <script type="text/javascript">
    $('.main-footer').first().remove();

 function previewImages() {

  var preview = document.querySelector('#preview');
  
  if (this.files) {
    [].forEach.call(this.files, readAndPreview);
  }

  function readAndPreview(file) {

    // Make sure `file.name` matches our extensions criteria
    if (!/\.(jpe?g|png|gif)$/i.test(file.name)) {
      return alert(file.name + " is not an image");
    } // else...
    
    var reader = new FileReader();
    
    reader.addEventListener("load", function() {
      var image = new Image();
      image.height = 100;
      image.title  = file.name;
      image.src    = this.result;
      preview.appendChild(image);
    });
    $('preview').css('padding','40px 0 0px 0');
    reader.readAsDataURL(file);
    
  }

}

document.querySelector('#file-input').addEventListener("change", previewImages);
</script>
<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
  
  <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
  <!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
  <!-- <script src="{{admin_asset("vendor/laravel-admin/AdminLTE/dist/js/typeahead.js")}}"></script>  -->

  <!-- <script>
    $(document).ready(function() {

      $().ready(function() {
        $( "#contract_start_date" ).datepicker({

          dateFormat: 'yy-mm-dd',
            orientation: "right bottom"



        });
                $( "#contract_end_date" ).datepicker({

          dateFormat: 'yy-mm-dd',
  orientation: "right bottom"


        });
});
      });
  </script> -->
  <script>
     var path = "{{ route('autocomplete') }}";
    $('input.typeahead').typeahead({
        source: function (query, process) {
                $.ajax({
                    url: path,
                    type: 'GET',
                    dataType: 'JSON',
                    data: 'query=' + query,
                    success: function(data) {
                       
                        return process(data);
                        }
                        
                });
            },
             afterSelect: function(args){
                $('#renterid').val(args.id );
            }
    });

</script>