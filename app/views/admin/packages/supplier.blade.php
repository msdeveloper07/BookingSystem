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
                  <li><a href="/package/{{$package->package_id}}/show">General</a></li>
                <li><a href="/package/{{$package->package_id}}/edit">Edit</a></li>
                <li><a href="/package/gallery/{{$package->package_id}}">Gallery</a></li>
                 <li class="active"><a href="/package/suppliers/{{$package->package_id}}">Supplier</a></li>
                <li><a href="/package/items/{{$package->package_id}}">Items</a></li>
                <li><a href="/package/itinerary/{{$package->package_id}}">Itinerary</a></li>
                <li><a href="/package/quotes/{{$package->package_id}}">Quotes</a></li>
            </ul>
        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->
</div>
<div class="row">
    <form  role="form" action='/package/savesupplier/{{$package->package_id}}' name='supplier_type_form' id='supplier_type_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                  
                </div><!-- /.box-header -->

                 <div class="box-body" >
                    
                    <div id="box_body1">
                        
                        <?php $i = 1;
                        $j = 0;
                        if($count > 0)
                        {
                         ?>  
                        @foreach($packagesuppliers as $p)
                        
                        
                         <div id="div_repeat_{{$i}}" class="holder_div">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Type</label>
                                   <div class="form-group">
                                    <select class="form-control" name="supplier_type_id[]"  id="supplier_type_id_{{$i}}" onchange="suppliertype(this.value, {{$i}})">
                                        <option value="">Please Select</option>
                                        <?php $supplier_types = SupplierType::where('supplier_type_parent_id','0')->get(); ?>
                                        @foreach($supplier_types as $s)
                                        <option {{$s->supplier_type_id==$p->supplier_type_parent_id?'selected="selected"':''}} value="{{$s->supplier_type_id}}">{{$s->supplier_type}} </option>
                                        @endforeach
                                    </select>
                             </div>
                            </div>
                        </div>
                    </div>
                
                     <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Sub Type</label>
                                   <div class="form-group">
                                       <select class="form-control" name="supplier_sub_type_id[]" id="supplier_sub_type_{{$i}}" onchange="supplierInfo(this.value, {{$i}})">
                                        <option value="">Please Select</option>
                                        <?php $suppliersubtype = SupplierType::where('supplier_type_parent_id',$p->supplier_type_parent_id)->get(); ?>
                                      @foreach($suppliersubtype as $s)
                                      <option {{$s->supplier_type_id==$p->supplier_type_id?'selected="selected"':''}} value="{{$s->supplier_type_id}}">{{$s->supplier_type}}</option>
                                      @endforeach
                                    </select>
                             </div>
                            </div>
                        </div>
                    </div>
                    
                       <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="InputTitle">Supplier</label>
                                   <div class="form-group">
                                    <select class="form-control" name="supplier_id[]" id="supplier_id_{{$i}}" >
                                        <option value="">Please Select</option>
                              <?php    $supplier = Supplier::where('supplier_sub_type_id',$p->supplier_type_id)->get(); ?>
                                        @foreach($supplier as $s)
                                        <option {{$s->supplier_id==$p->supplier_id?'selected="selected"':''}} value="{{$s->supplier_id}}">{{$s->supplier_name}}</option>
                                        @endforeach
                                    </select>
                             </div>
                            </div>
                        </div>
                    </div>
                       
                             <a href="javascript:void(0);" onclick="removeItem({{$i}});"><i class="fs fa-times"></i>&nbsp;Remove</a>
                        
                              
                        </div><!-- /Holder div -->
                           <hr />
                            
                            <?php $i++;
                            $j++;
                            ?>
                            @endforeach
                        <?php } 
                        
                        
                        else { ?>
                        
                        
                         <div id="div_repeat_{{$i}}" class="holder_div">
                             
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Type</label>
                                   <div class="form-group">
                                    <select class="form-control" name="supplier_type_id[]"  id="supplier_type_id_{{$i}}" onchange="suppliertype(this.value, {{$i}})">
                                        <option value="">Please Select</option>
                                        <?php $supplier_types = SupplierType::where('supplier_type_parent_id','0')->get(); ?>
                                        @foreach($supplier_types as $s)
                                        <option  value="{{$s->supplier_type_id}}">{{$s->supplier_type}} </option>
                                        @endforeach
                                    </select>
                             </div>
                            </div>
                        </div>
                    </div>
                
                     <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Sub Type</label>
                                   <div class="form-group">
                                       <select class="form-control" name="supplier_sub_type_id[]" id="supplier_sub_type_{{$i}}" onchange="supplierInfo(this.value, {{$i}})">
                                        <option value="">Please Select</option>
                                    
                                    </select>
                             </div>
                            </div>
                        </div>
                    </div>
                    
                       <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="InputTitle">Supplier</label>
                                   <div class="form-group">
                                    <select class="form-control" name="supplier_id[]" id="supplier_id_{{$i}}" >
                                        <option value="">Please Select</option>
                            
                                    </select>
                             </div>
                            </div>
                        </div>
                    </div>
                       
                             <a href="javascript:void(0);" onclick="removeItem({{$i}});"><i class="fs fa-times"></i>&nbsp;Remove</a>
                        
                              
                        </div><!-- /Holder div -->
                    
                        <?php 
                        $i++;
                        } ?>
                        
                        </div><!-- /.box-body1 -->
                     <div class="row">
                    <div class="col-md-4" style='float:right;'>
                        <a id="more"  href='#'><i class='fa fa-plus'></i>Add New</a>             
                    </div>
                </div>
                  
            </div><!-- /.box-body -->
            
          
            <div class="box-footer">

                <button class="btn btn-primary" type="submit">Submit</button>
                &nbsp;
           
            </div>
            
            

            </div>

        </div>
    </form>
</div>
               
<div id="add_new_template" style="display:none">
    <div id="div_repeat_index"  class="holder_div">
        <hr />
        
          <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Type</label>
                                   <div class="form-group">
                                    <select class="form-control" name="supplier_type_id[]" id="supplier_type_id_index" onchange="suppliertype(this.value, 'index')">
                                        <option value="">Please Select</option>
                                        <?php $supplier_types = SupplierType::where('supplier_type_parent_id','0')->get(); ?>
                                        @foreach($supplier_types as $s)
                                        <option  value="{{$s->supplier_type_id}}">{{$s->supplier_type}} </option>
                                        @endforeach
                                    </select>
                             </div>
                            </div>
                        </div>
                    </div>
                
                     <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Sub Type</label>
                                   <div class="form-group">
                                       <select class="form-control" name="supplier_sub_type_id[]" id="supplier_sub_type_index" onchange="supplierInfo(this.value, 'index')">
                                        <option value="">Please Select</option>
                               
                                    </select>
                             </div>
                            </div>
                        </div>
                    </div>
                    
                       <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Supplier</label>
                                   <div class="form-group">
                                    <select class="form-control" name="supplier_id[]" id="supplier_id_index">
                                        <option value="">Please Select</option>
                            
                                    </select>
                             </div>
                            </div>
                        </div>
                    </div>
                        <a href="javascript:void(0);" onclick="removeItem('index');"><i class="fs fa-times"></i>&nbsp;Remove</a>
                        
                              
                       
    
    
    
    </div>
      
    
     </div><!-- /Holder div -->
</div>            
               
                                    
       
@stop