<?php

class ItemsController extends BaseController {

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
//            if(!User::checkPermission(Auth::user()->user_group_id,'faq','manage'))
//            {
//                return Redirect::to('/permissionDenied');
//            }
//            
            $data = array();

            $data['items'] = Item::paginate('20');
           

            $basicPageVariables = ZnUtilities::basicPageVariables("Items"," All Items", "items","1");
            $data = array_merge($data,$basicPageVariables);
            
            
            $data['breadcrumbs']['Items'] = array("link"=>'/items',"active"=>'0');
           
            ZnUtilities::push_js_files('components/items.js');
            
             $data['active_nav'] = 'items';
            
	     return View::make('admin.items.list',$data);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
//             if(!User::checkPermission(Auth::user()->user_group_id,'faq','manage'))
//            {
//                return Redirect::to('/permissionDenied');
//            }
//            
            
            
                            
       
		$data = array();
                
                
                
                $basicPageVariables = ZnUtilities::basicPageVariables("Items"," Add New Item", "add_item","1");
                $data = array_merge($data,$basicPageVariables);
                
                $data['breadcrumbs']['All Items'] = array("link"=>'/items',"active"=>'0');
                $data['breadcrumbs']['New Item'] = array("link"=>"","active"=>'1'); 
            
               return View::make('admin.items.create',$data);
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
        
       
       public function itemSearch($search)
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
               
                $basicPageVariables = ZnUtilities::basicPageVariables("Faqs","Search Results", "Faqs","1");
                $data = array_merge($data,$basicPageVariables);
               
                $data['breadcrumbs']['All Faqs'] = array("link"=>'/faq',"active"=>'0');
               $data['breadcrumbs']['Search'] = array("link"=>"","active"=>'1'); 
               
                $data['search'] = $search;

                return View::make('admin.faqs.list',$data);
               
           
          
        }
        
        public function itemActions()
        {
//             if(!User::checkPermission(Auth::user()->user_group_id,'faq','manage'))
//            {
//                return Redirect::to('/permissionDenied');
//            }
//            
            $search = Input::get('search');
            if($search!='')
            {
                return Redirect::to('/itemSearch/'.$search);
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
                            DB::table('items')
                                ->where('item_id', $id)
                                    ->update(array('item_status' =>  'blocked'));
                                  
                        }
                       
                       return Redirect::to('/items/')->with('success_message', 'Rows Updated!');

                        break;
                    }
                    case 'active':
                    {
                        foreach($cid as $id)
                        {
                            DB::table('items')
                                ->where('item_id', $id)
                                ->update(array('item_status' =>  'active'));
                        }
                        
                       return Redirect::to('/items/')->with('success_message', 'Rows Updated!');
                    }
                    case 'delete':
                    {
                      
                        foreach($cid as $id)
                        {
                            DB::table('items')
                                ->where('item_id', $id)
                                ->delete();
                        }
                        
                        return Redirect::to('/items/')->with('success_message', 'Rows Delete!');
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
               
                return View::make('admin.faqs.result',$data);
	}

}
