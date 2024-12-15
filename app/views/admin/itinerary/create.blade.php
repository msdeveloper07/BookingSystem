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
    <form  role="form" action='/itinerary' name='itinerary_form' id='itinerary_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

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
                            <input type="text" placeholder="Enter Itinerary Title" id="loction_name" name="Itinerary_title[]" class="form-control required" value="">
                        </div>
                    </div>
                          
                     </div>
                <!--jk-->
            
                <!--jk-->
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="InputTitle">Date</label>
                            <input type="text" placeholder=""  id="date_times"  name="date_time[]"  class="form-control date_times" value="">
                        </div>
                    </div>
                
                     
                    <div class="col-md-3">
                         <div class="form-group">
                              <label for="InputTitle">Time</label>
                        <div class="input-append date" id="date_time2">
                           
                            <!--<input type="text" placeholder=""  data-forment="dd/MM/yyyy hh:mm:ss" class="form-control" name="time2[]"  value="">-->
                        <input type="text" name="start_time" class="form-control timepicker" placeholder="h:mm PM" data-default-time="false">
                        
                        </div>
                    </div>
                              
                    </div>
                  </div>   
                             
                             
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="InputTitle">Description</label>
                            <textarea class="form-control" id="description" name="description[]" value=""> </textarea>
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
                   
                       <!--;;;-->
                 
                       
                       <!--..-->
                     
                </div><!-- /.box-body -->
                

                <div class="box-footer">
                    <button class="btn btn-primary" type="submit" >Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>

                <div id="add_new_template" style="display:none">
    <div id="div_repeat_index"  class="holder_div">
         <hr/>
          <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="InputTitle">Itinerary Name</label>
                            <input type="text" placeholder="Enter Itinerary Title" id="loction_name" name="Itinerary_title[]" class="form-control required" value="">
                        </div>
                    </div>
                          
                     </div>
       
                  <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="InputTitle">Date</label>
                            <input type="text" placeholder=""  id="date_times"  name="date_time[]"  class="form-control date_times" value="">
                        </div>
                    </div>
                
                     
                    <div class="col-md-3">
                         <div class="form-group">
                              <label for="InputTitle">Time</label>
                        <div class="input-append date" id="date_time2">
                           
                            <!--<input type="text" placeholder=""  data-forment="dd/MM/yyyy hh:mm:ss" class="form-control" name="time2[]"  value="">-->
                            <input type="text" name="start_time" class="form-control timepicker" onfocus="onload(index)" placeholder="h:mm PM" data-default-time="false">
                        
                        </div>
                    </div>
                              
                    </div>
                  </div>   
                  
                  
                  <!--End time-->
                   
                    
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="InputTitle">Description</label>
                            <textarea class="form-control" id="description" name="description[]" value=""> </textarea>
                        </div>
                    </div>
                    </div>
                 
                
        
    </div>
   </div>
        
<!--//'''''''-->










@stop