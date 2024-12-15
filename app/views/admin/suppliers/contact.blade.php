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
    <div class="col-md-12">
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="/supplier/{{$supplier->supplier_id}}/edit">General</a></li>
                <li class="active"><a href="/supplier/contacts/{{$supplier->supplier_id}}">Contact</a></li>
                 
                 <li><a href="/payment_method/{{$supplier->supplier_id}}">Payment Method</a></li>
                 <li><a href="/Commission/{{$supplier->supplier_id}}">Commission</a></li>
        </div>   
    </div>

</div>
<div class="row">
    <form  role="form" action='/supplier/contact/{{$supplier->supplier_id}}' name='Contact_form' id='Contact_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Contact</h3>
                  
                </div><!-- /.box-header -->

                <div class="box-body">
                    
                  
                    
                    <?php
                    if($supplier->address_id=='')
                    {
                        $address = new stdClass();    
                        $address->address = $address->city = $address->state = $address->country = $address->telephone = $address->fax = $address->latitude = $address->longitude = $address->official_email = '';
                    }
                    
                    ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Address</label>
                                <input type="text" id="address" name="address" value="{{Input::old('address',$address->address)}}" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">City</label>
                                <input type="text" id="city" name="city" value="{{Input::old('city',$address->city)}}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">State</label>
                                <input type="text" id="state" name="state" value="{{Input::old('state',$address->state)}}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Country</label>
                                <input type="text" id="country" name="country" value="{{Input::old('country',$address->country)}}" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Telephone</label>
                                <input type="text" id="telephone" name="telephone" value="{{Input::old('telephone',$address->telephone)}}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Fax</label>
                                <input type="text" id="fax" name="fax" value="{{Input::old('fax',$address->fax)}}" class="form-control">
                            </div>
                        </div>
                    </div>
                   
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Official Email</label>
                                <input type="text" id="official_email" name="official_email" value="{{Input::old('official_email',$address->official_email)}}" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    
                  </div><!-- /.box-body -->
            
          
            <div class="box-footer">

                <button class="btn btn-primary" type="submit">Submit</button>
                &nbsp;
           
            </div>
            
            

            </div>

        </div>
    </form>
</div>
               
                 
               
                                    
       
@stop