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
    <div class="col-md-12">
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="/quotes/{{$quote->quote_id}}/show">General</a></li>
                 <li><a href="/quotes/{{$quote->quote_id}}/edit">Edit</a></li>
                <li><a href="/quotes/dates/{{$quote->quote_id}}">Dates</a></li>
                <li><a href="/quotes/suppliers/{{$quote->quote_id}}">Suppliers</a></li>
                <li class="active"><a href="/quotes/items/{{$quote->quote_id}}">Items</a></li>
                <li><a href="/quotes/itinerary/{{$quote->quote_id}}">Itinerary</a></li>
                <li><a href="/quotes/bookings/{{$quote->quote_id}}">Bookings</a></li>
                
                
            </ul>
        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->
</div>


<div class="row">
   <form  role="form" action='/quotes/saveitems/{{$quote->quote_id}}' name='quotes_form' id='quotes_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">



        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
<!--                    <div style='float:right;'>
                        <a class="btn btn-success" href='/packages'>Back To Packages</a>           
                    </div>-->
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div id="box_body1">
                        
                        <?php $i = 1; 
                         $j = 0;
                        if($count > 0) { ?>
                       
                            @foreach($items as $item)
                         
                             <div id="div_repeat_{{$i}}" class="row holder_div">
                                <div class="col-md-6">
                                     <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="InputTitle">Supplier Type</label>
                                          <select class="form-control" id="supplier_type_id_{{$i}}" onchange="supplierTypeQuoteSuppliers({{$quote->quote_id}}, this.value, {{$i}})" name="supplier_type[]" >
                                               
                                              <option value="">Select Supplier Type</option>
                                               @foreach($quote_suppliers as $p)
                                             <?php $supplier_type = SupplierType::where('supplier_type_id',$p->supplier_type_parent_id)->get(); ?>
                                                @foreach($supplier_type as $s)
                                           <option {{$s->supplier_type_id==$item->supplier_type_id?'selected="selected"':''}} value="{{$s->supplier_type_id}}">{{$s->supplier_type}}</option>
                                                @endforeach
                                                  @endforeach
                                                
                                            
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                    
                                     <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="InputTitle">Supplier Name</label>
                                                    <select class="form-control" id="supplier_id_{{$i}}" onchange="supplierItems({{$quote->quote_id}}, this.value, {{$i}})" name="supplier_id[]" >
                                                        <option value="">Select Suppliers</option>
                                                         <?php
                                                         $quote_supplier_name = QuoteSupplier::where('quote_id',$quote->quote_id)->where('supplier_type_parent_id',$item->supplier_type_id)->get(); ?>
                                                        @foreach($quote_supplier_name as $ps)
                                                        <?php $suppliers = Supplier::where('supplier_id',$ps->supplier_id)->get(); ?>
                                                        @foreach($suppliers as $s)
                                                        <option {{$s->supplier_id==$item->supplier_id?'selected="selected"':''}} value="{{$s->supplier_id}}">{{$s->supplier_name}}</option>
                                                        @endforeach
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="InputTitle">Supplier Item</label>
                                            <select class="form-control" id="supplier_item_id_{{$i}}" name="supplier_item[]" >
                                                <option value="">Select Item</option>
                                              
                                                
                                                 <?php 
                                              
                                             
                                                 $supplier_items = SupplierItem::where('supplier_id',$item->supplier_id)->get(); ?>
                                                 @foreach($supplier_items as $si)
                                              <?php    $supplier_type_items = SupplierTypeItem::where('supplier_type_item_id',$si->supplier_type_item_id)->get();  ?>
                                                @foreach($supplier_type_items as $s)
                                                <option {{$s->supplier_type_item_id==$item->supplier_type_item_id?'selected="selected"':''}} value="{{$s->supplier_type_item_id}}">{{$s->supplier_item_name}}</option>
                                                @endforeach
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                </div>   <!--  <div class="row">     -->
                                  <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="InputTitle">Enter Cost</label>
                                            <input type="text"  id="cost_{{$i}}" name="cost[]" value="{{$item->cost}}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                
                              
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="InputTitle">Extra Notes</label>
                                            <textarea name="extra_notes[]" id="extra_notes_{{$i}}" class="form-control">{{$item->extra_notes}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                </div>
                               
                                <a href="javascript:void(0);" onclick="removeItem('{{$i}}');"><i class="fs fa-times"></i>&nbsp;Remove</a>

                            </div>
                            <hr />
                            
                            <?php $i++;
                                  $j++;
                            ?>
                            @endforeach
                        
                        <?php } else { ?>
                           
                            <div id="div_repeat_{{$i}}" class="holder_div row">
                                <div class="col-md-6">
                                   
                                    <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="InputTitle">Supplier Type</label>
                                                    <select class="form-control" id="supplier_type_id_{{$i}}" onchange="supplierTypeQuoteSuppliers({{$quote->quote_id}}, this.value, {{$i}})" name="supplier_type[]" >
                                                        <option value="">Select Supplier Type</option>
                                                        @foreach($quote_suppliers as $p)
                                                        <?php $supplier_type = SupplierType::where('supplier_type_id', $p->supplier_type_parent_id)->get(); ?>
                                                        @foreach($supplier_type as $s)
                                                        <option  value="{{$s->supplier_type_id}}">{{$s->supplier_type}}</option>
                                                        @endforeach
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="InputTitle">Supplier Name</label>
                                                    <select class="form-control" id="supplier_id_{{$i}}" onchange="supplierItems({{$quote->quote_id}}, this.value, {{$i}})" name="supplier_id[]" >
                                                        <option value="">Select Suppliers</option>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="InputTitle">Supplier Item</label>
                                            <select class="form-control" id="supplier_item_id_{{$i}}" name="supplier_item[]" >
                                                   <option value="">Select Item</option>
                                               
                                               
                                     
                                        
                                         
                                         
                                        
                                             </select>
                                        </div>
                                    </div>

                                </div>   <!--  <div class="row">     -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="InputTitle">Enter Cost</label>
                                            <input type="text" id="cost_{{$i}}" name="cost[]" value="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                    
                                </div>
                                <div class="col-md-6">
                                      <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="InputTitle">Extra Notes</label>
                                            <textarea name="extra_notes[]" id="extra_notes_{{$i}}" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                
                              
                              


                            </div>
                          
                        <?php 
                        
                        $i++;
                        
                        } ?>    


                    </div>
                </div>
                <div id="append_data"></div>

                <div class="row">
                    <div class="col-md-4" style='float:right;'>
                        <a id="more"  href='#'><i class='fa fa-plus'></i>Add New</a>             
                    </div>
                </div>

            </div><!-- /.box-body -->


            <div class="box-footer">

                <button class="btn btn-primary" type="submit">Submit</button>


            </div>



        </div>

</div>
</form>
</div>
<div id="add_new_template" style="display:none">

    <div id="div_repeat_index" class="holder_div row"> 
        <hr />
        <div class="col-md-6">
  <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="InputTitle">Supplier Type</label>
                    <select class="form-control" id="supplier_type_id_index" onchange="supplierTypeQuoteSuppliers({{$quote->quote_id}}, this.value, 'index')" name="supplier_type[]" >
                              <option value="">Select Supplier Type</option>
                                               @foreach($quote_suppliers as $p)
                                             <?php $supplier_type = SupplierType::where('supplier_type_id',$p->supplier_type_parent_id)->get(); ?>
                                                @foreach($supplier_type as $s)
                                                <option  value="{{$s->supplier_type_id}}">{{$s->supplier_type}}</option>
                                                @endforeach
                                                 @endforeach
                    </select>
                </div>
            </div>
        </div>
            
            <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="InputTitle">Supplier Name</label>
                    <select class="form-control" id="supplier_id_index" onchange="supplierItems({{$quote->quote_id}}, this.value, 'index')" name="supplier_id[]" >
                              <option value="">Select Suppliers</option>
                                            
                    </select>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="InputTitle">Supplier Item</label>
                    <select class="form-control" id="supplier_item_id_index" name="supplier_item[]" >
                                    <option value="">Select Item</option>
                                          
                                             </select>
                </div>
            </div>


        </div>   <!--  <div class="row">     -->
        
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="InputTitle">Enter Cost</label>
                    <input type="text" id="cost_index" name="cost[]" value="" class="form-control">
                </div>
            </div>
        </div>            
        </div>

        <div class="col-md-6">
            <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="InputTitle">Extra Notes</label>
                    <textarea name="extra_notes[]" id="extra_notes_index" class="form-control"></textarea>
                </div>
            </div>
        </div>
        </div>

      
        
        


        <a href="javascript:void(0);" onclick="removeItem('index');"><i class="fs fa-times"></i>&nbsp;Remove</a>
    </div>  

</div>              




@stop