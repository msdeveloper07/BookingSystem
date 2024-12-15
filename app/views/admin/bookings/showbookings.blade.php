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
             
             
              <div class="col-md-3">
                        <div class="input-group">
                        
                              <input type="text" value="<?php if(isset($search)){ echo $search;}?>" class="form-control" name="search" id="search" placeholder="Search booking">
                         <span class='input-group-btn'>
                          <button type="submit" class="btn btn-default btn-flat">Find Booking</button>
                          </span>
                        </div>
                </div>
             
             
              <div class="col-md-3">
                     Sort Booking &nbsp;
                     <div class="form-group">
                         <select id="selectbox" name="bulk_action1" class="form-control" onchange="sort_results(this.value);"  >
                                <option value="All">All</option>
                            <option {{(isset($action)?$action:'') == 'canceled'?'selected="selected"':''}} value="canceled">Canceled</option>
                            <option {{(isset($action)?$action:'') == 'process'?'selected="selected"':''}} value="process">Processing</option>
                         </select>
                     </div>
                 
             </div>
             
             <div class="col-md-1">
                    <a href="/bookings/create" class="btn btn-primary btn-flat">Add New Booking </a>
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
          
            <th>Discounts</th>
            
              <th>Booking Cost</th>
            <th>&nbsp;</th>
            @if(((isset($action)?$action:'')=='canceled')||((isset($action)?$action:'')!='process'))   <th>&nbsp;</th> @endif
           
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
        {{$c->discount}} 
               </td>
                <td>
          {{$c->booking_cost}} 
               </td>
                   
         @if((isset($action)?$action:'')=='canceled') <td>
              <a href="/bookings/activebooking/{{$c->booking_id}}" title="Active Booking"><i class="fa fa-check-circle"></i>&nbsp;Active Booking</a>
          </td> @endif
           @if((isset($action)?$action:'')=='all') <td>
               <?php
               if($c->booking_status=='active')
               {
                   echo '<span class="text-success"><i class="fa fa-circle"></i>Active</span>';
                   
               }
               else if($c->booking_status=='cancel')
               {
                   echo '<span class="text-danger"><i class="fa fa-circle"></i>Cancel</span>';
                  
               }
               
               ?>
              
               @endif
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