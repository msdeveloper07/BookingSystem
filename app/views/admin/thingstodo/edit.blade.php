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
    <form  role="form" action='/thingstodo/{{$thingstodo->thing_todo_id}}' name='customer_form' id='customer_form' method="post">
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
                              <label for="InputTitle">Thing To Do</label>
                              <input type="text" placeholder="Enter Thing" id="thingstodo" name="thingstodo" style="text-transform:Capitalize" class="form-control required"  value="{{$thingstodo->thing_todo}}">
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