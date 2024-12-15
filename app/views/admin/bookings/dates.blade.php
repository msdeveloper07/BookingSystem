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
               <li class="active"><a href="/bookings/dates/{{$booking->booking_id}}">Dates</a></li>
                <li><a href="/bookings/features/{{$booking->booking_id}}">Features</a></li>
                <li><a href="/bookings/itinerary/{{$booking->booking_id}}">Itinerary</a></li>
                <li><a href="/bookings/tasks/{{$booking->booking_id}}">Tasks</a></li>
                <li><a href="/bookings/payments/{{$booking->booking_id}}">Payments</a></li>
                   <li><a href="/bookings/cancelbooking/{{$booking->booking_id}}">Cancel Booking</a></li>
            </ul>
        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->
</div>

<div class="row">
    <form  role="form" action='/bookings/saveDates' name='quote_form' id='quote_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="booking_id" value="{{$booking->booking_id}}">

        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Dates</h3>
                 
                </div><!-- /.box-header -->

                <div class="box-body">
                
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="InputTitle">Number of Days</label>
                                <div class="form-group">
                                   <input type="text" name="number_of_days" id="number_of_days" value="{{Input::old('date_from',$booking->number_of_days)}}" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="InputTitle">Date From</label>
                                <div class="form-group">
                                    <input type="text" name="date_from" id="date_from" value="{{Input::old('date_from',ZnUtilities::format_date($booking->date_from,'2'))}}"  class="form-control"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    
  
            


                </div><!-- /.box-body -->


                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>

@stop


