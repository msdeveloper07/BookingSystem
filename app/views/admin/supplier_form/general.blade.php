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
    <form  role="form" action='/supplier/supplierStore' name='supplier_type_form' id='supplier_type_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                  
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Name</label>
                                <input type="text" id="supplier_name" name="supplier_name" value="" placeholder="Enter Supplier Name" class="form-control">
                            </div>
                        </div>
                    </div>
                   
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Type</label>
                                   <div class="form-group">
                                    <select class="form-control" name="supplier_type_id"  id="supplier_type_id">
                                        <option value="">Please Select</option>
                                        <?php $supplier_types = SupplierType::where('supplier_type_parent_id','0')->get(); ?>
                                        @foreach($supplier_types as $l)
                                            <option value="{{$l->supplier_type_id}}">{{$l->supplier_type}} </option>
                                        @endforeach
                                    </select>
                             </div>
                            </div>
                        </div>
                    </div>
                    
                     <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Sub Type</label>
                                   <div class="form-group">
                                    <select class="form-control" name="supplier_sub_type_id" id="supplier_sub_type">
                                        <option value="">Please Select</option>
                                     
                                    </select>
                             </div>
                            </div>
                        </div>
                    </div>
                     



                     <!--  <div class="row">     -->
                  
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