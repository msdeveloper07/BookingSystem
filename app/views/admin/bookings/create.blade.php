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
                <li class="active"><a href="/bookings/create">General</a></li>
            </ul>
        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->
</div>

<div class="row">
    <form  role="form" action='/bookings' name='package_form' id='package_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="quote_id" value="{{isset($quote)&&$quote->quote_id!=''?$quote->quote_id:''}}">

        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                  
                </div><!-- /.box-header -->
 
                <div class="box-body">
<!--                @if(isset($quote)&&$quote->quote_id!='')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="InputTitle">Quote</label>
                                <div class="form-group">
                                    <select id="quote_id" name="quote_id" class="form-control">
                                        <option value="">Please Select</option>
                                        @foreach($quotes as $p)
                                        <option {{$quote->quote_id==$p->quote_id?'selected="selected"':''}} value="{{$p->quote_id}}">{{$p->quote_title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
@endif-->
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="InputTitle">Booking Title</label>
                                <div class="form-group">
                                    <input type="text" placeholder="Booking Title" id="quote_title" name="booking_title" class="form-control" value="{{Input::old('quote_title')}}">
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
                                        <option  value="{{$c->contact_first_name.$c->contact_last_name}}">{{$c->contact_first_name.$c->contact_last_name}}</option>
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
                                    <input type="text" placeholder="Booking Cost" id="package_cost" name="package_cost" class="form-control" value="{{isset($quote)&&$quote->quoted_cost!=''?$quote->quoted_cost:''}}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="InputTitle">Discount (if any)</label>
                                <div class="form-group">
                                    <input type="text" placeholder="Discount" id="discount" name="discount" class="form-control" value="{{Input::old('discount')}}">
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
                                    <input type="text" readonly placeholder="Total Cost" id="quoted_cost" name="booking_cost" class="form-control" value="" readonly>
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
    <div class="row">
                         <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Number Of Adults</label>
                                <input type="text"  id="number_of_adults" name="number_of_adults" class="form-control" value="{{isset($quote)&&$quote->number_of_adults!=''?$quote->number_of_adults:''}}">
                             
                            </div>
                        </div>   
                           
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Number Of Children</label>
                                <input type="text"  id="number_of_children" name="number_of_children" class="form-control" value="{{isset($quote)&&$quote->number_of_children!=''?$quote->number_of_children:''}}">
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
                                <textarea description  id="description" name="description" class="form-control" value="">{{isset($quote)&&$quote->package_desc!=''?$quote->package_desc:''}}</textarea>
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
                                    @if(isset($quote)&&$quote->quote_id!='')
                                    @foreach($location_from as $l)
                             <option {{$l->location_id==Input::old('location_from', $quote->location_from)?'selected="selected"':''}} value="{{$l->location_id}}">{{$l->location_name}} </option>
                                    @endforeach
                                    @else
                                    @foreach($location_from as $l)
                                    <option  value="{{$l->location_id}}">{{$l->location_name}} </option>
                                    @endforeach
                                    @endif
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
                                         @if(isset($quote)&&$quote->quote_id!='')
                                             @foreach($location_from as $l)
                                             <option {{$l->location_id==Input::old('location_to',$quote->location_to)?'selected="selected"':''}} value="{{$l->location_id}}">{{$l->location_name}} </option>
                                              @endforeach
                                              @else
                                              @foreach($location_from as $l)
                                              <option value="{{$l->location_id}}">{{$l->location_name}} </option>
                                              @endforeach
                                              @endif
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


