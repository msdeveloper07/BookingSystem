@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif

<?php $folders = EmailTemplateFolder::get();?>
    @if($folders->count() > 0)
        <div class="row">
            <div class="col-md-12">
                <a href="/emailTemplates" class="btn {{$folder_id=='0'?'btn-primary':'btn-default'}}">Root</a>
                @foreach($folders as $f)
                    <a href="/emailTemplates/folder/{{$f->email_template_folder_id}}" class="btn {{$folder_id==$f->email_template_folder_id?'btn-primary':'btn-default'}}">{{$f->email_template_folder_title}}</a>
                @endforeach
            </div>
        </div>
    <div class="clearfix">&nbsp;</div>
    @endif


<form class="form-inline" action="/emailTemplateActions" method="post" name="actions_form" id="actions_form">
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                   
<div class="box box-danger">
    
        
    <div class="box-body">
         <div class="row">
             
             <div class="col-md-4">
                     Actions
                     <div class="form-group">
                        <select id="bulk_action" name="bulk_action" class="form-control" placeholder="Select Action"  >
                            <option value="">Select An Action</option>
                            <option value="delete">Delete Selected Email Templates</option>
                        </select>
                     </div>
                 
             </div>
             
             <div class="col-md-3">
                     <div class="form-group">
                        <select id="move_to" name="move_to" class="form-control"  >
                            <option value="">Move To Folder</option>
                            <option value="0">Root</option>
                          @if($folders->count() > 0)
                             @foreach($folders as $f)
                                <option value="{{$f->email_template_folder_id}}">{{$f->email_template_folder_title}}</option>
                             @endforeach
                          @endif  
                        </select>
                     </div>
             </div>
             
                

                <div class="col-md-2">
                    <a href="/emailTemplates/create" class="btn btn-primary btn-flat">Add New Email Template </a>
                </div>

            </div>
    </div>    
   
</div>


<div class='table-responsive col-md-8'>
    <table class="table table-hover table-bordered pull-left table-striped table-condensed admin-user-table">
    <thead>
        <tr>
            <th>
            <!-- <button id="checkall" class="btn-info">Toggle</button>-->
            <input type="checkbox" id="checkall" class="check" value="" />
            </th>
            <th>Title</th>
            <th>Folder</th>
          
            
            <th>&nbsp;</th>
           
       </tr>
     </thead>
     <tbody>
        @foreach($email_templates as $c)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$c->email_template_id}}" id="cid{{$c->email_template_id}}" />
           </td>

           <td  data-title="Email Template Name">
               <a href="/emailTemplates/{{$c->email_template_id}}/edit/" title="Edit">
                  {{$c->email_template_title}} 
               </a>
           </td>

           <td>@if(($c->folder_id!='')&&($c->folder_id!=0))
               {{EmailTemplateFolder::find($c->folder_id)->email_template_folder_title}}</td>
               @endif
           <td>
              <a href="/emailTemplates/{{$c->email_template_id}}/edit/" title="Edit"><i class="fa fa-pencil-square fa-lg"></i>&nbsp;Edit</a>
           </td>
       </tr>
       @endforeach
    </tbody>


</table>
</div>


</form>
    
<div class="row">
    <div class="col-md-12">
        {{$email_templates->appends(Input::except('page'))->links()}}
    </div>
</div>



@stop

