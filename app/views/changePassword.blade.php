@extends('layouts.blankTemplate')

@section('content')

  <div class="form-box" id="login-box">
            <div class="header">{{Auth::user()->password==''?'Create your password':'Change your password'}}</div>
            <form role="form" id="changePassword_form" action="/changePassword" method="post">
              <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <div class="body bg-gray">
                    <div class="form-group">
                        <input type="password" class="form-control required" name="password" id="password" placeholder="Choose your password">
                    </div>
                    <div class="form-group">
                       <input type="password" class="form-control required" id="cpassword" name="cpassword" equalTo="#password" placeholder="Re-enter your Password" />
                    </div>          
                   
                </div>
                <div class="footer">                                                               
                    <button type="submit" class="btn bg-olive btn-block">{{Auth::user()->password==''?'Sign me in':'Change Password'}}</button>  
                </div>
            </form>
        </div>





    

@stop
