<div class="sidebar" id="sidebar">
            <ul class="nav navbar-side">
                <li {{$component=="Dashboard"?'class="active"':""}}><a href="/"><i class="fa fa-desktop"></i><span>Dash-Board</span></a></li>
                
                @if(User::checkPermission(Auth::user()->user_group_id,'users','manage'))
                    <li class='{{$component=="Users"?"active":""}}'><a href="/users"><i class="fa fa-user"></i><span>Users</span></a></li>
                @endif
                @if(User::checkPermission(Auth::user()->user_group_id,'suppliers','manage'))
                <li class='{{$component=="Supplier"?"active":""}}'><a href="/supplier"><i class="fa fa-user"></i><span>Supplier</span></a></li>
                @endif
                @if(User::checkPermission(Auth::user()->user_group_id,'suppliers','manage'))
                <li class='{{$component=="SupplierType"?"active":""}}'><a href="/suppliertype"><i class="fa fa-th-list"></i><span>Supplier Type</span></a></li>
                @endif
                @if(User::checkPermission(Auth::user()->user_group_id,'hotels','manage'))
                <li class='{{$component=="Hotels"?"active":""}}'><a href="/hotels"><i class="fa fa-h-square"></i><span>Hotels</span></a></li>
                @endif
                @if(User::checkPermission(Auth::user()->user_group_id,'airlines','manage'))
                <li class='{{$component=="Airlines"?"active":""}}'><a href="/airlines"><i class="fa fa-plane"></i><span>Airlines</span></a></li>
                @endif
                
                @if(User::checkPermission(Auth::user()->user_group_id,'faq','manage'))
                <li class='{{$component=="Faqs"?"active":""}}'><a href="/faq"><i class="fa fa-archive"></i><span>Utility</span></a></li>
                @endif<!--
                @if(User::checkPermission(Auth::user()->user_group_id,'messages','manage'))
                <li class='{{$component=="Messages"?"active":""}}'><a href="/messages"><i class="fa fa-envelope"></i><span>Message</span></a></li>
                @endif
                @if(User::checkPermission(Auth::user()->user_group_id,'email_templates','manage'))
                <li class='{{$component=="Email Templates"?"active":""}}'><a href="/emailTemplates"><i class="fa fa-file-o"></i><span>Email Template</span></a></li>
                @endif-->
<!--                @if(User::checkPermission(Auth::user()->user_group_id,'settings','manage'))
                <li class='{{$component=="Settings"?"active":""}}'><a href="/settings"><i class="fa fa-gear"></i><span>Settings</span></a></li>
                @endif-->
<!--                 @if(User::checkPermission(Auth::user()->user_group_id,'currency','manage'))
                <li class='{{$component=="Currency"?"active":""}}'><a href="/currency"><i class="fa fa-usd"></i><span>Currency Converter</span></a></li>
                @endif-->
                 @if(User::checkPermission(Auth::user()->user_group_id,'currency','manage'))
                <li class='{{$component=="Contacts"?"active":""}}'><a href="/contacts"><i class="fa fa-list-alt"></i><span>Contacts</span></a></li>
                @endif
                
<!--                @if(User::checkPermission(Auth::user()->user_group_id,'currency','manage'))
                <li class='{{$component=="MailingList"?"active":""}}'><a href="/mailinglists"><i class="fa fa-th-list"></i><span>Mailing List</span></a></li>
                @endif
                
                @if(User::checkPermission(Auth::user()->user_group_id,'mailingList_subscriber','manage'))
                <li class='{{$component=="MailingListSubscribers"?"active":""}}'><a href="/mailinglistsubscribers"><i class="fa  fa-user-plus"></i><span>Mailing List Subscribers</span></a></li>
                @endif
                
                 @if(User::checkPermission(Auth::user()->user_group_id,'newsletter','manage'))
                <li class='{{$component=="Newsletters"?"active":""}}'><a href="/newsletters"><i class="fa fa-tasks"></i><span>NewsLetter</span></a></li>
                @endif-->
                
                 @if(User::checkPermission(Auth::user()->user_group_id,'location','manage'))
                <li class='{{$component=="Locations"?"active":""}}'><a href="/locations"><i class="fa fa-map-marker"></i><span>Locations</span></a></li>
                @endif
                
<!--                   @if(User::checkPermission(Auth::user()->user_group_id,'task','manage'))
                <li class='{{$component=="Task"?"active":""}}'><a href="/tasks"><i class="fa fa-tasks"></i><span>Task</span></a></li>
                @endif-->
                   
                @if(Auth::user()->user_group_id=='2')
                <li class='{{$component=="Task"?"active":""}}'><a href="/showTaskUser"><i class="fa fa-tasks"></i><span>Task</span></a></li>
                @endif
                
                  @if(User::checkPermission(Auth::user()->user_group_id,'package','manage'))
                <li class='{{$component=="Packages"?"active":""}}'><a href="/package"><i class="fa fa-building"></i><span>Package</span></a></li>
                @endif
                @if(User::checkPermission(Auth::user()->user_group_id,'quote','manage'))
                <li class='{{$component=="Quotes"?"active":""}}'><a href="/allquotes"><i class="fa fa-quote-right"></i><span>Quotes</span></a></li>
                @endif
                @if(User::checkPermission(Auth::user()->user_group_id,'booking','manage'))
                <li class='{{$component=="Bookings"?"active":""}}'><a href="/allbookings"><i class="fa fa-shopping-cart"></i><span>Bookings</span></a></li>
                @endif
              
                @if(User::checkPermission(Auth::user()->user_group_id,'brands','manage'))
                <li class='{{$component=="Brands"?"active":""}}'><a href="/brands"><i class="fa fa-bold"></i><span>Brands</span></a></li>
                @endif
                
                @if(User::checkPermission(Auth::user()->user_group_id,'brandvariables','manage'))
                <li class='{{$component=="BrandVariables"?"active":""}}'><a href="/brandvariables"><i class="fa fa-list"></i><span>BrandsVariables</span></a></li>
                @endif
                
<!--                @if(User::checkPermission(Auth::user()->user_group_id,'brands','manage'))
                <li class='{{$component=="BrandSettings"?"active":""}}'><a href="/brandsettings"><i class="fa fa-gear"></i><span>Brand Settings</span></a></li>
                @endif-->
<!--                @if(User::checkPermission(Auth::user()->user_group_id,'todolist','manage'))
                <li class='{{$component=="ToDoList"?"active":""}}'><a href="/todolist"><i class="fa fa-check-square-o"></i><span>TO Do Lists</span></a></li>
                @endif-->
                
                                </ul>
        </div><!--/.sidebar-->