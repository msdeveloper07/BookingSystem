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
      <h3 class="text-info">{{$brands->brand_title}}</h3>
      <br/>
      <br/>
      
        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->



<div class="row">
    <div class="col-md-12">
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active" ><a href="/brands/{{$brands->brand_id}}/edit">Edit</a></li>
                <li ><a href="/brands/settings/{{$brands->brand_id}}">Settings</a></li>
           
            </ul>
        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->
</div>


<div class="row">
    <form  role="form" action='/brands/{{$brands->brand_id}}' name='user_form' id='user_form' method="post">
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
                            <label for="InputTitle">Brand Title</label>
                            <input type="text" placeholder="Enter Brand Name" id="brand_title" name="brand_title" class="form-control required" value="{{$brands->brand_title}}">
                        </div>
                    </div>
                    </div>
                    <div class="row">
                       <div class="col-md-8">
                           <div class="form-group">
                              <label for="InputTitle">Brand Description</label>
                              <textarea placeholder="Enter Description Here" id="brand_desc" name="brand_desc" class="form-control required" value="">{{$brands->description}}</textarea>
                                                          </div>
                       </div>
                   </div>
                    <div id="brand-image" class="row">
                       <div class="col-md-8">
                           <div class="form-group">
                              <label for="InputTitle">Brand Image</label>
                               <img src="<?php echo url().'/brandimages/'.$brands->logo; ?>" style="width:100px">
                                                          </div>
                       </div>
                   </div>
              
                
                <div id="upload-doc-div" class="row">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <label for="InputStatus">Update Brand Image: </label>
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
              
                
                      
                    
                    
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>

@stop