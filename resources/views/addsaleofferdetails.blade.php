                       
        <div class="row"><div class="col-md-12"><div class="row">
    <div class="col-md-12">
        <div class="box box-info">
    <div class="box-header with-border">
            
        <h3 class="box-title">{{trans('admin.add sale offer')}}</h3>

        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 5px">    
</div>

        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <div class="form-horizontal">

      <?php if(isset($_GET['myproperty_id'])){ ?>
    <form action="{!! action('SaleoffersController@insert_saleofferdetails') !!}?myproperty_id=<?php echo $_GET['myproperty_id']?>" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" pjax-container="">
    <?php }else{ ?>
<form action="{!! action('SaleoffersController@insert_saleofferdetails') !!}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" pjax-container="">
   <?php  } ?>
{{ csrf_field() }}

        <div class="box-body">

            <div class="fields-group">                                
                <div class="form-group ">
                     
                  
                                        
                        <div class="col-md-12">                    
                        
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Property name')}}:</b></div>   
                        <div class="col-lg-2 col-12"> <input type="text" name="name" value="{{isset($myproperty_data->name)?$myproperty_data->name:''}}" autocomplete="off" class="form-control" required></div>
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Property name arabic')}}:</b></div> 
                        <div class="col-lg-2 col-12"><input type="text" name="name_ar" value="{{isset($myproperty_data->name_ar)?$myproperty_data->name_ar:''}}" autocomplete="off" class="form-control" required>
                        </div>                     

            </div>

                        <div class="col-md-12">       
                                                <div class="col-lg-2 col-12"><b>{{trans('admin.Location')}}</b></div>   
             
                        <div class="col-lg-6 col-12">
                            <input type="text" name="location" value="{{isset($myproperty_data->location)?$myproperty_data->location:''}}" autocomplete="off" class="form-control" required>
                        </div>   
                                          
</div>
   
            
                    
                    <div class="col-md-12">                    
                            <h4>{{trans('admin.Property Specifications')}}</h4>
                        </div>  

                    <div class="col-md-12">                    
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Apartment count')}}:</b></div>   
                        <div class="col-lg-2 col-12">
<input type="text" name="apartment_count" value="{{isset($res_data->apartment_count)?$res_data->apartment_count:''}}" class="form-control" required autocomplete="off"></div>
                       <!--  <div class="col-lg-2 col-12"><b>{{trans('admin.Bathroom count')}}:</b></div>   
                        <div class="col-lg-2 col-12"><input type="checkbox" name="bathroom_count"></div>
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Hall count')}}:</b></div> 
                        <div class="col-lg-2 col-12"><input type="checkbox" name="hall_count"></div> -->
                     <div class="col-lg-2 col-12"><b>{{trans('admin.Building area')}}:</b></div>   
                     <div class="col-lg-2 col-12">
 <input type="textbox" name="area_partment" 
 value="{{isset($myproperty_data->building_area)?$myproperty_data->building_area:''}}" 
  class="form-control" autocomplete="off" required></div>
                      
                    </div>

                    <div class="col-md-12">
                         <div class="col-lg-2 col-12"><b>{{trans('admin.Property age')}}:</b></div>   
                        <div class="col-lg-2 col-12">
<input type="textbox" name="property_age"
 value="{{isset($res_data->property_age)?$res_data->property_age:''}}" class="form-control" autocomplete="off" required></div>

                        <div class="col-lg-2 col-12"><b>{{trans('admin.Price')}}:</b></div>   
                        <div class="col-lg-2 col-12">
<input type="textbox" name="price" value="{{isset($myproperty_data->price)?$myproperty_data->price:''}}" class="form-control" autocomplete="off" required> </div>
                    
                    </div>
                    <?php
                            if(isset($res_data->car_entrance) && $res_data->car_entrance==1){
                                $checked_car_entrance='checked';
                            }else{
                                $checked_car_entrance='';
                            }
                            if(isset($res_data->elevator) && $res_data->elevator==1){
                                $checked_elevator='checked';
                            }else{
                                $checked_elevator='';
                            }
                         ?>
                    
                    <div class="col-md-12">
                        <div class="col-lg-2 col-12"></div>   
                        <div class="col-lg-2 col-12"><input type="checkbox" {{$checked_car_entrance}} name="car_entrance"><b> {{trans('admin.Car entrance')}}</b></div>    
                                            
                        <div class="col-lg-2 col-12"></div>   
                        <div class="col-lg-2 col-12"><input type="checkbox" {{$checked_elevator}} name="elevator"><b> {{trans('admin.Elevator')}}</b></div>     
                    </div> 



                        <div class="col-md-12">
                             <div class="col-lg-2 col-12"><b>{{trans('admin.Properity cover')}}:</b></div>   
                            <!-- <div class="col-lg-2 col-12"><input type="file" name="images" multiple></div> -->
<div class="col-lg-6 col-12">
<div id="preview">    
    @if(isset($myproperty_data->image) && $myproperty_data->image!='')
    <img height="100px;" width="100px;" src="{{url('storage')}}/{{$myproperty_data->image}}">
    @endif
</div>
          <div class="input-group file-caption-main">
  <div class="file-caption form-control  kv-fileinput-caption" tabindex="500">
  <span class="file-caption-icon"></span>
  <input class="file-caption-name" onkeydown="return false;"  onpaste="return false;" placeholder="Select image">
</div>
<div class="input-group-btn input-group-append">
      
      
      
      <div tabindex="500" class="btn btn-primary btn-file"><i class="glyphicon glyphicon-folder-open"></i>&nbsp;  <span class="hidden-xs">{{trans('admin.Browse')}}</span><input type="file" class="image" name="image_cover" id="file-input"></div>
    </div>
</div>
</div>


                         
                    </div>

                        <div class="col-md-12">
</div>

                        <div class="col-md-12">
                             <div class="col-lg-2 col-12"><b>{{trans('admin.Properity images')}}:</b></div>   
                            <!-- <div class="col-lg-2 col-12"><input type="file" name="images" multiple></div> -->
<div class="col-lg-6 col-12">
<div id="previewimages">
    
      @if(isset($property_images) && count($property_images)>0)
                            @foreach($property_images as $property_rows)                    
                            <img height="100px;" width="100px;" src="{{url('storage')}}/{{$property_rows->image}}" >
                        @endforeach
                        @endif


</div>
          <div class="input-group file-caption-main">
  <div class="file-caption form-control  kv-fileinput-caption" tabindex="500">
  <span class="file-caption-icon"></span>
  <input class="file-caption-name" onkeydown="return false;" onpaste="return false;" placeholder="Select image">
</div>
<div class="input-group-btn input-group-append">
      
      
      
      <div tabindex="500" class="btn btn-primary btn-file"><i class="glyphicon glyphicon-folder-open"></i>&nbsp;  <span class="hidden-xs">{{trans('admin.Browse')}}</span><input type="file" class="image" name="images[]" id="file-inputimages" multiple></div>
    </div>
</div>
</div></div>
                  <div class="col-md-12">
</div>
                        <div class="col-md-12">


<div class="col-lg-2 col-12"><b>{{trans('admin.attachment files')}}:</b></div>   
                        <div class="col-lg-6 col-12">
<div id="attachmentfiles">



 @if(isset($myproperty_attachment) && count($myproperty_attachment)>0)

                         <ul style="list-style:decimal;">                                                      
                        @foreach($myproperty_attachment as $property_file_rows)                    
                               
                                    <li>
                                        <a href="{{url('storage')}}/{{$property_file_rows->files}}" download target="_blank">{{trans('admin.Click to download')}}</a>

                                    </li>
                                
                                  
                        @endforeach
                        
                        </ul>
 
                        @endif



</div>
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



                        </div></div>

                         
                   



                    <div class="col-md-12">                    
                            
                        </div>  
                
                    
                          <div class="col-md-12">                    
</div>
                          <div class="col-md-12">                    
                        
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Description')}}:</b></div>   
                        <div class="col-lg-6 col-12"><textarea class="form-control" cols="5" rows="5" name="description">{{isset($myproperty_data->description)?$myproperty_data->description:''}}</textarea></div>
                        
                        </div>  
                                                <div class="col-md-12">                    
</div>
                        <div class="col-md-12">                    
                        
                        <div class="col-lg-2 col-12"><b>{{trans('admin.Description arabic')}}:</b></div>   
                        <div class="col-lg-6 col-12"><textarea class="form-control" cols="5" rows="5" name="description_ar">{{isset($myproperty_data->description_ar)?$myproperty_data->description_ar:''}}</textarea></div>
                        
                        </div>              
                        <div class="col-md-12">                    
                        <div class="col-lg-2 col-12">
                        </div>    
                        <div class="col-lg-2 col-12">
                        @if(isset($id) && $id!='')
                          <input type="submit" name="update" class="btn btn-sm btn-primary" value="{{trans('admin.Update')}}">
                          @else
                          <input type="submit" name="save" class="btn btn-sm btn-primary" value="{{trans('admin.save')}}">
                        @endif
                        </div>       
                        </div>       

            </div>
        </div>
        <!-- /.box-body -->
    </div>
</div>
 @if(isset($id) && $id!='')

<input type="hidden" name="myproperty_id" value="{{isset($myproperty_id)?$myproperty_id:''}}">
<input type="hidden" name="id" value="{{isset($id)?$id:''}}">
<input type="hidden" name="image_path" value="{{isset($myproperty_data->image)?$myproperty_data->image:''}}">

@endif
</form>
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
            padding: 5px;
        }
        #previewimages img{
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
    </style>
    <script type="text/javascript">
    $('.main-footer').first().remove();

 function previewImages() {

  var preview = document.querySelector('#preview');
  $('#preview').html('');
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
 function previewImagesimages() {
 
  var previewimages = document.querySelector('#previewimages');
  
  if (this.files) {
    [].forEach.call(this.files, readAndPreviewimages);
  }

  function readAndPreviewimages(file) {

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
      previewimages.appendChild(image);
    });
    $('#previewimages img').css('padding','40px 0 0px 0');
    reader.readAsDataURL(file);
    
  }

}

document.querySelector('#file-input').addEventListener("change", previewImages);
document.querySelector('#file-inputimages').addEventListener("change", previewImagesimages);

</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
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
  </script>