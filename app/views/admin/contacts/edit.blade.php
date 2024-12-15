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
    <div class="col-md-8">
        <form  role="form" action='/contacts/{{$contact->contact_id}}' name='contact_form' id='contact_form' method="post">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="_method" value="PUT">

            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="row">

                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="InputTitle">Title</label>
                                <input type="text" placeholder="Job Title for the contact" id="job_title" name="job_title" class="form-control required" value="{{Input::old('job_title',$contact->job_title)}}">
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="InputTitle">First Name</label>
                                <input type="text" size="15" placeholder="Ex: John" id="contact_first_name" name="contact_first_name" class="form-control required" value="{{Input::old('contact_first_name',$contact->contact_first_name)}}">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="InputTitle">Last Name</label>
                                <input type="text" size="15" placeholder="Ex: Miller" id="contact_last_name" name="contact_last_name" class="form-control required"  value="{{Input::old('contact_last_name',$contact->contact_last_name)}}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3"> 
                            <div class="form-group">
                                <label for="InputTitle">Primary Phone</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" id="contact_phone"  name="contact_phone" class="form-control"  value="{{Input::old('contact_phone',$contact->contact_phone)}}" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3"> 
                            <div class="form-group">
                                <label for="InputTitle">Mobile</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" id="contact_mobile" name="contact_mobile" class="form-control" value="{{Input::old('contact_mobile',$contact->contact_mobile)}}" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3"> 
                            <div class="form-group">
                                <label for="InputTitle">Fax</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-fax"></i>
                                    </div>
                                    <input type="text" id="contact_fax" name="contact_fax" class="form-control" value="{{Input::old('contact_fax',$contact->contact_fax)}}" />
                                </div>
                            </div>
                        </div>
                    </div>   
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Email</label>
                                <input type="email" placeholder="Ex: you@abc.com" id="contact_email" name="contact_email" class="form-control"  value="{{Input::old('contact_email',$contact->contact_email)}}">
                            </div>
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




</div>

@stop