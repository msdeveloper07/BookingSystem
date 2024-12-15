@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif


@if(count($email_templates_favorite)>0)
<div class="row">
    <div class="col-md-12">
        <h3>Favorite Templates</h3>  
    </div>
</div>





<div class="row">
    <?php $i = 0;?>
 @foreach($email_templates_favorite as $c)
 <div class="col-md-2">
        
        <div class="well well-sm needs_hover" id="f-{{$i}}" style="height:172px;width:172px">
             <h4>{{$c->email_template_title}}
<!--                <a href="/chooseTemplate/confirm/{{$lead_id}}/{{$c->email_template_id}}">{{$c->email_template_title}}</a> &nbsp;
                <a href="/removeFavorite/{{$c->email_template_id}}" title="Make Favorite">
                    <i class="fa fa-star"></i>
                </a>-->
             </h4>
        </div>
     <div class="contenthover" >
         <div class="row">
             <center><div class="col-md-12">
                     <a href="/chooseTemplate/confirm/{{$lead_id}}/{{$c->email_template_id}}" class="btn btn-primary">Select</a>
                 </div></center>
         </div>
         <br/>
         <div class="row">
             <center><div class="col-md-12">
                     <a href="/removeFavorite/{{$c->email_template_id}}" class="btn btn-warning">Un-Favorite</a>
                 </div></center>
         </div>
     </div>
     
     
     
    </div>
 <?php $i++;?>
 @endforeach
</div>
@endif


@if(count($email_templates)>0)
<div class="row">
  <div class="col-md-12">
    <h3>Templates</h3>     
  </div>
</div>
    
<div class="row">
 @foreach($email_templates as $c)
    <div class="col-md-2" style='margin-bottom:15px;'>
        <div class="well well-sm needs_hover"  style="height:172px;width:172px">
        <h4> {{$c->email_template_title}}
<!--            <a href="/chooseTemplate/confirm/{{$lead_id}}/{{$c->email_template_id}}">{{$c->email_template_title}}</a>
   &nbsp;
                <a href="/makeFavorite/{{$c->email_template_id}}" title="Make Favorite">
                    <i class="fa fa-star-o"></i>
                </a>-->
        </h4>
        </div>
        <div class="contenthover">
         <div class="row">
             <center><div class="col-md-12">
                     <a href="/chooseTemplate/confirm/{{$lead_id}}/{{$c->email_template_id}}" class="btn btn-primary">Select</a>
                 </div></center>
         </div>
         <br/>
         <div class="row">
             <center><div class="col-md-12">
                     <a href="/makeFavorite/{{$c->email_template_id}}" class="btn btn-success">Favorite</a>
                 </div></center>
         </div>
     </div>
        
    </div>
 @endforeach
</div>
@endif

<div class="row">
    <div class="col-md-12">
        
    </div>
</div>



@stop

