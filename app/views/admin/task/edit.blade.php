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
    <form  role="form" action='/task/{{$task->task_id}}' name='user_form' id='user_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="_method" value="PUT">


         <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                       <div style='float:right;'>
                            <a class="btn btn-success" href='/task'>Back To Task</a>             
                        </div>
                </div><!-- /.box-header -->
                 <div class="box-body">
                      <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="InputTitle">Task Title</label>
                            <input type="text" placeholder="Enter Task" id="task_title" name="task_title" class="form-control required" value="{{$task->task_title}}">
                        </div>
                    </div>
                          
                     </div>
                
                    <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="InputTitle">Task Description</label>
                            <textarea placeholder="Enter Task Description" id="task_description" name="task_description" class="form-control required" value="">{{$task->task_description}} </textarea>
                        </div>
                    </div>
                    </div>
                     
                    
                     
                    <div class="row">      
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="InputTitle">Asign To</label>
                            <select id="assign_to" name="assign_to" class="form-control">
                                <option value="">Please Select</option>
                                @foreach($assign_to as $c)
                                <option {{$c->id==$task->task_id?'selected="selected"':''}} value="{{$c->id}}">{{$c->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                  
                
                     </div>
                     
                    
<!--                      <div class="row">-->
<!--                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Asign Date</label>
                                <input type="text"  id="assign_date" name="assign_date" class="form-control" value="">
                            </div>
                        </div>-->
                          <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Due Date</label>
                                <input type="text"  id="due_date" name="due_date" class="form-control" value="{{$task->due_date}}">
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

@stop