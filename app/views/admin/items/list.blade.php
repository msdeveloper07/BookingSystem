@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif

<form class="form-inline" action="/itemActions" method="post" name="actions_form" id="actions_form">

<div class="box box-danger">
    
        
    <div class="box-body">
         <div class="row">
             
             <div class="col-md-4">
                     Actions
                     <div class="form-group">
                        <select id="bulk_action" name="bulk_action" class="form-control" placeholder="Select Action"  >
                            <option value="">Select An Action</option>
                            <option value="blocked">Unpublish Selected Item</option>
                            <option value="active">Unpublish` Selected Item</option>
                            <option value="delete">Delete Selected Item</option>
                        </select>
                     </div>
                 
             </div>
             
             
                <div class="col-md-5">
                  
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                        <div class="input-group">
                          <input type="text" value="<?php if(isset($search)){ echo $search;}?>" class="form-control" name="search" id="search" placeholder="Search Items">
                          <span class="input-group-btn">
                          <button type="submit" class="btn btn-default btn-flat">Find Item</button>
                          </span>
                        </div>
                      
                </div>

                <div class="col-md-2">
                    <a href="/items/create" class="btn btn-primary btn-flat">Add New Item </a>
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
            <th>Item</th>
            <th>Item For</th>
            <th>Field Type</th>
            <th>Options</th>
            <th>Status</th>
            <th>&nbsp;</th>
           
       </tr>
     </thead>
     <tbody>
        @foreach($items as $c)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$c->item_id}}" id="cid{{$c->item_id}}" />
           </td>

           <td  data-title="Item">
               {{$c->title}}
           </td>
           
           <td  data-title="Item For">
               {{$c->title_for}}
           </td>
           

           <td  data-title="Field Type">
               {{$c->field_type}}
           </td>
           
           <td data-title="Options">
               {{$c->options}}
           </td>
           
          
         
          
           <td data-title="Status"><?php 
            if($c->user_status=='active')
            {
                echo '<span class="text-success"><i class="fa fa-check"></i> Active</span>';
            }
            else if($c->user_status=='blocked')
            {
                echo '<span class="text-danger"><i class="fa fa-circle"></i> Deactive</span>';
            }
           ?></td>
           <td data-title="Options">
               &nbsp;
           </td>
         
       </tr>
       @endforeach
    </tbody>


</table>
</div>


</form>
    
{{$items->appends(Input::except('page'))->links();}}

@stop

