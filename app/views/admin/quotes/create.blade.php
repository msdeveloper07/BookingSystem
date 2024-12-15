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
                <li class="active"><a href="/quotes/create">General</a></li>
            </ul>
        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->
</div>

<div class="row">
    <form  role="form" action='/quotes' name='package_form' id='package_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="package_id" value="{{isset($package)&&$package->package_id!=''?$package->package_id:''}}">

        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                  
                </div><!-- /.box-header -->
 
                <div class="box-body">
                @if(isset($package)&&$package->package_id!='')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="InputTitle">Package</label>
                                <div class="form-group">
                                    <select id="package_id" name="package_id" class="form-control">
                                        <option value="">Please Select</option>
                                        @foreach($packages as $p)
                                        <option {{$package->package_id==$p->package_id?'selected="selected"':''}} value="{{$p->package_id}}">{{$p->package_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
@endif
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="InputTitle">Quote Title</label>
                                <div class="form-group">
                                    <input type="text" placeholder="Quote Title" id="quote_title" name="quote_title" class="form-control" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                                 
                   <div class="row">
                        <div class="col-md-9">
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
                       
                          <div class="col-md-2">
                            <div class="form-group">
                                <label for="InputTitle">&nbsp; </label>
                               <input type="button" data-toggle="modal"   data-target="#myModal" id="add_new_contact" class="btn btn-info" value="Add New Contact" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="InputTitle">Quote Cost</label>
                                <div class="form-group">
                                    <input type="text" placeholder="Quote Cost" id="package_cost" name="package_cost" class="form-control" value="{{isset($package)&&$package->total_cost!=''?$package->total_cost:''}}">
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
                                    <input type="text" readonly placeholder="Quoted Cost" id="quoted_cost" name="quoted_cost" class="form-control" value="" readonly>
                                </div>
                            </div>
                        </div>
                        
                           <div class="row">
                         <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Number Of Adults</label>
                                <input type="text"  id="number_of_adults" name="number_of_adults" class="form-control" value="{{isset($package)&&$package->number_of_adults!=''?$package->number_of_adults:''}}">
                             
                            </div>
                        </div>   
                           
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Number Of Children</label>
                                <input type="text"  id="number_of_children" name="number_of_children" class="form-control" value="{{isset($package)&&$package->number_of_children!=''?$package->number_of_children:''}}">
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
                        
                        
                    </div>
                    <div class="row">
                         <div class="col-md-12">
                            <div class="form-group">
                                <label for="InputTitle">Package Description</label>
                                <textarea description  id="description" name="description" class="form-control" value="">{{isset($package)&&$package->package_desc!=''?$package->package_desc:''}}</textarea>
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
                                    @if(isset($package)&&$package->package_id!='')
                                    @foreach($location_from as $l)
                             <option {{$l->location_id==Input::old('location_from', $package->location_from)?'selected="selected"':''}} value="{{$l->location_id}}">{{$l->location_name}} </option>
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
                                         @if(isset($package)&&$package->package_id!='')
                                             @foreach($location_from as $l)
                                             <option {{$l->location_id==Input::old('location_to',$package->location_to)?'selected="selected"':''}} value="{{$l->location_id}}">{{$l->location_name}} </option>
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


 


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Create New Contact</h4>
      </div>
        <form  role="form" action='/quotes/contacts/{{$quote_id = 'new'}}' name='new_contact_form' id='new_contact_form' method="post">
              <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
      <div class="modal-body">
          
            <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">First Name</label>
                                <input type="text" placeholder="" required id="contact_first_name" name="contact_first_name" class="form-control"  value="{{Input::old('contact_first_name');}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Last Name</label>
                                <input type="text" placeholder="" required id="contact_last_name" name="contact_last_name" class="form-control"  value="{{Input::old('contact_last_name');}}">
                            </div>
                        </div>
                    </div>
          <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Job Title</label>
                                <input type="text"  placeholder="" id="job_title" name="job_title" class="form-control" value="{{Input::old('job_title');}}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row" id="container-emails">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Email</label>
                                  <small class="text-warning">(Enter either email or phone number or both) </small>
                                  <input type="email" placeholder="" required id="contact_email" name="contact_email" class="form-control email-address"  value="{{Input::old('contact_email');}}">
                                
                            </div>
                        </div>
                   </div>
                        
                  
                        
                    <div class="row" id="container-phones">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Phone</label>
                                <input type="text" placeholder="" required id="contact_phone" name="contact_phone" class="form-control phone-numbers" value="{{Input::old('contact_phone');}}">
                            </div>
                        </div>
                    </div>
                    
                 
                        
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Mobile</label>
                                <input type="text" placeholder=""  id="contact_mobile" name="contact_mobile" class="form-control" value="{{Input::old('contact_mobile');}}">
                            </div>
                        </div>
                         <div class="col-md-3"> 
                            <div class="form-group">
                                <label for="InputTitle">Fax</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-fax"></i>
                                    </div>
                                    <input type="text" id="contact_fax" name="contact_fax" class="form-control"  value="{{Input::old('contact_fax');}}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                   
                 
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
        </form>
    </div>
  </div>
</div>
@stop


