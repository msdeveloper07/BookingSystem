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
    <div class="col-md-4">
        <form  role="form" action='/settings' name='settings_form' id='settings_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Create New Setting</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-info">Use underscore(_)to in place of white spaces</p>
                        </div>
                     </div>
                    <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="InputTitle">Setting Name</label>
                            <input type="text" id="setting_name" name="setting_name" class="form-control required" value="{{Input::old('setting_name');}}">
                        </div>
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
    
    <div class="col-md-8">
         <form  role="form" action='/settings/update' name='settings_form' id='settings_form' method="post">
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
                                @foreach($settings as $s)
                                <tr>
                                    <td>{{$s->setting_name}}</td>
                                    <td>
                                        <textarea name="setting_value[{{$s->setting_id}}][]" class="form-control">{{$s->setting_value}}</textarea>
                                    </td>
                                    <td><input type="submit" name="submit" value="Update" class="btn btn-success btn-flat" /></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                   </div>
                </div><!-- /.box-body -->
                 <div class="box-footer">
                  {{$settings->appends(Input::except('page'))->links();}}
                </div>
            </div>
          </form>
    </div>
    
    
</div>

@stop

