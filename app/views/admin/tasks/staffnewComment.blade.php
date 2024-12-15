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
    <div class="col-md-6">
        <form  role="form" action='/bookings/updatetasks/' name='user_form' id='user_form' method="post">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">


            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Task Information</h3>
                    <div style='float:right;'>
                        <h3 class="box-title"> @if($taskss->booking_id >='1') <?php $title = Booking::where('booking_id',$taskss->booking_id)->pluck('booking_title'); ?> Booking Title:&nbsp;{{$title}} &nbsp; @else Not relation of Booking &nbsp; @endif   </h3>     
                    </div>
                    
                 

                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="InputTitle">Task Title</label>
                                <input type="text" readonly placeholder="Enter Task" id="task_title" name="task_title" class="form-control required" value="{{$taskss->task_title}}">
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="InputTitle">Task Description</label>
                                <textarea placeholder="Enter Task Description" readonly id="task_description" name="task_description" class="form-control required" value="">{{$taskss->task_description}}</textarea>
                            </div>
                        </div>
                    </div>



                    <div class="row">      
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Asign To</label>
                                <select id="assign_to" readonly name="assign_to" class="form-control">
                                    <option value="">Please Select</option>
                                    @foreach($assign_to as $c)
                                    <option {{$c->id==$taskss->assign_to?'selected="selected"':''}} value="{{$c->id}}">{{$c->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Due Date</label>
                                <input type="text" readonly  id="due_date" name="due_date" class="form-control" value="{{$taskss->due_date}}">
                            </div>
                        </div>
                    </div>


                </div><!-- /.box-body -->

                <!--                <div class="box-footer">
                                    <button class="btn btn-primary" type="submit" >Update</button>
                                </div>-->
            </div>

        </form>

    </div>


    <div class="col-md-6">



        <div class="col-md-12">
            <div class="row">
                <form  role="form" action='/staffstoreComment/{{$booking_task_id}}' name='user_form' id='user_form' method="post">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">



                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title">ADD Comment</h3>

                            </div><!-- /.box-header -->
                            <div class="box-body">


                                <?php
                                $j = 0;
                                $i = null;
                                for ($i = 1; $i <= $commentCount; $i++) {
                                    ?>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="InputTitle"></label>
                                                <div class="form-group">

                                                    <?php $name = User::where('id', '=', $username[$j])->pluck('name'); ?>
                                                    {{(Auth::user()->id==$username[$j])?'You':$name}}                                        
    <?php echo '&nbsp:&nbsp"'; ?>
                                                    <b>{{$taskComment[$j]}}</b><?php echo '"'; ?> </br>
                                                    Date:-{{$commentdate[$j]}}
                                                    </br>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $j++;
                                    }
                                    ?>
                                </div>


                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="InputTitle">New Comment</label>
                                            <textarea placeholder="Enter Answer Here" id="comments" name="comments" class="form-control required" > </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="InputTitle">Task Status</label>
                                            <select id="task_status" name="task_status" class="form-control">
                                                <option  value="Open"> Open</option>
                                                <option  value="InProgress">In Progress</option>
                                                <option  value="Closed"> Closed</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" type="submit" >Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>   
    </div>
</div>

@stop