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





<form class="form-inline" action="/quotesActions" method="post" name="actions_form" id="actions_form">


<div class="box box-danger">
    <div class="box-body">
         <div class="row">
             
             <div class="col-md-4">
                     Actions
                     <div class="form-group">
                        <select id="bulk_action" name="bulk_action" class="form-control" placeholder="Select Action"  >
                            <option value="">Select An Action</option>
                            <option value="delete">Delete Selected Quote</option>
                        </select>
                     </div>
                 
             </div>
             
             
              <div class="col-md-5">
                        <div class="input-group">
                        
                              <input type="text" value="<?php if(isset($search)){ echo $search;}?>" class="form-control" name="search" id="search" placeholder="Search quote">
                         <span class='input-group-btn'>
                          <button type="submit" class="btn btn-default btn-flat">Find Quote</button>
                          </span>
                        </div>
                </div>
		    <div class="col-md-2">
                    <a href="/quotes/create" class="btn btn-primary btn-flat">Add New Quote</a>
                </div>
              

            </div>
    </div>    
   
</div>


<div class='table-responsive'>
    <table class="table table-hover table-bordered pull-left table-striped table-condensed admin-quote-table">
    <thead>
        <tr>
            <th>
            <!-- <button id="checkall" class="btn-info">Toggle</button>-->
            <input type="checkbox" id="checkall" class="check" value="" />
            </th>
            <th>Quote Title</th>
          
            <th>Created On</th>
            <th>Discounts</th>
            <th>Quoted Cost</th>
            <th>&nbsp;</th>
           
       </tr>
     </thead>
     <tbody>
        @foreach($quotes as $c)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$c->quote_id}}" id="cid{{$c->quote_id}}" />
           </td>

           <td  data-title="User Name">
               <a href="/quotes/{{$c->quote_id}}/show/" title="Edit">
                 {{isset($c->quote_title)?$c->quote_title:''}} 
               </a>
           </td>
           
          
           
           
         
                <td>
         {{ZnUtilities::format_date($c->created_on,'2')}} 
               </td>
                <td>
        {{$c->discount}} 
               </td>
                <td>
          {{$c->quoted_cost}} 
               </td>
                   
           <td>
              <a href="/quotes/{{$c->quote_id}}/edit/" title="Edit"><i class="fa fa-pencil-square fa-lg"></i>&nbsp;Edit</a>
                  
           </td>
       </tr>
       @endforeach
    </tbody>
 

</table>
</div>


</form>
    


@stop