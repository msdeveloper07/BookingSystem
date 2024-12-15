@extends('layouts.adminTemplate')

@section('content')

<!--<button name="trigger" style="float:right;" class="btn btn-success" id="myBtn3" >Currency Converted</button>-->
<button  name="trigger" id="myBtn3" style="display:none;" > </button>   
            <article class="main-links">
                    <div class="row">
                        <div class="col-sm-6 col-lg-3">
                            <div class="links" style="background-color:#90d0b0;">
                                <div class="row">
                                    <a href="/allbookings" ><div class="col-xs-2"><i class="fa fa-shopping-cart"></i></div>
                                        <div class="col-xs-10">Bookings</div></a>
                                </div><!--row-->
                            </div>
                        </div><!--/.col-sm-6 col-lg-3-->
                        <div class="col-sm-6 col-lg-3">
                            <div class="links" style="background-color:#3db39e;">
                                <div class="row">
                                    <a href="/allquotes"> <div class="col-xs-2"><img src="/assets/images/latest-booking.png" alt="latest booking" /></div>
                                        <div class="col-xs-10">Quotes</div></a>
                                </div><!--row-->
                            </div>
                        </div><!--/.col-sm-6 col-lg-3-->
                        <div class="col-sm-6 col-lg-3">
                            <div class="links" style="background-color:#ecc558;">
                                <div class="row">
                                    <a href="/newsletters"> <div class="col-xs-2"><img src="/assets/images/send-newslaters.png" alt="latest booking" /></div>
                                        <div class="col-xs-10">Newsletter</div> </a>
                                </div><!--row-->
                            </div>
                        </div><!--/.col-sm-6 col-lg-3-->
                        
                        <div class="col-sm-6 col-lg-3">
                           
                         <div class="links" style="background-color:#42c4f0;">
                              <label for="myBtn3">  <div class="row">
                                    <div class="col-xs-2"><i class="fa fa-usd"></i></div><div class="col-xs-10">Converted</div>
                                      
                                </div><!--row--></label>
                            </div>
                             
                        </div><!--/.col-sm-6 col-lg-3-->
                    </div><!--/.row-->
                </article><!--/.main-links-->
            
                <article class="user-details">
                        <div class="row">
                            <div class="col-xs-6"><h2>Welcome : Super Admin</h2></div>
                            <?php $last_login = User::where('id',Auth::user()->id)->pluck('last_login'); ?>
                            <div class="col-xs-6"><h3 class="text-right">Last Login : <span>{{$last_login}}</span></h3></div>
                        </div><!--/.row-->             
                </article><!--/.user-details-->
                
                <article class="summary-details">
                        <h1 class="article-heading">Income Summary</h1>
                        <div class="row">
                            <div class="col-sm-6 col-lg-3">
                                <div class="summary">
                                        <div class="row">
                                            <div class="col-xs-6"><p>Today</p></div>
                                            <div class="col-xs-6"><h3 class="text-right" style="color:#629408;">$0</h3></div>
                                        </div><!--/.row-->
                                </div><!--summary-->
                            </div><!--col-sm-6 col-lg-3-->
                            <div class="col-sm-6 col-lg-3">
                                <div class="summary">
                                        <div class="row">
                                            <div class="col-xs-6"><p>This Month</p></div>
                                            <div class="col-xs-6"><h3 class="text-right" style="color:#349886;">$0</h3></div>
                                        </div><!--/.row-->
                                </div><!--summary-->
                            </div><!--col-sm-6 col-lg-3-->
                            <div class="col-sm-6 col-lg-3">
                                <div class="summary third">
                                        <div class="row">
                                            <div class="col-xs-6"><p>This Year</p></div>
                                            <div class="col-xs-6"><h3 class="text-right" style="color:#bf392b;">$133.5</h3></div>
                                        </div><!--/.row-->
                                </div><!--summary-->
                            </div><!--col-sm-6 col-lg-3-->
                            <div class="col-sm-6 col-lg-3">
                                <div class="summary year-sale-chart">
                                    <span class="chat-heading">Yearly Sales</span>
                                    <div id="year-sale-chart"></div>
                                </div><!--/.summary-->
                            </div><!--col-sm-6 col-lg-3-->
                        </div><!--row-->
                </article><!--sale-details-->
                
                 
            
                
          
                
                <article class="visit-details">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                
                                <article class="text-editor">
                      <h1 class="article-heading">Booking Tasks</h1>
                  
                
                
                 <?php
                $user_task = BookingTask::where('assign_to',Auth::user()->id)->where('task_status','!=','Closed')->orderBy('booking_task_id','desc')->pluck('booking_task_id');
                $admin = User::where('id',Auth::user()->id)->pluck('id');
               
                if($user_task)
                {
                $task = BookingTask::with('user_name')->where('assign_to',Auth::user()->id)->where('task_status','!=','Closed')->orderBy('booking_task_id','desc')->paginate();
                }
                elseif($admin=='1')
                {
                    $task = BookingTask::with('user_name')->where('task_status','!=','Closed')->orderBy('booking_task_id','desc')->paginate(); 
                }
                else
                {
                    
                } ?>   <?php  if($user_task||$admin=='1')
                
                  {  ?>
    <div class='table-responsive'>
    <table class="table table-hover table-bordered pull-left table-striped table-condensed admin-user-table">
    <thead>
        <tr> <th>Task Name</th>
            <th>Due Date</th>
          <th>&nbsp;</th></tr>
             </thead>
     <tbody>
        @foreach($task as $l)
       <tr>
           <td  data-title="Task Name">
               <a href="/staffnewTaskComment/{{$l->booking_task_id}}" title="Comment">
                 {{isset($l->task_title)?$l->task_title:''}}    </a>  </td>
          <td>  {{isset($l->due_date)?$l->due_date:''}}    </td>
             <td><a href="/staffnewTaskComment/{{$l->booking_task_id}}" title="Comment"><i class="fa fa-comment"></i>&nbsp;({{TaskComment::where('booking_task_id',$l->booking_task_id)->get()->count()}})</a> </td>
       </tr>
       @endforeach
    </tbody></table>
</div> <?php }    ?>
                            
                    
                </article><!--text-editor-->
                                
                                
                                
                            </div>
                            
                              <div class="col-md-9">
                                
                    <h1 class="article-heading">Visit Statistics of June:</h1>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="statement-chart">
                                <div id="statement-chart"></div>
                            </div><!--statement-chart-->
                        </div><!--/.col-sm-7-->
                        <div class="col-md-6" style="padding:0px;">
                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="total-earing">
                                        <div class="total-earing-inner" style="background-color:#9972b5;">
                                            <span class="pull-left">$540  (0.4%)</span>
                                            <span class="pull-right">Today</span>
                                            <div id="earing-today"></div>
                                            
                                            <h1>hello</h>
                                        </div><!--total-earing-inner-->
                                        <div class="total-statement">
                                            <h3>540.00 $</h3>
                                            <p>Total Earnings Today</p>
                                        </div><!--total-statement-->
                                        <div class="canvas">
                                            <div class="circle" id="circles-1"></div>
                                        </div>
                                    </div><!--/.total-earing-->
                                    
                                    <div class="total-earing">
                                        <div class="total-earing-inner" style="background-color:#de6734;">
                                            <span class="pull-left">$540  (0.4%)</span>
                                            <span class="pull-right">Today</span>
                                            <div id="earing-monthly"></div>
                                        </div><!--total-earing-inner-->
                                        <div class="total-statement">
                                            <h3>540.00 $</h3>
                                            <p>Total Earnings Today</p>
                                        </div><!--total-statement-->
                                        <div class="canvas">
                                            <div class="circle" id="circles-2"></div>
                                        </div>
                                    </div><!--/.total-earing-->
                                </div><!--/.col-sm-7-->
                                <div class="col-sm-5">
                                    <div class="visitors-count">
                                        <div id="donut-blue" class="graph"></div>
                                    </div><!--visitors-count-->
                                    <div class="visitors-count">
                                        <div id="donut-orange" class="graph"></div>
                                    </div><!--visitors-count-->
                                </div><!--/.col-sm-5-->
                            </div><!--/.row-->
                        </div><!--/.col-sm-5-->
                    </div><!--/.row-->
                    </div>
                    </div>
                    </div>
                </article><!--sale-details-->
                
            
                










@stop