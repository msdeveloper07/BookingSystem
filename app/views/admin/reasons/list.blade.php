@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif

<form class="form-inline" action="/reasonActions" method="post" name="actions_form" id="actions_form">

<div class="box box-danger">
    
        
    <div class="box-body">
         <div class="row">
             
             <div class="col-md-4">
                     Actions
                     <div class="form-group">
                        <select id="bulk_action" name="bulk_action" class="form-control" placeholder="Select Action"  >
                            <option value="">Select An Action</option>
                            <option value="delete">Delete Selected Reasons</option>
                             <option value="Active">Active Selected Reasons</option>
                              <option value="Deactive">Deactive Selected Reasons</option>
                        </select>
                     </div>
                 
             </div>
               
             
                <div class="col-md-5">
                  
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                        <div class="input-group">
                         
                              <input type="text" value="<?php if(isset($search)){ echo $search;}?>" class="form-control" name="search" id="search" placeholder="Search Reasons">
                       <span class='input-group-btn'>
                          <button type="submit" class="btn btn-default btn-flat">Find Reasons</button>
                       </span>
                        </div>
                      
                </div>

                <div class="col-md-2">
                    <a href="/reason/create" class="btn btn-primary btn-flat">Add New Reason</a>
                </div>

            </div>
    </div>    
   
</div>


<div class='table-responsive'>
    <table class="table table-hover table-bordered pull-left table-striped table-condensed admin-user-table">
    <thead>
        <tr>
            <th>
            <input type="checkbox" id="checkall" class="check" value="" />
            </th>
            <th>Reasons</th>
            <th>Reason Status</th>
            <th>&nbsp;</th>
           
       </tr>
     </thead>
     <tbody>
        @foreach($reason as $p)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$p->reason_id}}" id="cid{{$p->reason_id}}" />
           </td>

           <td  data-title="reason">
               <a href="/reason/{{$p->reason_id}}/edit/" title="Edit">
                 {{isset($p->reason)?$p->reason:''}} 
               </a>
           </td>
          
            <td><strong><p class="text-{{(isset($p->status)&&($p->status=='Active'))?'success':'danger'}}">
               {{isset($p->status)?$p->status:''}} 

          </p></strong> </td>
          
           <td>
              <a href="/reason/{{$p->reason_id}}/edit/" title="Edit"><i class="fa fa-pencil-square fa-lg"></i>&nbsp;Edit</a>
                  
           </td>
       </tr>
       @endforeach
    </tbody>


</table>
</div>


</form>
    
{{$reason->appends(Input::except('page'))->links();}}

@stop

