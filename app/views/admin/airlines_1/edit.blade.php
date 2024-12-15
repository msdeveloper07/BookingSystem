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
               <li  class="active"><a href="/airlines/{{$airline->airline_id}}/edit">General</a></li>
               <li><a href="/airline/editproperties/{{$airline->airline_id}}">Properties</a></li>
              
                
            </ul>
        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->
</div>

<div class="row">
    <form  role="form" action='/airlines/{{$airline->airline_id}}' name='user_form' id='user_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="_method" value="PUT">

<div class="col-md-10">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="InputTitle">Airline Name</label>
                            <input type="text" placeholder="Enter : RR" id="hotel_name" name="airline_name" class="form-control required" value="{{$airline->airline_name}}">
                        </div>
                    </div>
                    </div>
            
                   
                    <div class="row">
                       <div class="col-md-8">
                           <div class="form-group">
                              <label for="InputTitle">Description</label>
                              <textarea placeholder="Enter Text Here" id="description" name="description" class="form-control required" value="">{{$airline->description}}</textarea>
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