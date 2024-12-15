<?php

class MessageFolderController extends BaseController {

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
            if(!User::checkPermission(Auth::user()->user_group_id,'message_folders','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();

            $data['message_folders'] = MessageFolder::orderBy('created_on','asc')->paginate('20');

            $basicPageVariables = ZnUtilities::basicPageVariables("Message Folders"," All Message Folders", "message_folders","1");
            $data = array_merge($data,$basicPageVariables);
            $data['breadcrumbs']['All Message Folders'] = array("link"=>'/message_folders',"active"=>'1');
            
            
            
            ZnUtilities::push_js_files('jquery.validate.min.js');
            ZnUtilities::push_js_files('components/message_folders.js');
        
            $js = "$(function() {
                    $('#actions_form').validate();
                });";
            ZnUtilities::push_js($js); 
           
            return View::make('admin.message_folders.list',$data);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'message_folders','manage'))
            {
                return Redirect::to('/permissionDenied');
            }

            $js = '$(function() {
                 $(".data-mask").inputmask(); 
            });';
            ZnUtilities::push_js($js);
            
            
            
            
            $data = array();
            $data['salutations'] = Config::get('salutations');
            
            $basicPageVariables = ZnUtilities::basicPageVariables("Message Folders","Create new message folder", "add_message_folder","1");
            $data = array_merge($data,$basicPageVariables);
            
            $data['breadcrumbs']['All Message Folder'] = array("link"=>'/message_folders',"active"=>'0');
            $data['breadcrumbs']['Add'] = array("link"=>"","active"=>'1'); 
            
            ZnUtilities::push_js_files('jquery.validate.min.js');
            ZnUtilities::push_js_files('components/message_folders.js');
        
            $js = "$(function() {
                    $('#message_folder_form').validate();
                });";
            ZnUtilities::push_js($js); 
            
            return View::make('admin.message_folders.create',$data);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'message_folders','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
           // ZnUtilities::pa($_POST);
            
//            
           // die;
            $validator = Validator::make(
                array(
                    'title' => Input::get('message_folder_title'),
                    ),
                array(
                    'title' => 'required',
                  )
            );
            
            if($validator->passes())
            {
                $message_folder = new MessageFolder();
                $message_folder->message_folder_title = Input::get('message_folder_title');
                $message_folder->created_on = date('Y-m-d H:i:s');
                $message_folder->created_by = Auth::user()->id;
                $message_folder->save();
                
                
                return Redirect::to('message_folders/'.$message_folder->message_folder_id.'/edit')->with('success_message', 'Message Folder created successfully!');
            }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('message_folders/create')->withErrors($validator)->withInput();;
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
             if(!User::checkPermission(Auth::user()->user_group_id,'message_folders','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
                $data = array();
            
             
                $data['message_folder'] = MessageFolder::findOrFail($id);
                
                
		$delete_js = "$('.delete_form').submit(function() {
                        var c = confirm('Are you sure you want to delete this Message Folder?');
                        return c; 
                    });";
                 ZnUtilities::push_js($delete_js); 
                 
                
               
                $basicPageVariables = ZnUtilities::basicPageVariables("Message Folders","Edit message_folder", "message_folders","1");
                $data = array_merge($data,$basicPageVariables);
            
                $data['breadcrumbs']['All Message Folder'] = array("link"=>'/message_folders',"active"=>'0');
                $data['breadcrumbs']['Edit'] = array("link"=>"","active"=>'1'); 
                
            
                 ZnUtilities::push_js_files('jquery.validate.min.js');
                ZnUtilities::push_js_files('components/message_folders.js');

                $js = "$(function() {
                        $('#message_folder_form').validate();
                    });";
                ZnUtilities::push_js($js); 
                return View::make('admin.message_folders.edit',$data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
            if(!User::checkPermission(Auth::user()->user_group_id,'message_folders','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
          
           $validator = Validator::make(
                array(
                    'title' => Input::get('message_folder_title'),
                    ),
                array(
                    'title' => 'required',
                  )
            );
            
            if($validator->passes())
            {
                $message_folder = MessageFolder::find($id);
                $message_folder->message_folder_title = Input::get('message_folder_title');
                $message_folder->save();
                
                
                return Redirect::to('message_folders/'.$message_folder->message_folder_id.'/edit')->with('success_message', 'Message Folder created successfully!');
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

        
     
        
       public function message_folderSearch($search)
        {
            if(!User::checkPermission(Auth::user()->user_group_id,'message_folders','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
           
           
               $message_folder = MessageFolder::where("message_folder_title","like","%".$search."%")
                            ->orWhere("first_name","like","%".$search."%")
                            ->orWhere("last_name","like","%".$search."%")
                            ->paginate(); 
               
               $data = array();
               $data['message_folders'] = $message_folder;
               $data['search'] = $search;
               $basicPageVariables = ZnUtilities::basicPageVariables("Message Folders","Message Folders matching term: ".$search, "message_folders","1");
               $data = array_merge($data,$basicPageVariables);

                $data['breadcrumbs']['All Message Folder'] = array("link"=>'/message_folders',"active"=>'1');

                 ZnUtilities::push_js_files('jquery.validate.min.js');
                ZnUtilities::push_js_files('components/message_folders.js');

                $js = "$(function() {
                        $('#actions_form').validate();
                    });";
                ZnUtilities::push_js($js); 
                
                return View::make('admin.message_folders.list',$data);
               
           
        }
        
   
        public function message_folderActions()
        {
            $search = Input::get('search');
            if($search!='')
            {
                return Redirect::to('/message_folderSearch/'.$search);
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
                            DB::table('message_folders')
                                ->where('message_folder_id', $id)
                                ->update(array('message_folder_status' =>  'deactive'));
                        }
                        
                       return Redirect::to('/message_folders/')->with('success_message', 'Rows Updated!');

                        break;
                    }
                    case 'active':
                    {
                        foreach($cid as $id)
                        {
                            DB::table('message_folders')
                                ->where('message_folder_id', $id)
                                ->update(array('message_folder_status' =>  'active'));
                        }
                        
                       return Redirect::to('/message_folders/')->with('success_message', 'Rows Updated!');
                    }
                    case 'delete':
                    {
                         if(!User::checkPermission(Auth::user()->user_group_id,'message_folders','delete'))
                        {
                            return Redirect::to('/permissionDenied');
                        }
                        
                            
                        $message_folder_titles = array();
                        
                        foreach($cid as $id)
                        {
                            
                            $message_folders = MessageFolder::find($id);
                            if($message_folders->lead->count()==0)
                            {
                                DB::table('message_folders')
                                ->where('message_folder_id', $id)
                                ->delete();
                            }
                            else {
                                $message_folder_titles[] = '<b>'.$message_folders->message_folder_title."</b>";
                            }
                        }
                        
                        if(count($message_folder_titles)>0)
                        {
                            return Redirect::to('/message_folders/')->with('warning_message', 'Following message_folder can not be deleted as they have leads associated <br>'. implode("<br />", $message_folder_titles));
                        }
                        else{
                            return Redirect::to('/message_folders/')->with('success_message', 'Rows Delete');
                        }
                        break;
                    }
                } //end switch
            } // end if statement
            
        }
}
