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
                <li class="active"><a href="/package/{{$package->package_id}}/show">General</a></li>
                <li><a href="/package/{{$package->package_id}}/edit">Edit</a></li>
                <li><a href="/package/gallery/{{$package->package_id}}">Gallery</a></li>
                <li><a href="/package/features/{{$package->package_id}}">Features</a></li>
                <li><a href="/package/itinerary/{{$package->package_id}}">Itinerary</a></li>
                <li><a href="/package/quotes/{{$package->package_id}}">Quotes</a></li>

            </ul>
        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->
</div>


<div class="container" style="margin-top: 40px;">
    <div class="box-header">
        <h3 class="box-title"></h3>
      
    </div><!-- /.box-header -->
    <div class="row">

        <div class="span8">
            <h2>Booking Title: {{$package->package_name}}</h2>
        </div>

        <br/>

        <div class="span4 well">
            <table class="invoice-head">
                <tbody>
                    <tr>
                        <td class="pull-right"><strong>Customer #</strong></td>
                        <td>21398324797234</td>
                    </tr>
                    <tr>
                        <td class="pull-right"><strong>Invoice #</strong></td>
                        <td>2340</td>
                    </tr>
                    <tr>
                        <td class="pull-right"><strong>Date</strong></td>
                        <td>10-08-2013</td>
                    </tr>
                    <tr>
                        <td class="pull-right"><strong>Period</strong></td>
                        <td>9/1/2103 - 9/30/2013</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="span8">
            <h2>Description</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <p class="form-group">{{$package->package_desc}}</p> 
        </div>
    </div>


    <br/>
    <div class="row">
        <div class="span8">
            <h2>Package Detials</h2>
        </div>
    </div>
    <br/>
    <div class="row">
        <?php $location_from = Location::where('location_id', $package->location_from)->pluck('location_name') ?>
        <?php $location_to = Location::where('location_id', $package->location_to)->pluck('location_name') ?>
        <div class="span8 well invoice-body">
            <table class="table table-bordered">
                <thead>
                    <tr>

                        <th>Location From</th>
                        <th>Location TO</th>
                        <th>Date From</th>
                        <th>Date To</th>
                        <th>Number Of Days</th>
                        <th>Total Person</th>


                    </tr>
                </thead>
                <tbody>
                    <?php $totalperson = $package->number_of_children + $package->number_of_adults; ?>
                    <tr>
                        <td>{{$location_from}}</td>
                        <td>{{$location_to}}</td>
                        <td>{{ZnUtilities::format_date($package->date_from,'2')}}</td>
                        <td>{{ZnUtilities::format_date($package->date_to,'2')}}</td>

                        <td>{{$package->number_of_days}}</td>
                        <td>{{$totalperson}}</td>


                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <hr/>
    <br/>
    <div class="row">
        <div class="span8">
            <h2>Features Detials</h2>
        </div>
    </div>



    <br/>
    <div class="row span8 well invoice-thank">
        
            </br>      
            <h3>Package Feature  &nbsp; <a href="/package/features/{{$package->package_id}}">Edit</a> </h3> 
            </br>

            <?php $supplier_parent = PackageFeature::where('package_id', $package->package_id)->distinct()->lists('supplier_parent_id');  ?>
           
 @foreach($supplier_parent as $s)
  <?php $stpid = SupplierType::where('supplier_type_id', $s)->pluck('supplier_type');  ?>
     <div class="row span8 well invoice-thank">
                <h3>{{$stpid}}</h3>
                <br/>
             
    <?php  $items = PackageFeature::where('package_id',$package->package_id)->where('supplier_parent_id',$s)->get(); ?>
    @foreach($items as $i)
             <div class="col-md-3 span8 well invoice-thank">
          <?php  $stid = SupplierType::where('supplier_type_id', $i->supplier_id)->pluck('supplier_type'); ?>
               <h4>{{$stid}}</h4>
                <br/>
<?php $feature_item = json_decode($i->items); ?>
                @foreach($feature_item as $t=>$T)
                @foreach($T as $m=>$M)
                @foreach($M as $n)
                @if($n!='')
                <strong>   {{$m}}:- </strong>
                &nbsp;
                {{$n}}

                <br/>
                @endif
                
                @endforeach
                @endforeach
                @endforeach
                <br/>
               
                </div>
             <div class="col-lg-1">
                    &nbsp;
                </div>
                
            @endforeach
            </br>
               </div>
            @endforeach
        </div>
   


    <div class="row">
        <div class="span8">
            <h2>Itinerary</h2>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="span8 well invoice-thank">

            </br>
            @foreach($itineraries as $l) 
            <div class="row">

                <div class="col-md-12">
                    <label>Day  {{isset($l->days)?$l->days:''}} {{isset($l->itinerary_title)?"(".$l->itinerary_title.")":''}}</label>
                    <p>{{$l->extra_notes}}</p>
                </div>
                <div class="col-md-12">
                    <strong>Things to Do</strong>
<?php
$saved_things = explode(',', $l->things_todo);
$things = ThingToDo::whereIn('thing_todo_id', $saved_things)->get();
?>
                    @if(count($things)>0)
                    <ul>
                        @foreach($things as $t)
                        <li>{{$t->thing_todo}}</li>
                        @endforeach
                    </ul>
                    @endif
                </div> 
                <div class="col-md-12">
                    <strong>Image Gallery</strong>
                    <?php $itinerary_image = PackageItineraryImage::where('package_itinerary_id', $l->package_itinerary_id)->where('days', $l->days)->get(); ?>
                    <p>@foreach($itinerary_image as $i)

                        <img src="<?php echo url() . '/images/itinerary/' . $i->image; ?>"   height="100px">

                        @endforeach</p>
                </div>


            </div>
            <hr />
            @endforeach

        </div>

        <div class="row">
            <div class="span8 well invoice-thank">
                <h5 style="text-align:center;">Thank You!</h5>
            </div>
        </div>
        <div class="row">
            <div class="span3">
                <strong>Phone:</strong> <a href="tel:555-555-5555">555-555-5555</a>
            </div>
            <div class="span3">
                <strong>Email:</strong> <a href="mailto:hello@5marks.co">hello@bootply.com</a>
            </div>
            <div class="span3">
                <strong>Website:</strong> <a href="http://5marks.co">http://bootply.com</a>
            </div>
        </div>
    </div>
</div>
    @stop

