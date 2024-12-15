@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif

<form class="form-inline" action="/userActions" method="post" name="actions_form" id="actions_form">

<div class="box box-danger">
    
        
    <div class="box-body">
         <div class="row">
             
             <div class="col-md-4">
                     Actions
                     <div class="form-group">
                        <select id="bulk_action" name="bulk_action" class="form-control" placeholder="Select Action"  >
                            <option value="">Select An Action</option>
                            <option value="blocked">Block Selected Users</option>
                            <option value="active">Activate Selected Users</option>
                            <option value="delete">Delete Selected Users</option>
                        </select>
                     </div>
                 
             </div>
             
             
                <div class="col-md-5">
                  
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                                                
                        <div class="input-group">
                            <input type="text" class="form-control"  name="search" value="<?php if(isset($search)){ echo $search;}?>" placeholder="Search Users" aria-describedby="basic-addon2">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-default btn-flat">Find User</button>
                            </span>
                        </div>
                      
                </div>

                <div class="col-md-2">
                    <a href="/users/create" class="btn btn-primary btn-flat">Add New User </a>
                </div>

            </div>
    </div>    
   
</div>


<div class='table-responsive'>
    <table class="table table-hover table-bordered pull-left table-striped table-condensed admin-user-table">
    <thead>
        <tr>
            <th>
            <!-- <button id="checkall" class="btn-info">Toggle</button>-->
            <input type="checkbox" id="checkall" class="check" value="" />
            </th>
            <th>Name</th>
            <th>Email</th>
            <th>User Group</th>
            <th>Status</th>
            <th>&nbsp;</th>
           
       </tr>
     </thead>
     <tbody>
        @foreach($users as $c)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$c->id}}" id="cid{{$c->id}}" />
           </td>

           <td  data-title="User Name">
               <a href="/users/{{$c->id}}/edit/" title="Edit">
                 {{$c->name}} 
               </a>
           </td>
           <td  data-title="Email">{{$c->email}}</td>
           <td  data-title="title">{{$c->userGroup}}</td>
          
           <td data-title="Status"><?php 
            if($c->user_status=='active')
            {
                echo '<span class="text-success"><i class="fa fa-check"></i> Active</span>';
            }
            else if($c->user_status=='deactive')
            {
                echo '<span class="text-danger"><i class="fa fa-circle"></i> Deactive</span>';
            }
           ?></td>
           
           <td>
              <a href="/users/{{$c->id}}/edit/" title="Edit"><i class="fa fa-pencil-square fa-lg"></i>&nbsp;Edit</a>
                  
           </td>
       </tr>
       @endforeach
    </tbody>


</table>
</div>


</form>
    
{{$users->appends(Input::except('page'))->links();}}

@stop

