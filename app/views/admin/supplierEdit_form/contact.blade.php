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
                <li class="active"><a href="/suppliercontacts_edit/{{$supplier->supplier_id}}">Contact</a></li>
                <li><a href="/supplieritems_edit/{{$supplier->supplier_id}}">Supplier Item</a></li>
                <li><a href="/payment_method_edit/{{$supplier->supplier_id}}">Payment Method</a></li>
                <li><a href="/Commission_edit/{{$supplier->supplier_id}}">Commission</a></li>
        </div>   
    </div>

</div>
<div class="row">
    <form  role="form" action='/suppliercontact_update/{{$supplier->supplier_id}}' name='Contact_form' id='Contact_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Contact</h3>
                    <div style='float:right;'>
                            <a class="btn btn-success" href='/supplier'>Back To Supplier</a>             
                        </div>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Email</label>
                                <input type="text" id="supplier_email" name="supplier_email" value="{{$supplier->supplier_email}}" required class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Phone</label>
                               <input type="text" id="supplier_phone" name="supplier_phone" value="{{$supplier->supplier_phone}}" class="form-control">
                            </div>
                        </div>
                    </div>
                <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Address</label>
                                <textarea placeholder="Enter Supplier Address"id="supplier_address" name="supplier_address" value="" class="form-control">{{$supplier->supplier_address}}</textarea>
                            </div>
                        </div>
                    </div>
                     



                 
                  
            </div><!-- /.box-body -->
            
          
            <div class="box-footer">

                <button class="btn btn-primary" type="submit">Update</button>
                &nbsp;
           
            </div>
            
            

            </div>

        </div>
    </form>
</div>
               
                 
               
                                    
       
@stop