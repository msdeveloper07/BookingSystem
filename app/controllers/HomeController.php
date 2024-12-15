<?php

class HomeController extends BaseController {

	public function __construct()
        {
            $this->beforeFilter(function()
            {
                if(!Auth::check())
                {
                    return Redirect::to('login')->with('error_message',"Please login to access features of this website");
                }
                 if(Auth::user()->reset_password_code!='')
                {
                    return Redirect::to('changePassword')->with('error_message',"Please create your password");

                }
                
                $user = User::find(Auth::user()->id);
                if($user->user_status=='deactive')
                {
                    Auth::logout();
                    return Redirect::to('login')->with('error_message',"Your Account is Disabled. Contact Support for more information.");
                }
                
            });
            
          
            $this->beforeFilter('csrf', array('on' => 'post'));
        }
        
	public function getIndex()
	{
            $data = array();
            
           
            $basicPageVariables = ZnUtilities::basicPageVariables("Dashboard","Booking System","1");
            $data = array_merge($data,$basicPageVariables);
            ZnUtilities::push_js_files("jquery.validate.min.js");
            ZnUtilities::push_js_files('components/currencyConverter.js');
            ZnUtilities::push_js_files('raphael-min.js');
            ZnUtilities::push_js_files('prettify.min.js');
            ZnUtilities::push_js_files('circles.js');
            ZnUtilities::push_js_files('redactor.js');
            ZnUtilities::push_js_files('morris.js');
            ZnUtilities::push_js_files('components/dashboard.js');
            

        
            
          


        return View::make('dashboard',$data);
            
        }

      public function  getContacts()
        {
            $url = 'http://leads.dev/api';
            
            // create curl resource
            $ch = curl_init();
            // set url
            curl_setopt($ch, CURLOPT_URL, $url);
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // $output contains the output string
            $output = curl_exec($ch);
            // close curl resource to free up system resources
            curl_close($ch);      
            
            echo $output;
        }
        
        
         
}
