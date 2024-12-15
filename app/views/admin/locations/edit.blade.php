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
    <form  role="form" action='/locations/{{$location->location_id}}' name='user_form' id='user_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="_method" value="PUT">


        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                      
                </div><!-- /.box-header -->

            <div class="box-body">
                      <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="InputTitle">Location Name</label>
                            <input type="text" placeholder="Enter Location" id="loction_name" name="location_name" class="form-control required" value="{{$location->location_name}}">
                        </div>
                    </div>
                          
                     </div>
                
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="InputTitle">State</label>
                            <input type="text" placeholder="Enter State" id="state" name="state" class="form-control required" value="{{$location->state}}">
                        </div>
                    </div>
                        
                        
                
                     </div>
                          <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="InputTitle">Country</label>
                            <select id="country" name="country" class="form-control">
                                <option value="">Please Select</option>
                                @foreach(Config::get('extras.countries') as $c)
                                <option {{$location->country==$c?'selected="selected"':''}} value='{{$c}}'>{{$c}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                  
                
                     </div>
                 
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Update</button>
                </div>
            </div>
        </div>
    </form>
</div>

@stop