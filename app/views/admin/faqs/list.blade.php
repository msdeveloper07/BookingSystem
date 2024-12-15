@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif

<form class="form-inline" action="/faqActions" method="post" name="actions_form" id="actions_form">

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
                            <option value="delete">Delete Selected FAQ</option>
                        </select>
                     </div>
                 
             </div>
             
             
                <div class="col-md-4">
                  
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                        <div class="input-group">
                          <input type="text" value="<?php if(isset($search)){ echo $search;}?>" class="form-control" name="search" id="search" placeholder="Search FAQs">
                          <span class="input-group-btn">
                          <button type="submit" class="btn btn-default btn-flat">Find FAQ</button>
                          </span>
                        </div>
                      
                </div>

                <div class="col-md-2">
                    <a href="/faqs/uploadcsv" class="btn btn-primary btn-flat">Upload Csv File</a>
                </div>
                <div class="col-md-2">
                    <a href="/faq/create" class="btn btn-primary btn-flat">Add New FAQ </a>
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
            <th>Question</th>
            <th>Answer</th>
<!--            <th>Status</th>-->
            <!--<th>&nbsp;</th>-->
           
       </tr>
     </thead>
     <tbody>
        @foreach($faq as $c)
       <tr>
           <td  data-title="Select">
               <input type="checkbox" class="check" name="cid[]" value="{{$c->faq_id}}" id="cid{{$c->faq_id}}" />
           </td>

           <td  data-title="Question">
               <a href="/result/{{$c->faq_id}}" title="">
                 {{$c->question}} 
               </a>
           </td>
           <td  data-title="Answer">
               
          
               
                  <?php echo substr($c->answer,'0','40'),'.','......'  ?> <a href="/result/{{$c->faq_id}}" title="">.continue
               </a>
           </td>
         
          
<!--           <td data-title="Status"><?php 
//            if($c->user_status=='active')
//            {
//                echo '<span class="text-success"><i class="fa fa-check"></i> Active</span>';
//            }
//            else if($c->user_status=='deactive')
//            {
//                echo '<span class="text-danger"><i class="fa fa-circle"></i> Deactive</span>';
//            }
//           ?></td>-->
           
         
       </tr>
       @endforeach
    </tbody>


</table>
</div>


</form>
    
{{$faq->appends(Input::except('page'))->links();}}

@stop

