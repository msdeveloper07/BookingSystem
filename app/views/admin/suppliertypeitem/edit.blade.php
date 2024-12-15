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
    <form  role="form" action='/suppliertypeitem/{{$suppliertypeitem->supplier_type_item_id}}' name='supplier_type_form' id='supplier_type_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
             <input type="hidden" name="_method" value="PUT">

        <div class="col-md-10">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                    <div style='float:right;'>
                        <a class="btn btn-success" href='/suppliertypeitem'>Back To Supplier Items</a>             
                    </div>
                </div><!-- /.box-header -->
         <?php    $vak = $suppliertypeitem->supplier_type_id;  ?>
        <?php     $supp = SupplierType::where('supplier_type_id',$vak)->pluck('supplier_type_parent_id');
   
        ?>
             
             
             
                <div class="box-body" >

                    <div id="box_body1">

                        <div id="div_repeat_1" class="holder_div">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="InputTitle">Supplier Type</label>
                                        <select class="form-control" name="supplier_type_id[]" id="supplier_type_id_1" onchange="loadSubTypes(this.value, '1')">
                                            <option value="">Select Supplier Type</option>
                                            @foreach($suppliertype as $s)

                                            <option {{$supp==$s->supplier_type_id?'selected="selected"':''}} value="{{$s->supplier_type_id}}">{{$s->supplier_type}} </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="InputTitle">Supplier Sub Type</label>
                                        <div class="form-group">
                                            
                                                
                                            <?php  $suppli = SupplierType::where('supplier_type_parent_id',$supp)->get();                                       ?>
                                            
                                            <select class="form-control" name="supplier_sub_type_id[]" id="supplier_sub_type_id_1">
                                                <option value="">Please Select</option>
                                                 @foreach($suppli as $s)
                                                 <option {{$suppliertypeitem->supplier_type_id==$s->supplier_type_id?'selected="selected"':''}}  value="{{$s->supplier_type_id}}">{{$s->supplier_type}}</option>
                                                 @endforeach
                                            </select>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="InputTitle">Supplier Item</label>
                                        <input type="text" placeholder="Enter Supplier Item" id="supplier_item_name" name="supplier_item_name[]" class="form-control" value="{{$suppliertypeitem->supplier_item_name}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="InputTitle">Supplier Item Values</label>
                                        <input type="text" class="form-control" data-role="tagsinput" id="keywords" value="{{$suppliertypeitem->value}}" name="keywords[]" placeholder="separated by comma(,) "/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="InputTitle">Value Type </label>
                                       
                                        <select class="form-control" name="field_type[]" id="field_type">
                                           
                                            <option {{$suppliertypeitem->field_type=='single'?'selected="selected"':''}} value="single">Single</option>
                                            <option {{$suppliertypeitem->field_type=='multiple'?'selected="selected"':''}} value="multiple">Multiple</option>
                                           
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div id="append_data"></div>

                  
                </div><!-- /.box-body -->


                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>

@stop