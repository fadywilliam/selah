<div class="row"><div class="col-md-12"><div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{trans('admin.Edit')}}</h3>

        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 5px">
    <a href="{{admin_url()}}/auth/users" class="btn btn-sm btn-default" title="List"><i class="fa fa-list"></i><span class="hidden-xs">&nbsp;{{trans('admin.List')}}</span></a>
</div>
        </div>
    </div>
    <?php
    $readonly='';
if(isset($id)){
//print_r($prop_data);
$readonly='readonly';
}
     ?>
    <!-- /.box-header -->
    <!-- form start -->    
    <form action="{!! action('MypropertyController@edit') !!}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" pjax-container="">    
    <div class="box-body">

                    <div class="fields-group">

                                                            <div class="col-md-12">
      


                                                            <div class="form-group  ">

    <label for="name" class="col-sm-2 asterisk control-label">{{trans('admin.Location')}}</label>

    <div class="col-sm-8">

        
        <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
            
            <input type="text" id="location" name="location" class="form-control name" value="<?php echo isset($prop_data->location)?$prop_data->location:'';?>" autocomplete="off" placeholder="" required="1">

            
        </div>

        
    </div>
</div>




                                                            <div class="form-group  ">

    <label for="name" class="col-sm-2 asterisk control-label">{{trans('admin.Myproperty name')}}</label>

    <div class="col-sm-8">

        
        <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
            
            <input type="text" id="name" name="name" class="form-control name" value="<?php echo isset($prop_data->name)?$prop_data->name:'';?>" autocomplete="off" placeholder="" required="1">

            
        </div>

        
    </div>
</div>
                                                      <div class="form-group  ">

    <label for="name" class="col-sm-2 asterisk control-label">{{trans('admin.Myproperty name arabic')}}</label>

    <div class="col-sm-8">

        
        <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
            
            <input type="text" id="name_ar" name="name_ar" class="form-control name" value="<?php echo isset($prop_data->name_ar)?$prop_data->name_ar:'';?>" autocomplete="off" placeholder="" required="1">

            
        </div>

        
    </div>
</div>



                                                            <div class="form-group  ">

    <label for="name" class="col-sm-2 asterisk control-label">{{trans('admin.Myproperty type')}}</label>

    <div class="col-sm-8">

        
        <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                <?php
                    $building_selected='';
                    $land_selected='';
                    $flat_selected='';
                    $villa_selected='';
                    $compound_selected='';
                    $tower_selected='';
                    $office_selected='';
                    $shop_selected='';
                    if($prop_data->type=='building'){
                        $building_selected='selected';
                    }
                    if($prop_data->type=='land'){
                        $land_selected='selected';
                    }
                    if($prop_data->type=='flat'){
                        $flat_selected='selected';
                    }
                    if($prop_data->type=='villa'){
                        $villa_selected='selected';
                    }
                    if($prop_data->type=='compound'){
                        $compound_selected='selected';
                    }
                    if($prop_data->type=='tower'){
                        $tower_selected='selected';
                    }
                    if($prop_data->type=='office'){
                        $office_selected='selected';
                    }

                    if($prop_data->type=='shop'){
                        $shop_selected='selected';
                    }                                        
                 ?>
            <select name="type" class="form-control name" required>
                <option></option>
                <option value="building" {{$building_selected}} >{{trans('admin.Building')}}</option>
                <option value="land" {{$land_selected}} >{{trans('admin.Land')}}</option>
                <option value="flat" {{$flat_selected}} >{{trans('admin.Flat')}}</option>
                <option value="villa" {{$villa_selected}} >{{trans('admin.Villa')}}</option>
                <option value="compound" {{$compound_selected}} >{{trans('admin.Compound')}}</option>
                <option value="tower" {{$tower_selected}} >{{trans('admin.Tower')}}</option>
                <option value="office" {{$office_selected}} >{{trans('admin.Office')}}</option>
                <option value="shop" {{$shop_selected}} >{{trans('admin.Shop')}}</option>
            </select>

            
        </div>

        
    </div>
</div>

                                                


                                                            <div class="form-group  ">

    <label for="name" class="col-sm-2 asterisk control-label">{{trans('admin.Building status')}}</label>

    <div class="col-sm-8">

        
        <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
            <?php 
            $sale_selected='';
            $rent_selected='';
            if($prop_data->status=='sale'){
                    $sale_selected='selected';
            }
            if($prop_data->status=='rent'){
                    $rent_selected='selected';
            }

            ?>
            <select name="status" class="form-control name">
                <option></option>
                <option value="sale" {{$sale_selected}} >{{trans('admin.Sale')}}</option>
                <option value="rent" {{$rent_selected}} >{{trans('admin.Rent')}}</option>                
            </select>

            
        </div>

        
    </div>
</div>



                                                            <div class="form-group  ">

    <label for="item_logo" class="col-sm-2  control-label">{{trans('admin.Property image')}}</label>

    <div class="col-sm-8">

        
        <!-- <input type="file" class="item_logo" name="item_logo" id="1583755254671_53"> -->
        
                     
            <img src="{{url('storage')}}/{{$prop_data->image}}" id="image_tag" width="100px" style="padding: 0px 0px 13px 0;" />
        

        


          <div class="input-group file-caption-main">
  <div class="file-caption form-control  kv-fileinput-caption" tabindex="500">
  <span class="file-caption-icon"></span>
  <input class="file-caption-name" onkeydown="return false;" onpaste="return false;" placeholder="Select image">
</div>
<div class="input-group-btn input-group-append">
      
      
      
      <div tabindex="500" class="btn btn-primary btn-file"><i class="glyphicon glyphicon-folder-open"></i>&nbsp;  <span class="hidden-xs">{{trans('admin.Browse')}}</span><input type="file" class="image" name="image"   id="image"></div>
    </div>
</div>

        
    </div>
</div>




                                                      <div class="form-group  ">

    <label for="name" class="col-sm-2 asterisk control-label">{{trans('admin.Description')}}</label>

    <div class="col-sm-8">

        
        <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
            
                        <textarea class="form-control name" cols="5" rows="5" name="description" id="description"><?php echo isset($prop_data->description)?$prop_data->description:'';?></textarea>

            
        </div>

        
    </div>
</div>

      


                                                      <div class="form-group  ">

    <label for="name" class="col-sm-2 asterisk control-label">{{trans('admin.Description arabic')}}</label>

    <div class="col-sm-8">

        
        <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
            
                        <textarea class="form-control name" cols="5" rows="5" name="description_ar" id="description_ar"><?php echo isset($prop_data->description_ar)?$prop_data->description_ar:'';?></textarea>

            
        </div>

        
    </div>
</div>



                                                </div>
        
    </div>
    <!-- /.box-body -->

    <div class="box-footer">

<!--     <input type="hidden" name="_token" value="oFgXxnzjBPfSjLP1T3HXPLRT185Izs85jlhsYjRz"> -->
                            {{ csrf_field() }}


    <div class="col-md-2">
    </div>

    <div class="col-md-8">

                <div class="btn-group pull-right">
            <button type="submit" class="btn btn-primary">{{trans('admin.edit')}}</button>
        </div>

                    
                    
                    
        
            </div>
</div>
    <input type="hidden" name="owner_id" value="<?php echo $prop_data->owner_id; ?>">
    <input type="hidden" name="image_path" value="{{isset($prop_data->image)?$prop_data->image:''}}">
<input type="hidden" name="id" value="{{isset($prop_data->id)?$prop_data->id:''}}">



<!-- /.box-footer -->
    </form>
</div>

</div></div>
<script type="text/javascript">
    $('.main-footer').first().remove();

 function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#image_tag').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#image").change(function(){
        readURL(this);        
    });


     

   
</script>