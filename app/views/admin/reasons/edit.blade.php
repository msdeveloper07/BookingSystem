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
   
       <form  role="form" action='/reason/{{$reason->reason_id}}' name='reason_form' id='reason_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="_method" value="PUT">

<div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                       <div style='float:right;'>
                            <a class="btn btn-success" href='/reason'>Back To Reasons</a>             
                        </div>
                </div><!-- /.box-header -->
                 <div class="box-body">
                      <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="InputTitle">Reasons</label>
                            <input type="text" placeholder="Enter Reason" id="reason" name="reason" class="form-control required" value="{{$reason->reason}}">
                        </div>
                    </div>
                          
                     </div>
                
                    
                   
                     
                         <div class="row" > 
                        <div class="col-md-6">
                            <div class="form-group"> 
                                 <label for="InputTitle">Reason Status</label>
                                <label class="radio-inline">
                                    <input type="radio" name="reason_status" {{$reason->status=='Active' ? 'checked="checked"':''}} value="Active"   id="reason_status"  checked="checked">Active
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="reason_status" {{$reason->status=='Deactive' ? 'checked="checked"':''}} value="Deactive" id="reason_status" >Deactive
                                </label>


                            </div>
                        </div>
                    </div>
                 
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <button class="btn btn-primary" type="submit" >Update</button>
                </div>
            </div>
        </div>
    </form>
</div>

@stop