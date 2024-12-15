@extends('layouts.adminTemplate')

@section('content')



<div class="row">
    <div class="col-md-12">
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="/quotes/{{$quote->quote_id}}/edit">General</a></li>
                <li><a href="/quotes/dates/{{$quote->quote_id}}">Dates</a></li>
                <li><a href="/quotes/items/{{$quote->quote_id}}">Items</a></li>
                <li class="active"><a href="/quotes/itinerary/{{$quote->quote_id}}">Itinerary</a></li>
                <li><a href="/quotes/bookings/{{$quote->quote_id}}">Bookings</a></li>
                <li><a href="/bookings/fromQuote/{{$quote->quote_id}}">New Booking</a></li>
            </ul>

        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->

</div>


<div class="row">
    <div class="col-md-8">
        <form  role="form" action='/quotes/items/{{$package->package_id}}' name='package_form' id='package_form' method="post">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Itinerary</h3>
                    <div style='float:right;'>
                        <a class="btn btn-success" href='/packages'>Back To Packages</a>             
                    </div>
                </div><!-- /.box-header -->

                <div class="box-body">
                    
                        @foreach($itineraries as $l) 
                        <div class="row">
                            
                           <div class="col-md-12">
                              <h3>Day  {{isset($l->days)?$l->days:''}} {{isset($l->itinerary_title)?"(".$l->itinerary_title.")":''}}</h3>
                              <p>{{$l->extra_notes}}</p>
                          </div>
                        <div class="col-md-12">
                            <strong>Things to Do</strong>
                            <?php $saved_things = explode(',', $l->things_todo); 
                                   $things =  ThingToDo::whereIn('thing_todo_id', $saved_things)->get();
                            ?>
                            @if(count($things)>0)
                            <ul>
                                @foreach($things as $t)
                                <li>{{$t->thing_todo}}</li>
                                @endforeach
                            </ul>
                            @endif
                        </div>  
                        <div class="col-md-12">
                           <a href="/quotes/itinerary/remove/{{$l->quote_itinerary_id}}" title="Edit"><i class="fa fa-pencil-square fa-lg"></i>&nbsp;Remove</a>

                        </div>
                        
                        </div>
                        <hr />
                        @endforeach
                    
                 

                </div>



            </div><!-- /.box-body -->


        </form>


    </div>
    <div class="col-md-4">

        @if ( $errors->count() > 0 )

        <p>The following errors have occurred:</p>
        <ul>
            @foreach($errors->all() as $m)
            <li><span class="text-danger">{{$m}}</span></li>
            @endforeach
        </ul>

        @endif

        <div class="row">
            <form  role="form" action='/quotes/saveItinerary' name='itinerary_form' id='itinerary_form' method="post">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <input type="hidden" name="quote_id" value="{{$quote->quote_id}}">

                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Add Day and Things to do</h3>
                            
                        </div><!-- /.box-header -->


                        <div class="box-body">
                            <div id="box_body1">

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="InputTitle">Itinerary Title</label>
                                            <input type="text" placeholder="Enter Itinerary Title" id="itinerary_title" name="itinerary_title" class="form-control required" value="">
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="InputTitle">Day</label>
                                            <select name="day" id="day" class="form-control">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div id="div_repeat_1" class="holder_div">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="InputTitle">Things To Do</label>
                                                <select id="thingstodo" name="thingstodo[]" class="form-control">
                                                    <option value="">Please Select</option>
                                                    @foreach($thingstodo as $t)
                                                    <option value="{{$t->thing_todo_id}}">{{$t->thing_todo}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="InputTitle">Add New</label>
                                                <input type="text" placeholder="Enter Things To Do" id="new_thingstodo" name="new_thingstodo[]" class="form-control required" value="">
                                        <a class="pull-right" href="javascript:void(0);" onclick="removeItem('1');"><i class="fa fa-times text-danger"></i>&nbsp;Remove</a>          

                                            </div>
                                        </div>
                                    </div>

                                </div> <!---reppet-->


                            </div> <!--box body1-->     

                            <div class="row">
                                <div class="col-md-6" style='float:right;'>
                                    <a href='#'  id="add_new_items"><i class='fa fa-plus'></i>Add New Items </a> 
                                </div>
                            </div> 

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="InputTitle">Extra Notes</label>
                                        <textarea class="form-control" id="extra_note" name="extra_note" value=""> </textarea>
                                    </div>
                                </div>
                            </div>





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

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="InputTitle">Things To Do</label>
                            <select id="thingstodo" name="thingstodo[]" class="form-control">
                                <option value="">Please Select</option>
                                @foreach($thingstodo as $t)
                                <option value="{{$t->thing_todo_id}}">{{$t->thing_todo}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="InputTitle">Add New</label>
                            <input type="text" placeholder="Enter Things To Do" id="new_thingstodo" name="new_thingstodo[]" class="form-control required" value="">
                            <a class="pull-right" href="javascript:void(0);" onclick="removeItem('index');"><i class="fa fa-times text-danger"></i>&nbsp;Remove</a>       

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>


</div>




@stop