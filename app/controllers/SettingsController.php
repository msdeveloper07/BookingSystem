<?php

class SettingsController extends BaseController
{
     public function __construct()
        {
            $this->beforeFilter(function()
            {
                if(!Auth::check())
                {
                    return Redirect::to('login')->with('error_message',"You are either not logged in or you dont have permissions to access this page");
                }
                
            });
            
           
            
            $this->beforeFilter('csrf', array('on' => 'post'));
        }
        
        public function index()
	{
            // Check if user has permission to access this page
            if(!User::checkPermission(Auth::user()->user_group_id,'settings','manage'))
            {
                 return Redirect::to('admin/permissionDenied');
            };
            
            $data = array();
            $data['settings'] = Setting::paginate();
            
            $basicPageVariables = ZnUtilities::basicPageVariables("Settings"," All Settings", "settings","1");
             $data = array_merge($data,$basicPageVariables);
            
            $data['breadcrumbs']['All Settings'] = array("link"=>'/settings',"active"=>'1');
           
            // Load User Specific Javascripts
           //   ZnUtilities::push_js_files('components/settings.js');
            
             $data['submenus'] = $this->_submenus('index');
            return View::make('admin.settings.list',$data);
	}
        
        public function store()
        {
            // Check if user has permission to access this page
            if(!User::checkPermission(Auth::user()->user_group_id,'settings','manage'))
            {
                 return Redirect::to('admin/permissionDenied');
            };
            
             $validator = Validator::make(
                array(
                    'setting_name' => Input::get('setting_name'),
                    'setting_value' => Input::get('setting_value'),
                    ),
                array(
                    'setting_name' => 'required|unique:settings',
                    'setting_value' => 'required',
                    )
            );
             
             if($validator->passes())
            {
                $setting = new Setting();
                $setting->setting_name = Input::get('setting_name');
                $setting->setting_value = Input::get('setting_value');
                $setting->save();
                 
                return Redirect::to('settings')->with('success_message', 'Setting saved successfully!');
             }
             else
             {
                return Redirect::to('settings')->withErrors($validator)->withInput();
             }
        }
        
        public function update()
        {
            // Check if user has permission to access this page
            if(!User::checkPermission(Auth::user()->user_group_id,'settings','manage'))
            {
                 return Redirect::to('admin/permissionDenied');
            };
            
             $validator = Validator::make(
                array(
                    'setting_value' => Input::get('setting_value'),
                    ),
                array(
                    'setting_value' => 'required',
                    )
            );
             
         
             
             if($validator->passes())
            {
                foreach(Input::get('setting_value') as $key=>$s)
                {
                    $setting = Setting::find($key);
                    $setting->setting_value = $s[0];
                    $setting->save();
                }
               
                 
                return Redirect::to($_SERVER['HTTP_REFERER'])->with('success_message', 'Setting updated successfully!');
             }
             else
             {
                return Redirect::to($_SERVER['HTTP_REFERER'])->withErrors($validator)->withInput();
             }
        }
        
                private function _submenus($active)
    {   
        $data = array();
        $data['Settings'] = array("link" => '/settings', "active" => $active=='index'?'1':'0' ,"icon" => 'fa-gear');
        return $data;
    }
}

