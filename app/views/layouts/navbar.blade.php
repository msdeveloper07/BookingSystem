<nav class="navbar navbar-fixed-top">
        <div class="navbar-header">
          
          <a class="navbar-brand visible-xs" id="menu-xs" href="#"><i class="fa fa-navicon"></i></a>
          <a class="navbar-brand hidden-xs" id="menu-sm" href="#"><i class="fa fa-navicon"></i></a>
        </div>
        
    <div class='navbar-header'>
        <a href="/" class="logo">
              {{Setting::where('setting_name','site_name')->first()->setting_value}}
            </a>
        
    </div>
     <form id="header-search">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Search">
              <span class="input-group-btn">
                <button class="btn" type="button"><i class="fa fa-search"></i></button>
              </span>
            </div><!-- /input-group -->
        </form><!--/.form-->
        
            <button class="close-btn" id="close-btn"><i class="fa fa-search"></i></button>
        
            
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                <i class="fa fa-user"></i>
                <i class="fa fa-angle-down"></i>
              </a>
              <ul class="dropdown-menu" role="menu" aria-labelledby="drop2">
                <li role="presentation"><a role="menuitem" tabindex="-1" href="/users/{{Auth::user()->id}}/edit">Profile Edit</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="/image/create">Upload Pic</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="/changePassword">Change Password</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="/logout">Log Out</a></li>
              </ul>
            </li>
          </ul>
        <?php $user_image = Upload::where('id',Auth::user()->id)->pluck('file_name');?>
        <div class="user-detail">
            <a href="/profile"><img src="<?php echo url().'/images/profile_pic/'.$user_image; ?>" alt="user" title="{{Auth::user()->name}}" /> </a><span>Welcome, <a href="/profile">{{Auth::user()->name}}</a></span>
            &nbsp; <span><a href="/messages" class="btn-primary"><i class="fa fa-envelope fa-lg" > </i></a></span>
            &nbsp; <span><a href="/tasks" class="btn-primary"><i class="fa fa-tasks fa-lg" > </i></a></span>
            &nbsp; <span><a href="/settings" class="btn-primary"><i class="fa fa-gear fa-lg" > </i></a></span>
        </div><!--/.user-detail-->
        
        
        
        <button class="fa fa-usd currency-converter" name="trigger" title="Currency-Converter" id="myBtn3"></button>
        
    </nav><!--/.top-nav-->