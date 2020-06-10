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
//print_r($user_data);
$readonly='readonly';
}
     ?>
    <!-- /.box-header -->
    <!-- form start -->
    @if(isset($id) && $id!='')
    <form action="{!! action('UserController@update') !!}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" pjax-container="">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
    @else
    <form action="{!! action('UserController@insert') !!}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" pjax-container="">
    @endif
    <div class="box-body">

                    <div class="fields-group">

                                                            <div class="col-md-12">
      


                                                            <div class="form-group  ">

    <label for="name" class="col-sm-2 asterisk control-label">{{trans('admin.Employee name')}}</label>

    <div class="col-sm-8">

        
        <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
            
            <input type="text" id="name" name="name" class="form-control name" value="<?php echo isset($user_data->name)?$user_data->name:'';?>" autocomplete="off" placeholder="Input Item name" required="1">

            
        </div>

        
    </div>
</div>



                                                            <div class="form-group  ">

    <label for="name" class="col-sm-2 asterisk control-label">{{trans('admin.User name')}}</label>

    <div class="col-sm-8">

        
        <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
            
            <input type="text" id="username" name="username" <?php echo $readonly; ?> value="<?php echo isset($user_data->username)?$user_data->username:'';?>" class="form-control name" autocomplete="off" placeholder="Input Item name" required="1">

            
        </div>

        
    </div>
</div>

                                                <div class="form-group  ">

    <label for="name" class="col-sm-2 asterisk control-label">{{trans('admin.password')}}</label>

    <div class="col-sm-8">

        
        <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
            
            <input type="password" id="password" name="password" <?php echo $readonly; ?> value="<?php echo isset($user_data->password)?$user_data->password:'' ?>" class="form-control name" autocomplete="off" placeholder="Input Item name" autocomplete="off" required="1">

            
        </div>

        
    </div>
</div>

                                                      <div class="form-group  ">

    <label for="name" class="col-sm-2 asterisk control-label">{{trans('admin.Job title')}}</label>

    <div class="col-sm-8">

        
        <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
            
            <input type="text" id="name" name="job_title" value="<?php echo isset($user_data->job_title)?$user_data->job_title:'';?>" class="form-control name" autocomplete="off" placeholder="Input Item name" required="1">

            
        </div>

        
    </div>
</div>




                                                      <div class="form-group  ">

    <label for="name" class="col-sm-2 asterisk control-label">{{trans('admin.Mobile')}}</label>

    <div class="col-sm-8">

        
        <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
            
            <input type="text" id="name" name="mobile" value="<?php echo isset($user_data->mobile)?$user_data->mobile:'';?>" class="form-control name" autocomplete="off" placeholder="Input Item name" required="1">

            
        </div>

        
    </div>
</div>


                                                      <div class="form-group  ">

    <label for="name" class="col-sm-2 asterisk control-label">{{trans('admin.Permissions')}}</label>

    <div class="col-sm-8">

        
        <div class="input-group">
<?php //print_r($employee_permission_data); 
$addadv_checked='';
$payment_checked='';
$fullpermission_checked='';
$addproperty_checked='';
$adddocument_checked='';
    if(isset($id)){

        foreach ($employee_permission_data as $permission) {
            if($permission->permission=='addadv'){
                $addadv_checked='checked';
            } 
            if($permission->permission=='payment'){
                $payment_checked='checked';
            }  
            if($permission->permission=='fullpermission'){
                $fullpermission_checked='checked';
            } 
            if($permission->permission=='addproperty'){
                $addproperty_checked='checked';
            }
            if($permission->permission=='adddocument'){
                $adddocument_checked='checked';
            }

        }
}
?>
            <div>
            <input type="checkbox" id="permissions" name="permissions[]" <?php echo $addadv_checked; ?> value="addadv" >{{trans('admin.addadv')}}
            <input type="checkbox" id="permissions" name="permissions[]" <?php echo $payment_checked;  ?> value="payment" >{{trans('admin.payment')}}
            <input type="checkbox" id="permissions" name="permissions[]" <?php echo $fullpermission_checked;  ?> value="fullpermission" >{{trans('admin.fullpermission')}}
</div>
      <div>
            
            <input type="checkbox" id="permissions" name="permissions[]" <?php echo $addproperty_checked;  ?> value="addproperty" >{{trans('admin.addproperty')}}
            <input type="checkbox" id="permissions" name="permissions[]" <?php echo $adddocument_checked;  ?> value="adddocument" >{{trans('admin.adddocument')}}
           </div> 
            
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
            <button type="submit" class="btn btn-primary">{{trans('admin.Submit')}}</button>
        </div>

                    
                    
                    
        
            </div>
</div>

    
<!-- /.box-footer -->
    </form>
</div>

</div></div>
<script type="text/javascript">
    $('.main-footer').first().remove();
</script>