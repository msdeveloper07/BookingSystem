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
    <form  role="form" action='/suppliertypeitem' name='supplier_type_form' id='supplier_type_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        <div class="col-md-10">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                   
                </div><!-- /.box-header -->

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

                                            <option value="{{$s->supplier_type_id}}">{{$s->supplier_type}} </option>
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
                                            <select class="form-control" name="supplier_sub_type_id[]" id="supplier_sub_type_id_1">
                                                <option value="">Please Select</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="InputTitle">Supplier Item</label>
                                        <input type="text" placeholder="Enter Supplier Item" id="supplier_item_name" name="supplier_item_name[]" class="form-control" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="InputTitle">Supplier Item Values</label>
                                        <input type="text" class="form-control" data-role="tagsinput" id="keywords" value="{{Input::old('keywords')}}" name="keywords[]" placeholder="separated by comma(,) "/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="InputTitle">Value Type </label>
                                        <select class="form-control" name="field_type[]" id="field_type">
                                            <option value="single">Single</option>
                                            <option value="multiple">Multiple</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div id="append_data"></div>

                    <div class="row">
                        <div class="col-md-4" style='float:right;'>
                            <a id="more"  href='javascript:void(0)'><i class='fa fa-plus'></i>add new</a>             
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

 <script src="http://booking_system.dev/assets/js/bootstrap-tagsinput2.min.js" type="text/javascript"></script>
    <div id="div_repeat_index"  class="holder_div">

        <hr/>
        <div class="row">
            <div class="col-md-7">
                <div class="form-group">
                    <label for="InputTitle">Supplier Type</label>
                    <select class="form-control" name="supplier_type_id[]" onchange="loadSubTypes(this.value, 'index')" id="supplier_type_id_index">
                        <option value="">Select Supplier Type</option>

                        @foreach($suppliertype as $s)

                        <option value="{{$s->supplier_type_id}}">{{$s->supplier_type}} </option>
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
                        <select class="form-control" name="supplier_sub_type_id[]" id="supplier_sub_type_id_index">
                            <option value="">Please Select</option>

                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7">
                <div class="form-group">
                    <label for="InputTitle">Supplier Item</label>
                    <input type="text" placeholder="Enter Supplier Item" id="supplier_item_name" name="supplier_item_name[]" class="form-control" value="">
                </div>
            </div>
        </div>
       <div class="row">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="InputTitle">Supplier Item Values</label>
                                        <input type="text" class="form-control" data-role="tagsinput" id="keywords" value="{{Input::old('keywords')}}" name="keywords[]" placeholder="separated by comma(,) "/>
                                    </div>
                                </div>
                            </div>
        <div class="row">
            <div class="col-md-7">
                <div class="form-group">
                    <label for="InputTitle">Value Type </label>
                    <select class="form-control" name="field_type[]" id="field_type">
                        <option value="single">Single</option>
                        <option value="multiple">Multiple</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>


@stop