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
    <form  role="form" action='/suppliertypeitem/{{$suppliertypeitem->supplier_type_item_id}}' name='user_form' id='user_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="_method" value="PUT">


        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                    <div style='float:right;'>
                            <a class="btn btn-success" href='/suppliertypeitem'>Back To Supplier Items</a>             
                        </div>
                </div><!-- /.box-header -->

               <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Type</label>
                                
                                <select class="form-control" name="supplier_type_id[]" id="supplier_type_id_1" onchange="loadSubTypes(this.value,'1')" >
                                    <option value="">Select Supplier Type</option>
                                  
                                    @foreach($suppliertype as $s)
                                    
                                    @if($suppliertypeitem->supplier_type_parent_id != 0)
                                    <option {{$s->supplier_type_id==$suppliertypeitem->supplier_type_parent_id?'selected="selected"':''}} value="{{$s->supplier_type_id}}">{{$s->supplier_type}} </option>
                                    
                                    @else
                                    <option {{$s->supplier_type_id==$suppliertypeitem->supplier_type_id?'selected="selected"':''}} value="{{$s->supplier_type_id}}">{{$s->supplier_type}} </option>
                                    @endif
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Item</label>
                                <input type="text" placeholder="Supplier Type" id="supplier_item_name" name="supplier_item_name[]" class="form-control" value="{{$suppliertypeitem->supplier_item_name}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Sub Type</label>
                                   <div class="form-group">
                                    <select class="form-control" name="supplier_sub_type_id[]" id="supplier_sub_type_id_1" >
                                        <option value="">Please Select</option>
                                    
                                        @foreach($suppliersubtype as $s)
                                    
                                
                                        <option {{$s->supplier_type_id==$suppliertypeitem->supplier_type_id?'selected="selected"':''}} value="{{$s->supplier_type_id}}">{{$s->supplier_type}} </option>
                                 
                                    @endforeach
                                    </select>
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