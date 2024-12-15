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
    <div class="col-md-12">
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li ><a href="/bookings/{{$booking->booking_id}}/show">General</a></li>
                <li><a href="/bookings/{{$booking->booking_id}}/edit">Edit</a></li>
               <li><a href="/bookings/dates/{{$booking->booking_id}}">Dates</a></li>
                <li><a href="/bookings/features/{{$booking->booking_id}}">Features</a></li>
                <li><a href="/bookings/itinerary/{{$booking->booking_id}}">Itinerary</a></li>
                <li  class="active"><a href="/bookings/tasks/{{$booking->booking_id}}">Tasks</a></li>
                <li><a href="/bookings/payments/{{$booking->booking_id}}">Payments</a></li>
                   <li><a href="/bookings/cancelbooking/{{$booking->booking_id}}">Cancel Booking</a></li>
            </ul>
        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->
</div>

<div class="row">
    <div class="col-md-6">
    <form  role="form" action='/bookings/savetasks/{{$booking->booking_id}}' name='user_form' id='user_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                     <div style='float:right;'>
                         <h3 class="box-title">   Booking Title:&nbsp; {{$booking->booking_title}} &nbsp; </h3>     
                        </div>
                    
                </div><!-- /.box-header -->
                 <div class="box-body">
                      <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="InputTitle">Task Title</label>
                            <input type="text" placeholder="Enter Task" id="task_title" name="task_title" class="form-control required" value="{{Input::old('task_title');}}">
                        </div>
                    </div>
                          
                     </div>
                
                    <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="InputTitle">Task Description</label>
                            <textarea placeholder="Enter Task Description" id="task_description" name="task_description" class="form-control required" value=""> </textarea>
                        </div>
                    </div>
                    </div>
                     
                     
                     
                    <div class="row">      
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="InputTitle">Asign To</label>
                            <select id="assign_to" name="assign_to" class="form-control">
                                <option value="">Please Select</option>
                                @foreach($assign_to as $c)
                                <option value="{{$c->id}}">{{$c->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                  
                
                     </div>
                     
                    
<!--                      <div class="row">-->
<!--                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Asign Date</label>
                                <input type="text"  id="assign_date" name="assign_date" class="form-control" value="">
                            </div>
                        </div>-->
                          <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Due Date</label>
                                <input type="text"  id="due_date" name="due_date" class="form-control" value="">
                            </div>
                        </div>
                          </div>
                
                 
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <button class="btn btn-primary" type="submit" >Submit</button>
                </div>
            </div>
        
    </form>
        
        </div>
    <div class="col-md-6">
        
        
        @if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif

<form class="form-inline" action="/bookings/taskActions/{{$booking->booking_id}}" method="post" name="actions_form" id="actions_form">

<div class="box box-danger">
    
        
    <div class="box-body">
         <div class="row">
             
             <div class="col-md-4">
                     Actions
                     <div class="form-group">
                        <select id="bulk_action" name="bulk_action" class="form-control" placeholder="Select Action"  >
                            <option value="">Select An Action</option>
                            <option value="delete">Delete Selected Task</option>
                        </select>
                     </div>
                 
             </div>
               
             
                <div class="col-md-5">
                  
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                        <div class="input-group">
                         
                              <input type="text" value="<?php if(isset($search)){ echo $search;}?>" class="form-control" name="search" id="search" placeholder="Search Task">
                       <span class='input-group-btn'>
                          <button type="submit" class="btn btn-default btn-flat">Find Task</button>
                       </span>
                        </div>
                      
                </div>

                <div class="col-md-2">
                    <a href="/bookings/tasks/{{$booking->booking_id}}" class="btn btn-primary btn-flat">Add New Task</a>
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
            <th>Task Name</th>
            <!--<th>Task Description</th>-->
            <th>Assign To</th>
              <th>Assign Date</th>
              <th>Due Date</th>
              <th>Task Status</th>
              <th>Comments</th>
         
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
               <a href="/bookings/tasks/edit/{{$booking->booking_id}}/{{$l->booking_task_id}}" title="Edit">
                 {{isset($l->task_title)?$l->task_title:''}} 
               </a>
           </td>
<!--           <td>
               {{isset($l->task_description)? substr($l->task_description,'0','40').('...'):''}} 
           </td>-->
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
                  <a href="/bookings/tasks/edit/{{$booking->booking_id}}/{{$l->booking_task_id}}"><i class="fa fa-comment"></i>  Comments ({{TaskComment::where('booking_task_id',$l->booking_task_id)->get()->count()}}) </a>
           </td>
          
           <td>
              <a href="/bookings/tasks/edit/{{$booking->booking_id}}/{{$l->booking_task_id}}" title="Edit"><i class="fa fa-pencil-square fa-lg"></i>&nbsp;Edit</a>
                  
           </td>
       </tr>
       @endforeach
    </tbody>


</table>
</div>


</form>
    
{{$task->appends(Input::except('page'))->links();}}

@stop

        
        
        
        
        
    </div>
</div>

@stop