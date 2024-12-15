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

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
    @if($mode!='')
                <a href="/messages/" class="btn btn-app">
                    <span class="badge bg-aqua"><?php echo $all_messages_count = Message::totalUnread();?></span>
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

<div class="row">
    <div class="col-md-8">
        
        
<form action="/messages/new" method="post" name="actions_form" id="actions_form">
<div class="box box-info">
                                <div class="box-header ui-sortable-handle" style="cursor: move;">
                                    
                                    <i class="fa fa-envelope"></i>
                                    <h3 class="box-title">New Email</h3>
                                 
                                </div>
                                <div class="box-body">
                       
                                    <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">TO:</span>
                                    <input type="text" placeholder="Email TO" class="form-control" name="email_to"  id="email_to" value="{{Input::old('email_to')}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">NAME:</span>
                                    <input type="text" placeholder="Enter Name" class="form-control" name="recipient_name"  id="recipient_name" value="{{Input::old('recipient_name')}}">
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
                                    <input type="text" placeholder="Email BCC" class="form-control" name="email_bcc" id="email_bcc" value="{{Input::old('email_bcc')}}">
                                </div>
                            </div>
                                    
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">SUBJECT</span>
                                    <input type="text" placeholder="Subject" class="form-control" name="subject" id='subject' value="{{Input::old('subject')}}">
                                </div>
                            </div>
                                    
                      <div class="row">
                        <div class="col-md-3">
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