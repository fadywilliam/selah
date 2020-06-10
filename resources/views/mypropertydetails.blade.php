
        
                        
        <div class="row"><div class="col-md-12"><div class="row">
    <div class="col-md-12">
        <div class="box box-info">
    <div class="box-header with-border">
        <?php

        $mypropertyname='';
        if(isset($_GET['myproperty_id'])){
           $myproperty_data=DB::table('myproperty')->where(['id'=>$_GET['myproperty_id']])->first();
           $mypropertyname=$myproperty_data->name;
        }
        $ownername='';
        if(isset($_GET['owner_id'])){
           $user_data=DB::table('users')->where(['id'=>$_GET['owner_id']])->first();
           $ownername=$user_data->name;
        }
        ?>
        
                <h3 class="box-title">{{$ownername}} -> {{trans('admin.Myproperty')}} -> {{$mypropertyname}}</h3>


        <div class="box-tools">
<?php if(isset($_GET['myproperty_id'])){ ?>
            <a href="{{admin_url('auth/mypropertydetails/create') }}?myproperty_id=<?php echo $_GET['myproperty_id']; ?>&type=flat" class="btn-group pull-left addnewproperty">+</a>
<?php } ?>
            <div class="btn-group pull-right" style="margin-right: 5px">    
</div>

        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <div class="form-horizontal">

        <div class="box-body">

            <div class="fields-group">                                
                <div class="form-group ">
                     
                    @foreach ($res_data as $row)

                     <div class="col-md-3">                    
                    
                    <div class="col-lg-12 col-12">

    <a href="{{admin_url()}}/auth/mypropertydetails/{{$row->id}}?id={{$row->id}}&myproperty_id=<?php echo $_GET['myproperty_id']; ?>&owner_id=<?php echo $_GET['owner_id']; ?>">
<div class="collectionbox">
                        <!-- <button type="button" class="myowners" >{{$row->name}}</button> -->
                        <?php 
                            if($row->renter_id!=0){
                             ?>
                            <img src="{{url('')}}/storage/images/rented.png" alt="no photo" class="collectionboximg">
                                <?php }else{?>
                            <img src="{{url('')}}/storage/images/notrented.png" class="collectionboximg">
                                <?php } ?>
                            <div class="mypropertyname">{{$row->name}}</div>

                    </div>
                    </a>
                    <div class="delete"><a onclick="return confirm('are you want to delete?');" href="{{ url('deletemoredetails') }}?id=<?php echo $row->id ?>&myproperty_id=<?php echo $_GET['myproperty_id'] ?>&owner_id=<?php echo $_GET['owner_id'] ?>" ><i class="fa fa-trash" aria-hidden="true"></i></a></div>
                    </div>
                     

            </div>
             

                    @endforeach


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
        
        .addnewproperty {
    background-color: #fff;
    color: white;
    text-align: center;
    margin-bottom: 5px;
    height: 25px;
    width: 25px;
    border-radius: 50%;
    border: 2px solid #000000;
    color: #000000;
    font-weight: bold;
    text-align: center;
}
.collectionbox {
    border-radius: 5px;    
    background-color: #fff;    
    margin-bottom: 7%;
    width: 220px;
    height: 180px;
    -webkit-box-shadow: 10px 10px 5px -4px rgba(40,52,117,0.67);
-moz-box-shadow: 10px 10px 5px -4px rgba(40,52,117,0.67);
box-shadow: 10px 10px 5px -4px rgba(40,52,117,0.67);
margin-bottom: 10px;
}
.mypropertyname{
    text-align: center;
    padding-bottom: 5%;
    font-weight:bold;
}
    </style>