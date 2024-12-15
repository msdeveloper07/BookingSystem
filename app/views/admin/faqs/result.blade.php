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
    
<!--        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="_method" value="PUT">-->


        <div class="col-md-10">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                    <div class="col-md-8">
                      <div class="form-group">
                            <label for="InputTitle">Question</label>
                            <input type="text"  id="question" name="question" class="form-control required" readonly value="{{$faq->question}}">
                        </div>
                         
                    </div>
                    </div>
                    <div class="row">
                       <div class="col-md-8">
                            <div class="form-group">
                              <label for="InputTitle">Answer</label>
                              <textarea placeholder="Enter Answer Here" id="answer" name="answer" class="form-control required" readonly value="{{$faq->answer}}">{{$faq->answer}}</textarea>
                            </div>
                       </div>
                   </div>
                   
                  
                </div>

                <div class="box-footer">
                       <a href="/faq/{{$faq->faq_id}}/edit/" title="Edit"> <button class="btn btn-primary" type="submit">Edit</button></a>
                
                </div>
            </div>
        </div>
   
</div>

@stop