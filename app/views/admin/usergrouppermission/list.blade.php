@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif

<form class="form-inline" action="/usergroupActions" method="post" name="actions_form" id="actions_form">

<div class="box box-danger">
    
        
    <div class="box-body">
         <div class="row">
             
             <div class="col-md-4">
                  
                 
             </div>
             
             
                <div class="col-md-5">
                  
                      
                      
                </div>

                <div class="col-md-2">
                    <a href="/usergrouppermission/create" class="btn btn-primary btn-flat">Add New User Group Permission </a>
                </div>

            </div>
    </div>    
   
</div>


<div class='table-responsive'>
    <table class="table table-hover table-bordered pull-left table-striped table-condensed admin-user-table">
    <thead>
        <tr>
            <th>
            <!-- <button id="checkall" class="btn-info">Toggle</button>-->
            <input type="checkbox" id="checkall" class="check" value="" />
            </th>
            <th>User Group Name</th>
          
            <th>&nbsp;</th>
           
       </tr>
     </thead>
     <tbody>
        @foreach($usergroup as $c)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$c->user_group_id}}" id="cid{{$c->user_group_id}}" />
           </td>

           <td  data-title="User Name">
               <a href="/usergrouppermission/{{$c->user_group_id}}/edit/" title="Edit">
                 {{$c->user_group_name}} 
               </a>
           </td>
                     
           <td>
              <a href="/usergrouppermission/{{$c->user_group_id}}/edit/" title="Edit"><i class="fa fa-pencil-square fa-lg"></i>&nbsp;Edit</a>
                  
           </td>
       </tr>
       @endforeach
    </tbody>


</table>
</div>


</form>
    
{{$usergroup->appends(Input::except('page'))->links();}}

@stop

