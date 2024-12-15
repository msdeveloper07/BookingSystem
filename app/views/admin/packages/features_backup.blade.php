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
                 <li class="active"><a href="/package/features/{{$package->package_id}}">Features</a></li>
                <li><a href="/package/itinerary/{{$package->package_id}}">Itinerary</a></li>
                <li><a href="/package/quotes/{{$package->package_id}}">Quotes</a></li>
            </ul>
        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->
</div>
<div class="row">
    <form  role="form" action='/package/addsupplier/{{$package->package_id}}' name='supplier_type_form' id='supplier_type_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Features</h3>
                  
                </div><!-- /.box-header -->

                 <div class="box-body" >
                     @foreach($supplier_type as $st)
                   <div class="row">
                        <div class="col-md-12">
                            
                            <h4>{{$st->supplier_type}}</h4>
                          </div>
                    </div> 
                     <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle"></label>
                                <div class="form-group">
                                    <?php $value= SupplierType::where('supplier_type_parent_id',$st->supplier_type_id)->get();?>
                                    <select class="form-control" name="">
                                        @foreach($value as $v)
                                        <option {{($v->supplier_type=='Hotel'||$v->supplier_type=='hotel'||$v->supplier_type=='airline'||$v->supplier_type=='Airline')?'selected="selected"':''}} value="{{$v->supplier_type_id}}">{{$v->supplier_type}}</option>
                                        @endforeach
                                    </select>
                            </div>
                            </div>
                        </div>
                    </div>
<!--                     <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                 <select class="form-control" name="feature[]">
                                     <option value="">Please Select</option>
                                @if($st->supplier_type=='Accommodation')
                                <?php $hotel= Hotel::all(); ?>
                               
                                    
                                    @foreach($hotel as $h)
                                    <option value="{{$h->hotel_id}}">{{$h->hotel_name}}</option>
                                    @endforeach
                                
                                @elseif($st->supplier_type=='Travel')
                                <?php $airline= Airline::all(); ?>
                               
                                   
                                    @foreach($airline as $h)
                                    <option value="{{$h->airline_id}}">{{$h->airline_name}}</option>
                                    @endforeach
                                
                                @endif
                            </select>    
                                
                            
                            </div>
                        </div>
                    </div>-->
                        <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                @if($st->supplier_type=='Accommodation')
                                <?php $supplierTid= SupplierType::where('supplier_type','Hotel')->pluck('supplier_type_id'); ?>
                                @elseif($st->supplier_type=='Travel')
                                <?php $supplierTid= SupplierType::where('supplier_type','Airline')->pluck('supplier_type_id'); ?>
                                @endif
                               <?php $in=0; $item = SupplierTypeItem::where('supplier_type_id',$supplierTid)->get();?>
                              @foreach($item as $i)
                              <strong>{{$i->supplier_item_name}}</strong><br/>
                              
                             <?php $newvalue= explode(',', $i->value);
                             ?>
                              @foreach($newvalue as $v)
                              @if($i->field_type=="multiple")
                              <input type="checkbox"  value="{{$v}}"> &nbsp;{{$v}}
                              @elseif($i->field_type=="single")
                              <input type="radio" name="radio{{$in}}" value="{{$v}}"> &nbsp;{{$v}}
                              @endif
                              @endforeach
                            <?php $in++;?>
                              <br/>
                            @endforeach
                            </div>
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="InputTitle">Extra item</label>
                                <div class="form-group">
                                 <input type="text" placeholder="Extra Item" id="extra_item" name="extra_item[]" class="form-control" value="">
                            </div>
                            </div>
                        </div>
                    </div>
                                              
                     <br/>
                     <hr>
                  @endforeach
                  
            </div><!-- /.box-body -->
            
          
            <div class="box-footer">

                <button class="btn btn-primary" type="submit">Submit</button>
                &nbsp;
           
            </div>
            
            

            </div>

        </div>
    </form>
</div>
               

                                    
       
@stop