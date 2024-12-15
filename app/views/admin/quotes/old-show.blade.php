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
            <li class="active"><a href="/quotes/{{$quote->quote_id}}/show">General</a></li>
                <li><a href="/quotes/{{$quote->quote_id}}/edit">Edit</a></li>
               <li><a href="/quotes/suppliers/{{$quote->quote_id}}">Suppliers</a></li>
                <li><a href="/quotes/dates/{{$quote->quote_id}}">Dates</a></li>
                <li><a href="/quotes/items/{{$quote->quote_id}}">Items</a></li>
                <li><a href="/quotes/itinerary/{{$quote->quote_id}}">Itinerary</a></li>
                <li><a href="/quotes/bookings/{{$quote->quote_id}}">Bookings</a></li>
                
            </ul>
        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->
</div>

<div class="row">
    

        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                    <div style='float:right;'>
                        <a class="btn btn-success" href='/quotes'>Back To Quotes</a>             
                    </div>
                </div><!-- /.box-header -->

                <div class="box-body">
                
                    <div class="row">
                        <div class="col-md-12">
                         <label>Package</label>
                                <p class="form-group">{{$package->package_name}}</p> 
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
                         <label>Quote Title</label>
                               <p class="form-group">{{$quote->quote_title}}</p> 
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
                               <p class="form-group">{{$quote->discount}}</p> 
                         </div>
                    
                        <div class="col-md-4">
                         <label>Quoted Cost</label>
                               <p class="form-group">{{$quote->quoted_cost}}</p> 
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
                               <p class="form-group">{{$quote->number_of_days}}</p> 
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
                                <?php $itinerary_image= QuoteItineraryImage::where('quote_itinerary_id',$l->quote_itinerary_id)->where('days',$l->days)->get(); ?>
                                <p>@foreach($itinerary_image as $i)

                                <img src="<?php echo url().'/images/itinerary/'.$i->image; ?>"   height="100px">
                              
                                @endforeach</p>
                            </div>
                        
                        
                        </div>
                        <hr />
                        @endforeach
                    
                           
                </div><!-- /.box-body -->


                <div class="box-footer">
                    <a href="/quotes/{{$quote->quote_id}}/edit" class="btn btn-primary">Edit</a>
                </div>
            </div>
        </div>
    
</div>

@stop


