@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif

@if ( $errors->count() > 0 )

<p>The following errors have occurred:</p>
<ul>
    @foreach($errors->all() as $m)
    <li><span class="text-danger">{{$m}}</span></li>
    @endforeach
</ul>

@endif


<div class="row">
    <div class="col-md-5">
        <form  role="form" action='/brandsettings' name='settings_form' id='settings_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Create New Brand Setting</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-info">Use underscore(_)to in place of white spaces</p>
                        </div>
                     </div>
                       <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="InputTitle">Brand Title</label>
                            <select name="brand_title" id="brand_title" class="form-control">
                            <option value="">Please Select</option>
                                @foreach($brands as $b)
                            <option value="{{$b->brand_id}}">{{$b->brand_title}}</option>
                            @endforeach
                            </select>
                            </div>
                    </div>
                  </div>
                    
                    
                    
                    <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="InputTitle">Brand Variable Name</label>
                            <select name="brand_variable" id="brand_variable" class="form-control">
                            <option value="">Please Select</option>
                                @foreach($brand_variable as $b)
                            <option value="{{$b->brand_variable_id}}">{{$b->variable_name}}</option>
                            @endforeach
                            </select>
                            </div>
                    </div>
                         <div class="col-md-1">
                    <button type="button" class="btn btn-primary btn-flat" data-toggle="modal" data-target="#myModal">Add Brand Variable</a>
                </div>
                        
                    </div>
                    
                    <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="InputTitle">Value</label>
                            <input type="text" id="setting_value" name="setting_value" class="form-control required" value="{{Input::old('setting_value');}}">
                        </div>
                    </div>
                    </div>
                    
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
                
            </div>
        
    </form>
        </div>
    
    <div class="col-md-7">
         <form  role="form" action='/brandsettings/update' name='settings_form' id='settings_form' method="post">
         <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        

        
            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title">Saved Settings</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                   <div class='table-responsive'>
                        <table class="table table-hover table-bordered table-striped table-condensed admin-user-table">
                            <thead>
                                <tr>
                                    <th>Setting Name</th>
                                    <th>Value</th>
                                    <th></th>
                                </tr>
                             </thead>
                            <tbody>
                                @foreach($brand_settings as $s)
                                <tr>
                                    <td>{{$s->variable_name->variable_name}}</td>
                                    <td>
                                        <textarea name="setting_value[{{$s->brand_setting_id}}][]" class="form-control">{{$s->values}}</textarea>
                                    </td>
                                    <td><input type="submit" name="submit" value="Update" class="btn btn-success btn-flat" /></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                   </div>
                </div><!-- /.box-body -->
                 <div class="box-footer">
                  {{$brand_settings->appends(Input::except('page'))->links();}}
                </div>
            </div>
          </form>
    </div>
    
    
</div>



<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Create New Brand Variable</h4>
      </div>
        <form  role="form" action='/addvariables/{{$settings = 'brand'}}' name='user_form' id='user_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        
      <div class="modal-body">
          
            <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="InputTitle">Variable Name</label>
                            <input type="text" placeholder="Enter Brand Name" id="variable_name" name="variable_name" class="form-control required" value="">
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

