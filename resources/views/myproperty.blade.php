
        
                        
        <div class="row"><div class="col-md-12"><div class="row">
    <div class="col-md-12">
        <div class="box box-info">
    <div class="box-header with-border">
<?php 
        $ownername='';
        if(isset($_GET['owner_id'])){
           $user_data=DB::table('users')->where(['id'=>$_GET['owner_id']])->first();
           $ownername=$user_data->name;
        }

?>
        <h3 class="box-title">{{$ownername}} -> {{trans('admin.Myproperty')}}</h3>

        <div class="box-tools">
            <a href="{{admin_url('auth/myproperty/create')}}?owner_id=<?php echo $_GET['owner_id'] ?>" class="btn-group pull-left addnewproperty">+</a>
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
                                          <div class="col-md-12">                    

                    @foreach ($res_data as $row)                                    
                    <div class="col-lg-3 col-12">
                       @if($row->type=='building')
                       <div class="collectionbox">
                        <a href="{{admin_url()}}/auth/myproperty/{{$row->id}}?myproperty_id={{$row->id}}&owner_id=<?php echo $_GET['owner_id'] ?>">
                            <!-- <button type="button" class="myowners" >{{$row->name}}</button>                              -->
                            <?php 
                                if($row->image==''){
                            ?>
                            <img src="{{url('')}}/storage/images/building_avatar.jpg" class="collectionboximg" class="collectionboximg" >
                                <?php }else{ ?>
                            <img src="{{url('')}}/storage/{{$row->image}}" class="collectionboximg" >
                                <?php } ?>
                            <div class="mypropertyname">{{$row->name}}</div>



                        </a>
                <div class="delete"><a onclick="return confirm('are you want to delete?');" href="{{ url('delete') }}?myproperty_id=<?php echo $row->id ?>&owner_id=<?php echo $_GET['owner_id'] ?>" ><i class="fa fa-trash" aria-hidden="true"></i></a></div>
                 <div class="edit"><a href="{{ admin_url('auth/myproperty/'.$row->id.'/edit/') }}?myproperty_id=<?php echo $row->id ?>" ><i class="fa fa-edit" aria-hidden="true"></i></a></div>
                        </div>

                   @else
                                          <div class="collectionbox">

                        <a href="{{admin_url()}}/auth/mypropertydetails/{{$row->id}}?type={{$row->type}}?&myproperty_id=<?php echo $row->id ?>&owner_id=<?php echo $_GET['owner_id'] ?>">
                            <!-- <button type="button" class="myowners" >{{$row->name}}</button> -->
                            <?php 
                            if($row->image==''){
                             ?>
                            <img src="{{url('')}}/storage/images/building_avatar.jpg" alt="no photo" class="collectionboximg">
                            <?php }else{?>
                            <img src="{{url('')}}/storage/{{$row->image}}" class="collectionboximg">


                            <?php } ?>
                            <div class="mypropertyname">{{$row->name}}</div>

                        </a>
                                        <div class="delete"><a onclick="return confirm('are you want to delete?');" href="{{ url('delete') }}?myproperty_id=<?php echo $row->id ?>&owner_id=<?php echo $_GET['owner_id'] ?>"><i class="fa fa-trash" aria-hidden="true"></i></a></div>
                                         <div class="edit"><a href="{{ admin_url('auth/myproperty/'.$row->id.'/edit/') }}?myproperty_id=<?php echo $row->id ?>" ><i class="fa fa-edit" aria-hidden="true"></i></a></div>

                    </div>
                   @endif
                    </div>
                     


                    @endforeach

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
.mypropertyname{
    text-align: center;
    padding-bottom: 5%;
    width: 200px;
    font-weight: bold;
}
.collectionbox {
    border-radius: 5px;    
    /*background-color: #fff;    */
box-shadow: 10px 10px 5px -4px rgba(40,52,117,0.67);
    margin-bottom: 20px;
    width: 210px;

    
}
.collectionboximg{
   width: 200px;
   height: 200px;
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
.delete{
    float: right;

}
.edit{
    float: left;
}
</style>