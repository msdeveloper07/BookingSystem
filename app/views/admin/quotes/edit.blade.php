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
            <li><a href="/quotes/{{$quote->quote_id}}/show">General</a></li>
            <li class="active"><a href="/quotes/{{$quote->quote_id}}/edit">Edit</a></li>
             <li><a href="/quotes/dates/{{$quote->quote_id}}">Dates</a></li>
           <li><a href="/quotes/features/{{$quote->quote_id}}">Features</a></li>
                <li><a href="/quotes/itinerary/{{$quote->quote_id}}">Itinerary</a></li>
                <li><a href="/quotes/bookings/{{$quote->quote_id}}">Bookings</a></li>
                
            </ul>
        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->
</div>

<div class="row">
    <form  role="form" action='/quotes/{{$quote->quote_id}}' name='package_form' id='package_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="package_id" value="">

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
                                    <input type="text" placeholder="Quote Title" id="quote_title" name="quote_title" class="form-control" value="{{Input::old('quote_title',$quote->quote_title)}}">
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
                                        <option {{$c->contact_id==$quote->contact_id?'selected="selected"':''}} value="{{$c->contact_id}}">{{$c->first_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="InputTitle">Quote Cost</label>
                                <div class="form-group">
                                    <input type="text" placeholder="Package Cost" id="package_cost" name="package_cost" class="form-control" value="{{Input::old('quoted_cost', $quote->quoted_cost)}}">
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="InputTitle">Discount (if any)</label>
                                <div class="form-group">
                                    <input type="text" placeholder="Discount" id="discount" name="discount" class="form-control" value="">
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
                                    <input type="text" placeholder="Quoted Cost" id="quoted_cost" name="quoted_cost" class="form-control" value="" readonly>
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
                    
                      <div class="row">
                         <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Number Of Adults</label>
                                <input type="text"  id="number_of_adults" name="number_of_adults" class="form-control" value="{{$quote->number_of_adults}}">
                             
                            </div>
                        </div>   
                           
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Number Of Children</label>
                                <input type="text"  id="number_of_children" name="number_of_children" class="form-control" value="{{$quote->number_of_children}}">
                            </div>
                        </div>
                          
                           <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Quote Currency</label>
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
                                <label for="InputTitle">Package Description</label>
                                <textarea description  id="description" name="description" class="form-control" value="">{{$quote->quote_desc}}</textarea>
                            </div>
                        </div> 
                     </div>

                   <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Location From</label>
                                  <div class="form-group">
                                 <select class="form-control" name="location_from" id="location_from">
                                    <option value="">Select Location From</option>
                                    <?php $location_from = Location::all(); ?>
                                    @foreach($location_from as $l)

                                    <option {{$l->location_id==Input::old('location_from', $quote->location_from)?'selected="selected"':''}} value="{{$l->location_id}}">{{$l->location_name}} </option>
                                    @endforeach
                                 </select>
                             </div>
                            </div>
                        </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="InputTitle">Location To</label>

                                     <div class="form-group">
                                         <select  class="form-control" name="location_to" id="location_to">
                                         <option value="">Select Location From</option>
                                             <?php $location_from = Location::all(); ?>
                                             @foreach($location_from as $l)
                                              <option {{$l->location_id==Input::old('location_to',$quote->location_to)?'selected="selected"':''}} value="{{$l->location_id}}">{{$l->location_name}} </option>
                                              @endforeach
                                         </select>
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


