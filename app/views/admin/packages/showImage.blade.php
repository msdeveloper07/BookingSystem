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
    <form  role="form" action='/package/{{$packageInfo->package_id}}' name='package_form' id='package_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="_method" value="PUT">


        <div class="col-md-10">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">{{$packageInfo->package_name}} &nbsp; Images</h3>
                     <div style='float:right;'>
                            <a class="btn btn-success" href='/package'>Back To Packages</a>             
                        </div>
                    
                </div><!-- /.box-header -->

                <div class="box-body">
                   <?php
                   $j=0;
                   $i = null;
                   for($i=1; $i <= $package; $i++)  { ?>
                
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="InputTitle"></label>
                                  <div class="form-group">
                                       <img src="/packageimage/{{$package_image[$j]}}">
                                       <a href="/deletePackageImage/{{$packageInfo->package_id}}/{{$package_image_id[$j]}}"  class="btn btn-danger">Delete</a>
                                 </div>
                            </div>
                        </div>
                    
                      
                   <?php  $j++; } ?>
                    
                   </div>
                     </div>
                    
                   </hr>
                    
                   <hr>
                    <div id="upload-doc-div" class="row">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <label for="InputStatus">Upload Package Images: </label>
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
                   
               <!-- /.box-body -->
              
               <div class="box-footer" style="display:none" id="addImage" ><a href="/packageImages/{{$packageInfo->package_id}}">    <button class="btn btn-primary" type="submit">Upload Images</button> </a> </div></div>
        </div>
                   
    </form>
    
</div>

@stop


 