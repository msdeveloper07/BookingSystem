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
                <li><a href="/bookings/{{$booking->booking_id}}/edit">Edit</a></li>
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
    

        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                   
                </div><!-- /.box-header -->

                <div class="box-body">
                     <div class="row">
                        <div class="col-md-12">
                       <p class="form-group"><h2>{{$booking->booking_title}}</h2></p> 
                       
                         </div>
                    </div>
                  
                    <div class="row">
                        <div class="col-md-12">
                        
                               <p class="form-group">{{$booking->booking_desc}}</p> 
                         </div>
                    </div>
                   
                    <div class="row">
                        <div class="col-md-12">
                         <label>Contact Name</label>
                               <p class="form-group">{{$booking->contact->contact_name}}</p> 
                         </div>
                    </div>
                
                  
                       <div class="row">
                    <div class="col-md-12">
                        <label>Quote Detials &nbsp; <a href="/quotes/{{$booking->booking_id}}/edit">Edit</a> </label>
                        <div class="row">
                            <div class="col-md-12">

                                <p class="form-group">Quote Cost :&nbsp;{{$booking->booking_cost}}</p> 

                                <p class="form-group">Number of Days :&nbsp;{{$booking->number_of_days}}</p> 
                            </div>
                        </div>
                    </div>    
                    <div class="col-md-12">
                        <label>Date</label>&nbsp; <a href="/quotes/dates/{{$booking->booking_id}}">Edit</a>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="form-group">From :&nbsp;{{ZnUtilities::format_date($booking->date_from,'2')}}</p> 
                            </div>
                            <div class="col-md-4">
                                <p class="form-group">To :&nbsp;{{ZnUtilities::format_date($booking->date_to,'2')}}</p> 
                            </div>
                        </div>
                    </div>

                </div>     
                 
                    <div class="row">
                     
                        
                               <div class="col-md-12">
                        <label>Location</label>
                          <div class="row">
                            <div class="col-md-4">
                         <?php $location_from =  Location::where('location_id',$booking->location_from)->pluck('location_name') ?>
                        <p class="form-group">From :&nbsp;{{$location_from}}</p> 
                            </div>
                              <div class="col-md-4">
                      <?php $location_to =  Location::where('location_id',$booking->location_to)->pluck('location_name') ?>
                        <p class="form-group">To :&nbsp;{{$location_to}}</p>
                              </div>
                    </div>
                </div>
                        
                  
                        
                    </div>
                    
                  
                    
                    </br>      
                    <h3>Quote Feature  &nbsp; <a href="/bookings/features/{{$booking->booking_id}}">Edit</a> </h3> 
                    </br>
                    
                    <?php  $items = BookingFeature::where('booking_id',$booking->booking_id)->get();  
                    

                    
                    ?>
                    
                    @foreach($items as $i)
                    
                    <?php $stpid = SupplierType::where('supplier_type_id',$i->supplier_parent_id)->pluck('supplier_type');
                    $stid = SupplierType::where('supplier_type_id',$i->supplier_id)->pluck('supplier_type');?>
                    <h3>{{$stpid}}</h3>
                    <br/>
                    <h5>{{$stid}}</h5>
                    <br/>
                    <?php  $feature_item = json_decode($i->items); ?>
                    
                    @foreach($feature_item as $t=>$T)
                   
                    @foreach($T as $m=>$M)
                    @foreach($M as $n)
                    
                    
                    {{$m}}
                    &nbsp;
                {{$n}}
                
                <br/>
                               @endforeach
                               @endforeach
                               @endforeach
                               <br/>
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
                    <a href="/quotes/{{$booking->booking_id}}/edit" class="btn btn-primary">Edit</a>
                </div>
            </div>
        </div>
    
</div>

@stop

