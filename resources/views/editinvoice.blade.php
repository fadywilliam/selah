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
    <form action="{!! action('InvoiceController@update_invoice') !!}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" pjax-container="">    
    <div class="box-body">

                    <div class="fields-group">

                                                            <div class="col-md-12">
      

    <div class="form-group  ">

    <label for="name" class="col-sm-2 asterisk control-label">{{trans('admin.Paid status')}}</label>

    <div class="col-sm-8">

        
        <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
            
            <select name="paid_status" class="form-control name" required>
                <option></option>  
                <?php                     
                   $paid_status_selected='';                   
                   $not_paid_status_selected='';                   
                    if($paid_status==1){
                        $paid_status_selected='selected';
                        $not_paid_status_selected='';
                    }
                    else{
                        $paid_status_selected='';   
                        $not_paid_status_selected='selected';                 
                    }                    

                ?>       
                <option value="1" <?php echo $paid_status_selected; ?>>{{trans('admin.Paid')}}</option>
                <option value="0" <?php echo $not_paid_status_selected; ?>>{{trans('admin.Not paid')}}</option>
                
            </select>

            
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

  <input type="hidden" name="id" value="{{$id}}">
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