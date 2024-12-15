<?php

class ThingsToDoController extends BaseController {

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
            if(!User::checkPermission(Auth::user()->user_group_id,'thingstodo','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();
                
            $data['thingstodo'] = ThingToDo::paginate('20');
           

            $basicPageVariables = ZnUtilities::basicPageVariables("ThingsToDo"," All ThingsToDo", "ThingsToDo","1");
            $data = array_merge($data,$basicPageVariables);
            
            
            $data['breadcrumbs']['ThingsToDo'] = array("link"=>'/thingstodo',"active"=>'0');
           
            ZnUtilities::push_js_files('components/thingstodo.js');
            
             $data['active_nav'] = 'thingstodo';
            
			
             
                     return View::make('admin.thingstodo.list',$data);
           
             
            }
            
            
            
            

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'thingstodo','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
		$data = array();
                
                $data['thingstodo'] = ThingToDo::all();
                
                $basicPageVariables = ZnUtilities::basicPageVariables("ThingsToDo"," Add New ThingsToDo", "Add Thingstodo","1");
                $data = array_merge($data,$basicPageVariables);
                
                $data['breadcrumbs']['All ThingsToDo'] = array("link"=>'/thingstodo',"active"=>'0');
                $data['breadcrumbs']['New ThingsToDo'] = array("link"=>"","active"=>'1'); 
            
               return View::make('admin.thingstodo.create',$data);
	}
        
      


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
     
	public function store()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'thingstodo','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $validator = Validator::make(
                array(
                    
                    'thing_todo' => Input::get('thingstodo'),
                   
                    ),
                array(

                    'thing_todo' => 'required|unique:things_todo',
                   
                    )
            );
            
            if($validator->passes())
            {
                $activation_code= ZnUtilities::random_string('alphanumeric','50');
                 
                
                
                $thingstodo = new ThingToDo();
                $thingstodo->thing_todo = Input::get('thingstodo');
        
                $thingstodo->save();
                
             
                
                return Redirect::to('thingstodo')->with('success_message', 'ThingsToDo created successfully!');
            }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('thingstodo/create')->withErrors($validator)->withInput();;
            }
            
	}

        public function edit($id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'thingstodo','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            		$data = array();

                //Throw exception if project id does not exists
                $data['thingstodo'] = ThingToDo::findOrFail($id);
                
    
                // Load Component JS
                 ZnUtilities::push_js_files("jquery.validate.min.js");
                 ZnUtilities::push_js_files("admin_components/thingstodo.js");
                

                
                $js = "$('.delete_form').submit(function() {
                        var c = confirm('Are you sure you want to delete this user?');
                        return c; 
                    });";
                 ZnUtilities::push_js($js);
                
                $basicPageVariables = ZnUtilities::basicPageVariables("ThingsToDo","Edit ThingsToDo", "ThingsToDo","1");
                $data = array_merge($data,$basicPageVariables);
                
                 $data['breadcrumbs']['All ThingsToDo'] = array("link"=>'/thingstodo',"active"=>'0');
                 $data['breadcrumbs']['Edit ThingsToDo'] = array("link"=>"","active"=>'1'); 
               
                return View::make('admin.thingstodo.edit',$data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
            if(!User::checkPermission(Auth::user()->user_group_id,'thingstodo','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            //skip email's unique validation if email is not changed
            $thingstodo = ThingToDo::find($id);
           
            {
                  $validator = Validator::make(
                    array(

                      'thing_todo' => Input::get('thingstodo'),
                   
                        ),
                    array(

                        'thing_todo' => 'required',
                   )
            );
            
            }
          
            
            if($validator->passes())
            {
                
                $thingstodo->thing_todo = Input::get('thingstodo');
                      
                $thingstodo->save();
                
                
              
                
                return Redirect::to('thingstodo')->with('success_message', 'Things To Do Updated Successfully');
            }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('thingstodo/'.$id.'/edit/')->withErrors($validator)->withInput();
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
             if(!User::checkPermission(Auth::user()->user_group_id,'thingstodo','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            // delete
		$thingstodo = ThingToDo::find($id);
		$thingstodo->delete();

		// redirect
		return Redirect::to('thingstodo')->with('success_message', 'Things To Do Deleted Successfully!');
	}

        
      
        
       public function thingstodoSearch($search)
        {
            if(!User::checkPermission(Auth::user()->user_group_id,'thingstodo','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
           
               
               $thingstodo = ThingToDo::where("thing_todo","like","%".$search."%")
                            
                            ->paginate(); 
               
               $data = array();
               $data['thingstodo'] = $thingstodo;
                //Basic Page Settings
               
                $basicPageVariables = ZnUtilities::basicPageVariables("ThingToDo","Search results", "ThingToDo","1");
                $data = array_merge($data,$basicPageVariables);
               
                $data['breadcrumbs']['All ThingToDo'] = array("link"=>'/thingstodo',"active"=>'0');
               $data['breadcrumbs']['Search'] = array("link"=>"","active"=>'1'); 
               
                $data['search'] = $search;

                return View::make('admin.thingstodo.list',$data);
               
           
          
        }
        
        public function thingstodoActions()
        {
             if(!User::checkPermission(Auth::user()->user_group_id,'thingstodo','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $search = Input::get('search');
            if($search!='')
            {
                return Redirect::to('/thingstodoSearch/'.$search);
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
                            DB::table('things_todo')
                                ->where('thing_todo_id', $id)
                                ->delete();
                        }
                        
                        return Redirect::to('/thingstodo/')->with('success_message', 'Rows Deleted!');
                        break;
                    }
                } //end switch
            } // end if statement
            return Redirect::to('/thingstodo');
            }
        }

}
