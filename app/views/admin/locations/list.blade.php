@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif

<form class="form-inline" action="/locationActions" method="post" name="actions_form" id="actions_form">

<div class="box box-danger">
    
        
    <div class="box-body">
         <div class="row">
             
             <div class="col-md-4">
                     Actions
                     <div class="form-group">
                        <select id="bulk_action" name="bulk_action" class="form-control" placeholder="Select Action"  >
                            <option value="">Select An Action</option>
                            <option value="delete">Delete Selected Locations</option>
                        </select>
                     </div>
                 
             </div>
               
             
                <div class="col-md-5">
                  
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                        <div class="input-group">
                         
                              <input type="text" value="<?php if(isset($search)){ echo $search;}?>" class="form-control" name="search" id="search" placeholder="Search Location">
                       <span class='input-group-btn'>
                          <button type="submit" class="btn btn-default btn-flat">Find Location</button>
                       </span>
                        </div>
                      
                </div>

                <div class="col-md-2">
                    <a href="/locations/create" class="btn btn-primary btn-flat">Add New Location</a>
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
            <th>Location Name</th>
            <th>State</th>
            <th>Country</th>
         
            <th>&nbsp;</th>
           
       </tr>
     </thead>
     <tbody>
        @foreach($location as $l)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$l->location_id}}" id="cid{{$l->location_id}}" />
           </td>

           <td  data-title="Location Name">
               <a href="/locations/{{$l->location_id}}/edit/" title="Edit">
                 {{isset($l->location_name)?$l->location_name:''}} 
               </a>
           </td>
           <td>
               {{isset($l->state)?$l->state:''}} 
           </td>
            <td>
               {{isset($l->country)?$l->country:''}} 
           </td>
          
           <td>
              <a href="/locations/{{$l->location_id}}/edit/" title="Edit"><i class="fa fa-pencil-square fa-lg"></i>&nbsp;Edit</a>
                  
           </td>
       </tr>
       @endforeach
    </tbody>


</table>
</div>


</form>
    
{{$location->appends(Input::except('page'))->links();}}

@stop

