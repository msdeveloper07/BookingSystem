<?php

class UsersController extends BaseController {

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
    
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
            if(!User::checkPermission(Auth::user()->user_group_id,'users','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();

            $data['users'] = User::paginate('20');
           

            $basicPageVariables = ZnUtilities::basicPageVariables("Users"," All Users", "users","1");
            $data = array_merge($data,$basicPageVariables);
            
            
            $data['breadcrumbs']['Users'] = array("link"=>'/users',"active"=>'0');
           
            ZnUtilities::push_js_files('components/users.js');
            
             $data['active_nav'] = 'users';
            
             $data['submenus'] = $this->_submenus('users');
			
             return View::make('admin.users.list',$data);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'users','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
		$data = array();
                
                $data['userGroups'] = Usergroup::all();
                
                $basicPageVariables = ZnUtilities::basicPageVariables("Users"," Add new user", "add_user","1");
                $data = array_merge($data,$basicPageVariables);
                
                $data['breadcrumbs']['All User'] = array("link"=>'/users',"active"=>'0');
                $data['breadcrumbs']['New User'] = array("link"=>"","active"=>'1'); 
            
                $data['submenus'] = $this->_submenus('users');
               return View::make('admin.users.create',$data);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'users','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
           // ZnUtilities::pa($_POST);
            
//            
           // die;
            $validator = Validator::make(
                array(
                    'name' => Input::get('name'),
                    'email' => Input::get('email'),
                    //'password' => Input::get('password'),
                    'user_group_id' => Input::get('user_group_id'),
                   
                    ),
                array(
                    'name' => 'required',
                    'email' => 'required|unique:users',
                  //  'password' => 'required',
                    'user_group_id' => 'required',
                   
                    )
            );
            
            if($validator->passes())
            {
                $activation_code= ZnUtilities::random_string('alphanumeric','50');
                 
                
                
                $user = new User();
                $user->name = Input::get('name');
            //    $user->password =Hash::make(ZnUtilities::random_string('alphanumeric'));
                $user->email = Input::get('email');
               
                $user->registered_on  = date('Y-m-d H:i:s');
                $user->activation_code  = $activation_code;
                $user->reset_password_code  = ZnUtilities::random_string('alphanumeric','50');
                $user->user_group_id  = Input::get('user_group_id');
                $user->user_status  = Input::get('user_status');
                
              
                $user->save();
                
              /*  ZnUtilities::echoViewContent('emails.auth.new_user_activation',  array(
                            'name'=>Input::get('name'),
                            'activation_code'=>$activation_code));
                die;*/
                
                Mailgun::send('emails.auth.new_user_activation', 
                        array(
                            'name'=>Input::get('name'),
                            'activation_code'=>$activation_code), 
                        function($message){
                            $message->to(Input::get('email'), Input::get('name'))
                                    ->subject('Your account has been created');
                            }
                    );
                
                
                return Redirect::to('users')->with('success_message', 'User created successfully!');
            }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('users/create')->withErrors($validator)->withInput();;
            }
            
	}

        public function edit($id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'users','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            		$data = array();

                //Throw exception if project id does not exists
                $data['user'] = User::findOrFail($id);
                $data['userGroups'] = Usergroup::all();
		
                
              
                // Load Component JS
                 ZnUtilities::push_js_files("jquery.validate.min.js");
                 ZnUtilities::push_js_files("admin_components/users.js");
                

                
                $js = "$('.delete_form').submit(function() {
                        var c = confirm('Are you sure you want to delete this user?');
                        return c; 
                    });";
                 ZnUtilities::push_js($js);
                
                $basicPageVariables = ZnUtilities::basicPageVariables("Users","Edit user", "users","1");
                $data = array_merge($data,$basicPageVariables);
                
                 $data['breadcrumbs']['All User'] = array("link"=>'/users',"active"=>'0');
                 $data['breadcrumbs']['Edit User'] = array("link"=>"","active"=>'1'); 
               
                 $data['submenus'] = $this->_submenus('users');
                 
                return View::make('admin.users.edit',$data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
            if(!User::checkPermission(Auth::user()->user_group_id,'users','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            //skip email's unique validation if email is not changed
            $user = User::find($id);
            if($user->email==Input::get('email'))
            {
                  $validator = Validator::make(
                    array(
                        'name' => Input::get('name'),
                        'email' => Input::get('email'),
                      
                        'user_group_id' => Input::get('user_group_id'),

                        ),
                    array(
                        'name' => 'required',
                        'email' => 'required',
                        
                        'user_group_id' => 'required',
                    )
            );
            
            }
            else
            {
                 $validator = Validator::make(
                array(
                    'name' => Input::get('name'),
                    'email' => Input::get('email'),
                  
                    'user_group_id' => Input::get('user_group_id'),
                   
                    ),
                array(
                    'name' => 'required',
                    'email' => 'required|unique:users',
                   
                    'user_group_id' => 'required',
                   
                    )
            );
            
            }
            
            if($validator->passes())
            {
                
                $user->name = Input::get('name');
              
                if(Input::get('password')!='')
                    $user->password =Hash::make(ZnUtilities::random_string('alphanumeric'));
                
                $user->email = Input::get('email');
             
                $user->user_group_id  = Input::get('user_group_id');
                $user->user_status  = Input::get('user_status');
                $user->save();
                
                
                /*
                Mail::send('email_templates.new_user_activation', 
                        array(
                            'name'=>Input::get('name'),
                            'activation_code'=>$activation_code), 
                        function($message){
                            $message->to(Input::get('email'), Input::get('name'))
                                    ->subject('Welcome to the APGReports.com');
                            }
                    );
                */
                
                return Redirect::to('users/'.$id.'/edit/')->with('success_message', 'User Updated Successfully');
            }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('users/'.$id.'/edit/')->withErrors($validator)->withInput();
            }
            
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'users','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            // delete
		$user = User::find($id);
		$user->delete();

		// redirect
		return Redirect::to('users')->with('success_message', 'User deleted successfully!');
	}

        
        public function changeStatus($id)
        {
            if(!User::checkPermission(Auth::user()->user_group_id,'users','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
              $user = User::find($id);
              if($user->user_status=='active')
              {
                  $user->user_status = 'deactive';
                  $msg = 'User Deactivated!';
              }
              else if(($user->user_status=='deactive')||($user->user_status=='not_activated'))
              {
                  $user->user_status = 'active';
                    $msg = 'User Activated!';

              }
              
              $user->save();
              
              return Redirect::to('users/'.$id.'/edit')->with('success_message', $msg );
        }
        
        public static function resetPassword($id,$return='')
        {
            
            $reset_password = ZnUtilities::random_string('alphanumeric','50');
            $update = DB::table('users')
                ->where('id',$id)
                ->update(array('reset_password_code'=>$reset_password));
            
            $user = User::find($id);
            
//             ZnUtilities::echoViewContent('emails.auth.reminder', array(
//                    'name'=>$user->name,
//                    'reset_code'=>$reset_password
//                ));
//            die;
//          
            Mailgun::send('emails.auth.reminder', 
                    array(
                        'name'=>$user->name,
                        'reset_code'=>$reset_password), 
                    function($message) use ($user){
                        $message->to($user->email, Input::get('name'))
                                ->subject('Reset Password Link');
                        }
                );
           
             if($return!='')
             {
                return '1';
             }
                 
            return Redirect::to($_SERVER['HTTP_REFERER'])->with('success_message', "Reset password link Sent" );

            
        }
        
       public function userSearch($search)
        {
            if(!User::checkPermission(Auth::user()->user_group_id,'users','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
           
               
               $users = User::where("name","like","%".$search."%")
                            ->orWhere("email","like","%".$search."%")
                            ->paginate(); 
               
               $data = array();
               $data['users'] = $users;
                //Basic Page Settings
               
                $basicPageVariables = ZnUtilities::basicPageVariables("Users","Search results", "users","1");
                $data = array_merge($data,$basicPageVariables);
               
                $data['breadcrumbs']['All User'] = array("link"=>'/users',"active"=>'0');
               $data['breadcrumbs']['Search'] = array("link"=>"","active"=>'1'); 
               
                $data['search'] = $search;

                $data['submenus'] = $this->_submenus('users');
                return View::make('admin.users.list',$data);
               
           
          
        }
        
        public function userActions()
        {
             if(!User::checkPermission(Auth::user()->user_group_id,'users','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $search = Input::get('search');
            if($search!='')
            {
                return Redirect::to('/userSearch/'.$search);
            }
            else{
                
                
            //die(Input::get('bulk_action')   );
            
            $cid = Input::get('cid');
            $bulk_action = Input::get('bulk_action');
            if($bulk_action!='')
            {
                switch($bulk_action)
                {
                    case 'blocked':
                    {
                        foreach($cid as $id)
                        {
                            DB::table('users')
                                ->where('id', $id)
                                ->update(array('user_status' =>  'deactive'));
                        }
                       
                       return Redirect::to('/users/')->with('success_message', 'Rows Updated!');

                        break;
                    }
                    case 'active':
                    {
                        foreach($cid as $id)
                        {
                            DB::table('users')
                                ->where('id', $id)
                                ->update(array('user_status' =>  'active'));
                        }
                        
                       return Redirect::to('/users/')->with('success_message', 'Rows Updated!');
                    }
                    case 'delete':
                    {
                        
                        
                        foreach($cid as $id)
                        {
                            DB::table('users')
                                ->where('id', $id)
                                ->delete();
                        }
                        
                        return Redirect::to('/users/')->with('success_message', 'Rows Delete!');
                        break;
                    }
                } //end switch
            } // end if statement
            return Redirect::to('/users');
            }
        }
        
    private function _submenus($active)
    {   
        $data = array();
        if(User::checkPermission(Auth::user()->user_group_id,'users','manage'))
            $data['Users'] = array("link" => '/users', "active" => $active=='users'?'1':'0' ,"icon" => 'fa-user');
        
        if(User::checkPermission(Auth::user()->user_group_id,'user_group','manage'))
            $data['User Group'] = array("link" => '/usergroup', "active" => $active=='usergroups'?'1':'0' ,"icon" => 'fa-users');
        
        if(User::checkPermission(Auth::user()->user_group_id,'user_permission','manage'))
            $data['User Permissions'] = array("link" => '/permission', "active" => $active=='permissions'?'1':'0' ,"icon" => 'fa-lock');
        
        if(User::checkPermission(Auth::user()->user_group_id,'user_group_permission','manage'))
            $data['User Group Permissions'] = array("link" => '/usergrouppermission', "active" => $active=='usergroupPermissions'?'1':'0' ,"icon" => 'fa-user-secret');
        
        return $data;
    }


}
