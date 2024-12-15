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
    <form  role="form" action='/convertCurrency' name='user_form' id='user_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Currency Converter</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Enter Amount</label>
                                <input type="text" placeholder="Enter Amount" id="amount" name="amount" class="form-control required" value="{{isset($amount)?$amount:''}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="InputTitle">From</label>
                                <select class="form-control" id="from" name="from">
                                    <option value="">Select Currency From </option>
                                    @foreach($currency as $c)
                                    <option {{(isset($from)&&$from==$c->iso)?'selected="selected"':''}} value="{{$c->iso}}">{{$c->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="InputTitle">To</label>
                                <select class="form-control" id="to" name="to">
                                    <option value="">Select Currency To </option>
                                    @foreach($currency as $c)
                                    <option {{(isset($to)&&$to==$c->iso)?'selected="selected"':''}} value="{{$c->iso}}">{{$c->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Converted Amount</label>
                                <input type="email" placeholder="Converted" id="converted_amount" name="converted_amount" class="form-control" readonly  value="{{isset($converted_amount)?$converted_amount:''}}">
                            </div>
                        </div>
                    </div>

                </div><!-- /.box-body -->

                <div class="box-footer">
                    <button class="btn btn-primary" id="submit" type="submit">Submit</button>
                </div>
            </div>
        </div>

    </form>
</div>

@stop