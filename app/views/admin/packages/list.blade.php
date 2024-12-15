@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif

<form class="form-inline" action="/packageActions" method="post" name="actions_form" id="actions_form">

<div class="box box-danger">
    
        
    <div class="box-body">
         <div class="row">
             
             <div class="col-md-4">
                     Actions
                     <div class="form-group">
                        <select id="bulk_action" name="bulk_action" class="form-control" placeholder="Select Action"  >
                            <option value="">Select An Action</option>
                          
                            <option value="delete">Delete Selected Package</option>
                        </select>
                     </div>
                 
             </div>
             
             
              <div class="col-md-5">
                  
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                        <div class="input-group">
                        
                              <input type="text" value="<?php if(isset($search)){ echo $search;}?>" class="form-control" name="search" id="search" placeholder="Search package">
                         <span class='input-group-btn'>
                          <button type="submit" class="btn btn-default btn-flat">Find Package</button>
                          </span>
                        </div>
                </div>

                <div class="col-md-2">
                    <a href="/package/create" class="btn btn-primary btn-flat">Add New Package</a>
                </div>

            </div>
    </div>    
   
</div>


<div class='table-responsive'>
    <table class="table table-hover table-bordered pull-left table-striped table-condensed admin-package-table">
    <thead>
        <tr>
            <th>
            <!-- <button id="checkall" class="btn-info">Toggle</button>-->
            <input type="checkbox" id="checkall" class="check" value="" />
            </th>
            <th>Package Name</th>
            
            <th>Main Image </th>
            <th>Location From</th>
            <th>Location To</th>
            
            <th>Days</th>
            <th>Date From</th>
            
            <th>Number Of Childern</th>
            <th>Number Of Adults</th>
<!--            <th>Adult Cost</th>
            <th>Child Cost</th>-->
<!--            <th>Package Total Cost</th>-->
            <th>Gallery</th>
            
           
            <th>&nbsp;</th>
           
       </tr>
     </thead>
     <tbody>
        @foreach($package as $c)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$c->package_id}}" id="cid{{$c->package_id}}" />
           </td>

           <td  data-title="User Name">
               <a href="/package/{{$c->package_id}}/show/" title="Show">
                 {{isset($c->package_name)?$c->package_name:''}} 
               </a>
           </td>
           
           <td>
               <?php // $package_image = PackageImage::where('package_id',$c->package_id)->pluck('attachment'); 
               ?>
               
               
                           <img src="<?php echo url().'/packageimage/'.$c->package_main_image; ?>" style="width:30px">
               
           </td>
           </td>
           
           <td>
         {{isset($c->loc_from->location_name)?$c->loc_from->location_name:''}} 
               </td>
       
           <td>
         {{isset($c->loc_to->location_name)?$c->loc_to->location_name:''}} 
               </td>
            <td>
         {{isset($c->date_from)?$c->date_from:''}} 
               </td>
                <td>
       {{isset($c->date_to)?$c->date_to:''}}
               </td>
                <td>
         {{isset($c->number_of_children)?$c->number_of_children:''}} 
               </td>
                <td>
         {{isset($c->number_of_adults)?$c->number_of_adults:''}} 
               </td>
<!--                <td>
         {{isset($c->adult_cost)?$c->adult_cost:''}} 
               </td>
                <td>
         {{isset($c->child_cost)?$c->child_cost:''}} 
               </td>-->
<!--               <td>
         {{isset($c->total_cost)?$c->total_cost:''}} 
               </td>-->
                        <td>
                            <a href="/packageImage/{{$c->package_id}}/">Upload&nbsp;({{PackageImage::where('package_id',$c->package_id)->get()->count()}})</a>
               </td>
           <td>
              <a href="/quotes/create/{{$c->package_id}}/" title="Create Quote"><i class="fa fa-plus fa-lg"></i>&nbsp;Create Quote</a>&nbsp;
              <a href="/package/{{$c->package_id}}/edit/" title="Edit"><i class="fa fa-pencil-square fa-lg"></i>&nbsp;Edit</a>
                  
           </td>
       </tr>
       @endforeach
    </tbody>
 

</table>
</div>


</form>
    
{{$package->appends(Input::except('page'))->links();}}

@stop