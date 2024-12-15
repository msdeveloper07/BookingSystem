@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif

<form class="form-inline" action="/suppliertypeitemActions" method="post" name="actions_form" id="actions_form">

<div class="box box-danger">
    
        
    <div class="box-body">
         <div class="row">
             
             <div class="col-md-4">
                     Actions
                     <div class="form-group">
                        <select id="bulk_action" name="bulk_action" class="form-control" placeholder="Select Action"  >
                            <option value="">Select An Action</option>
                          
                            <option value="delete">Delete Selected Supllier Items</option>
                        </select>
                     </div>
                 
             </div>
             
             
                <div class="col-md-5">
                  
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                        <div class="input-group">
                          <input type="text" value="<?php if(isset($search)){ echo $search;}?>" class="form-control" name="search" id="search" placeholder="Search Supplier Type  Item">
                          <span class="input-group-btn">
                          <button type="submit" class="btn btn-default btn-flat">Search Supplier Type Item</button>
                          </span>
                        </div>
                      
                </div>

                <div class="col-md-2">
                    <a href="/suppliertypeitem/create" class="btn btn-primary btn-flat">Add New Supplier Type Item</a>
                </div>

            </div>
    </div>    
   
</div>


<div class='table-responsive'>
    <table class="table table-hover table-bordered pull-left table-striped table-condensed admin-supplieritem-table">
    <thead>
        <tr>
            <th>
            <!-- <button id="checkall" class="btn-info">Toggle</button>-->
            <input type="checkbox" id="checkall" class="check" value="" />
            </th>
            <th>Supplier Item</th>
              <th>Supplier Type Name</th>
           
            <th>&nbsp;</th>
           
       </tr>
     </thead>
     <tbody>
        @foreach($suppliertypeitem as $c)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$c->supplier_type_item_id}}" id="cid{{$c->supplier_type_item_id}}" />
           </td>

           <td  data-title="User Name">
               <a href="/suppliertypeitem/{{$c->supplier_type_item_id}}/edit/" title="Edit">
                 {{isset($c->supplier_item_name)?$c->supplier_item_name:''}} 
               </a>
           </td>
           <td>
         {{isset($c->supplier_type)?$c->supplier_type:''}} 
           
               
               
               
           </td>
       
           
           <td>
              <a href="/suppliertypeitem/{{$c->supplier_type_item_id}}/edit/" title="Edit"><i class="fa fa-pencil-square fa-lg"></i>&nbsp;Edit</a>
                  
           </td>
       </tr>
       @endforeach
    </tbody>


</table>
</div>


</form>
    
{{$suppliertypeitem->appends(Input::except('page'))->links();}}

@stop