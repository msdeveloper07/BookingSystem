<?php

class PermissionsController extends BaseController {

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
            if(!User::checkPermission(Auth::user()->user_group_id,'user_permission','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();

            $data['permission'] = Permission::orderBy('permission_id','desc')->paginate(10);
           

            $basicPageVariables = ZnUtilities::basicPageVariables("Permissions"," All Permissions", "permissions","1");
            $data = array_merge($data,$basicPageVariables);
            $data['breadcrumbs']['ALL Permissions'] = array("link"=>'/permission',"active"=>'0');
            ZnUtilities::push_js_files('components/users.js');
            
            $data['submenus'] = $this->_submenus('permissions');
            
	    return View::make('admin.permissions.list',$data);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'user_permission','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
		$data = array();
                
                $data['permission'] = Permission::all();
                
                $basicPageVariables = ZnUtilities::basicPageVariables("Permissions"," Add New Permissions ", "add_permissions","1");
                $data = array_merge($data,$basicPageVariables);
                
                $data['breadcrumbs']['All Permissions'] = array("link"=>'/permission',"active"=>'0');
                $data['breadcrumbs']['New Permissions'] = array("link"=>"","active"=>'1'); 
            
                $data['submenus'] = $this->_submenus('permissions');
                
               return View::make('admin.permissions.create',$data);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'user_permission','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
        
            $validator = Validator::make(
                array(
                    'component' => Input::get('component'),
                    'element' => Input::get('element'),
                  
                    'title' => Input::get('title'),
                   
                    ),
                array(
                    'component' => 'required',
                    'element' => 'required',
                
                    'title' => 'required',
                   
                    )
            );
            
            if($validator->passes())
            {
              
                 
                
                
                $permission = new Permission();
                $permission->component = Input::get('component');
                $permission->element = Input::get('element');
                $permission->title  = Input::get('title');
              
              
                $permission->save();
          
         
                
                return Redirect::to('permission')->with('success_message', 'Permission created successfully!');
            }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('permission/create')->withErrors($validator)->withInput();;
            }
            
	}

        public function edit($id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'user_permission','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            		$data = array();

                //Throw exception if project id does not exists
                $data['permission'] = Permission::findOrFail($id);
//                $data['userGroups'] = Usergroup::all();
		
                
              
                // Load Component JS
                 ZnUtilities::push_js_files("jquery.validate.min.js");
                 ZnUtilities::push_js_files("admin_components/users.js");
                

                
                $js = "$('.delete_form').submit(function() {
                        var c = confirm('Are you sure you want to delete this user?');
                        return c; 
                    });";
                 ZnUtilities::push_js($js);
                
                $basicPageVariables = ZnUtilities::basicPageVariables("Permissions","Edit Permission", "permission","1");
                $data = array_merge($data,$basicPageVariables);
                
                 $data['breadcrumbs']['All Permissions'] = array("link"=>'/permission',"active"=>'0');
                 $data['breadcrumbs']['Edit Permission'] = array("link"=>"","active"=>'1'); 
               
                 $data['submenus'] = $this->_submenus('permissions');
                return View::make('admin.permissions.edit',$data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
            if(!User::checkPermission(Auth::user()->user_group_id,'user_permission','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $permissions = Permission::find($id);
            //skip email's unique validation if email is not changed
            $validator = Validator::make(
                array(
                    'component' => Input::get('component'),
                    'element' => Input::get('element'),
                  
                    'title' => Input::get('title'),
                   
                    ),
                array(
                    'component' => 'required',
                    'element' => 'required',
                
                    'title' => 'required',
                   
                    )
            );
            
            if($validator->passes())
            {
              
                 
                
                
//                $permissions = Permission();
                $permissions->component = Input::get('component');
                $permissions->element = Input::get('element');
                $permissions->title  = Input::get('title');
              
              
                $permissions->save();
          
         
                
                
                return Redirect::to('permission')->with('success_message', 'Permission Updated Successfully');
            }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('permission/'.$id.'/edit/')->withErrors($validator)->withInput();
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
             if(!User::checkPermission(Auth::user()->user_group_id,'user_Permission','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            // delete
		$permission = Permission::find($id);
		$permission->delete();

		// redirect
		return Redirect::to('permission')->with('success_message', 'Permission deleted successfully!');
	}

        
       
       public function permissionSearch($search)
        {
            if(!User::checkPermission(Auth::user()->user_group_id,'user_permission','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
           
               
               $permission = Permission::where("element","like","%".$search."%")
                            ->orWhere("component","like","%".$search."%")
                             ->orWhere("title","like","%".$search."%")
                            ->paginate(); 
               
               $data = array();
               $data['permission'] = $permission;
                //Basic Page Settings
               
                $basicPageVariables = ZnUtilities::basicPageVariables("Permissions","Search results", "permission","1");
                $data = array_merge($data,$basicPageVariables);
               
                $data['breadcrumbs']['All Permissions'] = array("link"=>'/permission',"active"=>'0');
               $data['breadcrumbs']['Search'] = array("link"=>"","active"=>'1'); 
               
                $data['search'] = $search;

                $data['submenus'] = $this->_submenus('permissions');
                return View::make('admin.permissions.list',$data);
               
           
          
        }
        
        public function permissionActions()
        {
             if(!User::checkPermission(Auth::user()->user_group_id,'user_permission','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $search = Input::get('search');
            if($search!='')
            {
                return Redirect::to('/permissionSearch/'.$search);
            }
            else{
                
                
            //die(Input::get('bulk_action')   );
            
            $cid = Input::get('cid');
            $bulk_action = Input::get('bulk_action');
            if($bulk_action!='')
            {
                switch($bulk_action)
                {
                    
                   
                    case 'delete':
                    {
                        
                        
                        foreach($cid as $id)
                        {
                            DB::table('permissions')
                                ->where('permission_id', $id)
                                ->delete();
                        }
                        
                        return Redirect::to('/permission/')->with('success_message', 'Rows Delete!');
                        break;
                    }
                } //end switch
            } // end if statement
            return Redirect::to('/permission');
            }
        }
            private function _submenus($active)
    {   
        $data = array();
        $data['Users'] = array("link" => '/users', "active" => $active=='users'?'1':'0' ,"icon" => 'fa-male');
        $data['User Group'] = array("link" => '/usergroup', "active" => $active=='usergroups'?'1':'0' ,"icon" => 'fa-list');
        $data['User Permissions'] = array("link" => '/permission', "active" => $active=='permissions'?'1':'0' ,"icon" => 'fa-list');
        $data['User Group Permissions'] = array("link" => '/usergrouppermission', "active" => $active=='usergroupPermissions'?'1':'0' ,"icon" => 'fa-list');
        return $data;
    }

}
