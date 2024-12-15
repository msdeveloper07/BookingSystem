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
    <form  role="form" action='/usergrouppermission/{{$user_group_id}}' name='permission_form' id='permission_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="_method" value="PUT">


        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                </div><!-- /.box-header -->
                 
                <div class="box-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="InputTitle">User Group</label>
                            <select class="form-control" name="user_group_id"   id="user_group_id">
                                  <option value="">Select User Group</option>
                                <?php $usergroupper = Usergroup::all(); ?>
                                @foreach($usergroupper as $u)
                               
                                <option {{$u->user_group_id==$user_group_id?'selected="selected"':''}} value="{{$u->user_group_id}}">{{$u->user_group_name}} </option>
                            @endforeach
                            </select>
                            
                           </div>
                        </div>
                    </div>
                

                    
    <div class='table-responsive'>
    <table class="table table-hover table-bordered  table-striped table-condensed admin-user-table">
    <thead>
        <tr>
<!--            <th>-->
<!--             <button id="checkall" class="btn-info">Toggle</button>-->
<th>  <input type="checkbox" id="checkall" class="check" value="" />
            </th>
            
            <th>Element</th>
            <th>Component</th>
            <th>Title</th>
           
         
           
       </tr>
     </thead>
     <tbody>
         <?php
         $permission = Permission::all();
         ?>
        @foreach($permission as $c)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" {{in_array($c->permission_id, $ugpermission)?'checked="checked"':''}} value="{{$c->permission_id}}" id="cid{{$c->permission_id}}" />
           </td>

           <td  data-title="User Name">
             
                 {{$c->element}} 
             
           </td>
           <td  data-title="Email">{{$c->component}}</td>
           <td  data-title="title">{{$c->title}}</td>
          
          
        
       </tr>
       @endforeach
    </tbody>


</table>
</div>


                   
                </div><!--box body--> 
                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>


@stop