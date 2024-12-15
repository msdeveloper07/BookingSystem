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
                <li ><a href="/supplier/contacts/{{$supplier->supplier_id}}">Contact</a></li>
                 <li><a href="/supplier/items/{{$supplier->supplier_id}}">Supplier Item</a></li>
                 <li class="active"><a href="/payment_method/{{$supplier->supplier_id}}">Payment Method</a></li>
                 <li><a href="/Commission/{{$supplier->supplier_id}}">Commission</a></li>
        </div>   
    </div>

</div>

<div class="row">
    <form  role="form" action='/payment_method_store/{{$supplier->supplier_id}}' name='supplier_type_form' id='supplier_type_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Payment Method</h3>
                    <div style='float:right;'>
                            <a class="btn btn-success" href='/supplier'>Back To Supplier</a>             
                        </div>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Select Payment Method</label>
                                </br>
                                <input type="radio"  name="paypal" value="paypal" checked>Pay Pal </br>
                              
                               <input type="radio"  name="paypal" value="credit_card">Credit Card
                            </div>
                        </div>
                    </div>

<!--                         <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Item</label>
                                <select class="form-control" id="supplier_item_id" name="supplier_item" >
                                  
                                   
                                    
                                </select>
                            </div>
                        </div>
                             
                              <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Add New Supplier Item</label>
                                <input type="text" id="supplier_item_id" name="supplier_item" value="" class="form-control">
                            </div>
                        </div>

                     
                                


                    </div>     <div class="row">     
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Enter Cost</label>
                                <input type="text" id="cost" name="cost" value="" class="form-control">
                            </div>
                        </div>
                    </div>-->

            </div><!-- /.box-body -->
            
          
            <div class="box-footer">

                <button class="btn btn-primary" type="submit">Continue</button>
                         

            </div>
            
            

            </div>

        </div>
    </form>
</div>
               
                 
               
                                    
      
@stop