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
   
       <form  role="form" action='/paymenttypes/{{$paymenttype->payment_type_id}}' name='payment_type_form' id='payment_type_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="_method" value="PUT">

<div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                       <div style='float:right;'>
                            <a class="btn btn-success" href='/paymenttypes'>Back To PaymentTypes</a>             
                        </div>
                </div><!-- /.box-header -->
                 <div class="box-body">
                      <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="InputTitle">Payment Type</label>
                            <input type="text" placeholder="Enter PaymentType" id="payment_type" name="payment_type" class="form-control required" value="{{$paymenttype->payment_type}}">
                        </div>
                    </div>
                          
                     </div>
                
                    
                   
                     
                         <div class="row" > 
                        <div class="col-md-6">
                            <div class="form-group"> 
                                 <label for="InputTitle">Payment Status</label>
                                <label class="radio-inline">
                                    <input type="radio" name="payment_status" {{$paymenttype->payment_status=='active' ? 'checked="checked"':''}} value="active"   id="payment_status"  checked="checked">Active
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="payment_status" {{$paymenttype->payment_status=='deactive' ? 'checked="checked"':''}} value="deactive" id="payment_status" >Deactive
                                </label>


                            </div>
                        </div>
                    </div>
                 
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <button class="btn btn-primary" type="submit" >Update</button>
                </div>
            </div>
        </div>
    </form>
</div>

@stop