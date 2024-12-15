<?php

   //     use Illuminate\Database\Eloquent\ModelNotFoundException;


class LoginController extends BaseController
{
    
    
    public function __construct()
    {
        $this->beforeFilter('csrf', array('on' => 'post'));
    }
    
    public function getIndex()
    {
         $data = array();
                
        // Hide Navigation menu if user is not logged in
        $data['navigation'] = 0;
        $data['page_title'] = "Log In";
        
        ZnUtilities::push_js_files('jquery.validate.min.js');
        
        $js = "$(function() {
                $('#login_form').validate();
            });";
        ZnUtilities::push_js($js);
        
        return View::make('login',$data);
    }
    
    public function postProcessLogin()
    {
        
        $input = Input::only('email','password');
       // $password = Hash::make($input['password']);
        
        if (Auth::attempt(array('email' => $input['email'], 'password' => $input['password'])))
        {
            
            $user = User::find(Auth::user()->id);
            $user->last_login = date('Y-m-d H:i:s');
            $user->save();
         
            
            return Redirect::intended('/')->with('success_message', 'Welcome back!');
        }
        else
        {
             return Redirect::to('login')->with('error_message', 'Login Failed. Please check your login credentials');
        }
    }
    
    public function doLogout()
    {
        Auth::logout();
        return Redirect::to('login')->with('success_message', 'Logout Successful');
    }
    
    public function forgotPassword()
    {
          $data = array();
    
        // Hide Navigation menu if user is not logged in
        $data['navigation'] = 0;
        $data['page_title'] = "Forgot Password";
        
        return View::make('forgotPassword',$data);
}
    
    public function processForgotPassword()
    {
        $email = Input::get('email');
        $user_exist = User::where('email',$email)->count();
        
        if($user_exist=='0')
        {
             return Redirect::to('login/forgotPassword')->with('error_message', 'Sorry, that username/email was not found.');
        }

        $user = User::where('email',$email)->first();
        
       $mail_sent =  UsersController::resetPassword($user->id,'return');
           return Redirect::to('login')->with('success_message', 'An email is sent to you with password resent link');
       
        
    }
    
}
