@extends('layouts.adminTemplate')

@section('content')



<div class="row">
    <div class="col-md-12">
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li ><a href="/bookings/{{$booking->booking_id}}/show">General</a></li>
                <li><a href="/bookings/{{$booking->booking_id}}/edit">Edit</a></li>
               <li class="active"><a href="/bookings/dates/{{$booking->booking_id}}">Dates</a></li>
                <li><a href="/bookings/features/{{$booking->booking_id}}">Features</a></li>
                <li><a href="/bookings/itinerary/{{$booking->booking_id}}">Itinerary</a></li>
                <li><a href="/bookings/tasks/{{$booking->booking_id}}">Tasks</a></li>
                <li><a href="/bookings/payments/{{$booking->booking_id}}">Payments</a></li>
                   <li><a href="/bookings/cancelbooking/{{$booking->booking_id}}">Cancel Booking</a></li>
            </ul>
        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->
</div>


<div class="row">
    <div class="col-md-8">
        <form  role="form" action='/bookings/items/{{$booking->booking_id}}' name='package_form' id='package_form' method="post">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Itinerary</h3>
                 
                </div><!-- /.box-header -->

                <div class="box-body">
                    
                        @foreach($itineraries as $l) 
                        
                        @if(isset($booking_itinerary)&&($booking_itinerary->booking_itinerary_id == $l->booking_itinerary_id))
                        <div class="row">
                            <div class="col-md-8">
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
                                <strong>Image Gallery</strong>
                                <?php $itinerary_image= BookingItineraryImage::where('booking_itinerary_id',$l->booking_itinerary_id)->where('days',$l->days)->get(); ?>
                                <p>@foreach($itinerary_image as $i)

                                <img src="<?php echo url().'/images/itinerary/'.$i->image; ?>"   height="100px">
                                <a href="/bookings/itinerary/remove/image/{{$i->booking_itinerary_image_id}}" title="Remove" style="    vertical-align: bottom; display: inline-block; margin-left: -20px;"><i class="fa fa-times-circle fa-lg text-danger"></i></a>
                                @endforeach</p>
                            </div></div>
                        <div class="col-md-4">
                            <a href="/bookings/itinerary/edit/{{$l->booking_id}}/{{$l->booking_itinerary_id}}"><i class="fa fa-pencil-square fa-lg"></i>&nbsp;Edit</a>&nbsp;&nbsp;
                            <a href="/bookings/itinerary/remove/{{$l->booking_itinerary_id}}" title="Remove"><i class="fa fa-times fa-lg"></i>&nbsp;Remove</a>
                            </div>
                                
                        </div>
                        <hr />
                        @endif
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
            <form  role="form" action='/bookings/updateItinerary' name='itinerary_form' id='itinerary_form' method="post">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <input type="hidden" name="booking_id" value="{{$booking->booking_id}}">
                <input type="hidden" name="booking_itinerary_id" value="{{$booking_itinerary->booking_itinerary_id}}">

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
                                            <input type="text" placeholder="Enter Itinerary Title" id="itinerary_title" name="itinerary_title" class="form-control required" value="{{isset($booking_itinerary)?$booking_itinerary->itinerary_title:''}}">
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="InputTitle">Day</label>
                                            <select name="day" id="day" class="form-control">
                                                <?php for($o=1;$o<=$day;$o++){?>
                                                <option {{((isset($booking_itinerary))&&($booking_itinerary->days==$o)?'selected="selected"':'')}} value="{{$o}}">{{$o}}</option>
                                                <?php } ?>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div id="upload-doc-div" class="row">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <label for="InputStatus">Upload Itinerary Image: </label>
                                <noscript><div class="alert alert-danger">Javascript Must be Enabled for File Upload.</div></noscript>
                                <input type="file" class="btn btn-flat btn-upload" id="upload-button" name="partner_logo" />
                                <input type="hidden" value="" name="specs_location">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group"> 
                                <div id="progress_bar"></div>
                            </div>
                        </div>   
                    </div>

                    <div class="row" style="display:block" id="file_holder">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <div id="prev_upload">

                                    <table class="table table-striped table-bordered table-hover table-condensed" id="files_table" style="display:none;">
                                        <thead>
                                            <tr>
                                                <th style="width:5%;">File Type</th>
                                                <th>Filename</th>
                                                <th style="width:10%;">&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>

                                </div>
                            </div>
                        </div> 
                    </div>
                                <?php for($co=1;$co<=$count;$co++){ ?>
                                <div id="div_repeat_{{$co}}" class="holder_div">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="InputTitle">Things To Do</label>
                                                <select id="thingstodo" name="thingstodo[]" class="form-control">
                                                    <option value="">Please Select</option>
                                                    @foreach($thingstodo as $t)
                                                    <option {{isset($booking_itinerary_thingstodo)&& $booking_itinerary_thingstodo[$co-1]==$t->thing_todo_id?'selected="selected"':''}} value="{{$t->thing_todo_id}}">{{$t->thing_todo}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="InputTitle">Add New</label>
                                                <input type="text" placeholder="Enter Things To Do" id="new_thingstodo" name="new_thingstodo[]" class="form-control required" value="">
                                        <a class="pull-right" href="javascript:void(0);" onclick="removeItem('{{$co}}');"><i class="fa fa-times text-danger"></i>&nbsp;Remove</a>          

                                            </div>
                                        </div>
                                    </div>

                                </div> <!---reppet-->
                                <?php }?>


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
                                        <textarea class="form-control" id="extra_note" name="extra_note" value="">{{isset($booking_itinerary)?$booking_itinerary->extra_notes:''}} </textarea>
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