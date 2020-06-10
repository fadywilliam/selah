<div class="row"><div class="col-md-12"><div class="row">
    <div class="col-md-12">
        <div class="box box-info">
    <div class="box-header with-border">
       
        
                <h3 class="box-title">{{trans('admin.Chat')}}</h3>


        <div class="box-tools">

            <div class="btn-group pull-right" style="margin-right: 5px">    
</div>

        </div>
    </div>
    
<div class="row">
    <!--<div class="box-header with-border">-->
    <!--    <h3 class="box-title">{{trans('admin.Chat')}}</h3>-->
    <!--    </div>-->
    <div class="col-sm-12">
        <div class="m-portlet ">
        
        
        <div class="m-portlet__body ">
            <div class="row">
        <div class="col-md-3">
            <div class="emailBody">
                <div class="emailInfo">

                    <div class="infoLine">
                        <div class="infoInput" id="chat-rooms">
                            <?php $classowner='';$owners_room=''; ?>
                            @foreach($owner_data as $rows)
                            <?php 
                if(isset($_GET['sender_id']) && $rows->id==$_GET['sender_id']){
                               $classowner='activeuser';
                               $owners_room='owners_room active_room';
                               
                    }else{
                               $classowner='';
                               $owners_room='owners_room';

                    }
                                if($rows->image!=''){
                                    $image=url('storage/'.$rows->image);
                                }else{
                                    $image=url('storage/images/customer-avatar.png');
                                }
                             ?>
                            <div class="{{$classowner}}">                            
                                <img src="{{$image}}" class="avatarimage">

<a href="{{admin_url('auth/chat')}}?sender_id={{$rows->id}}" class="{{$owners_room}}">{{$rows->name}}</a>
                            </div>
                            @endforeach
                     </div>

                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div id="chat_body">
                    @if(count($chat_data)>0)
                    @foreach($chat_data as $rowmsg)
                        @if($rowmsg->sender_id==$_GET['sender_id'])
                <div class="received-message-container">
                            <?php
                                $user_data=DB::table('users')->where(['id'=>$rowmsg->sender_id])->first();
                                if($user_data->image!=''){
                                    $usrimage=url('storage/'.$user_data->image);
                                }else{
                                    $usrimage=url('storage/images/customer-avatar.png');
                                }

                             ?>
                    <div class="receiverclss">
                        <p class="msg-single-content">{{$rowmsg->message}}<br><strong>{{$user_data->name}}</strong></p>
                        <img src="{{$usrimage}}" class="avatarimage"></div>
                 </div>                    
                    
                    <br>
                    @else
                <div class="sent-message-container">
                        <div>
                            <img src="{{url('storage/images/support-avatar.png')}}" style="width: 50px;border-radius: 50%;">
                        </div>
                        <p class="msg-single-content">{{$rowmsg->message}}<br><strong>{{trans('admin.Chat')}}</strong></p>
                </div>
                <br>
                @endif
@endforeach




                @endif
                        
</div>
        





        </div>
    </div>

<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-9">
            <form action="{!! action('ChatController@sendmessage') !!}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" pjax-container="">   
            <div class="form-group" style="display:flex;padding: 15px;">
            
                 
                   <?php if(isset($_GET['sender_id'])){ ?>

                    <input class="form-control"  id="new-message-body" name="message" onkeypress="handleKeyPress(event)" required placeholder="الرسا لة">
                   <input type="hidden" name="reciever_id" value="<?php echo $_GET['sender_id']; ?>">
                    <button id="add-new-message">{{trans('admin.sent')}}</button>
                    <?php } ?>
            
            </div>
            </form>
    </div>
</div>

        <p></p>
        </div>

            </div>
    </div>
</div>
<style type="text/css">
    
    #chat-rooms{
        height: 475px;
        overflow:auto;
        display: inline-block !important; 
        padding: 5px !important;
    }
    #chat_body {
    border-radius: 5px;
    padding: 20px;
    height: 475px;
    overflow: auto;
    box-shadow: 1px 3px 8px 5px #3D3C57;
}
.received-message-container{
    text-align: left;
    margin: 5px;
}
.box-header.with-border{
    margin-bottom: 2%;
}
.msg-single-content{
    padding: 13px; 
    border-radius:12px;
    margin: 0px;
    margin-right: 10px;
}
.sent-message-container{
    display: inline-flex;
    margin: 5px;
}
.avatarimage{
    width: 50px;
    height: 50px;
    border-radius: 50%;
}
.receiverclss{
    display: inline-flex;
}
.content-header{
    padding:0px;
}
.activeuser {
    background-color: #283475;
    border-radius: 25px;
}
.owners_room{
    color:#283475;
    font-weight:bold;
}
a.owners_room.active_room{
  color:#fff!important; 
  font-weight:bold;
}
.m-portlet__body {
    padding: 25px;
}
@media only screen and (max-width: 600px) {
    #chat-rooms{
        height: 300px;
        overflow:auto;
    }
}
</style>