@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif

<form class="form-inline" action="/suppliertypeActions" method="post" name="actions_form" id="actions_form">

<div class="box box-danger">
    
        
    <div class="box-body">
         <div class="row">
             
             <div class="col-md-4">
                     Actions
                     <div class="form-group">
                        <select id="bulk_action" name="bulk_action" class="form-control" placeholder="Select Action"  >
                            <option value="">Select An Action</option>
                     
                            <option value="delete">Delete Selected Supplier Type</option>
                        </select>
                     </div>
                 
             </div>
             
             
                <div class="col-md-5">
                  
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                        <div class="input-group">
                          <input type="text" value="<?php if(isset($search)){ echo $search;}?>" class="form-control" name="search" id="search" placeholder="Search Supplier Type">
                          <span class="input-group-btn">
                          <button type="submit" class="btn btn-default btn-flat">Search Supplier Type</button>
                          </span>
                        </div>
                      
                </div>

                <div class="col-md-2">
                    <a href="/suppliertype/create" class="btn btn-primary btn-flat">Add New Supplier Type </a>
                </div>

            </div>
    </div>    
   
</div>


<div class='table-responsive'>
    <table class="table table-hover table-bordered pull-left table-striped table-condensed admin-suppliertype-table">
    <thead>
        <tr>
            <th>
            <!-- <button id="checkall" class="btn-info">Toggle</button>-->
            <input type="checkbox" id="checkall" class="check" value="" />
            </th>
            <th>Supplier Type</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            
           
       </tr>
     </thead>
     <tbody>
        @foreach($supplier as $c)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$c->supplier_type_id}}" id="cid{{$c->supplier_type_id}}" />
           </td>

           <td  data-title="">
               <a href="/suppliertype/{{$c->supplier_type_id}}/edit/" title="Edit"/>
                 {{$c->supplier_type}} 
                 </a>  
                <?php    $supp = SupplierType::where('supplier_type_parent_id',$c->supplier_type_id)->get();  
                $count=count($supp);
              $i=0;
              if($count > 0){
              ?>
               <table>
        @foreach($supp as $s)
        <tr><td>>>></td>
        <td><a href="/suppliertype/{{$s->supplier_type_id}}/edit/" title="Edit"/>
               {{$s->supplier_type}}&nbsp;</a>
            
        </td> 
        </td>
        <?php  $itemcount = SupplierTypeItem::where('supplier_type_id',$s->supplier_type_id)->get()->count(); ?>
          <td>
              <a href="/suppliertype/additem/{{$s->supplier_type_id}}" title="Add Item"><i class="fa fa-plus"></i>&nbsp;Add Item</a>({{$itemcount}})
                  
           </td>
        
        
        </tr>
               
                @endforeach
               </table>
              <?php } ?>
           </td>
        
           
           
           
           <td>
              <a href="/suppliertype/{{$c->supplier_type_id}}/edit/" title="Edit"><i class="fa fa-pencil-square fa-lg"></i>&nbsp;Edit</a>
                  
           </td>
       </tr>
       @endforeach
    </tbody>


</table>
</div>


</form>
    
{{$supplier->appends(Input::except('page'))->links();}}

@stop

