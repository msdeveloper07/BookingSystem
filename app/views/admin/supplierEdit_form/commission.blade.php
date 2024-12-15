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
                                    <li><a href="/suppliercontacts_edit/{{$supplier->supplier_id}}">Contact</a></li>
                                    <li><a href="/supplieritems_edit/{{$supplier->supplier_id}}">Supplier Item</a></li>
                                    <li><a href="/payment_method_edit/{{$supplier->supplier_id}}">Payment Method</a></li>
                                     <li class="active"><a href="/Commission_edit/{{$supplier->supplier_id}}">Commission</a></li>

                                </ul>
                               
                            </div><!-- nav-tabs-custom -->
                        </div><!-- /.col -->

                    </div>


<div class="row">
    <form  role="form" action='/commission_update/{{$supplier->supplier_id}}' name='commission_form' id='commission_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Commission Details</h3>
                    <div style='float:right;'>
                            <a class="btn btn-success" href='/supplier'>Back To Supplier</a>             
                        </div>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Commission Of Item in %</label>
                                <?php
                                $commission_item = Commission::where('supplier_id',$supplier->supplier_id)->first();
                             
                                ?>
                                <input type="text" id="citem" name="citem" value="{{$commission_item->commision_of_item}}" placeholder="enter commission in %">
                            </div>
                        </div>
                    </div>
                     <?php
                                $commission_item = Commission::where('supplier_id',$supplier->supplier_id)->first();
                                
                              
                             
                                ?>
                  
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Select Payment Method</label>
                                </br>
                                <input type="radio" id="paypal" name="paypal" value="1" <?php if($commission_item->payment_method=='paypal') { echo "checked"; } ?>   > Pay Pal </br>
                              
                               <input type="radio" id="paypal" name="paypal" value="2" <?php if($commission_item->payment_method=='credit_card') { echo "checked"; } ?>   >Credit Card
                            </div>
                        </div>
                    </div>
            </div><!-- /.box-body -->
            
          
            <div class="box-footer">

                <button class="btn btn-primary" type="submit">Update</button>
                         

            </div>
            
            

            </div>

        </div>
    </form>
</div>
               
                 
               
                                    
      
@stop