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
    <form  role="form" action='/todolist' name='user_form' id='user_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                       <div style='float:right;'>
                            <a class="btn btn-success" href='/todolist'>Back To ToDoLists</a>             
                        </div>
                </div><!-- /.box-header -->
                 <div class="box-body">
                      <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="InputTitle">To DoList Question</label>
                            <input type="text" placeholder="Enter ToDoList Question" id="question" name="question" class="form-control required" value="">
                        </div>
                    </div>
                          
                     </div>
                
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <button class="btn btn-primary" type="submit" >Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>

@stop