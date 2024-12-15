@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif

<form class="form-inline" action="/contactActions" method="post" name="actions_form" id="actions_form">

<div class="box box-danger">
    
        
    <div class="box-body">
         <div class="row">
             
             <div class="col-md-4">
                     Actions
                     <div class="form-group">
                        <select id="bulk_action" name="bulk_action" class="form-control" placeholder="Select Action"  >
                            <option value="">Select An Action</option>
                            <option value="blocked">Block Selected Contacts</option>
                            <option value="active">Activate Selected Contacts</option>
                            <option value="delete">Delete Selected Contacts</option>
                        </select>
                     </div>
                 
             </div>
             
             
                <div class="col-md-5">
                  
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                        <div class="input-group">
                          <input type="text" value="<?php if(isset($search)){ echo $search;}?>" class="form-control" name="search" id="search" placeholder="Search Contacts">
                        <span class="input-group-btn">
                          <button type="submit" class="btn btn-default btn-flat">Find Contact</button>
                        </span>
                        </div>
                      
                </div>

                <div class="col-md-2">
                    <a href="/contacts/create" class="btn btn-primary btn-flat">Add New Contact </a>
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
            <th>Name</th>
            <th>Email</th>
            <th>Phone No</th>
            <th>&nbsp;</th>
           
       </tr>
     </thead>
     <tbody>
        @foreach($contacts as $c)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$c->contact_id}}" id="cid{{$c->contact_id}}" />
           </td>

           <td  data-title="Contact Name">
               <a href="/contacts/{{$c->contact_id}}/edit" title="Edit">
                  {{$c->contact_first_name}}&nbsp;{{$c->contact_last_name}} 
               </a>
           </td>
           <td  data-title="Email">{{$c->contact_email}}</td>
           <td  data-title="title">{{$c->contact_phone}}</td>
          
           <td>
              <a href="/contacts/{{$c->contact_id}}/edit" title="Edit"><i class="fa fa-pencil-square fa-lg"></i>&nbsp;Edit</a>
           </td>
       </tr>
       @endforeach
    </tbody>


</table>
</div>


</form>
    
{{$paginator->appends(Input::except('page'))->links();}}

@stop

