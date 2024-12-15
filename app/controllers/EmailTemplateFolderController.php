<?php

class EmailTemplateFolderController extends BaseController {

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
            if(!User::checkPermission(Auth::user()->user_group_id,'email_template_folders','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();

            $data['email_template_folders'] = EmailTemplateFolder::orderBy('created_on','asc')->paginate('20');

            $basicPageVariables = ZnUtilities::basicPageVariables("Email Template Folders"," All Email Template Folders", "email_template_folders","1");
            $data = array_merge($data,$basicPageVariables);
            $data['breadcrumbs']['All Email Template Folders'] = array("link"=>'/email_template_folders',"active"=>'1');
            
            ZnUtilities::push_js_files('jquery.validate.min.js');
            ZnUtilities::push_js_files('components/email_template_folders.js');
        
            $js = "$(function() {
                    $('#actions_form').validate();
                });";
            ZnUtilities::push_js($js); 
           
            return View::make('admin.email_template_folders.list',$data);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'email_template_folders','manage'))
            {
                return Redirect::to('/permissionDenied');
            }

            $js = '$(function() {
                 $(".data-mask").inputmask(); 
            });';
            ZnUtilities::push_js($js);
            
            
            
            
            $data = array();
            $data['salutations'] = Config::get('salutations');
            
            $basicPageVariables = ZnUtilities::basicPageVariables("Email Template Folders","Create new message folder", "add_email_template_folder","1");
            $data = array_merge($data,$basicPageVariables);
            
            $data['breadcrumbs']['All Message Folder'] = array("link"=>'/email_template_folders',"active"=>'0');
            $data['breadcrumbs']['Add'] = array("link"=>"","active"=>'1'); 
            
            ZnUtilities::push_js_files('jquery.validate.min.js');
            ZnUtilities::push_js_files('components/email_template_folders.js');
        
            $js = "$(function() {
                    $('#email_template_folder_form').validate();
                });";
            ZnUtilities::push_js($js); 
            
            return View::make('admin.email_template_folders.create',$data);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'email_template_folders','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
           // ZnUtilities::pa($_POST);
            
//            
           // die;
            $validator = Validator::make(
                array(
                    'title' => Input::get('email_template_folder_title'),
                    ),
                array(
                    'title' => 'required',
                  )
            );
            
            if($validator->passes())
            {
                $email_template_folder = new EmailTemplateFolder();
                $email_template_folder->email_template_folder_title = Input::get('email_template_folder_title');
                $email_template_folder->created_on = date('Y-m-d H:i:s');
                $email_template_folder->save();
              
                return Redirect::to('email_template_folders/'.$email_template_folder->email_template_folder_id.'/edit')->with('success_message', 'Message Folder created successfully!');
            }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('email_template_folders/create')->withErrors($validator)->withInput();;
            }
            
	}

	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'email_template_folders','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
                $data = array();
            
             
                $data['email_template_folder'] = EmailTemplateFolder::findOrFail($id);
                
                
		$delete_js = "$('.delete_form').submit(function() {
                        var c = confirm('Are you sure you want to delete this Message Folder?');
                        return c; 
                    });";
                 ZnUtilities::push_js($delete_js); 
                 
                
               
                $basicPageVariables = ZnUtilities::basicPageVariables("Email Template Folders","Edit email_template_folder", "email_template_folders","1");
                $data = array_merge($data,$basicPageVariables);
            
                $data['breadcrumbs']['All Message Folder'] = array("link"=>'/email_template_folders',"active"=>'0');
                $data['breadcrumbs']['Edit'] = array("link"=>"","active"=>'1'); 
                
            
                 ZnUtilities::push_js_files('jquery.validate.min.js');
                ZnUtilities::push_js_files('components/email_template_folders.js');

                $js = "$(function() {
                        $('#email_template_folder_form').validate();
                    });";
                ZnUtilities::push_js($js); 
                return View::make('admin.email_template_folders.edit',$data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
            if(!User::checkPermission(Auth::user()->user_group_id,'email_template_folders','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
          
           $validator = Validator::make(
                array(
                    'title' => Input::get('email_template_folder_title'),
                    ),
                array(
                    'title' => 'required',
                  )
            );
            
            if($validator->passes())
            {
                $email_template_folder = EmailTemplateFolder::find($id);
                $email_template_folder->email_template_folder_title = Input::get('email_template_folder_title');
                $email_template_folder->save();
                
                
                return Redirect::to('email_template_folders/'.$email_template_folder->email_template_folder_id.'/edit')->with('success_message', 'Message Folder created successfully!');
            }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('users/'.$id.'/edit/')->withErrors($validator)->withInput();;
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

        
     
        
       public function email_template_folderSearch($search)
        {
            if(!User::checkPermission(Auth::user()->user_group_id,'email_template_folders','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
           
           
               $email_template_folder = EmailTemplateFolder::where("email_template_folder_title","like","%".$search."%")
                            ->orWhere("first_name","like","%".$search."%")
                            ->orWhere("last_name","like","%".$search."%")
                            ->paginate(); 
               
               $data = array();
               $data['email_template_folders'] = $email_template_folder;
               $data['search'] = $search;
               $basicPageVariables = ZnUtilities::basicPageVariables("Email Template Folders","Email Template Folders matching term: ".$search, "email_template_folders","1");
               $data = array_merge($data,$basicPageVariables);

                $data['breadcrumbs']['All Message Folder'] = array("link"=>'/email_template_folders',"active"=>'1');

                 ZnUtilities::push_js_files('jquery.validate.min.js');
                ZnUtilities::push_js_files('components/email_template_folders.js');

                $js = "$(function() {
                        $('#actions_form').validate();
                    });";
                ZnUtilities::push_js($js); 
                
                return View::make('admin.email_template_folders.list',$data);
               
           
        }
        
   
        public function email_template_folderActions()
        {
            $search = Input::get('search');
            if($search!='')
            {
                return Redirect::to('/email_template_folderSearch/'.$search);
            }
            
                
            
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
                            DB::table('email_template_folders')
                                ->where('email_template_folder_id', $id)
                                ->update(array('email_template_folder_status' =>  'deactive'));
                        }
                        
                       return Redirect::to('/email_template_folders/')->with('success_message', 'Rows Updated!');

                        break;
                    }
                    case 'active':
                    {
                        foreach($cid as $id)
                        {
                            DB::table('email_template_folders')
                                ->where('email_template_folder_id', $id)
                                ->update(array('email_template_folder_status' =>  'active'));
                        }
                        
                       return Redirect::to('/email_template_folders/')->with('success_message', 'Rows Updated!');
                    }
                    case 'delete':
                    {
                        
                            
                        $email_template_folder_titles = array();
                        
                        foreach($cid as $id)
                        {
                            
                            DB::table('email_template_folders')
                                ->where('email_template_folder_id', $id)
                                ->delete();
                        }
                        
                       
                            return Redirect::to('/email_template_folders/')->with('success_message', 'Rows Delete');
                        
                        break;
                    }
                } //end switch
            } // end if statement
            
        }
}
