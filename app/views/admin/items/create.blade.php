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

        <div class="col-md-10">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="InputTitle">Item Title</label>
                            <input type="text" placeholder="Enter Title" id="title" name="title" class="form-control required" value="">
                        </div>
                    </div>
                    </div>
                    <div class="row">
                       <div class="col-md-6">
                        <div class="form-group"> 
                            <label for="InputType">Field Type</label>
                            <select name="field_type" id="field_type" class="form-control">
                                <option value="">Please select</option>
                                <option value="radio" {{Input::old('field_type','radio')=='radio'?'selected="selected"':''}} >Radio</option>
                                <option value="checkbox"{{Input::old('field_type')=='checkbox'?'selected="selected"':''}} >Checkbox</option>
                                <option value="textfield" {{Input::old('field_type')=='textfield'?'selected="selected"':''}} >Textfield</option>
                                <option value="textarea" {{Input::old('field_type')=='textarea'?'selected="selected"':''}}  >Textarea</option>
                                <option value="select" {{Input::old('field_type')=='select'?'selected="selected"':''}} >Select</option>
                            </select>   
                        </div>
                    </div>
                   </div>
                 
    <div class="row" id="options_field" > 
        <div class="col-md-6">
            <div class="form-group"> 
                <label for="InputEmail">Options
                    <small class="clearfix">Separated by comma</small></label>
                <textarea name="options" id="options" class="form-control" placeholder="option1, option2, option3">{{Input::old("options");}}</textarea>
            </div>
        </div>
    </div>
                    
    <div class="row" > 
        <div class="col-md-6">
            <div class="form-group"> 
                <label for="InputType">Item For</label>
                <select name="item_for" id="item_for" class="form-control">
                    <option value="">Please select</option>
                    <option value="Packages" {{Input::old('item_from')=='Packages'?'selected="selected"':''}} >Packages</option>
                    <option value="Bookings" {{Input::old('item_from')=='Bookings'?'selected="selected"':''}}>Bookings</option>
                    
                </select>   
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