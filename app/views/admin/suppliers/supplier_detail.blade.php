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
@foreach($supplier as $c)
<div class="row">
            <div class="col-md-7">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Supplier Detail</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                  <label for="InputTitle">Supplier Name:-</label> 
                               {{$c->supplier_name}} 
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Location:-</label>
                               
                                   
                                {{$c->loc->location_name}}
                                   
                            
                            </div>
                            </div>
                        </div>
                    
                    <div class="row">
                         <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Email:-</label>
                               
                                     {{$c->supplier_email}}
                            </div>
                        </div>
                     </div>
                         <div class="row">
                           <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Phone:-</label>
                               
                                     {{$c->supplier_phone}}  
                            </div>
                        </div>
                         </div> 
                         
                    
                     <div class="box-header">
                    <h3 class="box-title">Supplier Type,Item, Item Cost Detail</h3>
                </div><!-- /.box-header -->
                      
                        
                     <div class="row">
                         <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Type:-</label>
                               
                                    {{$c->supptype->supplier_type}}
                            </div>
                        </div>
                     </div>
                         <div class="row">
                         <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Item Name:-</label>
                               
                                     {{$c->suppitem->supplier_item_name}}
                            </div>
                        </div>
                        </div>
                         <div class="row">
                         <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Supplier Item Cost:-</label>
                               
                                     {{$c->supplier_cost}}
                            </div>
                        </div>
                         </div>
                     
                             
       
       

                 
                     

@endforeach

                     <!--  <div class="row">     -->
                  
            </div><!-- /.box-body -->
            
          
            <div class="box-footer">

                <a href="/supplier/{{$c->supplier_id}}/edit/" title="Edit"> <button class="btn btn-primary" type="submit">Edit Supplier</button></a>
                &nbsp;
           
            </div>
            
            

            </div>

        </div>
    
</div>
               
                 
               
                                    
       
@stop