@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif

<form class="form-inline" action="/newsletterActions" method="post" name="actions_form" id="actions_form">
 
<div class="box box-danger">
    
        
    <div class="box-body">
         <div class="row">
             
             <div class="col-md-4">
                   <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                     <div class="form-group">
                        <select id="bulk_action" name="bulk_action" class="form-control" placeholder="Select Action"  >
                            <option value="">Select An Action</option>
                         
                            <option value="delete">Delete Selected newsletter</option>
                        </select>
                     </div>
                 
             </div>
             
            
             
             
             <div class="col-md-5">
                  
                      
                </div>

                <div class="col-md-2">
                    <a href="/newsletters/create" class="btn btn-primary btn-flat">Add New newsletter</a>
                </div>

            </div>
    </div>    
   
</div>


<div class='table-responsive col-md-12'>
    <table class="table table-hover table-bordered pull-left table-striped table-condensed admin-user-table">
    <thead>
        <tr>
            <th>
            <!-- <button id="checkall" class="btn-info">Toggle</button>-->
            <input type="checkbox" id="checkall" class="check" value="" />
            </th>
            <th>MailingList Name</th>

            <th>Processed On</th>
             <th>newsletter Status</th>

       </tr>
     </thead>
     <tbody>
         
       
        @foreach($newsletter as $c)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$c->newsletter_id}}" id="cid{{$c->newsletter_id}}" />
           </td>
           
            <td  data-title="MailingList Name">
                
                {{isset($c->mailinglist_name->mailinglist_name)?$c->mailinglist_name->mailinglist_name:''}}
               
           </td>
         
          
           <td>
              {{isset($c->processed_on)?$c->processed_on:''}}
           </td>
           <td>
              {{isset($c->newsletter_status)?$c->newsletter_status:''}}
           </td>
           
         
       </tr>
       @endforeach
   
    </tbody>


</table>
</div>


</form>
    
        {{$newsletter->appends(Input::except('page'))->links()}}

@stop

