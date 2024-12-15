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
                <li><a href="/hotels/{{$hotel->hotel_id}}/edit">General</a></li>
                <li class="active"><a href="/hotel/properties/{{$hotel->hotel_id}}">Properties</a></li>


            </ul>
        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->
</div>

<div class="row">
    <form  role="form" action='/hotel/updateproperties/{{$hotel->hotel_id}}' name='user_form' id='user_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        <div class="col-md-10">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                        <?php $hotel_feature = HotelFeature::where('hotel_id',$hotel->hotel_id)->pluck('supplier_type_item_id');   ?>
                        @if($hotel_feature!='')
                        <div class="col-md-8">


                            <?php $i = 0; ?>
                            <?php $j = 0; ?>
                            @foreach($supplier_type_item as $s)
                            <input type="hidden" name="supplier_type_id" value="{{$s->supplier_type_id}}" />
                           
                            <div class="form-group">

                                <?php $field_type = SupplierTypeItem::where('supplier_type_item_id', $s->supplier_type_item_id)->pluck('field_type'); ?>


                                @if($field_type=='single')
                                 <label for="InputTitle">{{$s->supplier_item_name}}</label>
                                <?php
                                $values = $s->value;
                                $values = explode(',', $values)
                                ?>  
                                &nbsp;
                                </br>
                                
                                <div class="col-md-4">
                                    <?php $airline_fe = HotelFeature::where('hotel_id', $hotel->hotel_id)->where('supplier_type_item_id', $s->supplier_type_item_id)->get(); ?>
                                    <?php
                                    foreach ($airline_fe as $a) {
                                        $feat = $a->values;
                                        $feature = json_decode($feat);
                                    }
                                    ?>

                                    @foreach($feature as $f)
                                    
                                       
                                    <select name="property[{{$s->supplier_type_item_id}}][{{$j}}][name]" id="" class="form-control">

                                        <option value="">Please Select</option>
                                        @foreach($values as $v)
                                        <option {{$v==$f->name?'selected="selected"':''}} value="{{$v}}" >{{$v}}</option>

                                        @endforeach

                                    </select>
                                </div>

                                <input type="text" name="property[{{$s->supplier_type_item_id}}][{{$j}}][cost]" value="{{isset($f->cost)?$f->cost:''}}" /> Cost
                                            @endforeach      <!--<input type="hidden" name="property[{{$i}}][supplier_type]" value="{{$s->supplier_type_id}}" />-->
                                <?php $i++;
                                $j++; ?>

                                </br></br>

                                @else

                                <label for="InputTitle">{{$s->supplier_item_name}}</label> </br>
                                <?php
                                $values = $s->value;
                                $values = explode(',', $values)
                                ?>  
                                &nbsp;
                                </br>
                                <?php $k = 0; ?>

                                <?php $airline_fee = HotelFeature::where('hotel_id', $hotel->hotel_id)->where('supplier_type_item_id', $s->supplier_type_item_id)->get(); ?>
                                <?php
                                foreach ($airline_fee as $a) {

                                    $feat = $a->values;
                                    $feature = json_decode($feat);
                                }
                                ?>

                                @foreach($values as $v)
                                @foreach($feature as $f)
                                
                                <input type="checkbox" class="" {{$v==$f->name?'checked="checked"':''}}@endforeach id="hotel_name" name="property[{{$s->supplier_type_item_id}}][{{$k}}][name]" class="form-inline"  value="{{$v}}">&nbsp; {{$v}}
                                
                               &nbsp; &nbsp;  <input type="text" class="form-inline" name="property[{{$s->supplier_type_item_id}}][{{$k}}][cost]" value="{{isset($f->cost)?$f->cost:''}}"  > Cost  </input>
                              <!--<input type="hidden" name="property[{{$i}}][{{$j}}][supplier_type]" value="{{$s->supplier_type_id}}" />-->

                                <br />
                                <br />
                               
                                

                                <?php $k++; ?>


                                @endforeach




                                @endif


                            </div>
                            <?php $i++; ?>
                            @endforeach
                        </div>
                        @else
                        
                         <div class="col-md-8">
                            
                         
                            <?php $i = 0; ?>
                            <?php $j = 0; ?>
                            @foreach($supplier_type_item as $s)
                                                 <input type="hidden" name="supplier_type_id" value="{{$s->supplier_type_id}}" />
                             
                            <div class="form-group">

                                <?php $field_type = SupplierTypeItem::where('supplier_type_item_id', $s->supplier_type_item_id)->pluck('field_type'); ?>
                             
                                
                                @if($field_type=='single')
                                 <label for="InputTitle">{{$s->supplier_item_name}}</label>
                                <?php
                                $values = $s->value;
                                $values = explode(',', $values)
                                ?>  
                                &nbsp;
                                </br>
                                <div class="col-md-3">
                                <select name="property[{{$s->supplier_type_item_id}}][{{$j}}][name]" id="" class="form-control">
                                       <option value="">Please Select</option>
                                        
                                        @foreach($values as $v)
                                        <option  value="{{$v}}" >{{$v}}</option>

                                        @endforeach
                                       
                                    </select>
                                </div>

                                <input type="text" name="property[{{$s->supplier_type_item_id}}][{{$j}}][cost]" value="" /> Cost
                                                 <!--<input type="hidden" name="property[{{$i}}][supplier_type]" value="{{$s->supplier_type_id}}" />-->
                                <?php $i++; $j++; ?>
                                </br></br>
                                
                                
                                @else
                                 <label for="InputTitle">{{$s->supplier_item_name}}</label>
                              
                                <?php
                                $values = $s->value;
                                $values = explode(',', $values)
                                ?>  
                                &nbsp;
                                </br>
                            <?php $k = 0; ?>

                                @foreach($values as $v)
                                <input type="checkbox"   id="hotel_name" name="property[{{$s->supplier_type_item_id}}][{{$k}}][name]" class="form-inline"  value="{{$v}}">&nbsp; {{$v}}</input>
                                   <input type="text" name="property[{{$s->supplier_type_item_id}}][{{$k}}][cost]" value="" /> Cost
                                 <!--<input type="hidden" name="property[{{$i}}][{{$j}}][supplier_type]" value="{{$s->supplier_type_id}}" />-->
                              
                           
                                  <br />
                                <br />
                                <?php  $k++;  ?>
                                @endforeach


                                </br></br>
                                
                                @endif
                                

                            </div>
                            <?php $i++; ?>
                            @endforeach
                        </div>
                        @endif
                    </div>







                </div><!-- /.box-body -->
                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Update</button>
                </div>
            </div>
        </div>
    </form>
</div>

@stop