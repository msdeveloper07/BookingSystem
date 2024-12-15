<?php

class ImageController extends BaseController {

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
            
            
            $data = array();

            $data['users'] = User::paginate('20');
           

            $basicPageVariables = ZnUtilities::basicPageVariables("Users"," All Users", "users","1");
            $data = array_merge($data,$basicPageVariables);
            
            
            $data['breadcrumbs']['Users'] = array("link"=>'/users',"active"=>'0');
           
            ZnUtilities::push_js_files('components/users.js');
            
             $data['active_nav'] = 'users';
            
			return View::make('admin.users.list',$data);
	}

          public function profile() {


        $data = array();

        $all_users = User::where('id', Auth::user()->id)->first();
        //ZnUtilities::pa($all_users); die();

        if (Auth::user()->id != $all_users->id) {
            return Redirect::to('/permissionDenied');
        }
        $data['user'] = $all_users;
        //ZnUtilities::pa($users); die();
        $basicPageVariables = ZnUtilities::basicPageVariables("Profile", "User Profile", "profile", "1");
        $data = array_merge($data, $basicPageVariables);


        $data['breadcrumbs']['User Profile'] = array("link" => '/profile', "active" => '0');

        ZnUtilities::push_js_files('components/users.js');

        $jquery_ui_js = "http://code.jquery.com/ui/1.10.4/jquery-ui.js";
        ZnUtilities::push_js_files($jquery_ui_js);

        ZnUtilities::push_js_files('pekeUpload.js');

        $upload_js = '
                    jQuery(document).ready(function(){

                        $("#upload-button").pekeUpload({
                        theme:"bootstrap", 
                        btnText:"Upload/Change", 
                        url:"/profile-pic.php", 
                        allowed_number_of_uploads:1,
                        allowedExtensions:"jpeg|jpg|png|gif",
                        onFileSuccess:function(file,response){
                            var data = JSON.parse(response);
                            var upload_response = data;
                            var filename = data.file_name;
                            var filepath = "'.'/images/profile_pic/'.'";
                          
                            var pic_name = filepath+filename;
                            
                            console.log(data.image_250);   
                            $("#prev_upload").append(\'<div id="\'+data.image_id+\'" class="property_image pull-left col-md-3"><input type="hidden" name="profile_pic" value="\'+pic_name+\'" /></div> \');
                            $("#profile_pic").attr("src",""+pic_name);
                

                $.ajax({
                type: "POST",
                url: "updateprofile",
                data: "user_id=+' . Auth::user()->id . '+&img="+upload_response.file_name,
                });
                
       



$(".file").remove();
                            }
                        });
                   });

                   function remove_div(div_id)
                   {
                    $("#"+div_id).remove();
                   }

            ';
        ZnUtilities::push_js($upload_js);



        return View::make('admin.image.profile', $data);
    }
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
            
            
		$data = array();
                
                $data['userGroups'] = Usergroup::all();
                
                $basicPageVariables = ZnUtilities::basicPageVariables("Profile","Upload Profile Image", "add_image","1");
                $data = array_merge($data,$basicPageVariables);
                
                //$data['breadcrumbs']['All User'] = array("link"=>'/users',"active"=>'0');
                $data['breadcrumbs']['Upload Profile Image'] = array("link"=>"","active"=>'1'); 
            
               return View::make('admin.image.create',$data);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
           
            
          // ZnUtilities::pa("hello"); die();
            
//            
           // die;
//            $validator = Validator::make(
//                array(
//                    'path' => Input::file('path'),
//                    
//                    ),
//                array(
//                    'path' => 'required',
//                    
//                    )
//            );
//          if($validator->passes())
//            {
             
                $destinationPath ="upload/";
               
                 
                                
                $temp_name =Input::file('path')->getClientOriginalName();
                $pos = strpos($temp_name, '.');
                $file_name = substr($temp_name ,0,$pos);
                $date = new DateTime();
 
                $filename = $date->getTimestamp()."-".str_replace(" ","-",Input::file('path')->getClientOriginalName());
               
              Input::file('path')->move($destinationPath, $filename);

                $upload = new Upload();
                $upload->path = $destinationPath.$filename;
                $upload->upload_on  = date("Y-m-d H:i:s");
                $upload->id = Auth::user()->id; 
                $upload->file_name = $filename;
                if($upload->id)
                {
                 DB::table('upload')
                                ->where('id', Auth::user()->id)
                                ->delete();
                }
                $upload->save();
                
                  return Redirect::to('image')->with('success_message', 'File Upload successfully!');
            }
//            else
//            {
//                //$messages = $validator->messages();
//                return Redirect::to('image/create')->withErrors($validator)->withInput();;
//            }
            
// }
         

        public function edit($id)
	{
            
            
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
            
            
            // delete
		$user = User::find($id);
		$user->delete();

		// redirect
		return Redirect::to('users')->with('success_message', 'User deleted successfully!');
	}

        
        public function changeStatus($id)
        {
           
            
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

                return View::make('admin.users.list',$data);
               
           
          
        }
        
        public function userActions()
        {
            
            
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

}
