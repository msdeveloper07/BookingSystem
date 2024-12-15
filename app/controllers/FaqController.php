<?php

class FaqController extends BaseController {

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
            if(!User::checkPermission(Auth::user()->user_group_id,'faq','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();

            $data['faq'] = Faq::orderBy('faq_id','desc')->paginate(20);
           

            $basicPageVariables = ZnUtilities::basicPageVariables("Faqs"," All Faqs", "faqs","1");
            $data = array_merge($data,$basicPageVariables);
            
            
            $data['breadcrumbs']['Faqs'] = array("link"=>'/faq',"active"=>'0');
           
            ZnUtilities::push_js_files('components/faqs.js');
            
             $data['active_nav'] = 'faqs';
             
                          $data['submenus'] = $this->_submenus('faqs');
			return View::make('admin.faqs.list',$data);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'faq','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            
            
                            
        ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
        $editor_js = '$(function() {
                       // $("#content").wysihtml5();
                       CKEDITOR.replace("answer");
                       });';
        ZnUtilities::push_js($editor_js);
            
		$data = array();
                
                
                
                $basicPageVariables = ZnUtilities::basicPageVariables("Faqs"," Add new Faq", "faqs","1");
                $data = array_merge($data,$basicPageVariables);
                
                $data['breadcrumbs']['All Faqs'] = array("link"=>'/faq',"active"=>'0');
                $data['breadcrumbs']['New Faq'] = array("link"=>"","active"=>'1'); 
            
                  $data['submenus'] = $this->_submenus('faqs');
               return View::make('admin.faqs.create',$data);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'faq','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
           // ZnUtilities::pa($_POST);
            
//            
           // die;
            $validator = Validator::make(
                array(
                    'question' => Input::get('question'),
                    'answer' => Input::get('answer'),
                    
                    ),
                array(
                    'question' => 'required',
                    'answer' => 'required',
                 
                   
                    )
            );
            
            if($validator->passes())
            {
                $activation_code= ZnUtilities::random_string('alphanumeric','50');
                 
                
                
                $faq = new Faq();
                $faq->question = Input::get('question');
                $faq->answer = Input::get('answer');
               
                //$faq->faq_status  = Input::get('faq_status');
                
              
                $faq->save();
                
            
                
                return Redirect::to('faq')->with('success_message', 'FAQ created successfully!');
            }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('faq/create')->withErrors($validator)->withInput();;
            }
            
	}

        public function edit($id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'faq','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            
               ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
                         $editor_js = '$(function() {
                       // $("#content").wysihtml5();
                       CKEDITOR.replace("answer");
                       });';
                ZnUtilities::push_js($editor_js);

                     $data = array();

                //Throw exception if project id does not exists
                $data['faq'] = Faq::findOrFail($id);
               
                
                $basicPageVariables = ZnUtilities::basicPageVariables("Faqs","Edit Faq", "faqs","1");
                $data = array_merge($data,$basicPageVariables);
                
                 $data['breadcrumbs']['All Faqs'] = array("link"=>'/faq',"active"=>'0');
                 $data['breadcrumbs']['Edit Faqs'] = array("link"=>"","active"=>'1'); 
               
                   $data['submenus'] = $this->_submenus('faqs');
                return View::make('admin.faqs.edit',$data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
            if(!User::checkPermission(Auth::user()->user_group_id,'faq','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            //skip email's unique validation if email is not changed
            $faq = Faq::find($id);
          
                  $validator = Validator::make(
                    array(
                        'question' => Input::get('question'),
                        'answer' => Input::get('answer'),
                      
                       

                        ),
                    array(
                        'question' => 'required',
                        'answer' => 'required',
                        
                       
                    )
            );
            
            
            if($validator->passes())
            {
                
                $faq->question = Input::get('question');
                $faq->answer = Input::get('answer');
             
               
               // $user->user_status  = Input::get('user_status');
                $faq->save();
                
                
               
                return Redirect::to('faq')->with('success_message', 'FAQ Updated Successfully');
            }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('faq/'.$id.'/edit/')->withErrors($validator)->withInput();
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
             if(!User::checkPermission(Auth::user()->user_group_id,'faq','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            // delete
		$faq = Faq::find($id);
		$faq->delete();

		// redirect
		return Redirect::to('faq')->with('success_message', 'FAQ deleted successfully!');
	}

        
        public function Status($id)
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
        
       
       public function faqSearch($search)
        {
            if(!User::checkPermission(Auth::user()->user_group_id,'faq','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
           
               
               $faq = Faq::where("question","like","%".$search."%")
                           
                            ->paginate(); 
               
               $data = array();
               $data['faq'] = $faq;
                //Basic Page Settings
               
                $basicPageVariables = ZnUtilities::basicPageVariables("Faqs","Search Results", "faqs","1");
                $data = array_merge($data,$basicPageVariables);
               
                $data['breadcrumbs']['All Faqs'] = array("link"=>'/faq',"active"=>'0');
               $data['breadcrumbs']['Search'] = array("link"=>"","active"=>'1'); 
               
                $data['search'] = $search;
                
                  $data['submenus'] = $this->_submenus('faqs');
                return View::make('admin.faqs.list',$data);
               
           
          
        }
        
        public function faqActions()
        {
             if(!User::checkPermission(Auth::user()->user_group_id,'faq','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $search = Input::get('search');
            if($search!='')
            {
                return Redirect::to('/faqSearch/'.$search);
            }
            else{
                
                
            //die(Input::get('bulk_action')   );
            
            $cid = Input::get('cid');
            $bulk_action = Input::get('bulk_action');
            if($bulk_action!='')
            {
                switch($bulk_action)
                {
//                    case 'blocked':
//                    {
//                        foreach($cid as $id)
//                        {
//                            DB::table('faqs')
//                                ->where('faq_id', $id)
//                                    ->update(array('user_status' =>  'deactive'));
//                                  
//                        }
//                       
//                       return Redirect::to('/users/')->with('success_message', 'Rows Updated!');
//
//                        break;
//                    }
//                    case 'active':
//                    {
//                        foreach($cid as $id)
//                        {
//                            DB::table('users')
//                                ->where('id', $id)
//                                ->update(array('user_status' =>  'active'));
//                        }
//                        
//                       return Redirect::to('/users/')->with('success_message', 'Rows Updated!');
//                    }
                    case 'delete':
                    {
                      
                        foreach($cid as $id)
                        {
                            DB::table('faqs')
                                ->where('faq_id', $id)
                                ->delete();
                        }
                        
                        return Redirect::to('/faq/')->with('success_message', 'Rows Delete!');
                        break;
                    }
                } //end switch
            } // end if statement
            return Redirect::to('/faq');
            }
        }

        
         public function result($id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'faq','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            		$data = array();

                //Throw exception if project id does not exists
                $data['faq'] = Faq::findOrFail($id);
               
               
              
                // Load Component JS
                 ZnUtilities::push_js_files("jquery.validate.min.js");
                 ZnUtilities::push_js_files("admin_components/faqs.js");
                

                
                $js = "$('.delete_form').submit(function() {
                        var c = confirm('Are you sure you want to delete this user?');
                        return c; 
                    });";
                 ZnUtilities::push_js($js);
                
                $basicPageVariables = ZnUtilities::basicPageVariables("Faqs","Edit Faq", "faqs","1");
                $data = array_merge($data,$basicPageVariables);
                
                 $data['breadcrumbs']['All Faqs'] = array("link"=>'/faq',"active"=>'0');
                 $data['breadcrumbs']['Edit Faqs'] = array("link"=>"","active"=>'1'); 
               
                   $data['submenus'] = $this->_submenus('faqs');
                return View::make('admin.faqs.result',$data);
	}
        
        public function uploadCsv()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'faq','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();
                
                
                
                $basicPageVariables = ZnUtilities::basicPageVariables("Faqs"," Add new Faq", "add_faq","1");
                $data = array_merge($data,$basicPageVariables);
                
                $data['breadcrumbs']['All Faqs'] = array("link"=>'/faq',"active"=>'0');
                $data['breadcrumbs']['New Faq'] = array("link"=>"","active"=>'1'); 
            
                  $data['submenus'] = $this->_submenus('faqs');
               return View::make('admin.faqs.uploadcsv',$data);
	}
        
        public function saveImportCsv()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'faq','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
//            $import_csv = Input::file('csvfile');
//            ZnUtilities::pa($import_csv); die();
                
         
            
            $validator = Validator::make(
                array(
                    'importcsv' => Input::file('csvfile'),
                   
                    ),
                array(
                    'importcsv' => 'required',
                 
                  )
            );
            
            if($validator->passes())
            {
                
                     $destinationPath = "uploadCsv";
            if (is_dir($destinationPath)) {
                $destinationPath = "uploadCsv/";
            } else {
                mkdir($destinationPath, 0777);
                $destinationPath = "uploadCsv/";
            }


            $temp_name = Input::file('csvfile')->getClientOriginalName();
            $pos = strpos($temp_name, '.');
            $file_name = substr($temp_name, 0, $pos);
            $date = new DateTime();

            $filename = $date->getTimestamp() . "-" . str_replace(" ", "-", Input::file('csvfile')->getClientOriginalName());

            Input::file('csvfile')->move($destinationPath, $filename);
            
           //ZnUtilities::pa($destinationPath.$filename); die();
          
           // $articles->image = $destinationPath . $filename;
           $p_Filepath = $destinationPath.$filename;
           
           $csv_obj = new ZnParseCsv();
           $csv_obj->auto($p_Filepath);
           $content = $csv_obj->data;
           
          
           
           $keys = $csv_obj->titles;
            
          
                   
                foreach ($content as $k => $v) {
                  
              
                      
                        $faq = new Faq();
                        $faq->question = $v['Question'];
                        $faq->answer = $v['Answer'];

                        //$faq->faq_status  = Input::get('faq_status');


                        $faq->save();
                        
                    }
                    
                    
                        
                        
                
                              
             
            
      
               
            
            
            return Redirect::to('faq')->with('success_message', 'Faq updated successfully!');
        }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('contacts/create')->withErrors($validator)->withInput();;
            }
            
	}     
      
        
   
    
      private function _submenus($active)
    {   
        $data = array();
        
        $data['Back'] = array("link" => '/faq', "active" => $active=='faqs'?'1':'0' ,"icon" => 'fa-angle-left' ,);
        
        
        if(User::checkPermission(Auth::user()->user_group_id,'faq','manage'))
        $data['FAQ'] = array("link" => '/faq', "active" => $active=='faqs'?'1':'0' ,"icon" => 'fa-question');
        
        if(User::checkPermission(Auth::user()->user_group_id,'email_templates','manage'))
            $data['Email Template'] = array("link" => '/emailTemplates', "active" => $active=='email_templates'?'1':'0' ,"icon" => 'fa-file-o');
        
        if(User::checkPermission(Auth::user()->user_group_id,'mailingList','manage'))
            $data['Mailing Lists'] = array("link" => '/mailinglists', "active" => $active=='mailinglists'?'1':'0' ,"icon" => 'fa-list');
        
        if(User::checkPermission(Auth::user()->user_group_id,'mailingList_subscriber','manage'))
           $data['Mailing List Subscribers'] = array("link" => '/mailinglistsubscribers', "active" => $active=='mailinglistsubscribers'?'1':'0' ,"icon" => 'fa-user-plus');
       
        if(User::checkPermission(Auth::user()->user_group_id,'messages','manage'))
          $data['Messages'] = array("link" => '/messages', "active" => $active=='messages'?'1':'0' ,"icon" => 'fa-envelope');
       
        if(User::checkPermission(Auth::user()->user_group_id,'newsletter','manage'))
          $data['Newsletters'] = array("link" => '/newsletters', "active" => $active=='newsletter'?'1':'0' ,"icon" => 'fa-list');
      
        if(User::checkPermission(Auth::user()->user_group_id,'task','manage'))
          $data['Tasks'] = array("link" => '/tasks', "active" => $active=='tasks'?'1':'0' ,"icon" => 'fa-list');
       
        if(User::checkPermission(Auth::user()->user_group_id,'currency','manage'))
            $data['Currency Convert '] = array("link" => '/currency/create', "active" => $active=='convert_currency'?'1':'0' ,"icon" => 'fa-gear');
        return $data;
    }
}
