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
                <li class="active"><a href="/supplier/{{$supplier->supplier_id}}/edit">General</a></li>
                <li ><a href="/supplier/contacts/{{$supplier->supplier_id}}">Contact</a></li>
                 
                 <li><a href="/payment_method/{{$supplier->supplier_id}}">Payment Method</a></li>
                 <li><a href="/Commission/{{$supplier->supplier_id}}">Commission</a></li>
        </div>   
    </div>

</div>

<div class="row">
    <form  role="form" action='/supplier/{{$supplier->supplier_id}}' name='supplier_form' id='supplier_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="_method" value="PUT">



 <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                    <div style='float:right;'>
                            <a class="btn btn-success" href='/supplier'>Back To Supplier</a>             
                        </div>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Name</label>
                                <input type="text" id="supplier_name" name="supplier_name" value="{{$supplier->supplier_name}}" placeholder="Enter Supplier Name" class="form-control">
                            </div>
                        </div>
                    </div>
                   
                     <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Type</label>
                                   <div class="form-group">
<!--                                    <select class="form-control" name="supplier_type_id"  id="supplier_type_id">
                                        <option value="">Please Select</option>-->
                                       <div class="btn-group" data-toggle="buttons">
                                        <?php $supplier_types = SupplierType::where('supplier_type_parent_id','0')->get(); ?>
                                        @foreach($supplier_types as $s)
                                        <label class="btn btn-default {{in_array($s->supplier_type_id,$supplier_type_association)?'active':''}}">
                                      <input type="checkbox" autocomplete="off" name="supplier_type_id[]" value="{{$s->supplier_type_id}}" {{in_array($s->supplier_type_id,$supplier_type_association)?'checked':''}} > {{$s->supplier_type}}
                                    </label>&nbsp;
                                        
                                        @endforeach
                                       </div>
                             </div>
                            </div>
                        </div>
                    </div>
                    
                    
                     

                    

                     <!--  <div class="row">     -->
                  
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