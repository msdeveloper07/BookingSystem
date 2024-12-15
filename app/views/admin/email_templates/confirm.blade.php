@extends('layouts.adminTemplate')

@section('content')


@if ( $errors->count() > 0 )

<p>The following errors have occurred:</p>
<ul>
    @foreach($errors->all() as $m)
    <li><span class="text-danger">{{$m}}</span></li>
    @endforeach
</ul>

@endif


<div class="row">
   
        <div class="col-md-6">
             <form  role="form" action='/emailTemplates/send' name='email_template_form' id='email_template_form' method="post">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <input type="hidden" name="lead_id" value="{{Input::old('lead_id',$lead->lead_id)}}">
                <input type="hidden" name="email_template_id" value="{{Input::old('email_template_id',$email_template->email_template_id)}}">
                
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">Lead Details</h3>
                    </div><!-- /.box-header -->
                     <div class="box-body">
                         <div class="row">
                             <div class="col-md-12">
                                 
                                     <p><strong>Lead Title:</strong> {{$lead->lead_title}}</p>
                                   
                                     <p><strong>Contact Person:</strong> {{$lead->contactCompany[0]->contact->contact_first_name." ".$lead->contactCompany[0]->contact->contact_last_name}}</p>
                                     <p><strong>Email:</strong> {{$lead->contactCompany[0]->contact->contact_email}}</p>
                                
                             </div>
                         </div>
                         
                     </div>
                    
                </div>
                
                
                <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                    
                    <?php $messages = Message::where('parent_id','0')->where('lead_id',$lead->lead_id)->orderBy('occurred_on','desc')->get();
                    
                    if($messages->count() > 0)
                    {
                    ?>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="radio-inline"><input type="radio" {{Input::old('email_type')=='new'?'checked="checked"':''}} name="email_type" id="email_type_new" value="new" required class="email_type">New Email</label>
                                <label class="radio-inline"><input type="radio" {{Input::old('email_type')=='reply'?'checked="checked"':''}} name="email_type" id="email_type_reply" value="reply" class="email_type">Reply</label>
                            </div>
                            <label for="gender" class="error">Please select one option from above</label>
                        </div>
                    </div>
                    
                    
                    <div class="row" id="messages_list" style="display: {{Input::old('email_type')!='new'?'block':'none'}}">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Reply To</label>
                                <select name='parent_id' id='template_parent_id' class="form-control">
                                    <option value=''>Select Message</option>
                                    @foreach($messages as $m)
                                    <option {{Input::old('parent_id')== $m->message_id?'selected="selected"':''}}  value='{{$m->message_id}}'>{{$m->subject}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row" id='original_message_container' style='display: none'>
                        <div class="col-md-12">
                            <div class="well well-sm">
                                <h5>Original Message</h5>
                                <p id='message_content'>{{$messages[0]->content}}</p>
                            </div>
                        </div>
                    </div>
                    
                    
                    <?php } ?>
                    
                    <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">TO:</span>
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
                                    <input type="text" placeholder="Email BCC" class="form-control" name="email_bcc" id="email_bcc" value="{{Input::old('email_bcc')}}" >
                                </div>
                            </div>
                    
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="input-group">
                                   <span class="input-group-addon">SUBJECT:</span> 
                                    <input type="text" placeholder="Possible subject for the Email" id="email_template_title" name="email_template_title" class="form-control required" value="{{Input::old('subject','{lead_title}')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
<!--                                <label for="InputTitle">Content</label>-->
                                <textarea class="textarea" id="content" name="content" placeholder="Email Template Text" 
                                style="width: 100%; height: 325px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{Input::old('content',$email_template->content)}}</textarea>                                
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

                                      
                      
                    <div class="row" style="display:{{($email_template->attachment!='')?'block':'none'}}" id="file_holder">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <div id="prev_upload">

                                    <table class="table table-striped table-bordered table-hover table-condensed">
                                        <thead>
                                            <tr>
                                                <th>File Type</th>
                                                <th>Filename</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        @if($email_template->attachment!='')    
                                        <?php ?> 
                                        <tr id="rejsdlso20293" class="document_files">
                                                <td data-title="File Type">
                                                    <?php
                                                        $file_name_parts = explode('.',$email_template->attachment);
                                                        $file_parts_count = count($file_name_parts);

                                                        $ext = $file_name_parts[$file_parts_count-1];
                                                    ?>
                                                    <img src="/images/attachment.png" class="property_image" height="22px">
                                                    <span class="file-type"><?php echo $ext; ?></span>
                                                    
                                                </td>
                                                <td data-title="Filename">
                                                    <?php 
                                                    $file_name_parts = explode('/attachments/',$email_template->attachment);
                                                    $file_name_parts2 = explode('-',$file_name_parts[1]);
                                                    unset($file_name_parts2[0]);
                                                    echo implode('-',$file_name_parts2);
                                                    
                                                   ?>
                                                    <input type="hidden" value="{{$file_name_parts[1]}}" name="specs_location[]">
                                                </td>
                                                <td data-title="Remove File">
                                                    <a class="remove" href="javascript:void(0);" onclick='javascript:remove_div("rejsdlso20293");'>
                                                        <span class="glyphicon glyphicon glyphicon-remove"></span> Remove</a>
                                                    
                                                </td>
                                            </tr> 
                                            @endif
                                        </tbody>
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
    
        <div class="col-md-6">
            <div class="box box-warning">
                <div class="box-header">
                    <h3 class="box-title">Example</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                     <p class="text-muted"> 
                        Dear {name},<br/>
                        <br/>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut eget tempor nulla. Pellentesque auctor erat nec justo suscipit ultrices. Quisque commodo, odio eget varius imperdiet, eros ante molestie lacus, molestie viverra neque nisi at elit. Morbi nec congue urna.<br />
                        <br />    
                        {website_link}<br />
                        <br />
                        Regards<br />
                        {sender_name}
                     </p>
                    
                </div><!-- /.box-body -->

            </div>
            
            
            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title">Available Variables</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th>Variable</th>
                                <th>Purpose</th>
                            </tr>
                            <tr>
                                <td>{name}</td>
                                <td>Name of Contact of the Lead</td>
                            </tr>
                            <tr>
                                <td>{lead_title}</td>
                                <td>Title of Lead</td>
                            </tr>
                            <tr>
                                <td>{sender_name}</td>
                                <td>Name of the Sender</td>
                            </tr>
                            <tr>
                                <td>{website_link}</td>
                                <td>Link to the Booking Gatesway website</td>
                            </tr>
                            <tr>
                                <td>{follow_up_on}</td>
                                <td>Date on which the follow up is scheduled</td>
                            </tr>
                            <tr>
                                <td>{follow_up_time}</td>
                                <td>Time on which the follow up is scheduled</td>
                            </tr>
                            <tr>
                                <td>{interest}</td>
                                <td>Interest of the lead</td>
                            </tr>
                        </table>
                    </div>
                </div><!-- /.box-body -->

            </div>
        </div>
    
</div>

@stop