@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif

<form class="form-inline" action="/itineraryActions" method="post" name="actions_form" id="actions_form">

<div class="box box-danger">
    
        
    <div class="box-body">
         <div class="row">
             
             <div class="col-md-4">
                     Actions
                     <div class="form-group">
                        <select id="bulk_action" name="bulk_action" class="form-control" placeholder="Select Action"  >
                            <option value="">Select An Action</option>
                            <option value="delete">Delete Selected Itinerary</option>
                        </select>
                     </div>
                 
             </div>
               
             
                <div class="col-md-5">
                  
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                        <div class="input-group">
                         
                              <input type="text" value="<?php if(isset($search)){ echo $search;}?>" class="form-control" name="search" id="search" placeholder="Search Itinerary">
                       <span class='input-group-btn'>
                          <button type="submit" class="btn btn-default btn-flat">Find Itinerary</button>
                       </span>
                        </div>
                      
                </div>

                <div class="col-md-2">
                    <a href="/itinerary/create" class="btn btn-primary btn-flat">Add New Itinerary</a>
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
            <th>Itinerary Title</th>
            <th>Date</th>
            <th>Time</th>
            <th>Description</th>
         
            <th>&nbsp;</th>
           
       </tr>
     </thead>
     <tbody>
        @foreach($itinerary as $l)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$l->itinerary_id}}" id="cid{{$l->itinerary_id}}" />
           </td>

           <td  data-title="Itinerary Name">
               <a href="/itinerary/{{$l->itinerary_id}}/edit/" title="Edit">
                 {{isset($l->itinerary_title)?$l->itinerary_title:''}} 
               </a>
           </td>
           <td>
               {{isset($l->datetime)?$l->datetime:''}} 
           </td>
           
            <td>
               {{isset($l->time)?$l->time:''}} 
           </td>
            <td>
               {{isset($l->description)?$l->description:''}} 
           </td>
          
           <td>
              <a href="/itinerary/{{$l->itinerary_id}}/edit/" title="Edit"><i class="fa fa-pencil-square fa-lg"></i>&nbsp;Edit</a>
                  
           </td>
       </tr>
       @endforeach
    </tbody>


</table>
</div>


</form>
    
{{$itinerary->appends(Input::except('page'))->links();}}

@stop

