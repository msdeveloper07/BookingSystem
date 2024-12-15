@extends('layouts.blankTemplate')

@section('content')
        <div class="form-box" id="login-box">
            <div class="header">Sign In</div>
            <form role="form" id="login_form" action="/login/process-login" method="post">
              <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <div class="body bg-gray">
                    <div class="form-group">
                        <input type="email" name="email" class="form-control required email" placeholder="your@email.com"/>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control required" placeholder="**********" >
                    </div>          
                   
                </div>
                <div class="footer">                                                               
                    <button type="submit" class="btn bg-olive btn-block">Sign me in</button>  
                    
                    <p><a href="/forgotPassword">I forgot my password</a></p>
                   
                </div>
            </form>
        </div>


    
    
@stop
