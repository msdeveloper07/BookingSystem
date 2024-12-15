<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Currency Converter</h4>
      </div>
      <div class="modal-body">
        <div class="row">
    <form  role="form" action='/convertCurrencyModal' name='user_form' id='formoid' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        <div class="col-md-12">
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
                    <button class="btn btn-success" id="submit" type="submit">Submit</button>
                    <button type="reset"  class="btn btn-default" value="Reset">Reset</button>
                </div>
            </div>
        </div>
 <div id='somediv'></div>
    </form>
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        
        
      </div>
    </div>
  </div>
   
   
    
</div>