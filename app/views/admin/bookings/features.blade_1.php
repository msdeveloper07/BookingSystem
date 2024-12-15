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
                <li ><a href="/bookings/{{$booking->booking_id}}/show">General</a></li>
                <li><a href="/bookings/{{$booking->booking_id}}/edit">Edit</a></li>
               <li><a href="/bookings/dates/{{$booking->booking_id}}">Dates</a></li>
                <li class="active"><a href="/bookings/features/{{$booking->booking_id}}">Features</a></li>
                <li><a href="/bookings/itinerary/{{$booking->booking_id}}">Itinerary</a></li>
                <li><a href="/bookings/tasks/{{$booking->booking_id}}">Tasks</a></li>
                <li><a href="/bookings/payments/{{$booking->booking_id}}">Payments</a></li>
                   <li><a href="/bookings/cancelbooking/{{$booking->booking_id}}">Cancel Booking</a></li>
            </ul>
        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->
</div>

<div class="row">
    <form  role="form" action='/bookings/savefeatures/{{$booking->booking_id}}' name='supplier_type_form' id='supplier_type_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
 <div class="col-md-12">
        @foreach($supplier_type as $st)
  
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">{{$st->supplier_type}}</h3>
                </div><!-- /.box-header -->

                 <div class="box-body" >
                    <?php $booking_feature = BookingFeature::where('booking_id',$booking->booking_id)->pluck('supplier_id');  ?>
                    @if($booking_feature)
                     
                   @foreach($st->subTypes as $subType)
                     @if($subType->supplier_type=='Hotel'||$subType->supplier_type=='Airline')
                             <h3>{{$subType->supplier_type}}</h3>
                             <?php $items = SupplierTypeItem::where('supplier_type_id', $subType->supplier_type_id)->get();?>
                             <?php $m = 0;?>
                             @foreach($items as $item)
                             <div class="row">
                                     @if($item->field_type == 'single')
                                 <div class="col-md-4">
                                     <div class="form-group">
                                         <label>{{$item->supplier_item_name}}</label>
                                                
                                         <?php $booking_feature_item =  BookingFeature::where('supplier_id',$subType->supplier_type_id)->where('booking_id',$booking->booking_id)->get();
                                       $items_f = array();
                                       $it = array();
                                         foreach($booking_feature_item as $p)
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
                                     </div>
                                        <?php $booking_feature_item =  BookingFeature::where('supplier_id',$subType->supplier_type_id)->where('booking_id',$booking->booking_id)->get();
                                       $items_f = array();
                                       $it = array();
                                         foreach($booking_feature_item as $p)
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
                                          
                                @endif

                             </div>
                             
                               <?php $m++;?>
                                
                             @endforeach
                       @endif
                    @endforeach                          
                     <br/>
                     <hr>
                 @else
                 
                 
                 @foreach($st->subTypes as $subType)
                     @if($subType->supplier_type=='Hotel'||$subType->supplier_type=='Airline')
                             <h3>{{$subType->supplier_type}}</h3>
                             <?php $items = SupplierTypeItem::where('supplier_type_id', $subType->supplier_type_id)->get();?>
                             <?php $m = 0;?>
                             @foreach($items as $item)
                             <div class="row">
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
                                     </div>
                                                                          
                                     @foreach(explode(',', $item->value) as $value)
                                     <label class="checkbox-inline">
                                        <input  type="checkbox" id="inlineCheckbox1" value="{{$value}}" name="features[{{$st->supplier_type_id}}][{{$subType->supplier_type_id}}][{{$m}}][{{$item->supplier_item_name}}][]"> {{$value}}
                                      </label>
                                     @endforeach
                                 </div>
                                          
                                @endif

                             </div>
                             
                               <?php $m++;?>
                                
                             @endforeach
                       @endif
                    @endforeach                          
                     <br/>
                     <hr>
                 
                 @endif
                 
                  
                  
                  
            </div><!-- /.box-body -->
                    </div>

        </div>
  @endforeach
  </div>
    <div class="box-footer">
<button class="btn btn-primary" style="margin-left: 50%;" type="submit">Submit</button>
            </div>
 
    </form>
</div>
               

                                    
       
@stop