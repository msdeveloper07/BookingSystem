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
                                     <li><a href="/supplier/{{$supplier->supplier_id}}/edit">General</a></li>
                                    <li><a href="/supplier/contacts/{{$supplier->supplier_id}}">Contact</a></li>
                                    <li class="active"><a href="/supplier/items/{{$supplier->supplier_id}}">Supplier Item</a></li>
                                     
                                    <li><a href="/payment_method/{{$supplier->supplier_id}}">Payment Method</a></li>
                                    <li><a href="/Commission/{{$supplier->supplier_id}}">Commission</a></li>
                                    

                                </ul>
                               
                            </div><!-- nav-tabs-custom -->
                        </div><!-- /.col -->

                    </div>


<div class="row">
    <form  role="form" action='/supplier/items/{{$supplier->supplier_id}}' name='supplier_type_form' id='supplier_type_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

       
        
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                    <div style='float:right;'>
                            <a class="btn btn-success" href='/supplier'>Back To Supplier</a>             
                        </div>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div id="box_body1">
                        <?php $i = 1;?>
                        <?php if($supplierItems->count()>0){ ?>
                                @foreach($supplierItems as $s)
                                     <div id="div_repeat_{{$i}}" class="holder_div">
                                        <div class="row">
                                       <div class="col-md-4">
                                           <div class="form-group">
                                               <label for="InputTitle">Supplier Item</label>
                                               <select class="form-control" id="supplier_item_id_{{$i}}" name="supplier_item[]" >
                                                   <option value="">Select Item</option>
                                                   @foreach($supplierTypeItems as $item)
                                                   <option {{$s->supplier_type_item_id==$item->supplier_type_item_id?'selected="selected"':''}} value='{{$item->supplier_type_item_id}}'>{{$item->supplier_item_name}}</option>
                                                   @endforeach


                                               </select>
                                           </div>
                                       </div>

                                             <div class="col-md-4">
                                           <div class="form-group">
                                               <label for="InputTitle">Add New Supplier Item</label>
                                               <input type="text" name="supplier_new_item[]" value="" class="form-control">
                                           </div>
                                       </div>





                                   </div>   <!--  <div class="row">     -->
                                   <div class="row">
                                       <div class="col-md-4">
                                           <div class="form-group">
                                               <label for="InputTitle">Enter Cost</label>
                                               <input type="text"  id="cost_{{$i}}" name="cost[]" value="{{$s->cost}}" class="form-control">
                                           </div>
                                       </div>
                                   </div>
                                   <div class="row">
                                       <div class="col-md-6">
                                           <div class="form-group">
                                               <label for="InputTitle">Extra Notes</label>
                                               <textarea name="extra_notes[]" id="extra_notes_{{$i}}" class="form-control">{{$s->extra_notes}}</textarea>
                                           </div>
                                       </div>
                                   </div>
                                                       <a href="javascript:void(0);" onclick="removeItem('{{$i}}');"><i class="fs fa-times"></i>&nbsp;Remove</a>
 
                                       </div>
                                <?php $i++;?>
                                @endforeach
                        <?php }else{?>
                             <div id="div_repeat_{{$i}}" class="holder_div">
                         <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Item</label>
                                <select class="form-control" id="supplier_item_id_{{$i}}" name="supplier_item[]" >
                                    <option value="">Select Item</option>
                                    @foreach($supplierTypeItems as $item)
                                    <option value='{{$item->supplier_type_item_id}}'>{{$item->supplier_item_name}}</option>
                                    @endforeach
                                   
                                    
                                </select>
                            </div>
                        </div>
                             
                              <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Add New Supplier Item</label>
                                <input type="text" name="supplier_new_item[]" value="" class="form-control">
                            </div>
                        </div>

                     
                                


                    </div>   <!--  <div class="row">     -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Enter Cost</label>
                                <input type="text" id="cost_{{$i}}" name="cost[]" value="" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Extra Notes</label>
                                <textarea name="extra_notes[]" id="extra_notes_{{$i}}" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    
                        </div>
                        <?php } ?>    
                       
                    
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

  <div id="div_repeat_index" class="holder_div"> 
      <hr />
                    

                         <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Item</label>
                                <select class="form-control" id="supplier_item_id_index" name="supplier_item[]" >
                                    <option value="">Select Item</option>
                                  
                                     @foreach($supplierTypeItems as $item)
                                        <option value='{{$item->supplier_type_item_id}}'>{{$item->supplier_item_name}}</option>
                                     @endforeach
                                   
                                    
                                </select>
                            </div>
                        </div>
                             
                         <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Add New Supplier Item</label>
                                <input type="text"  name="supplier_new_item[]" value="" class="form-control">
                            </div>
                        </div>

                     
                                


                    </div>   <!--  <div class="row">     -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Enter Cost</label>
                                <input type="text" id="cost_index" name="cost[]" value="" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Extra Notes</label>
                                <textarea name="extra_notes[]" id="extra_notes_index" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    
                    <a href="javascript:void(0);" onclick="removeItem('index');"><i class="fs fa-times"></i>&nbsp;Remove</a>
          </div>  
    </div>              
                 
               
                                    
      
@stop