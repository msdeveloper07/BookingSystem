<?php

class UsersGroupPermissionsController extends BaseController {

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
            if(!User::checkPermission(Auth::user()->user_group_id,'user_group_permission','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();

            $data['usergroup'] = Usergroup::paginate(10);
           

            $basicPageVariables = ZnUtilities::basicPageVariables("User Group Permissions","All User Group Permissions", "user_group_permissions","1");
            $data = array_merge($data,$basicPageVariables);
            
            
            $data['breadcrumbs']['All User Group Permissions'] = array("link"=>'/usergrouppermission',"active"=>'0');
           
            ZnUtilities::push_js_files('components/ugpermission.js');
            
            // $data['active_nav'] = 'User Group';
            $data['submenus'] = $this->_submenus('usergroupPermissions');
            
			return View::make('admin.usergrouppermission.list',$data);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'user_group_permission','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
		$data = array();
                
                //$data['userGroups'] = Usergroup::all();
                
                  ZnUtilities::push_js_files("jquery.validate.min.js");
                // ZnUtilities::push_js_files("admin_components/ugpermission.js");
                  ZnUtilities::push_js_files("chosen.jquery.min.js");
                
                  ZnUtilities::push_js_files('components/ugpermission.js');
                
                  $js = "$(function() {
                    $('#user_form').validate();
                });";
            ZnUtilities::push_js($js);
                  
                $basicPageVariables = ZnUtilities::basicPageVariables("User Group Permissions"," Add New User Group ", "add_user_group_permissions","1");
                $data = array_merge($data,$basicPageVariables);
                
                $data['breadcrumbs']['All User Group Permissions'] = array("link"=>'/usergrouppermission',"active"=>'0');
                $data['breadcrumbs']['New User Group'] = array("link"=>"","active"=>'1'); 
            
                $data['submenus'] = $this->_submenus('usergroupPermissions');
               return View::make('admin.usergrouppermission.create',$data);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'user_group_permission','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
       
            $validator = Validator::make(
                array(
                    'User group' => Input::get('user_group_id'),
                  
                    ),
                array(
                    'User group' => 'required',

                    )
            );
            
            if($validator->passes())
            {

               $cid = Input::get('user_group_id');
               if($cid){
                   DB::table('user_group_permissions')
                                ->where('user_group_id', $cid)
                                ->delete();
                   
              $cidee = Input::get('cid');
             
               foreach($cidee as $c) 
               {
                   $new = new UsergroupPermission();
                   $new->user_group_id = $cid;
                   $new->permission_id = $c;
                   $new->save();
               }
               
               }
               
           
                return Redirect::to('usergrouppermission')->with('success_message', 'User Group Permission created successfully!');
            }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('usergrouppermission/create')->withErrors($validator)->withInput();;
            }
            
	}

        public function edit($id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'user_group_permission','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            		$data = array();

                $ugpermission = UserGroupPermission::select('permission_id')->where('user_group_id',$id)->get()->toArray();
		$data['user_group_id'] = $id;
                
                $ugarray = array();
                foreach($ugpermission as $u)
                {
                    $ugarray[] = $u['permission_id'];
                }
                 //ZnUtilities::pa($ugarray); die();
                $data['ugpermission'] = $ugarray;
           
                // Load Component JS
                 ZnUtilities::push_js_files("jquery.validate.min.js");
                // ZnUtilities::push_js_files("admin_components/ugpermission.js");
                
                     ZnUtilities::push_js_files('components/ugpermission.js');
                
//                $js = "$('.delete_form').submit(function() {
//                        var c = confirm('Are you sure you want to delete this user?');
//                        return c; 
//                    });";
               
                 //ZnUtilities::push_js($js);
                
                $basicPageVariables = ZnUtilities::basicPageVariables("User Group Permissions","Edit User Group Permission", "usergroup","1");
                $data = array_merge($data,$basicPageVariables);
                
                 $data['breadcrumbs']['All User Group Permissions'] = array("link"=>'/usergrouppermission',"active"=>'0');
                 $data['breadcrumbs']['Edit User Group Permission'] = array("link"=>"","active"=>'1'); 
               
                 $data['submenus'] = $this->_submenus('usergroupPermissions');
                return View::make('admin.usergrouppermission.edit',$data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
            if(!User::checkPermission(Auth::user()->user_group_id,'user_group_permission','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            //skip email's unique validation if email is not changed
//            $cid = UserGroupPermission::where($id);
//            ZnUtilities::pa($cid); die();
                 $validator = Validator::make(
                array(
                    'User group' => Input::get('user_group_id'),
                  
                    ),
                array(
                    'User group' => 'required',

                    )
            );
            
            if($validator->passes())
            {

               $cid = Input::get('user_group_id');
              
               if($cid){
                   DB::table('user_group_permissions')
                                ->where('user_group_id', $cid)
                                ->delete();
                   
              $cidee = Input::get('cid');
               foreach($cidee as $c) 
               {
                   $new = new UsergroupPermission();
                   $new->user_group_id = $cid;
                   $new->permission_id = $c;
                   $new->save();
               }
               
               }
               
           
                return Redirect::to('usergrouppermission')->with('success_message', 'User Group Permission created successfully!');
            }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('usergrouppermission/create')->withErrors($validator)->withInput();;
            }
            
	}
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
//	public function destroy($id)
//	{
//             if(!User::checkPermission(Auth::user()->user_group_id,'user_group_permission','manage'))
//            {
//                return Redirect::to('/permissionDenied');
//            }
//            
//            // delete
//		$user = Usergroup::find($id);
//		$user->delete();
//
//		// redirect
//		return Redirect::to('usergroup')->with('success_message', 'User Group deleted successfully!');
//	}

        
       
        
        
//       public function usergroupSearch($search)
//        {
//            if(!User::checkPermission(Auth::user()->user_group_id,'user_group_permission','manage'))
//            {
//                return Redirect::to('/permissionDenied');
//            }
//            
//           
//               
//               $usergroup = Usergroup::where("user_group_name","like","%".$search."%")->paginate(); 
//               
//               $data = array();
//               $data['usergroup'] = $usergroup;
//                //Basic Page Settings
//               
//                $basicPageVariables = ZnUtilities::basicPageVariables("User Group Permissions","Search results", "user_group","1");
//                $data = array_merge($data,$basicPageVariables);
//               
//                $data['breadcrumbs']['All User Group Permissions'] = array("link"=>'/usergrouppermission',"active"=>'0');
//               $data['breadcrumbs']['Search'] = array("link"=>"","active"=>'1'); 
//               
//                $data['search'] = $search;
//
//                return View::make('admin.usergroup.list',$data);
//               
//           
//          
//        }
//        
//        public function usergroupActions()
//        {
//             if(!User::checkPermission(Auth::user()->user_group_id,'user_group_permission','manage'))
//            {
//                return Redirect::to('/permissionDenied');
//            }
//            
//            $search = Input::get('search');
//            if($search!='')
//            {
//                return Redirect::to('/usergroupSearch/'.$search);
//            }
//            else{
//                
//                
//            //die(Input::get('bulk_action')   );
//            
//            $cid = Input::get('cid');
//            $bulk_action = Input::get('bulk_action');
//            if($bulk_action!='')
//            {
//                switch($bulk_action)
//                {
//                    
//                    case 'delete':
//                    {
//                        
//                        
//                        foreach($cid as $id)
//                        {
//                            DB::table('user_groups')
//                                ->where('user_group_id', $id)
//                                ->delete();
//                        }
//                        
//                        return Redirect::to('/usergroup/')->with('success_message', 'Rows Delete!');
//                        break;
//                    }
//                } //end switch
//            } // end if statement
//            return Redirect::to('/usergroup');
//            }
//        }

        
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
