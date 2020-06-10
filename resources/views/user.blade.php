<div class="row"><div class="col-md-12"><div class="row">
    <div class="col-md-12">
        <div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{trans('admin.Details')}}</h3>

        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 5px">    
</div><div class="btn-group pull-right" style="margin-right: 5px">
    <a href="{{admin_url()}}/auth/users/{{$id}}/edit" class="btn btn-sm btn-primary" title="Edit">
        <i class="fa fa-edit"></i><span class="hidden-xs">{{trans('admin.Edit')}}</span>
    </a>
</div><div class="btn-group pull-right" style="margin-right: 5px">
    <a href="{{admin_url()}}/auth/users" class="btn btn-sm btn-default" title="List">
        <i class="fa fa-list"></i><span class="hidden-xs"> {{trans('admin.List')}}</span>
    </a>
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
                    <div class="col-lg-6 col-12"><strong><i class="fa fa-user"></i> {{trans('admin.Name')}}:</strong></div>
                    <div class="col-lg-6 col-12"><span class="label label-success">{{$name}}</span></div>                    
            </div>

 <div class="col-md-12">
                    
                    <div class="col-lg-6 col-12"><strong><i class="fa fa-envelope" aria-hidden="true"></i>
 {{trans('admin.username')}}:</strong></div>
                    <div class="col-lg-6 col-12">{{$username}}</div>
            </div>


            <div class="col-md-12">
                    
                    <div class="col-lg-6 col-12"><strong><i class="fa fa-envelope" aria-hidden="true"></i>
 {{trans('admin.Job title')}}:</strong></div>
                    <div class="col-lg-6 col-12">{{$job_title}}</div>
            </div>

            
            <div class="col-md-12">
                    
                    <div class="col-lg-6 col-12"><strong><i class="fa fa-envelope" aria-hidden="true"></i>
 {{trans('admin.Mobile')}}:</strong></div>
                    <div class="col-lg-6 col-12">{{$mobile}}</div>
            </div>

                                         
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