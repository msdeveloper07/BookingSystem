@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif

<form class="form-inline" action="/thingstodoActions" method="post" name="actions_form" id="actions_form">

<div class="box box-danger">
    
        
    <div class="box-body">
         <div class="row">
             
             <div class="col-md-4">
                     Actions
                     <div class="form-group">
                        <select id="bulk_action" name="bulk_action" class="form-control" placeholder="Select Action"  >
                            <option value="">Select An Action</option>
                            <option value="delete">Delete Selected Things</option>
                        </select>
                     </div>
                 
             </div>
             
             
                <div class="col-md-5">
                  
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                        <div class="form-group">
                          <div class="col-md-6">
                              <input type="text" value="<?php if(isset($search)){ echo $search;}?>" class="form-control" name="search" id="search" placeholder="Search TingsToDo">
                          </div>
                          <button type="submit" class="btn btn-default btn-flat">Find ThingsToDo</button>

                        </div>
                      
                </div>

                <div class="col-md-2">
                    <a href="/thingstodo/create" class="btn btn-primary btn-flat">Add New ThingsToDo </a>
                </div>

            </div>
    </div>    
   
</div>


<div class='table-responsive'>
    <table class="table table-hover table-bordered pull-left table-striped table-condensed admin-user-table">
    <thead>
        <tr>
            <th>
           
            <input type="checkbox" id="checkall" class="check" value="" />
            </th>
            
            <th>Things To Do</th>
            <th>&nbsp;</th>
           
       </tr>
     </thead>
     <tbody>
        @foreach($thingstodo as $c)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$c->thing_todo_id}}" id="cid{{$c->thing_todo_id}}" />
           </td>

           <td>
               <a href="/thingstodo/{{$c->thing_todo_id}}/edit/" title="Edit">
                 {{$c->thing_todo}} 
               </a>
           </td>
          
           <td>
              <a href="/thingstodo/{{$c->thing_todo_id}}/edit/" title="Edit"><i class="fa fa-pencil-square fa-lg"></i>&nbsp;Edit</a>
                  
           </td>
       </tr>
       @endforeach
    </tbody>


</table>
</div>


</form>
    
{{$thingstodo->appends(Input::except('page'))->links();}}

@stop

