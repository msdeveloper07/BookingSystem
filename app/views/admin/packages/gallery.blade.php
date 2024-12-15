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
                <li><a href="/package/{{$package->package_id}}/edit">Edit</a></li>
                <li class="active"><a href="/package/gallery/{{$package->package_id}}">Gallery</a></li>
                <li><a href="/package/features/{{$package->package_id}}">Features</a></li>
                <li><a href="/package/itinerary/{{$package->package_id}}">Itinerary</a></li>
                <li><a href="/package/quotes/{{$package->package_id}}">Quotes</a></li>
            </ul>
        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->
</div>




<div class="row">
    <form  role="form" action='/package/updateGallery' name='package_form' id='package_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="package_id" value="{{$package->package_id}}">

        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                 
                </div><!-- /.box-header -->

                <div class="box-body">
                    
                    @if($package->packageImages->count()>0)
                        <div class="row">
                            @foreach($package->packageImages as $p)
                                <div class="col-md-3" id='package_image_{{$p->package_image_id}}'>
                                    <img src="{{url()."/packageimage/".$p->attachment}}" class="img-thumbnail">
                                    <br />
                                    <a href="javascript:void(0);" onclick="removeImageFromGallery('{{$p->package_image_id}}')" class="text-danger">Remove</a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                 
                    
                    <div id="upload-doc-div" class="row">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <label for="InputStatus">Package Images: </label>
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
                    
                    
                    
                    
                     <!--End Upload Main Image-->
                  
                   
                </div><!-- /.box-body -->
                
                
                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>

@stop


 
    </form>
</div>

@stop