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
                           <option value="delete">Delete Selected Users</option>
                         
                        </select>
                     </div>
                 
             </div>
             
             
                <div class="col-md-5">
                  
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                        <div class="form-group">
                          <div class="col-md-6">
                              <input type="text" value="<?php if(isset($search)){ echo $search;}?>" class="form-control" name="search" id="search" placeholder="Search Supplier">
                          </div>
                          <button type="submit" class="btn btn-default btn-flat">Find</button>

                        </div>
                      
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
             <th>Supplier Type Name</th>
             <th>Supplier Item Name</th>
             <th>Supplier Cost</th>
            <th>Commission Of Iitem</th>
             <th>Payment Method</th>
           <th>Commission</th>
            
       </tr>
     </thead>
     <tbody>
        @foreach($supplier as $c)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$c->supplier_id}}" id="cid{{$c->supplier_id}}" />
           </td>

           <td  data-title="User Name">
               
                  {{$c->supplierinfo->supplier_name}}
              
           </td>
           <td>
               <?php
               $preet  = $c->supplier_id;
               
               
               $itemid = Supplier::where('supplier_id',$preet)->pluck('supplier_type_id');
               
               $supplier_type = SupplierType::where('supplier_type_id',$itemid)->pluck('supplier_type');
              
               ?>
               {{isset($supplier_type)?$supplier_type:''}}
           </td>
          
          <td>
               <?php
               $preet  = $c->supplier_id;
               
               
               $itemid = Supplier::where('supplier_id',$preet)->pluck('supplier_item_id');
               
               $item_name = SupplierItem::where('supplier_item_id',$itemid)->pluck('supplier_item_name');
              
               ?>
               {{isset($item_name)?$item_name:''}}
           </td>
          
          
           <td>
               {{isset($c->supplierinfo->supplier_cost)?$c->supplierinfo->supplier_cost:''}}
           </td>
            <td>
               {{$c->commision_of_item,'','%'}}
           </td>
         
         <td>
               {{isset($c->payment_method)?$c->payment_method:''}}
           </td>
         <td>
               <?php
               
               $total = $c->supplierinfo->supplier_cost;
                 $comm = $c->commision_of_item;      
                       $totalcomm = $total * $comm /100;
                       ?>
             {{isset($totalcomm)?$totalcomm:''}}
           </td>
           
           
           
         
       </tr>
       @endforeach
    </tbody>


</table>
</div>


</form>
    
{{$supplier->appends(Input::except('page'))->links();}}

@stop

