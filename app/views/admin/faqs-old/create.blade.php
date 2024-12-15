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
    <form  role="form" action='/faq' name='user_form' id='user_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="InputTitle">Question</label>
                            <input type="text" placeholder="Enter Question" id="question" name="question" class="form-control required" value="">
                        </div>
                    </div>
                    </div>
                    <div class="row">
                       <div class="col-md-8">
                           <div class="form-group">
                              <label for="InputTitle">Answer</label>
                              <textarea placeholder="Enter Answer Here" id="answer" name="answer" class="form-control required" value=""></textarea>
                                                          </div>
                       </div>
                   </div>
                   
                   
<!--                   <div class="row" > 
                        <div class="col-md-6">
                            <div class="form-group"> 
                                <label for="InputEmail">User Status</label>
                                <label class="radio-inline">
                                    <input type="radio" name="user_status" value="active"   id="user_status_active"  checked="checked">Active
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="user_status" value="deactive" id="user_status_active" >Deactive
                                </label>


                            </div>
                        </div>
                    </div>-->
                     
                    
                    
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>

@stop