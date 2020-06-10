<div class="row"><div class="col-md-12"><div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{trans('admin.Create')}}</h3>

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
    <form action="{!! action('MypropertyController@add') !!}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" pjax-container="">    
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
            
            <select name="type" class="form-control name" required>
                <option value="0"></option>
                <option value="building">{{trans('admin.Building')}}</option>
                <option value="land">{{trans('admin.Land')}}</option>
                <option value="flat">{{trans('admin.Flat')}}</option>
                <option value="villa">{{trans('admin.Villa')}}</option>
                <option value="compound">{{trans('admin.Compound')}}</option>
                <option value="tower">{{trans('admin.Tower')}}</option>
                <option value="office">{{trans('admin.Office')}}</option>
                <option value="shop">{{trans('admin.Shop')}}</option>
            </select>

            
        </div>

        
    </div>
</div>

                                                


                                                            <div class="form-group  ">

    <label for="name" class="col-sm-2 asterisk control-label">{{trans('admin.Building status')}}</label>

    <div class="col-sm-8">

        
        <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
            
            <select name="status" class="form-control name">
                <option value="0"></option>
                <option value="sale">{{trans('admin.Sale')}}</option>
                <option value="rent">{{trans('admin.Rent')}}</option>                
            </select>

            
        </div>

        
    </div>
</div>



                                                            <div class="form-group  ">

    <label for="item_logo" class="col-sm-2  control-label">{{trans('admin.Property image')}}</label>

    <div class="col-sm-8">

        
        <!-- <input type="file" class="item_logo" name="item_logo" id="1583755254671_53"> -->
        
        <img src="" id="image_tag" width="200px" style="padding: 0px 0px 13px 0;" />


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
            <button type="submit" class="btn btn-primary">{{trans('admin.save')}}</button>
        </div>

                    
                    
                    
        
            </div>
</div>

    <input type="hidden" name="owner_id" value="<?php echo $_GET['owner_id']; ?>">
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