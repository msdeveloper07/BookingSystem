@extends('layouts.blankTemplate')

@section('content')
  <div class="form-box" id="forgot-password-box">
         <div class="header">Forgot Password</div>
         <form id="login_form1" action="/processForgotPassword" method="post">
             <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
              <div class="body bg-gray">
                  <div class="form-group">
                        <input type="email" name="email" class="form-control required email" placeholder="your@email.com"/>
                   </div>
              </div>
          <div class="footer">                                                               
                    <button type="submit" class="btn bg-olive btn-block">Send Password</button>  
                    
                    <p><a href="/login">I know my password</a></p>
                   
                </div>
            </form>
        </div>

@stop
