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
    <form  role="form" action='/leads/{{$contacts->contact_id}}/update' name='contact_form' id='contact_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        
        


        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                     <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">First Name</label>
                                <input type="text" required placeholder="" id="contact_first_name" name="contact_first_name" class="form-control"  value="{{$contacts->contact_first_name}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Last Name</label>
                                <input type="text" required placeholder="" id="contact_last_name" name="contact_last_name" class="form-control"  value="{{$contacts->contact_last_name}}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Job Title</label>
                                <input type="text"  placeholder="" id="job_title" name="job_title" class="form-control" value="{{$contacts->job_title}}">
                            </div>
                        </div>
                    </div>
                        
                       
                       
                    
                    <div class="row" id="container-emails">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Email</label>
                                 
                                <input type="email" required  placeholder="" id="contact_email" name="contact_email[]" class="form-control email-address"  value="{{$contacts->contact_email}}">
                                
                            </div>
                        </div>
                   </div>
                        
                    
                        
                    <div class="row" id="container-phones">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Phone</label>
                                <input type="text" required placeholder="" id="contact_phone" name="contact_phone[]" class="form-control phone-numbers" value="{{$contacts->contact_phone}}">
                            </div>
                        </div>
                    </div>
                    
                    
                        
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Mobile</label>
                                <input type="text" placeholder="" id="contact_mobile" name="contact_mobile" class="form-control" value="{{$contacts->contact_mobile}}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Facebook</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-facebook-square"></i></span>
                                    <input type="text" placeholder="" id="facebook_link" name="facebook_link" class="form-control" value="{{$contacts->facebook_link}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Twitter</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-twitter-square"></i></span>
                                    <input type="text" placeholder="" id="twitter_link" name="twitter_link" class="form-control" value="{{$contacts->twitter_link}}">
                                </div>
                            </div>
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