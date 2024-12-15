@extends('layouts.adminTemplate')

@section('content')







<div class="row">
    
       
    
        <div class="col-md-6">
            <div class="row">
                        <div class="col-md-8">
                            <p class="text-muted"> <strong>{{$message->sender_name}}</strong> &nbsp;{{$message->sender_email}}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="pull-right text-muted">{{ZnUtilities::format_date($message->occurred_on,'3')}}</p>
                        </div>
                      </div>
            <div class="box box-primary">
                <div class="box-header">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="box-title">{{$message->subject}}</h3>
                        </div>
                        
                        <div class="col-md-10" style="padding-left:20px;padding-top: 10">
                               
                                   <p><strong>TO:</strong> {{$message->recipient_email}}</p>
                            
                            @if($message->recipient_cc!='')
                            <p><strong>CC:</strong> {{$message->recipient_cc}}</p>
                            @endif
                            @if($message->recipient_bcc!='')
                            <p><strong>BCC:</strong> {{$message->recipient_bcc}}</p>
                            @endif
                            </div>
                        <div class="col-md-2" style="padding-top:10px">
                        @if($message->mode=='outgoing')
                            <a href="/messages/resend/{{$message->message_id}}" class="btn btn-link">Resend</a>
                        @endif
                         <div class="row">
<!--                            <div class="col-md-10" style="padding-left:20px;padding-top: 10">
                               &nbsp;
                            </div>-->
                             <div class="col-md-2" style="padding-top:10px">
                                 <a href="javascript:void(0);" onclick="show_forward_field('forward-message-{{$message->message_id}}');" class="btn btn-link">Forward</a>
                            </div>
                        </div>
                        <div class="row" id="forward-message-{{$message->message_id}}" style="display: none">
                            <form name="forward_email" action="/messages/forward/{{$message->message_id}}" method="post" >
                            <div class="col-md-7" style="padding-left:20px;padding-top: 10">
                               &nbsp;
                            </div>
                             <div class="col-md-3" style="padding-top:10px">
                                 <div class="form-group">
                                     <input type='email' name="forward_email_to" value="" class="form-control" placeholder="Email Address"/> 
                                 </div>
                             </div>
                             <div class="col-md-1" style="padding-top:10px">
                                 <div class="form-group">
                                     <input type='submit' name="submit" value="Send" class="btn btn-primary"/> 
                                 </div>
                             </div>
                        
                            </form>

                        </div>
                       
                        </div>
                        
                    </div>
                   
                    
                    
                </div><!-- /.box-header -->

                  <div class="box-body">

                     
                      
                        
                    <div class="row">
                        <div class="col-md-12">
                          &nbsp;
                        </div>
                    </div>
                      
                    <div class="row">
                        <div class="col-md-12" style="font-family:Tahoma ">
                            <?php $content = str_replace('?','',$message->content); ?>
                            <iframe onload="resizeIframe(this)" width="100%" src="/messages/showSingle/{{$message->message_id}}/parent" scrolling="yes" frameborder="0"></iframe>
                        </div>
                    </div>
                      @if($message->attachments->count()>0)
                      <hr />
                        <div class="row">
                            <div class="col-md-12">
                                <?php foreach($message->attachments as $a): ?>
                                          
                                    <a href='/messages/downloadAttachment/{{$a->attachment_id}}'>
                                              <i class="fa fa-paperclip"></i>&nbsp;{{$a->attachment_name}}
                                       </a>
                                       <?php endforeach;?>
                                
                            </div>
                        </div>
                      @endif
                </div><!-- /.box-body -->
            </div>
            
               
            <div class="row">
                <div class="col-md-12">
                  &nbsp;
                </div>
            </div>      
            
            
            <div class="row">
                <div class="col-md-12">
                  &nbsp;
                </div>
            </div>      
               
           
           
           
              
          
           
            @if(count($sub_messages)>0)
               @foreach($sub_messages as $m)
               
               <a name="message-{{$m->message_id}}"></a>
               <div class="row">
                                <div class="col-md-8">
                                    <!--<p class="text-muted">{{Auth::user()->id==$m->sender_id? "You" : $m->sender_name}}</p>-->
                                    <p class="text-muted">
                                        <strong>{{Auth::user()->id==$m->sender_id? $m->sender_name : $m->sender_name}}</strong>&nbsp;
                                        {{$m->sender_email}} </p>
                                </div>
                                <div class="col-md-4">
                                    <p class="pull-right text-muted"> {{ZnUtilities::format_date($m->occurred_on,'3')}}</p>
                                </div>
                              </div>
               
               <div class="box box-primary">
                   
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-10" style="padding-left:20px;padding-top: 10">
                               
                                     <p class="text-muted"><strong>TO:</strong> {{$m->recipient_email}}</p>

                                    @if($m->recipient_cc!='')
                                    <p class="text-muted"><strong>CC:</strong> {{$m->recipient_cc}}</p>
                                    @endif
                                    @if($m->recipient_bcc!='')
                                    <p class="text-muted"><strong>BCC:</strong> {{$m->recipient_bcc}}</p>
                                    @endif
                               
                                
                            </div>
                        <div class="col-md-2" style="padding-top:10px">
                            @if($m->mode=='outgoing')
                        
                                <a href="/messages/resend/{{$m->message_id}}" class="btn btn-link">Resend</a>
                             @endif   
                    
                        <div class="row">
<!--                            <div class="col-md-10" style="padding-left:20px;padding-top: 10">
                               &nbsp;
                            </div>-->
                             <div class="col-md-2" style="padding-top:10px">
                                 <a href="javascript:void(0);" onclick="show_forward_field('forward-message-{{$m->message_id}}');" class="btn btn-link">Forward</a>
                            </div>
                        </div>
                        <div class="row" id="forward-message-{{$m->message_id}}" style="display: none">
                            <form name="forward_email" action="/messages/forward/{{$m->message_id}}" method="post" >
                            <div class="col-md-7" style="padding-left:20px;padding-top: 10">
                               &nbsp;
                            </div>
                             <div class="col-md-3" style="padding-top:10px">
                                 <div class="form-group">
                                     <input type='email' name="forward_email_to" value="" class="form-control" placeholder="Email Address"/> 
                                 </div>
                             </div>
                             <div class="col-md-1" style="padding-top:10px">
                                 <div class="form-group">
                                     <input type='submit' name="submit" value="Send" class="btn btn-primary"/> 
                                 </div>
                             </div>
                        
                            </form>

                        </div>
                          
                    
                   
                                                      </div>
 
                        </div>
                          <div class="container-fluid">
                                 
                            </div>
                    </div>
                   
                  

              
                   
                          <div class="box-body">
                              


                           
                    <div class="row">
                        <div class="col-md-3">
                          &nbsp;
                        </div>
                      </div>
                              
                            <div class="row">
                                <div class="col-md-12" style="font-family:Tahoma ">
                                <?php  $content = str_replace('?','',$m->content); ?>
                                <iframe onload="resizeIframe(this)" width="100%" src="/messages/showSingle/{{$m->message_id}}" scrolling="no" frameborder="0"></iframe>
                                </div>
                            </div>
                               @if($m->attachments->count()>0)
                                <hr />
                                  <div class="row">
                                      <div class="col-md-12">
                                          <?php foreach($m->attachments as $a): ?>
                                          <?php
                                            if (strpos($a->attachment, "https://") !== false) {
                                            
                                                 $api_key = Config::get('mailgun::api_key');
                                            
                                                $download_link = str_replace("https://","https://api:".$api_key."@" , $a->attachment);
                                            }
                                            else
                                            {
                                                $download_link = '/messages/downloadAttachment/'.$a->attachment_id;
                                            }
                                          ?>
                                          
                                          
                                    <a href='{{$download_link}}'>
                                              <i class="fa fa-paperclip"></i>&nbsp;{{$a->attachment_name}}
                                       </a> &nbsp;
                                       <?php endforeach;?>
                                          
                                      </div>
                                  </div>
                                @endif
                        </div><!-- /.box-body -->
                    </div>
               
               @endforeach
            @endif
            
            
            
          

           <a name="reply"></a>
           @if ( $errors->count() > 0 )

        <p>The following errors have occurred:</p>
        <ul>
            @foreach($errors->all() as $m)
            <li><span class="text-danger">{{$m}}</span></li>
            @endforeach
        </ul>

        @endif
           
             <form  role="form" action='/messages' name='message_form' id='message_form' method="post">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                       
                        <input type="hidden" name="parent_id" value="{{Input::old('parent_id',$message->message_id)}}">
                
            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title">Reply</h3>
                </div><!-- /.box-header -->

                  <div class="box-body">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">TO:</span>
                                    <!--<input type="text" placeholder="Email TO" class="form-control" name="email_to" value="{{Input::old('email_to',$message->sender_email)}}" id="email_to" >-->
                                    <input type="text" placeholder="Email TO" class="form-control" name="email_to" value="{{Input::old('email_to')}}" id="email_to" >
                                </div>
                            </div>
                      
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">CC:</span>
                                    <input type="text" placeholder="Email CC" class="form-control" name="email_cc" id="email_cc" value="{{Input::old('email_cc')}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">BCC:</span>
                                    <input type="text" placeholder="Email BCC" class="form-control" name="email_bcc" id="email_bcc"  value="{{Input::old('email_bcc')}}" >
                                </div>
                            </div>
                      
                      <div class="row">
                        <div class="col-md-4">
                            <a class="btn btn-block btn-warning" data-toggle="modal" data-target="#compose-modal"><i class="fa fa-pencil"></i> Use Email Template</a>
                        </div>
                      </div>
                      
                      <div class="row">
                        <div class="col-md-3">
                          &nbsp;
                        </div>
                      </div>
                      
                      <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea class="textarea" id="content" name="content" placeholder="Reply to this message" 
                                style="width: 100%; height: 325px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{Input::old('content')}}</textarea>                                
                            </div>
                        </div>
                    </div>
                      <div id="upload-doc-div" class="row">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <label for="InputStatus">Upload Documents: </label>
                                <noscript><div class="alert alert-danger">Javascript Must be Enabled for File Upload.</div></noscript>
                                <input type="file" class="btn btn-flat btn-upload" id="upload-button" name="file" />
                                <input type="hidden" value="" name="specs_location">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group"> 
                                <div id="progress_bar"></div>
                            </div>
                        </div>   
                    </div>

                    <div class="row">
                        <div class="alert alert-info" id="file_limit" style="display: none">
                           You can upload only <strong>1 file</strong> so if you have more files to attach please add them to a zip file.
                        </div>
                    </div>

                    <div class="row" style="display:block" id="file_holder">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <div id="prev_upload">

                                    <table class="table table-striped table-bordered table-hover table-condensed" id="files_table" style="display:none;">
                                        <thead>
                                            <tr>
                                                <th style="width:15%;">File Type</th>
                                                <th>Filename</th>
                                                <th style="width:20%;">&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>

                                </div>
                            </div>
                        </div> 
                    </div>
                      
                </div><!-- /.box-body -->
                 <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Send</button>
                </div>
                

            </div>
         </form>
            
          

             
             
             
             </div>
   
   

    
    
     <!-- COMPOSE MESSAGE MODAL -->
        <div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-envelope-o"></i> Available Email Templates</h4>
                    </div>
                    <form action="#" method="post">
                        <div class="modal-body">
                           
                              <div id='templates_div' style='display:block'>
                                @if(count($email_templates_favorite)>0)
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>Favorite Templates</h3>  
                                    </div>
                                </div>





                        <div class="row">
                            <?php $i = 0;?>
                         @foreach($email_templates_favorite as $c)
                         <div class="col-md-4">

                                <div class="well well-sm needs_hover" id="f-{{$i}}" style="height:172px;width:172px">
                                     <h4>{{$c->email_template_title}}</h4>
                                </div>
                             <div class="contenthover" >
                                 <div class="row">
                                     <center><div class="col-md-12">
                                             <a href="javascript:void(0);" onclick="choose_template('{{$c->email_template_id}}');" class="btn btn-primary">Select</a>
                                         </div></center>
                                 </div>
                                 <br/>
                                 <div class="row">
                                     <center><div class="col-md-12">
                                             <a href="/removeFavorite/{{$c->email_template_id}}" class="btn btn-warning">Un-Favorite</a>
                                         </div></center>
                                 </div>
                             </div>



                            </div>
                         <?php $i++;?>
                         @endforeach
                        </div>
                        @endif


                        @if(count($email_templates)>0)
                        <div class="row">
                          <div class="col-md-12">
                            <h3>Templates</h3>     
                          </div>
                        </div>

                        <div class="row">
                         @foreach($email_templates as $c)
                            <div class="col-md-4" style='margin-bottom:15px;'>
                                <div class="well well-sm needs_hover"  style="height:172px;width:172px">
                                <h4> {{$c->email_template_title}}

                                </h4>
                                </div>
                                <div class="contenthover">
                                 <div class="row">
                                     <center><div class="col-md-12">
                                             <a href="javascript:void(0);" onclick="choose_template('{{$c->email_template_id}}');" class="btn btn-primary">Select</a>
                                         </div></center>
                                 </div>
                                 <br/>
                                 <div class="row">
                                     <center><div class="col-md-12">
                                             <a href="/makeFavorite/{{$c->email_template_id}}" class="btn btn-success">Favorite</a>
                                         </div></center>
                                 </div>
                             </div>

                            </div>
                         @endforeach
                        </div>
                        @endif





                            </div>  
                            

                        </div>
                        <div class="modal-footer clearfix">

                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>

                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    
    
    
@stop