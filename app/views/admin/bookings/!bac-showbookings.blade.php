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







<form class="form-inline" action="/bookingActions" method="post" name="actions_form" id="actions_form">


<div class="box box-danger">
    <div class="box-body">
         <div class="row">
             
             <div class="col-md-4">
                     Actions
                     <div class="form-group">
                        <select id="bulk_action" name="bulk_action" class="form-control" placeholder="Select Action"  >
                            <option value="">Select An Action</option>
                            <option value="delete">Delete Selected Bookings</option>
                        </select>
                     </div>
                 
             </div>
             
             
              <div class="col-md-5">
                        <div class="input-group">
                        
                              <input type="text" value="<?php if(isset($search)){ echo $search;}?>" class="form-control" name="search" id="search" placeholder="Search booking">
                         <span class='input-group-btn'>
                          <button type="submit" class="btn btn-default btn-flat">Find Booking</button>
                          </span>
                        </div>
                </div>

                
            </div>
    </div>    
   
</div>


<div class='table-responsive'>
    <table class="table table-hover table-bordered pull-left table-striped table-condensed admin-booking-table">
    <thead>
        <tr>
            <th>
            <!-- <button id="checkall" class="btn-info">Toggle</button>-->
            <input type="checkbox" id="checkall" class="check" value="" />
            </th>
            <th>Booking Title</th>
          
            <th>Created On</th>
            <th>Quoted Cost</th>
            <th>Discounts</th>
            
            <th>&nbsp;</th>
           
       </tr>
     </thead>
     <tbody>
        @foreach($bookings as $c)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$c->booking_id}}" id="cid{{$c->booking_id}}" />
           </td>

           <td  data-title="User Name">
               <a href="/bookings/{{$c->booking_id}}/show/" title="Show">
                 {{isset($c->booking_title)?$c->booking_title:''}} 
               </a>
           </td>
           
         
         
                <td>
         {{ZnUtilities::format_date($c->created_on,'2')}} 
               </td>
                <td>
         {{$c->booking_cost}} 
               </td>
                <td>
         {{$c->discount}} 
               </td>
                   
           <td>
              <a href="/bookings/{{$c->booking_id}}/edit/" title="Edit"><i class="fa fa-pencil-square fa-lg"></i>&nbsp;Edit</a>
                  
           </td>
       </tr>
       @endforeach
    </tbody>
 

</table>
</div>


</form>
    


@stop