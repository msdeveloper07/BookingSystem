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
     <div class="col-md-8">
    <form  role="form" action='/staffstoreComment/{{$booking_task_id}}' name='user_form' id='user_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
               
     
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
<!--                    <div style='float:right;'>
                        <a class="btn btn-success" href='/showTaskUser'>Back To Task</a>             
                    </div>-->
                </div><!-- /.box-header -->
                <div class="box-body">
                    
                     <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            <label for="InputTitle">Task Description</label>
                            <textarea placeholder="Enter Task Description" readonly id="task_description" name="task_description" class="form-control required" value="">{{$taskss->task_description}}</textarea>
                        </div>
                    </div>
                    </div> 
                     <div class="box-header">
                    <h3 class="box-title">Task Comments</h3>
<!--                  
                </div><!-- /.box-header -->
                    
                        <?php
                                             $j=0;
                                             $i = null;
                                             for($i=1; $i <= $commentCount; $i++)  { ?>
                             <div class="row">
                             <div class="col-md-8">
                                 <div class="form-group">
                                    
                                     <div class="form-group">
 <?php $name = User::where('id',$username[$j])->pluck('name'); ?>
        {{(Auth::user()->id==$username[$j])?'You':$name}}       
        <?php echo '&nbsp:&nbsp"';?>

                                        <b>{{$taskComment[$j]}}</b><?php echo '"';?> </br>
                                         Date:-{{$commentdate[$j]}}
                                         </br>
                                     </div>
                                 </div>
                             </div>
                             <?php $j++;
                         } ?>
                                 
                                 
                                 
                                 
                                 
                     </div>
             
            <div class="row">
                <div class="col-md-8">
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
                            <option {{$task=='Open'?'selected="selected"':''}} value="Open"> Open</option>
                            <option {{$task=='InProgress'?'selected="selected"':''}} value="InProgress">In Progress</option>
                            <option {{$task=='Closed'?'selected="selected"':''}} value="Closed"> Closed</option>

                        </select>
                    </div>
                </div>
            </div>
                </div>
                    <div class="box-footer">
                    <button class="btn btn-primary" type="submit" >Submit</button>
                </div>
            </div>
      
           
    </form>
    </div>
    
    
    
    
    
    
    

</div>

@stop