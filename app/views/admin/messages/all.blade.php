@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
    @if($mode!='')
                <a href="/messages/" class="btn btn-app">
                    <span class="badge bg-red"><?php echo $all_messages_count = Message::totalUnread();?></span>
                    <i class="fa {{$mode=="incoming"?"text-danger":""}} fa-envelope"></i> Inbox
                </a>
               
                <a class="btn btn-app" href="/messages/sent">
                    <i class="fa {{$mode=="outgoing"?"text-danger":""}} fa-arrow-circle-o-right"></i> Sent
                </a>
    
                <a class="btn btn-app" href="/messages/new">
                    <i class="fa {{$mode=="new"?"text-danger":""}} fa-pencil-square-o"></i> New Message
                </a>
    @endif
@endif


@if($mode=="incoming")
<?php $folders = MessageFolder::get();?>
    @if($folders->count() > 0)
        <div class="row">
            <div class="col-md-12">
                <a href="/messages" class="btn {{$folder_id=='0'?'btn-primary':'btn-default'}}">Inbox
                <?php $all_messages_count = Message::totalUnreadInFolder('0'); 
                 $all_unactioned_count = Message::totalUnactionedInFolder('0'); 
                                if($all_messages_count > 0){?>
                                    <span class="badge bg-red"><?php echo $all_messages_count;?></span>
                                <?php }
                                if($all_unactioned_count > 0){?>
                                    <span class="badge bg-aqua"><?php echo $all_unactioned_count;?></span>
                                <?php }?>
                
                </a>
                @foreach($folders as $f)
                    <a href="/messages/folder/{{$f->message_folder_id}}" class="btn  {{$folder_id==$f->message_folder_id?'btn-primary':'btn-default'}}">{{$f->message_folder_title}}
                      <?php $all_messages_count = Message::totalUnreadInFolder($f->message_folder_id); 
                       $all_unactioned_count = Message::totalUnactionedInFolder($f->message_folder_id); 
                        if($all_messages_count > 0){?>
                            <span class="badge bg-red"><?php echo $all_messages_count;?></span>
                        <?php }
                        if($all_messages_count > 0){?>
                            <span class="badge bg-aqua"><?php echo $all_unactioned_count;?></span>
                        <?php }?>
                    </a>
                @endforeach
            </div>
        </div>
    <div class="clearfix">&nbsp;</div>
    @endif
@endif



<form class="form-inline" action="/messageActions" method="post" name="actions_form" id="actions_form">
 <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
 <input type="hidden" name="mode" value="{{$mode}}">
 @if($mode=="incoming")
 <input type="hidden" name="folder_id" value="{{$folder_id}}">
 @endif
<div class="box box-danger">
    
        
    <div class="box-body">
         <div class="row">
             
             <div class="col-md-3">
                     <div class="form-group">
                        <select id="bulk_action" name="bulk_action" class="form-control" placeholder="Select Action"  >
                            <option value="">Select An Action</option>
                            <option value="viewUnread">View Unread Messages</option>
                            <option value="">-------------</option>
                            
                            <option value="viewActioned">View Actioned Messages</option>
                            <option value="viewUnactioned">View Unactioned Messages</option>
                            
                            <option value="">-------------</option>
                            <option value="unread">Mark as Unread</option>
                            <option value="read">Mark as Read</option>
                            <option value="">-------------</option>
                            
                            
                            <option value="actioned">Mark as Actioned</option>
                            <option value="unactioned">Mark as Unactioned</option>
                            <option value="">-------------</option>
                            
                            
                            <option value="delete">Delete Selected Messages</option>
                        </select>
                     </div>
             </div>
             
     @if($mode=="incoming")
       
             <div class="col-md-3">
                     <div class="form-group">
                        <select id="move_to" name="move_to" class="form-control"  >
                            <option value="">Move To Folder</option>
                            <option value="0">Inbox</option>
                          @if($folders->count() > 0)
                             @foreach($folders as $f)
                                <option value="{{$f->message_folder_id}}">{{$f->message_folder_title}}</option>
                             @endforeach
                          @endif  
                        </select>
                     </div>
             </div>
      @endif       
            
             
             
            <div class="col-md-6">
              
               

               <div class="form-group">
                   <input type="text" name="search" id="search" class="form-control" value="{{isset($search)?$search:''}}" placeholder="SEARCH MESSAGES" />
               </div>

               <div class="form-group">
                   <select id="action" name="action" class="form-control" placeholder="Action Performed"  >
                            <option value="">All Messages</option>
                            <option {{isset($action)&&($action=='replied')?'selected="selected"':''}} value="replied">Messages replied</option>
                           
                        </select>
               </div>
               
                <a href='javascript:void();' id='search_button' class="btn btn-default" >Search</a>&nbsp;
                   <a href="/messages" class="btn btn-default">Reset</a>
               
            </div>

            </div>
    </div>    
   
</div>


<div class='table-responsive col-md-12'>
    <table class="table table-hover table-bordered pull-left table-striped table-condensed admin-user-table">
    <thead>
        <tr>
            <th>
            <!-- <button id="checkall" class="btn-info">Toggle</button>-->
            <input type="checkbox" id="checkall" class="check" value="" />
            </th>
            <th>Subject</th>
             <th>{{$mode=="incoming"?"Sender":"To"}}</th>
            <th>Date</th>
        
            
            <th>Actions</th>
          
       </tr>
     </thead>
     <tbody>
         
       @if(count($messages)>0)
        @foreach($messages as $c)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$c->message_id}}" id="cid{{$c->message_id}}" />
           </td>
           
            <td  data-title="Message Name">
                @if($c->message_status=='unread')
                    <span class="label label-danger">&nbsp;</span>
                @endif
                
                @if($c->action_taken=='0')
                <span class="label label-info" title="Action Required">&nbsp;</span>
                @endif
                
                
               <?php $last_sub_message = Message::where('parent_id',$c->message_id)->orderBy('message_id','desc')->first();?>
               <a href="/messages/{{$c->message_id}}{{$last_sub_message?'#message-'.$last_sub_message->message_id:''}}" title="Read Message">
                  {{$c->subject}} 
               </a>
           </td>
           
           <td>
               <?php 

                $incoming_messages = array();
                $outgoing_messages = array();

                if($mode == 'incoming'){
                    $incoming_messages_query = Message::where('mode','incoming')->where('parent_id',$c->message_id)->get();
                    if($incoming_messages_query->count()>0){
                        foreach($incoming_messages_query as $i){
                            $emails = explode(',',$i->sender_email);
                            foreach($emails as $e){
                                $incoming_messages[] = trim($e);
                            }
                           
                        }
                        $incoming_messages = array_unique($incoming_messages);
                        
                    }
                    
                    echo count($incoming_messages)>0?implode(', ',$incoming_messages):$c->sender_email;

                }
                else{
                    
                    $outgoing_messages_query = Message::where('mode','outgoing')->where('parent_id',$c->message_id)->get();
                    if($outgoing_messages_query->count()>0){
                        foreach($outgoing_messages_query as $i){
                            $emails = explode(',',$i->recipient_email);
                            foreach($emails as $e){
                                $outgoing_messages[] = trim($e);
                            }
                        }

                        

                        
                        $outgoing_messages = array_unique($outgoing_messages);
                    }
                
                   
                  echo count($outgoing_messages)>0?implode(', ',$outgoing_messages):$c->recipient_email;
                }
                
               ?>
               
              
           </td>
           
          
           <td  data-title="Date">
               @if($mode == 'incoming')
               {{ZnUtilities::format_date($c->last_incoming_on,'3')}}
               @elseif($mode == 'outgoing')
               {{ZnUtilities::format_date($c->last_sent_on,'3')}}
               @endif
           </td>
           
         
          
           
           <td  data-title="Reply">
               <a href="/messages/{{$c->message_id}}#reply" class="text-warning"><i class="fa fa-reply"></i>&nbsp; Reply</a>
           </td>
           
       </tr>
       @endforeach
       @else
       <tr>
           <td colspan="7">No Messages</td>
       </tr>
       @endif
    </tbody>


</table>
</div>


</form>
    
@if($mode!='search')

<div class="row">
    <div class="col-md-12">
        {{$messages->appends(Input::except('page'))->links()}}
    </div>
</div>
@endif


@stop

