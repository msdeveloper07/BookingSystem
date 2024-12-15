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
            <li class="active"><a href="/bookings/{{$booking->booking_id}}/show">General</a></li>
                <li ><a href="/bookings/{{$booking->booking_id}}/edit">Edit</a></li>
             <li><a href="/bookings/dates/{{$booking->booking_id}}">Dates</a></li>
                <li><a href="/bookings/suppliers/{{$booking->booking_id}}">Suppliers</a></li>
                <li><a href="/bookings/items/{{$booking->booking_id}}">Items</a></li>
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
        <input type="hidden" name="package_id" value="<?php $booking->package_id; ?>">
        <input type="hidden" name="quote_id" value="<?php $booking->quote_id; ?>">

        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                    <div style='float:right;'>
                        <a class="btn btn-success" href='/quotes/bookings/{{$booking->quote_id}}'>Back To Bookings</a>             
                    </div>
                </div><!-- /.box-header -->

                <div class="box-body">
                
                    <div class="row">
                        <div class="col-md-12">
                         <label>Booking Title</label>
                               <p class="form-group">{{$booking->booking_title}}</p> 
                         </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                         <label>Package Description</label>
                               <p class="form-group">{{$package->package_desc}}</p> 
                         </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                         <label>Contact Name</label>
                               <p class="form-group">{{$quote->contact_name}}</p> 
                         </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                         <label>Package Cost</label>
                               <p class="form-group">{{$package->total_cost}}</p> 
                         </div>
                    
                        <div class="col-md-4">
                         <label>Discount (if any)</label>
                               <p class="form-group">{{$booking->discount}}</p> 
                         </div>
                    
                        <div class="col-md-4">
                         <label>Booking Cost</label>
                               <p class="form-group">{{$booking->booking_cost}}</p> 
                         </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                         <label>Location From</label>
                         <?php $location_from =  Location::where('location_id',$package->location_from)->pluck('location_name') ?>
                               <p class="form-group">{{$location_from}}</p> 
                         </div>
                        <div class="col-md-6">
                         <label>Location To</label>
                        <?php $location_to =  Location::where('location_id',$package->location_to)->pluck('location_name') ?>
                               <p class="form-group">{{$location_to}}</p> 
                         </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                         <label>Number of Days</label>
                               <p class="form-group">{{$booking->number_of_days}}</p> 
                         </div>
                    
                        <div class="col-md-4">
                         <label>Date From</label>
                               <p class="form-group">{{ZnUtilities::format_date($quote->date_from,'2')}}</p> 
                         </div>
                    </div>
                    
                    </br>      
                    <h3>Items</h3>
                    </br>
                    @foreach($items as $item)
                           
                                     <div class="row">
                                    <div class="col-md-6">
                                      <label>Supplier Type</label>
                                      <?php $supplier = SupplierType::where('supplier_type_id',$item->supplier_type_id)->pluck('supplier_type') ?>
                                             <p class="form-group">{{$supplier}}</p>
                                        </div>
                                    
                                    <div class="col-md-6">
                                      <label>Supplier Item</label>
                                      <?php $supplier_type_item = SupplierTypeItem::where('supplier_type_item_id',$item->supplier_type_item_id)->pluck('supplier_item_name') ?>
                                             <p class="form-group">{{$supplier_type_item}}</p>
                                        </div>
                                    </div>
                                 <div class="row">
                                    <div class="col-md-12">
                                        <label>Extra Notes</label>
                                        <p class="form-group">{{$item->extra_notes}}</p>
                                        </div>
                                    </div>
                            <hr />
                           @endforeach
                           
                    </br>      
                    <h3>Itinerary</h3>
                    </br>
                    @foreach($itineraries as $l) 
                        <div class="row">
                            
                           <div class="col-md-12">
                               <label>Day  {{isset($l->days)?$l->days:''}} {{isset($l->itinerary_title)?"(".$l->itinerary_title.")":''}}</label>
                              <p>{{$l->extra_notes}}</p>
                          </div>
                        <div class="col-md-12">
                            <strong>Things to Do</strong>
                            <?php $saved_things = explode(',', $l->things_todo); 
                                   $things =  ThingToDo::whereIn('thing_todo_id', $saved_things)->get();
                            ?>
                            @if(count($things)>0)
                            <ul>
                                @foreach($things as $t)
                                <li>{{$t->thing_todo}}</li>
                                @endforeach
                            </ul>
                            @endif
                        </div>  
                             <div class="col-md-12">
                                <strong>Image Gallery</strong>
                                <?php $itinerary_image= BookingItineraryImage::where('booking_itinerary_id',$l->booking_itinerary_id)->where('days',$l->days)->get(); ?>
                                <p>@foreach($itinerary_image as $i)

                                <img src="<?php echo url().'/images/itinerary/'.$i->image; ?>"   height="100px">
                              
                                @endforeach</p>
                            </div>
                        
                        
                        </div>
                        <hr />
                        @endforeach
                    
                           
                </div><!-- /.box-body -->


                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>

@stop


