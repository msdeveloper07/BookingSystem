@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif

<form class="form-inline" action="/permissionActions" method="post" name="actions_form" id="actions_form">

<div class="box box-danger">
    
        
    <div class="box-body">
         <div class="row">
             
             <div class="col-md-4">
                     Actions
                     <div class="form-group">
                        <select id="bulk_action" name="bulk_action" class="form-control" placeholder="Select Action"  >
                            <option value="">Select An Action</option>
                          
                            <option value="delete">Delete Selected Permissions</option>
                        </select>
                     </div>
                 
             </div>
             
             
                <div class="col-md-5">
                  
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                        <div class="form-group">
                          <div class="col-md-6">
                              <input type="text" value="<?php if(isset($search)){ echo $search;}?>" class="form-control" name="search" id="search" placeholder="Search Permissions">
                          </div>
                          <button type="submit" class="btn btn-default btn-flat">Find</button>

                        </div>
                      
                </div>

                <div class="col-md-2">
                    <a href="/permission/create" class="btn btn-primary btn-flat">Add New Permissions </a>
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
       
            <th>Element</th>
            <th>Component</th>
            <th>Title</th>
           
            <th>&nbsp;</th>
           
       </tr>
     </thead>
     <tbody>
        @foreach($permission as $c)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$c->permission_id}}" id="cid{{$c->permission_id}}" />
           </td>

           <td  data-title="User Name">
               <a href="/permission/{{$c->permission_id}}/edit/" title="Edit">
                 {{$c->element}} 
               </a>
           </td>
           <td  data-title="Email">{{$c->component}}</td>
           <td  data-title="title">{{$c->title}}</td>
          
          
           
           <td>
              <a href="/permission/{{$c->permission_id}}/edit/" title="Edit"><i class="fa fa-pencil-square fa-lg"></i>&nbsp;Edit</a>
                  
           </td>
       </tr>
       @endforeach
    </tbody>


</table>
</div>


</form>
    
{{$permission->appends(Input::except('page'))->links();}}

@stop

