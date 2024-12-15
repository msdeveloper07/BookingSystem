@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif



<form class="form-inline" action="/supplierActions" method="post" name="actions_form" id="actions_form">


    
   
   

<div class="box box-danger">
    
        
    <div class="box-body">
         <div class="row">
             
             <div class="col-md-4">
                     Actions
                     <div class="form-group">
                        <select id="bulk_action" name="bulk_action" class="form-control" placeholder="Select Action"  >
                            <option value="">Select An Action</option>
                           <option value="delete">Delete Selected Supplier</option>
                         
                        </select>
                     </div>
                 
             </div>
             
             
                <div class="col-md-5">
                  
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                        <div class="input-group">
                          <input type="text" value="<?php if(isset($search)){ echo $search;}?>" class="form-control" name="search" id="search" placeholder="Search Supplier">
                          <span class="input-group-btn">
                          <button type="submit" class="btn btn-default btn-flat">Search Supplier</button>
                          </span>
                        </div>
                      
                </div>

                <div class="col-md-2">
                    <a href="/supplier/create" class="btn btn-primary btn-flat">Add New Supplier</a>
                </div>

            </div>
    </div>    
   
</div>


<div class='table-responsive'>
    <table class="table table-hover table-bordered pull-left table-striped table-condensed admin-supplier-table">
    <thead>
        <tr>
            <th>
            <!-- <button id="checkall" class="btn-info">Toggle</button>-->
            <input type="checkbox" id="checkall" class="check" value="" />
            </th>
             <th>Supplier Name</th>
              <th>Supplier Type </th> 
             <th>Supplier Sub Type</th>
              <th>Supplier Location</th>
           <th>Supplier Email</th>
            <th>Supplier Phone</th>
            <th>&nbsp;</th>
           
       </tr>
     </thead>
     <tbody>
        @foreach($supplier as $c)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$c->supplier_id}}" id="cid{{$c->supplier_id}}" />
           </td>

           <td  data-title="User Name">
               <!--<a href="/supplier/{{$c->supplier_id}}/edit/" title="Edit">-->
               <a href="/supplier/{{$c->supplier_id}}/edit/" title="Edit">
                
                   {{isset($c->supplier_name)?$c->supplier_name:''}}
               </a>
           </td>
            <td>
              
                {{isset($c->suppTypeName->supplier_type)?$c->suppTypeName->supplier_type:''}}
           </td>
           <td>
              
                {{isset($c->suppSubType->supplier_type)?$c->suppSubType->supplier_type:''}}
           </td>
           <td>
              
                {{isset($c->loc->location_name)?$c->loc->location_name:''}}
           </td>
          
           
            <td>
               
               {{isset($c->supplier_email)?$c->supplier_email:''}}
           </td>
            <td>
              
               {{isset($c->supplier_email)?$c->supplier_phone:''}}
           </td>
       
           
           <td>
              <a href="/supplier/{{$c->supplier_id}}/edit/" title="Edit"><i class="fa fa-pencil-square fa-lg"></i>&nbsp;Edit</a>
                  
           </td>
       </tr>
       @endforeach
    </tbody>


</table>
</div>


</form>
    
{{$supplier->appends(Input::except('page'))->links();}}

@stop

