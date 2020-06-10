
        
                        
        <div class="row"><div class="col-md-12"><div class="row">
    <div class="col-md-12">
        <div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{trans('admin.Details')}}</h3>

        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 5px">    
</div><div class="btn-group pull-right" style="margin-right: 5px">
    <a href="{{admin_url()}}/auth/owners/{{$id}}/edit" class="btn btn-sm btn-primary" title="Edit">
        <i class="fa fa-edit"></i><span class="hidden-xs">{{trans('admin.Edit')}}</span>
    </a>
</div><div class="btn-group pull-right" style="margin-right: 5px">
    <a href="{{admin_url()}}/auth/owners" class="btn btn-sm btn-default" title="List">
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
                    <div class="col-lg-6 col-12"><strong><i class="fa fa-phone" aria-hidden="true"></i>
 {{trans('admin.Phone')}}:</strong></div>
                    <div class="col-lg-6 col-12">{{$phone}}</div>                    
            </div>

 <div class="col-md-12">                    
                    <div class="col-lg-6 col-12"><strong><i class="fa fa-check-circle-o"></i> {{trans('admin.National id')}}:</strong></div>
                    <div class="col-lg-6 col-12">{{$national_id}}</div>
            </div>
            


            <div class="col-md-12">                    
                    <div class="col-lg-6 col-12"><strong><i class="fa fa-phone" aria-hidden="true"></i>{{trans('admin.Units count')}}:</strong></div>
                    <div class="col-lg-6 col-12"><span class="label label-success">{{$unit_count}}</span></div>
            </div>



<div class="col-md-12">                    
                    
                    <div class="col-lg-6 col-12"><a href="{{admin_url()}}/auth/myproperty?owner_id={{$id}}"><button type="button" class="myowners" >{{trans('admin.My owners')}}</button></a></div>
                     <div class="col-lg-6 col-12"><a href="{{admin_url()}}/auth/myproperty/create?owner_id={{$id}}"><button type="button" class="myowners" >{{trans('admin.Add new property')}}</button></a></div>

            </div>

            

<div class="col-md-12">                    
                    
                    <div class="col-lg-6 col-12"><a href="{{admin_url()}}/auth/saleoffers"><button type="button" class="myowners" >{{trans('admin.Special sale offers')}}</button></a></div>
                     <div class="col-lg-6 col-12"><a href="{{admin_url()}}/auth/users/1"><button type="button" class="myowners" >{{trans('admin.My owners manager')}}</button></a></div>

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

    <style type="text/css">
        .myowners{
  
    margin: 25px 0px;
    height: 90px;
    width: 100%;
    background-color: #fff;
    color: #283475;
    text-align: center;
    font-size: 24px;
    padding: 2%;
    border-radius: 5px;
    border: 3px solid #354B92;
    font-family: monospace;
    -webkit-box-shadow: 10px 10px 5px -4px rgba(40,52,117,0.67);
    -moz-box-shadow: 10px 10px 5px -4px rgba(40,52,117,0.67);
    box-shadow: 10px 10px 5px -4px rgba(40,52,117,0.67);
    font-weight: bold;



        }
    </style>