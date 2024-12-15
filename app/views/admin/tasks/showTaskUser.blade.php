@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif

<form class="form-inline" action="/taskActions" method="post" name="actions_form" id="actions_form">

<div class="box box-danger">
    
        
    <div class="box-body">
         <div class="row">
             
          
               
             
                <div class="col-md-5">
                  
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                        <div class="input-group">
<!--                         
                              <input type="text" value="<?php if(isset($search)){ echo $search;}?>" class="form-control" name="search" id="search" placeholder="Search Task">
                       <span class='input-group-btn'>
                          <button type="submit" class="btn btn-default btn-flat">Find Task</button>
                       </span>-->
                        </div>
                      
                </div>

<!--                <div class="col-md-2">
                    <a href="/task/create" class="btn btn-primary btn-flat">Add New Task</a>
                </div>-->

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
            <th>Task Name</th>
            <th>Task Description</th>
            <th>Assign To</th>
              <th>Assign Date</th>
              <th>Due Date</th>
              <th>Task Status</th>
         
            <th>&nbsp;</th>
           
       </tr>
     </thead>
     <tbody>
        @foreach($task as $l)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$l->booking_task_id}}" id="cid{{$l->booking_task_id}}" />
           </td>

           <td  data-title="Task Name">
               <a href="/staffnewTaskComment/{{$l->booking_task_id}}" title="Comment">
                 {{isset($l->task_title)?$l->task_title:''}} 
               </a>
           </td>
           <td>
               {{isset($l->task_description)?$l->task_description:''}} 
           </td>
            <td>
               {{isset($l->user_name->name)?$l->user_name->name:''}} 
           </td>
           
              <td>
               {{isset($l->assign_date)?$l->assign_date:''}} 
           </td>
                <td>
               {{isset($l->due_date)?$l->due_date:''}} 
           </td>
           
              <td>
               {{isset($l->task_status)?$l->task_status:''}} 
           </td>
          
           <td>
              <a href="/staffnewTaskComment/{{$l->booking_task_id}}" title="Comment"><i class="fa fa-comment"></i>&nbsp;Comment({{TaskComment::where('booking_task_id',$l->booking_task_id)->get()->count()}})</a>
                  
           </td>
       </tr>
       @endforeach
    </tbody>


</table>
</div>


</form>
    
{{$task->appends(Input::except('page'))->links();}}

@stop

