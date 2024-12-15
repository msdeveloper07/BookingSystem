@extends('layouts.adminTemplate')

@section('content')



<div class="row">
    <div class="col-md-12">
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="/bookings/{{$booking->booking_id}}/show">General</a></li>
               <li ><a href="/bookings/{{$booking->booking_id}}/edit">Edit</a></li>
                <li><a href="/bookings/dates/{{$booking->booking_id}}">Dates</a></li>
                <li><a href="/bookings/suppliers/{{$booking->booking_id}}">Suppliers</a></li>
                <li><a href="/bookings/items/{{$booking->booking_id}}">Items</a></li>
                <li><a href="/bookings/itinerary/{{$booking->booking_id}}">Itinerary</a></li>
                <li><a href="/bookings/tasks/{{$booking->booking_id}}">Tasks</a></li>
                <li ><a href="/bookings/payments/{{$booking->booking_id}}">Payments</a></li>
                 <li class="active"><a href="/bookings/cancelbooking/{{$booking->booking_id}}">Cancel Booking</a></li>
            </ul>

        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->

</div>


<div class="row">
    <div class="col-md-8">
        <form  role="form" action='/bookings/savecancelbooking/{{$booking->booking_id}}' name='Payments_form' id='Payments_form' method="post">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Cancel Booking</h3>
                  
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                       
                
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Reason</label>
                                <select name="reason" id="reason" onchange="otherReason(this.value)"  class="form-control">
                                    <option value="">Select Cancel Booking Reason</option>
                                    @foreach(Config::get('extras.reasons') as $p)
                                <option value='{{$p}}'>{{$p}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                        
                  <div class="row">
                    <div class="col-md-6" >
                            <div class="form-group">
                                <label for="InputTitle">Reason Description</label>
                                <textarea placeholder="Enter Reason Description" id="reason_description" name="reason_description" class="form-control" value=""> </textarea>
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

</div>

@stop