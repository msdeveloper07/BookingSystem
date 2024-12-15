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
                <li  class="active"><a href="/bookings/{{$booking->booking_id}}/edit">Edit</a></li>
               <li><a href="/bookings/dates/{{$booking->booking_id}}">Dates</a></li>
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
    <form  role="form" action='/bookings/{{$booking->booking_id}}' name='package_form' id='package_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="booking_id" value="<?php $booking->booking_id; ?>">

        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                    
                </div><!-- /.box-header -->

                <div class="box-body">
                
                   

                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="InputTitle">Quote Title</label>
                                <div class="form-group">
                                    <input type="text" placeholder="Quote Title" id="booking_title" name="booking_title" class="form-control" value="{{Input::old('booking_title',$booking->booking_title)}}">
                                </div>
                            </div>
                        </div>
                    </div>

                  
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="InputTitle">Contact Name</label>
                                <div class="form-group">
                                    
                                    <select id="contact_name" name="contact_name" class="form-control">
                                        <option value="">Please Select Contacts</option>
                                        @foreach($contacts as $c)
                                        <option {{$booking->contact_id==$c->contact_id?'selected="selected"':''}} value="{{$c->contact_id}}">{{$c->first_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="InputTitle">Booking Cost</label>
                                <div class="form-group">
                                    <input type="text" placeholder="Booking Cost" id="package_cost" name="package_cost" class="form-control" value="{{$booking->booking_cost}}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="InputTitle">Discount (if any)</label>
                                <div class="form-group">
                                    <input type="text" placeholder="Discount" id="discount" name="discount" class="form-control" value="{{Input::old('discount',$booking->discount)}}">
                                </div>
                            </div>
                        </div>
                         <div class="col-md-3">
                            <div class="form-group">
                                <label for="InputTitle">Discount Type</label>
                                 <select id="discount_type" requied name="discount_type" class="form-control" onchange="amount(this.value);">
                                        <option value="">Select Discount Type</option>
                                        <option value="0">Fixed</option>
                                        <option value="1">Percentage</option>
                                    </select>
                        
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="InputTitle">Total Cost</label>
                                <div class="form-group">
                                    <input type="text" placeholder="Total Cost" id="quoted_cost" name="booking_cost" class="form-control" value="{{Input::old('bookingd_cost', $booking->booking_cost)}}" readonly>
                                </div>
                            </div>
                        </div>
                        
                          <div class="row">
                         <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Number Of Adults</label>
                                <input type="text"  id="number_of_adults" name="number_of_adults" class="form-control" value="{{$booking->number_of_adults}}">
                             
                            </div>
                        </div>   
                           
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Number Of Children</label>
                                <input type="text"  id="number_of_children" name="number_of_children" class="form-control" value="{{$booking->number_of_children}}">
                            </div>
                        </div>
                          
                           <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Booking Currency</label>
                                <select name="package_currency" id="package_currency" class="form-control">
                                    <option value="">Please Select</option>
                                    @foreach(Config::get('extras.currencies') as $k=>$c)
                                    <option {{$k==Input::old('GBP','GBP')?'selected="selected"':''}} value="{{$k}}">{{$k}} [{{$c}}]</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> 
                         
                    </div>
                        
                        <div class="row">
                         <div class="col-md-12">
                            <div class="form-group">
                                <label for="InputTitle">Booking Description</label>
                                <textarea description  id="description" name="description" class="form-control" value="">{{$booking->booking_desc}}</textarea>
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


