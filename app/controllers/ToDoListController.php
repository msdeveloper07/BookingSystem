<?php

class ToDoListController extends BaseController {

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
            if(!User::checkPermission(Auth::user()->user_group_id,'todolist','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();

            $data['todolist'] = ToDoList::paginate(10);
           

            $basicPageVariables = ZnUtilities::basicPageVariables("ToDoLists"," All ToDoLists", "ToDoLists","1");
            $data = array_merge($data,$basicPageVariables);
            
            
            $data['breadcrumbs']['ToDoLists'] = array("link"=>'/todolist',"active"=>'0');
           
            ZnUtilities::push_js_files('components/todolist.js');
            
             $data['active_nav'] = 'todolist';
            
			return View::make('admin.todoList.list',$data);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'todolist','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
		$data = array();
                
                
                  ZnUtilities::push_js_files("jquery.validate.min.js");
                 ZnUtilities::push_js_files("components/todolist.js");
               
                
                $basicPageVariables = ZnUtilities::basicPageVariables("ToDoList"," Add new ToDoList", "ToDoList","1");
                $data = array_merge($data,$basicPageVariables);
                
                $data['breadcrumbs']['All ToDoLists'] = array("link"=>'/todolist',"active"=>'0');
                $data['breadcrumbs']['New ToDoList'] = array("link"=>"","active"=>'1'); 
            
               return View::make('admin.todoList.create',$data);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'todolist','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
     
            
         
           
            $validator = Validator::make(
                array(
                    'todolist_question' => Input::get('question'),
             
                    ),
                array(
                    'todolist_question' => 'required',
             
                    )
            );
            
            if($validator->passes())
            {
                   $todolist = new ToDoList();
                $todolist->questions = Input::get('question');
           
                $todolist->save();
                
             
                return Redirect::to('todolist')->with('success_message', 'ToDoList created successfully!');
            }
            else
            {
               
                return Redirect::to('todolist/create')->withErrors($validator)->withInput();;
            }
            
	}

        public function edit($id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'todolist','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            		$data = array();

                //Throw exception if project id does not exists
                $data['todolist'] = ToDoList::findOrFail($id);
          
                 ZnUtilities::push_js_files("jquery.validate.min.js");
                 ZnUtilities::push_js_files("components/todolist.js");
                

         
                $basicPageVariables = ZnUtilities::basicPageVariables("ToDoList","Edit ToDoList", "ToDoList","1");
                $data = array_merge($data,$basicPageVariables);
                
                 $data['breadcrumbs']['All ToDoLists'] = array("link"=>'/todolist',"active"=>'0');
                 $data['breadcrumbs']['Edit ToDoList'] = array("link"=>"","active"=>'1'); 
               
                return View::make('admin.todoList.edit',$data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
            if(!User::checkPermission(Auth::user()->user_group_id,'todolist','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
          
           
             $todolist = ToDoList::find($id);
             
          
            
       
              $validator = Validator::make(
                array(
                    'todolist_question' => Input::get('question'),
             
                    ),
                array(
                    'todolist_question' => 'required',
             
                    )
            );
            
            if($validator->passes())
            {
                
            
                $todolist->questions = Input::get('question');
              
                $todolist->save();
                
             
                return Redirect::to('todolist')->with('success_message', 'ToDoList created successfully!');
            }
            else
            {
                
                return Redirect::to('todolist/'.$id.'/edit/')->withErrors($validator)->withInput();
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
             if(!User::checkPermission(Auth::user()->user_group_id,'todolist','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
         
		$todolist = ToDoList::find($id);
                
		$todolist->delete();

		// redirect
		return Redirect::to('todolist')->with('success_message', 'ToDoList deleted successfully!');
	}

        
      
       public function todolistSearch($search)
        {
            if(!User::checkPermission(Auth::user()->user_group_id,'todolist','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
           
               
               $todolist = ToDoList::where("questions","like","%".$search."%")->paginate(); 
               
               $data = array();
               $data['todolist'] = $todolist;
                //Basic Page Settings
               
                $basicPageVariables = ZnUtilities::basicPageVariables("ToDoList","Search results", "ToDoList","1");
                $data = array_merge($data,$basicPageVariables);
               
                $data['breadcrumbs']['All ToDoLists'] = array("link"=>'/todolist',"active"=>'0');
               $data['breadcrumbs']['Search'] = array("link"=>"","active"=>'1'); 
               
                $data['search'] = $search;

                return View::make('admin.todoList.list',$data);
               
           
          
        }
        
        public function todolistActions()
        {
             if(!User::checkPermission(Auth::user()->user_group_id,'todolist','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $search = Input::get('search');
            if($search!='')
            {
                return Redirect::to('/todolistSearch/'.$search);
            }
            else{
                
                
            //die(Input::get('bulk_action')   );
            
            $cid = Input::get('cid');
//            ZnUtilities::pa($cid);die();
            $bulk_action = Input::get('bulk_action');
            if($bulk_action!='')
            {
                switch($bulk_action)
                {
                   
                    
                    case 'delete':
                    {
                       
                        
                        foreach($cid as $id)
                        {
                            DB::table('todolists')
                                ->where('todolist_id', $id)
                                ->delete();
                        }
                        
                        return Redirect::to('/todolist/')->with('success_message', 'Rows Delete!');
                        break;
                    }
                } //end switch
            } // end if statement
            return Redirect::to('/todolist');
            }
        }

}
?>