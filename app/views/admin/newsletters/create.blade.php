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
    <div class="col-md-8">
        
        
<form action="/newsletters" method="post" name="actions_form" id="actions_form">
     <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
<div class="box box-info">
                                <div class="box-header ui-sortable-handle" style="cursor: move;">
                                    
                                    <i class="fa fa-envelope"></i>
                                    <h3 class="box-title">New News Letter</h3>
                                 
                                </div>
                                <div class="box-body">
                       
                                                          
                                   
                     <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <select class="form-control" id="mailinglist_id" name="mailinglist_id">
                                    <option value="">Please Select MailingList</option>
                                    @foreach($mailinglist as $m)
                                    <option value="{{$m->mailinglist_id}}">{{$m->mailinglist_name}}</option>
                               
                                @endforeach
                                </select>
                            </div>
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
                                    
                           
                           
                </div><!-- /.box-body -->
                 <div class="box-footer">
                    <button class="btn btn-primary" type="submit">SHOOT</button>
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