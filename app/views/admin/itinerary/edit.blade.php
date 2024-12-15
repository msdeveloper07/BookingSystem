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
    <form  role="form" action='/itinerary/{{$itinerary->itinerary_id}}' name='user_form' id='user_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="_method" value="PUT">


      <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                       <div style='float:right;'>
                            <a class="btn btn-success" href='/itinerary'>Back To Itinerary</a>             
                        </div>
                </div><!-- /.box-header -->
               
                <div class="box-body">
                      <div id="box_body1">
                         <div id="div_repeat_1" class="holder_div">
                      <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="InputTitle">Itinerary Name</label>
                            <input type="text" placeholder="Enter Itinerary Title" id="loction_name" name="Itinerary_title[]" class="form-control required" value="{{$itinerary->itinerary_title}}">
                        </div>
                    </div>
                          
                     </div>
                
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="InputTitle">Date</label>
                            <input type="date" placeholder=""  id="date_time_1" name="date_time[]" onfocus="loaddate(1)" class="form-control datepicker" value="{{$itinerary->datetime}}">
                        </div>
                    </div>
                  </div>
                             
                     <div class="row">
                         <div>
                            <label class="input-group">&nbsp;&nbsp;&nbsp;&nbsp;Time</label>
                            </div>    
                    <div class="col-md-3">
                      
                        <div class="form-group">
                            <label for="InputTitle">Hours</label>
                            <input type="text" placeholder="" id="time1"    class="form-control" name="time1[]"  value="">
                        </div>
                    </div>
                         
                              <div class="col-md-2">
                        <div class="input-append date" id="date_time2">
                            <label for="InputTitle">Mint</label>
                            <input type="text" placeholder=""  data-forment="dd/MM/yyyy hh:mm:ss" class="form-control" name="time2[]"  value="">
                        </div>
                    </div>
                              <div class="col-md-3">
                        <div class="form-group">
                            <label for="InputTitle">PM/AM</label>
                            <select class="form-control" id="time3" name="time3[]">
                                <option value="pm">PM</option>
                                <option value="am">AM</option>
                            </select>
                        </div>
                    </div>
                  </div>   
                             
                             
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="InputTitle">Description</label>
                            <textarea class="form-control" id="description" name="description[]" value="">{{$itinerary->description}} </textarea>
                        </div>
                    </div>
                    </div>
                   
                        </div> <!---reppet-->
                      </div> <!--box body1-->
                      
                     
                        <div class="row">
                    <div class="col-md-4" style='float:right;'>
                              <a href='javascript:void(0);'  id="add_itinerary"><i class='fa fa-plus'></i>Add Itinerary</a> 
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