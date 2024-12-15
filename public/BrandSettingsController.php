<?php

class BrandSettingsController extends BaseController
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
            $data['brand_settings'] = BrandSetting::with('variable_name')->paginate();
            $data['brand_variable'] = BrandVariable::get();
            
            
            $basicPageVariables = ZnUtilities::basicPageVariables("Settings"," All Settings", "settings","1");
             $data = array_merge($data,$basicPageVariables);
            
            $data['breadcrumbs']['All Settings'] = array("link"=>'/brandsettings',"active"=>'1');
           
            // Load User Specific Javascripts
           //   ZnUtilities::push_js_files('components/settings.js');
            
             $data['submenus'] = $this->_submenus('index');
            return View::make('admin.brandsettings.list',$data);
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
                    'brand_variable' => Input::get('brand_variable'),
                    'setting_value' => Input::get('setting_value'),
                    ),
                array(
                    'brand_variable' => 'required',
                    'setting_value' => 'required',
                    )
            );
             
             if($validator->passes())
            {
                $setting = new BrandSetting();
                $setting->brand_variable_id = Input::get('brand_variable');
                $setting->values = Input::get('setting_value');
                $setting->save();
                 
                return Redirect::to('brandsettings')->with('success_message', 'Brand Setting saved successfully!');
             }
             else
             {
                return Redirect::to('brandsettings')->withErrors($validator)->withInput();
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
                    $setting = BrandSetting::find($key);
                    $setting->values = $s[0];
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

