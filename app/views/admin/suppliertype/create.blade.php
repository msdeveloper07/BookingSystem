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
    <form  role="form" action='/suppliertype' name='supplier_type_form' id='supplier_type_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                    <div style='float:right;'>
                            <a class="btn btn-success" href='/suppliertype'>Back To Supplier Type</a>             
                        </div>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="InputTitle">Supplier Type</label>
                            <input type="text" placeholder="Supplier Type" id="supplier_type" name="supplier_type" class="form-control" value="">
                        </div>
                    </div>
                    </div>
                    
                     <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="InputTitle">Supplier Parent Type</label>
                            <select class="form-control" name="sub_type" id="sub_type">
                                <option value="0">Please select</option>
                                @foreach($supplier as $s)
                                <option  value="{{$s->supplier_type_id}}">{{$s->supplier_type}}</option>
                                
                                @endforeach
                            </select>
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