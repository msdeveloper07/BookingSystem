@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif

<form class="form-inline" action="/message_folderActions" method="post" name="actions_form" id="actions_form">

<div class="box box-danger">
    
        
    <div class="box-body">
         <div class="row">
             
             <div class="col-md-4">
                     Actions
                     <div class="form-group">
                        <select id="bulk_action" name="bulk_action" class="form-control" placeholder="Select Action"  >
                            <option value="">Select An Action</option>
                            <option value="delete">Delete Selected Folders</option>
                        </select>
                     </div>
                 
             </div>
             
             
                <div class="col-md-5">
                  
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

<!--                        <div class="form-group">
                          <div class="col-md-6">
                              <input type="text" value="<?php if(isset($search)){ echo $search;}?>" class="form-control" name="search" id="search" placeholder="Search Interests">
                          </div>
                          <button type="submit" class="btn btn-default btn-flat">Find Interest</button>

                        </div>-->
                      
                </div>

                <div class="col-md-2">
                    <a href="/message_folders/create" class="btn btn-primary btn-flat">Add New Folder </a>
                </div>

            </div>
    </div>    
   
</div>


<div class='table-responsive col-md-6'>
    <table class="table table-hover table-bordered pull-left table-striped table-condensed admin-user-table">
    <thead>
        <tr>
            <th>
            <!-- <button id="checkall" class="btn-info">Toggle</button>-->
            <input type="checkbox" id="checkall" class="check" value="" />
            </th>
            <th>Title</th>
          
            
            <th>&nbsp;</th>
           
       </tr>
     </thead>
     <tbody>
        @foreach($message_folders as $c)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$c->message_folder_id}}" id="cid{{$c->message_folder_id}}" />
           </td>

           <td  data-title="Interest Name">
               <a href="/message_folders/{{$c->message_folder_id}}/edit/" title="Edit">
                  {{$c->message_folder_title}} 
               </a>
           </td>
          
          
          
           <td>
              <a href="/message_folders/{{$c->message_folder_id}}/edit/" title="Edit"><i class="fa fa-pencil-square fa-lg"></i>&nbsp;Edit</a>
           </td>
       </tr>
       @endforeach
    </tbody>


</table>
</div>


</form>
    
<div class="row">
    <div class="col-md-12">
        {{$message_folders->appends(Input::except('page'))->links();}}
    </div>
</div>



@stop

