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
               <li  class="active"><a href="/hotels/{{$hotel->hotel_id}}/edit">General</a></li>
               <li><a href="/hotel/editproperties/{{$hotel->hotel_id}}">Properties</a></li>
              
                
            </ul>
        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->
</div>

<div class="row">
    <form  role="form" action='/hotels/{{$hotel->hotel_id}}' name='user_form' id='user_form' method="post">
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
                            <label for="InputTitle">Hotel Name</label>
                            <input type="text" placeholder="Enter Taj Hotel" id="hotel_name" name="hotel_name" class="form-control required" value="{{$hotel->hotel_name}}">
                        </div>
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="InputTitle">Location</label>
                            <input type="text" placeholder="Enter USA" id="location" name="location" class="form-control required" value="{{$hotel->location}}">
                        </div>
                    </div>
                    </div>
                   
                    <div class="row">
                       <div class="col-md-8">
                           <div class="form-group">
                              <label for="InputTitle">Description</label>
                              <textarea placeholder="Enter Text Here" id="description" name="description" class="form-control required" value="">{{$hotel->description}}</textarea>
                                                          </div>
                       </div>
                   </div>
                      <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="InputTitle">Nearest Airport</label>
                            <input type="text" name="nearest_airport" id="nearestair" class="form-control" value="{{$hotel->nearest_airport}}">
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