@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif

<form class="form-inline" action="/chooseTemplate/confirm" method="post" name="actions_form" id="actions_form">
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
<input type="hidden" name="lead_id" value="{{$lead_id}}">

<div class="box box-danger">
    
        
    <div class="box-body">
         <div class="row">
             
             <div class="col-md-4">
                 <input type="submit" class="btn btn-primary" value="Confirm" id="submit_confirm"/> 
             </div>
             
            </div>
    </div>    
   
</div>


<div class='table-responsive col-md-8'>
    <table class="table table-hover table-bordered pull-left table-striped table-condensed admin-user-table">
    <thead>
        <tr>
            <th>&nbsp;</th>
            <th>Title</th>
            <th>Content</th>
       </tr>
     </thead>
     <tbody>
        @foreach($email_templates as $c)
       <tr>
           <td  data-title="Select">
               <input type="radio" class="check" name="cid[]" value="{{$c->email_template_id}}" id="cid{{$c->email_template_id}}" />
           </td>

           <td  data-title="Email Template Name">
                  {{$c->email_template_title}} 
           </td>
           
           <td  data-title="Content">
                  {{$c->content}} 
           </td>
          
           
       </tr>
       @endforeach
    </tbody>


</table>
</div>


</form>
    
<div class="row">
    <div class="col-md-12">
        {{$email_templates->appends(Input::except('page'))->links()}}
    </div>
</div>



@stop

