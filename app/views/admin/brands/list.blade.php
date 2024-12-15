@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif



<form class="form-inline" action="/brandActions" method="post" name="actions_form" id="actions_form">

<div class="box box-danger">
    
        
    <div class="box-body">
         <div class="row">
             
             <div class="col-md-4">
                     Actions
                     <div class="form-group">
                        <select id="bulk_action" name="bulk_action" class="form-control" placeholder="Select Action"  >
                            <option value="">Select An Action</option>
<!--                            <option value="blocked">Block Selected FAQ</option>
                            <option value="active">Activate Selected FAQ</option>-->
                            <option value="delete">Delete Selected Brand</option>
                        </select>
                     </div>
                 
             </div>
             
             
                <div class="col-md-4">
                  
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                        <div class="input-group">
                          <input type="text" value="<?php if(isset($search)){ echo $search;}?>" class="form-control" name="search" id="search" placeholder="Search Brand">
                          <span class="input-group-btn">
                          <button type="submit" class="btn btn-default btn-flat">Find Brand</button>
                          </span>
                        </div>
                      
                </div>

             
                <div class="col-md-2">
                    <a href="/brands/create" class="btn btn-primary btn-flat">Add New Brand </a>
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
            <th>Brand Title</th>
            <th>Logo</th>
            <th>Brand Description</th>
            <th>&nbsp;</th>
            <th>Brand Settings</th>

           
       </tr>
     </thead>
     <tbody>
        @foreach($brands as $c)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$c->brand_id}}" id="cid{{$c->brand_id}}" />
           </td>
           
           

           <td  data-title="Question">
               <a href="/brands/{{$c->brand_id}}/edit/" title="">
                 {{$c->brand_title}} 
               </a>
           </td>
             <td>
            <img src="<?php echo url().'/brandimages/'.$c->logo; ?>" style="width:30px">
            </td>
        
            <td  data-title="Description">
               <?php echo substr($c->description,'0','40')  ?> 
               </a>
           </td>
           
               <td>
                    <a href="/brands/{{$c->brand_id}}/edit/" title="Edit"><i class="fa fa-pencil-square fa-lg"></i>&nbsp;Edit</a>
                  </td>
               <td>
                    <a href="/brands/settings/{{$c->brand_id}}" title="Settings"><i class="fa fa-gear"></i>&nbsp;Settings</a>
                  </td>
  
       </tr>
       @endforeach
    </tbody>


</table>
</div>


</form>
    
{{$brands->appends(Input::except('page'))->links();}}

@stop

