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
    <form  role="form" action='/permission/{{$permission->permission_id}}' name='permission_form' id='permission_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="_method" value="PUT">


        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                </div><!-- /.box-header -->
                  <div class="box-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="InputTitle">Component</label>
                            <select class="form-control" name="component" id="component">
                            <option value=''>Select Component</option>
                                <option {{$permission->component=='cp'?'selected="selected"':''}} value='cp'>Control Pannel</option>
                            </select> 
                           
                              </div>
                    </div>
                    </div>
                    
                    <div class="row">
                       <div class="col-md-8">
                           <div class="form-group">
                              <label for="InputTitle">Elements</label>
                              <input type="text" placeholder="Ex: demo " id="element" name="element" class="form-control required"  value="{{$permission->element}}">
                            </div>
                       </div>
                   </div>
                   
                     <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="InputTitle">Title</label>
                            <select class="form-control" name="title" id="title">
                            
                           <option value=''>Select Title</option>
                                <option {{$permission->title=='manage'?'selected="selected"':''}} value='manage'>Manage</option>
                            </select> 
                           
                              </div>
                    </div>
                    </div>
                   
                  </div><!--box body-->
                  
                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>

@stop