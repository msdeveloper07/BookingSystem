@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif

<form class="form-inline" action="/hotelActions" method="post" name="actions_form" id="actions_form">

<div class="box box-danger">
    
        
    <div class="box-body">
         <div class="row">
             
             <div class="col-md-4">
                     Actions
                     <div class="form-group">
                        <select id="bulk_action" name="bulk_action" class="form-control" placeholder="Select Action"  >
                            <option value="">Select An Action</option>
<!--                            <option value="blocked">Block Selected Hotel</option>
                            <option value="active">Activate Selected Hotel</option>-->
                            <option value="delete">Delete Selected Hotel</option>
                        </select>
                     </div>
                 
             </div>
             
             
                <div class="col-md-4">
                  
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                        <div class="input-group">
                          <input type="text" value="<?php if(isset($search)){ echo $search;}?>" class="form-control" name="search" id="search" placeholder="Search Hotels">
                          <span class="input-group-btn">
                          <button type="submit" class="btn btn-default btn-flat">Find Hotel</button>
                          </span>
                        </div>
                      
                </div>

              
                <div class="col-md-2">
                    <a href="/hotels/create" class="btn btn-primary btn-flat">Add New Hotel </a>
                </div>

            </div>
    </div>    
   
</div>


<div class='table-responsive'>
    <table class="table table-hover table-bordered pull-left table-striped table-condensed admin-user-table">
    <thead>
        <tr>
            <th>
            <!-- <button id="checkall" class="btn-info">Toggle</button>-->
            <input type="checkbox" id="checkall" class="check" value="" />
            </th>
            <th>Hotel Name</th>
            <th>Location</th>
            <th>Nearest Airport</th>
            <th>&nbsp;</th>

       </tr>
     </thead>
     <tbody>
        @foreach($hotel as $c)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$c->hotel_id}}" id="cid{{$c->hotel_id}}" />
           </td>

           <td  data-title="Question">
               <a href="/hotels/{{$c->hotel_id}}/edit/" title="">
                 {{$c->hotel_name}} 
               </a>
           </td>
           <td> {{$c->location}} </td>
           <td> {{$c->nearest_airport}} </td>
           </td>
         
   <td>
              <a href="/hotels/{{$c->hotel_id}}/edit/" title="Edit"><i class="fa fa-pencil-square fa-lg"></i>&nbsp;Edit</a>
                  
           </td>
           
         
       </tr>
       @endforeach
    </tbody>


</table>
</div>


</form>
    
{{$hotel->appends(Input::except('page'))->links();}}

@stop

