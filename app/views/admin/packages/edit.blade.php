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
                <li><a href="/package/{{$package->package_id}}/show">General</a></li>
                <li  class="active"><a href="/package/{{$package->package_id}}/edit">Edit</a></li>
                <li><a href="/package/gallery/{{$package->package_id}}">Gallery</a></li>
               <li><a href="/package/features/{{$package->package_id}}">Features</a></li>
                <li><a href="/package/itinerary/{{$package->package_id}}">Itinerary</a></li>
                <li><a href="/package/quotes/{{$package->package_id}}">Quotes</a></li>
            </ul>
        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->
</div>

<div class="row">
    <form  role="form" action='/package/{{$package->package_id}}' name='package_form' id='package_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="_method" value="PUT">


        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                </div><!-- /.box-header -->

                <div class="box-body" id="box_body1">


                    <div id="div_repeat">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="InputTitle">Package Name</label>
                                    <div class="form-group">
                                        <input type="text" placeholder="Package Name" id="package_name" name="package_name" class="form-control" value="{{$package->package_name}}">
                                    </div>
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

                                            <option {{$l->location_id==$package->location_from?'selected="selected"':''}} value="{{$l->location_id}}">{{$l->location_name}} </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="InputTitle">Location To</label>

                                    <div class="form-group">
                                        <select class="form-control" name="location_to" id="location_to">
                                            <option value="">Select Location To</option>
                                            <?php $location_from = Location::all(); ?>
                                            @foreach($location_from as $l)

                                            <option {{$l->location_id==$package->location_to?'selected="selected"':''}} value="{{$l->location_id}}">{{$l->location_name}} </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-3">
                            <div class="form-group">
                                <label for="InputTitle">Number of Days</label>
                                <input type="text"  id="number_of_days" name="number_of_days" class="form-control" value="{{$package->number_of_days}}">
                            </div>
                        </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="InputTitle">Date From</label>
                                    <input type="text"  id="date_from" name="date_from" class="form-control" value="{{ZnUtilities::format_date($package->date_from,'2')}}">
                                </div>
                            </div>

                            <div class="col-md-6" style="display:none">
                                <div class="form-group">
                                    <label for="InputTitle">Date To</label>
                                    <input type="text"  id="date_to" name="date_to" class="form-control" value="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="InputTitle">Number Of Adults</label>
                                    <input type="text"  id="number_of_adults" name="number_of_adults" class="form-control" value="{{$package->number_of_adults}}">
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="InputTitle">Number Of Children</label>
                                    <input type="text"  id="number_of_children" name="number_of_children" class="form-control" value="{{$package->number_of_children}}">
                                </div>
                            </div>

                            <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Package Currency</label>
                                <select name="package_currency" id="package_currency" class="form-control">
                                    <option value="">Please Select</option>
                                    @foreach(Config::get('extras.currencies') as $k=>$c)
                                    <option {{$k==$package->package_currency?'selected="selected"':''}} value="{{$k}}">{{$k}} [{{$c}}]</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>  
                            
                            
                        </div>
<!--                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="InputTitle">Adult Cost</label>
                                    <div class="input-group">
                                    <input type="text"  id="adult_cost" name="adult_cost" class="form-control" value="{{$package->adult_cost}}">
                                    <span class="input-group-addon" id="basic-addon2">per person</span>
                                 </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                            <div class="form-group">
                                    <label for="InputTitle">Child Cost</label>
                                    <div class="input-group">
                                    <input type="text"  id="child_cost" name="child_cost" class="form-control" value="{{$package->child_cost}}">
                                    <span class="input-group-addon" id="basic-addon2">per person</span>
                            </div>
                        </div>  
                            </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                    <label for="InputTitle">Package Total Cost</label>
                                    <div class="input-group">
                                        <input type="text"  id="total_cost" name="total_cost" class="form-control" value="{{$package->total_cost}}" readonly>
                                    </div>
                                </div>
                            </div>
                         
                            
                            
                        </div> -->
                        <div class="row">
                         <div class="col-md-12">
                            <div class="form-group">
                                <label for="InputTitle">Package Description</label>
                                <textarea description  id="description" name="description" class="form-control" value="">{{$package->package_desc}}</textarea>
                            </div>
                        </div> 
                     </div>
                        
                    
                        <div class="row" id="package_main_image_show">
                             <div class="col-md-3">
                                <div class="form-group"> 
                                    <label for="InputStatus">Main Images: </label><br/>
                                    <img class="img img-thumbnail" src="<?php echo url().'/packageimage/'.$package->package_main_image; ?>" /><br />
                                    <a href="#">Upload New</a>
                                </div>
                             </div>
                        </div>
                        
                     <div id="upload-doc-div" class="row" style="display:none">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <label for="InputStatus">Package Main Images: </label>
                                <noscript><div class="alert alert-danger">Javascript Must be Enabled for File Upload.</div></noscript>
                                <input type="file" class="btn btn-flat btn-upload" id="upload-button" name="file" />
                                <input type="hidden" value="" name="specs_location">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group"> 
                                <div id="progress_bar"></div>
                            </div>
                        </div>   
                    </div>

                    <div class="row" style="display:block" id="file_holder">
                        <div class="col-md-8">
                            <div class="form-group"> 
                                <div id="prev_upload">

                                    <table class="table table-striped table-bordered table-hover table-condensed" id="files_table" style="display:none;">
                                        <thead>
                                            <tr>
                                                <th style="width:20%;">Images</th>
                                                <th>Filename</th>
                                                <th style="width:20%;">&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>

                                </div>
                            </div>
                        </div> 
                    </div>
                    <!--Upload Main Image-->
                    
                        
                        
                    </div>


                </div><!-- /.box-body -->


                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Update Package</button>
                </div>
            </div>
        </div>
    </form>
</div>

@stop


