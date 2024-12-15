@extends('layouts.adminTemplate')

@section('content')



<div class="row">
    <div class="col-md-12">
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li ><a href="/bookings/{{$booking->booking_id}}/show">General</a></li>
                <li><a href="/bookings/{{$booking->booking_id}}/edit">Edit</a></li>
               <li><a href="/bookings/dates/{{$booking->booking_id}}">Dates</a></li>
                <li><a href="/bookings/suppliers/{{$booking->booking_id}}">Suppliers</a></li>
                <li><a href="/bookings/items/{{$booking->booking_id}}">Items</a></li>
                <li><a href="/bookings/itinerary/{{$booking->booking_id}}">Itinerary</a></li>
                 <li><a href="/bookings/tasks/{{$booking->booking_id}}">Tasks</a></li>
                <li  class="active"><a href="/bookings/payments/{{$booking->booking_id}}">Payments</a></li>
                   <li><a href="/bookings/cancelbooking/{{$booking->booking_id}}">Cancel Booking</a></li>
            </ul>
        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->
</div>
<div class="row">
 <div class="col-md-6">
<div class="row">
    <div class="col-md-12">
        <form  role="form" action='/bookings/savepayments/{{$booking->booking_id}}' autocomplete="off" name='Payments_form'  id='Payments_form' method="post">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Payments</h3>
                  
                </div><!-- /.box-header -->
           
                
                <div class="box-body">
                      <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Payment Type</label>
                                <select name="payment_type" id="payment_type" class="form-control" onchange="full(this.value)">
                                   <option value="">Select Payment Types</option>
                                   @foreach(Config::get('extras.payments') as $p)
                                <option value='{{$p}}'>{{$p}}</option>
                                @endforeach
                                
                              
                                </select>
                            </div>
                        </div>
                     
               
                    <div class="row">
                      <div class="col-md-6">
                      <div class="form-group">
                           <label>Amount</label>
                            <input type="text" class="form-control" name="amount" id="amount" required data-braintree-name="amount" onkeypress="return isNumber(event)" />
                       </div>
                       </div>
                       </div>
                <div id="due">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Next Due</label> <br />
                                <input type="text" class="form-control" name="due_date" id="due_date" />
                            </div>
                        </div>
                    </div>
                </div>
                    
                    
                      <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputTitle">Comment</label> <br />
                                <textarea class="form-control" name="comment" id="comment">write your comments here</textarea>
                            </div>
                        </div>
                      </div>
               
            </div><!-- /.box-body -->
            
                <div class="box-footer">
                       <button class="btn btn-primary" type="submit" >Submit</button>
                   </div>

    </div>
  

 </form>
        
</div>

</div>
         </div>
    <div class="col-md-6">
     
       <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                          <h3 class="box-title">Payment History</h3>
                          
                      <a  class='btn btn-info' style="float:right;">Total Due ={{$booking->booking_cost - $due}}</a> 
                          <a class='btn btn-success' style="float:right;">Booking Total ={{$booking->booking_cost}}</a>
                            
                    </div><!-- /.box-header -->
               @foreach($payments as $p) 
                <div class="box-body">
                            <div class="col-md-11">
                            <div class="form-group">
                                    
                                <h3>Payment On:&nbsp;{{ZnUtilities::format_date($p->submit_on, '2');}}</h3>

                            

                            </div>
                            </div>
                       
                    <div class="row">
                        
                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="InputTitle">Payment Type</label>

                                <p>{{$p->payment_type}}</p>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="form-group">

                            <label for="InputTitle">Amount</label>

                                <p>{{$p->amount}}</p>

                            </div>
                        </div>
                 

                        <div class="col-md-4">

                            <div class="form-group">

                            @if($p->next_due != '')
                            <label for="InputTitle">Next Due</label>

                                <p>{{$p->next_due}}</p>
                                @endif
                            </div>

                        </div>
                    </div>
                       
                        <div class="row">
                       
                            <div class="col-md-12">

                            <div class="form-group">

                            <label for="InputTitle">Comment</label>

                            <p>{{$p->comment}}</p>

                            </div>

                        </div>
                    </div>

<hr>
                </div>
               @endforeach
                <div class="row">
                        <div class="col-md-4">
                        </div>
               
                           
                              <div class="col-md-4">
                                  <div><b>Total Paid:</b>&nbsp;{{$due}}
                            </div>
                            </div>
                  </div>
           </div>

        </div>

</div>

     
    </div>
</div>


@stop
