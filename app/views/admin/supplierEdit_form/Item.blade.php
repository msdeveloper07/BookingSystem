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
                <li class="active"><a href="/supplieritems/{{$supplier->supplier_id}}">Supplier Item</a></li>

                <li><a href="/payment_method/{{$supplier->supplier_id}}">Payment Method</a></li>
                <li><a href="/Commission/{{$supplier->supplier_id}}">Commission</a></li>


            </ul>

        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->

</div>


<div class="row">
    <form  role="form" action='/supplier_item_update/{{$supplier->supplier_id}}' name='supplier_type_form' id='supplier_type_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">



        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                    <div style='float:right;'>
                        <a class="btn btn-success" href='/supplier'>Back To Supplier</a>             
                    </div>
                </div><!-- /.box-header -->

                <div class="box-body">

                    <div id="box_body1">
                        <?php $i = 1;
                        for ($j = 0; $j < $count; $j++) {
                            ?>
                        
                            <div id="div_repeat_{{$i}}" class="holder_div">
                                <hr />
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="InputTitle">Supplier Type</label>
                                            <select class="form-control" id="supplier_type_id_{{$i}}" onchange="loadItems(this.value,{{$i}})" name="supplier_type[]" >
                                                <option value="">select supplier type</option>

                                                @foreach($suppliertype as $s)
                                                <option {{$supplieritem[$j]->supplier_type_id==$s->supplier_type_id?"selected='selected'":""}} value="{{$s->supplier_type_id}}">{{$s->supplier_type}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="InputTitle">Supplier Item</label>
                                            <select class="form-control" id="supplier_item_id_{{$i}}" name="supplier_item[]" >
                                                <option value="">Select Item</option>
    <?php $supplier_type_item = SupplierTypeItem::where('supplier_type_id', $supplieritem[$j]->supplier_type_id)->get(); ?>
                                                @foreach($supplier_type_item as $st)
                                                <option {{$supplieritem[$j]->supplier_type_item_id==$st->supplier_type_item_id?"selected='selected'":""}} value="{{$st->supplier_type_item_id}}">{{$st->supplier_item_name}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="InputTitle">Add New Supplier Item</label>
                                            <input type="text" name="supplier_new_item[]" value="" class="form-control">
                                        </div>
                                    </div>





                                </div>   <!--  <div class="row">     -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="InputTitle">Enter Cost</label>
                                            <input type="text" id="cost_{{$i}}" name="cost[]" value="{{$supplieritem[$j]->cost}}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                
                                <a href="javascript:void(0);" onclick="removeItem({{$i}});"><i class="fs fa-times"></i>&nbsp;Remove</a>
                            </div>
                            
    <?php $i++;
} ?>

                    </div>
                    <div id="append_data"></div>

                    <div class="row">
                        <div class="col-md-4" style='float:right;'>
                            <a id="more"  href='#'><i class='fa fa-plus'></i>Add New</a>             
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
<div id="add_new_template" style="display:none">

    <div id="div_repeat_index" class="holder_div"> 
        <hr />
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="InputTitle">Supplier Type</label>
                    <select class="form-control" id="supplier_type_id_index" onchange="loadItems(this.value, 'index')" name="supplier_type[]">
                        <option value="">select supplier type</option>

                        @foreach($suppliertype as $s)
                        <option value="{{$s->supplier_type_id}}">{{$s->supplier_type}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="InputTitle">Supplier Item</label>
                    <select class="form-control" id="supplier_item_id_index" name="supplier_item[]" >
                        <option value="">Select Item</option>



                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="InputTitle">Add New Supplier Item</label>
                    <input type="text"  name="supplier_new_item[]" value="" class="form-control">
                </div>
            </div>





        </div>   <!--  <div class="row">     -->
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="InputTitle">Enter Cost</label>
                    <input type="text" id="cost_index" name="cost[]" value="" class="form-control">
                </div>
            </div>
        </div>

        <a href="javascript:void(0);" onclick="removeItem('index');"><i class="fs fa-times"></i>&nbsp;Remove</a>
    </div> 
    
</div>              




@stop