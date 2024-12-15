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
                <li class="active"><a href="/quotes/features/{{$quote->quote_id}}">Features</a></li>
                <li><a href="/quotes/itinerary/{{$quote->quote_id}}">Itinerary</a></li>
                <li><a href="/quotes/bookings/{{$quote->quote_id}}">Bookings</a></li>
                
                
            </ul>
        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->
</div>



<div class="row">
    <form  role="form" action='/quotes/savefeatures/{{$quote->quote_id}}' name='supplier_type_form' id='supplier_type_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
 
        
        <div class="row">
            <?php $i = 1;?>
        @foreach($supplier_type as $st)
  <?php if($i%2!=0){?>
        <div class="row">
  <?php } ?>    
        <div class='col-md-6' style="height:50%;">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">{{$st->supplier_type}}</h3>
                </div><!-- /.box-header -->

                 <div class="box-body" >
                    <?php $quote_feature = QuoteFeature::where('quote_id',$quote->quote_id)->pluck('supplier_id');  ?>
                    @if($quote_feature)
                       <div class="row">
                         <div class="col-md-7">
                               <div class="form-group">
                     
                                <select  class="form-control sub_type" name="sub_type">
                                 <option value="">Please Select</option>
                                 @foreach($st->subTypes as $subType)

                                 <option value="{{$subType->supplier_type_id}}">{{$subType->supplier_type}}</option>
                                 @endforeach 
                                 </select>
                                </div>
                          </div>
                     </div>
                   @foreach($st->subTypes as $subType)
                  
                             
                             <?php $items = SupplierTypeItem::where('supplier_type_id', $subType->supplier_type_id)->get();?>
                                  
                             <?php $m = 0;?>
                   
                             @foreach($items as $item)
                            <div class="row sub{{$subType->supplier_type_id}}" style="display:none;" >
                                     @if($item->field_type == 'single')
                                 <div class="col-md-4">
                                     <div class="form-group">
                                         <label>{{$item->supplier_item_name}}</label>
                                                
                                         <?php $quote_feature_item =  QuoteFeature::where('supplier_id',$subType->supplier_type_id)->where('quote_id',$quote->quote_id)->get();
                                       $items_f = array();
                                       $it = array();
                                         foreach($quote_feature_item as $p)
                                         {
                                           $feature_item = json_decode($p->items);  
                                         
                                         }
                                         foreach($feature_item as $t)   
                                       {
                                         foreach($t as $Y=>$u)
                                          {
                                             
                                           $field_type = SupplierTypeItem::where('supplier_type_id',$subType->supplier_type_id)->where('supplier_item_name',$Y)->pluck('field_type');
                                          if($field_type=='single')
                                          {
                                           $items_f[] = $u;
                                                 
                                            }
                                          }
                                         
                                         }
                                         foreach($items_f as $f)
                                         {
                                          foreach($f as $j)
                                             {
                                               $it[] = $j;  
                                             }
                                           }
                                          ?>
                                         <select name="features[{{$st->supplier_type_id}}][{{$subType->supplier_type_id}}][{{$m}}][{{$item->supplier_item_name}}][]" class="form-control">
                                             <option value="">Please Select</option>
                                             @foreach(explode(',', $item->value) as $value)
                                             <option {{in_array($value,$it)?'selected="selected"':''}} value="{{$value}}">{{$value}}</option>
                                             @endforeach
                                         </select>
                                           </div>
                                 </div>
                              
                                @elseif($item->field_type == 'multiple')          
                                 <div class="col-md-12">
                                     <div class="form-group">
                                         <label>{{$item->supplier_item_name}}</label>
                                         <br/>
                                  
                                        <?php $quote_feature_item =  QuoteFeature::where('supplier_id',$subType->supplier_type_id)->where('quote_id',$quote->quote_id)->get();
                                       $items_f = array();
                                       $it = array();
                                         foreach($quote_feature_item as $p)
                                         {
                                           $feature_item = json_decode($p->items);  
                                         
                                         }
                                         foreach($feature_item as $t)   
                                       {
                                         foreach($t as $Y=>$u)
                                          {
                                             
                                           $field_type = SupplierTypeItem::where('supplier_type_id',$subType->supplier_type_id)->where('supplier_item_name',$Y)->pluck('field_type');
                                          if($field_type=='multiple')
                                          {
                                           $items_f[] = $u;
                                                 
                                            }
                                          }
                                         
                                         }
                                         foreach($items_f as $f)
                                         {
                                          foreach($f as $j)
                                             {
                                               $it[] = $j;  
                                             }
                                           }
                                          ?>
                                     
                                     @foreach(explode(',', $item->value) as $value)
                                     <label class="checkbox-inline">
                                        <input {{in_array($value,$it)?'checked="checked"':''}} type="checkbox" id="inlineCheckbox1" value="{{$value}}" name="features[{{$st->supplier_type_id}}][{{$subType->supplier_type_id}}][{{$m}}][{{$item->supplier_item_name}}][]"> {{$value}}
                                      </label>
                                     @endforeach
                                 </div>
                                 </div>
                                          
                                @endif

                             </div>
                             
                               <?php $m++;?>
                                
                             @endforeach
                   
                    @endforeach                          
                    
                 @else
                        <div class="row">
                         <div class="col-md-7">
                               <div class="form-group">
                   <select  class="form-control sub_type" name="sub_type">
                         <option value="">Please Select</option>
                     @foreach($st->subTypes as $subType)
                     
                     <option value="{{$subType->supplier_type_id}}">{{$subType->supplier_type}}</option>
                     @endforeach 
                     </select>
                               </div>
                         </div>
                            </div>
                 
                 @foreach($st->subTypes as $subType)
                   
                           
                             <?php $items = SupplierTypeItem::where('supplier_type_id', $subType->supplier_type_id)->get();?>
                             <?php $m = 0;?>
                          
                             @foreach($items as $item)
                         
                             <div class="row sub{{$subType->supplier_type_id}}" style="display:none;"  >
                                     @if($item->field_type == 'single')
                                 <div class="col-md-4">
                                     <div class="form-group">
                                         <label>{{$item->supplier_item_name}}</label>
                                           <select name="features[{{$st->supplier_type_id}}][{{$subType->supplier_type_id}}][{{$m}}][{{$item->supplier_item_name}}][]" class="form-control">
                                             <option value="">Please Select</option>
                                             @foreach(explode(',', $item->value) as $value)
                                             <option  value="{{$value}}">{{$value}}</option>
                                             @endforeach
                                         </select>
                                           </div>
                                 </div>
                              
                                @elseif($item->field_type == 'multiple')          
                                 <div class="col-md-12">
                                     <div class="form-group">
                                         <label>{{$item->supplier_item_name}}</label>
                                         </br>
                                     
                                                                          
                                     @foreach(explode(',', $item->value) as $value)
                                     <label class="checkbox-inline">
                                        <input  type="checkbox" id="inlineCheckbox1" value="{{$value}}" name="features[{{$st->supplier_type_id}}][{{$subType->supplier_type_id}}][{{$m}}][{{$item->supplier_item_name}}][]"> {{$value}}
                                      </label>
                                     @endforeach
                                     </div>
                                 </div>
                                          
                                @endif

                             </div>
                             
                               <?php $m++;?>
                                
                             @endforeach

                    @endforeach                          
                     <br/>
                     <hr>
                 
                 @endif
                 
                  
                  
                  
            </div><!-- /.box-body -->
                    </div>

        </div>
        
            <?php if($i%2==0){?>
        </div> <!--ROW ENDED--->
            
            <?php } $i++;?>
  @endforeach
  </div>
    <div class="box-footer">
<button class="btn btn-primary" style="margin-left: 50%;" type="submit">Submit</button> </div>
 
    </form>
</div>
               

                                    
       
@stop