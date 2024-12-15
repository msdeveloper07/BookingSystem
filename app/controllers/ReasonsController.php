<?php

class ReasonsController extends BaseController {

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
            if(!User::checkPermission(Auth::user()->user_group_id,'reason','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();

            $data['reason'] = Reason::paginate(10);
           

            $basicPageVariables = ZnUtilities::basicPageVariables("Reasons"," All Reasons", "Reasons","1");
            $data = array_merge($data,$basicPageVariables);
            
            
            $data['breadcrumbs']['Reasons'] = array("link"=>'/reason',"active"=>'0');
           
            ZnUtilities::push_js_files('components/reasons.js');
            
            // $data['active_nav'] = 'reason';
               $data['submenus'] = $this->_submenus('index');
			return View::make('admin.reasons.list',$data);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'reason','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
		$data = array();
                
                
                  ZnUtilities::push_js_files("jquery.validate.min.js");
                 ZnUtilities::push_js_files("components/reasons.js");
               
                
                $basicPageVariables = ZnUtilities::basicPageVariables("Reasons","Add Reasons", "add_reasons","1");
                $data = array_merge($data,$basicPageVariables);
                
                $data['breadcrumbs']['All Reasons'] = array("link"=>'/reason',"active"=>'0');
                $data['breadcrumbs']['New Reason'] = array("link"=>"","active"=>'1'); 
               
                $data['submenus'] = $this->_submenus('index');
               return View::make('admin.reasons.create',$data);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'reason','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
     
            
         
           
            $validator = Validator::make(
                array(
                    'reason' => Input::get('reason'),
                   
                    'reason_status' => Input::get('reason_status'),
                   
                    ),
                array(
                    'reason' => 'required',
                    'reason_status' => 'required',
                  
                    )
            );
            
            if($validator->passes())
            {
               
                 
                
                
                $reason = new Reason();
                $reason->reason = Input::get('reason');
                $reason->status = Input::get('reason_status');
              
                $reason->save();
                
             
                return Redirect::to('reason')->with('success_message', 'Reason created successfully!');
            }
            else
            {
               
                return Redirect::to('reason/create')->withErrors($validator)->withInput();;
            }
            
	}

        public function edit($id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'reason','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            		$data = array();

                //Throw exception if project id does not exists
                $data['reason'] = Reason::findOrFail($id);
          
                 ZnUtilities::push_js_files("jquery.validate.min.js");
                 ZnUtilities::push_js_files("components/reasons.js");
                

         
                $basicPageVariables = ZnUtilities::basicPageVariables("Reasons","Edit Reason", "Reasons","1");
                $data = array_merge($data,$basicPageVariables);
                
                 $data['breadcrumbs']['All Reasons'] = array("link"=>'/reason',"active"=>'0');
                 $data['breadcrumbs']['Edit Reasons'] = array("link"=>"","active"=>'1'); 
               
             $data['submenus'] = $this->_submenus('index');
                return View::make('admin.reasons.edit',$data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
            if(!User::checkPermission(Auth::user()->user_group_id,'reason','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            //skip email's unique validation if email is not changed
            $reason = Reason::find($id);
           
                    $validator = Validator::make(
                array(
                    'reason' => Input::get('reason'),
                    'reason_status' => Input::get('reason_status'),
                   
                    ),
                array(
                    'reason' => 'required',
                    'reason_status' => 'required',
                  
                    )
            );
            
            if($validator->passes())
            {
               
                 
                
                
               
                $reason->reason = Input::get('reason');
                $reason->status = Input::get('reason_status');
              
                $reason->save();
                
            
                return Redirect::to('reason')->with('success_message', 'Reason Updated Successfully');
            }
            else
            {
                
                return Redirect::to('reason/'.$id.'/edit/')->withErrors($validator)->withInput();
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
             if(!User::checkPermission(Auth::user()->user_group_id,'reason','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
         
		$reason = Reason::find($id);
                
		$reason->delete();

		// redirect
		return Redirect::to('reason')->with('success_message', 'Reason Deleted Successfully!');
	}

        public function changeStatus($id)
        {
            if(!User::checkPermission(Auth::user()->user_group_id,'reason','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
              $reason = Reason::find($id);
              if($reason->reason_status=='Active')
              {
                  $reason->status = 'Deactive';
                  $msg = 'Reason Deactivated!';
              }
              else if(($reason->reason_status=='Deactive')||($reason->status=='Deactive'))
              {
                  $reason->reason_status = 'Active';
                    $msg = 'Reason Activated!';

              }
              
              $reason->save();
              
              return Redirect::to('reason/'.$id.'/edit')->with('success_message', $msg );
        } 
      
       public function reasonSearch($search)
        {
            if(!User::checkPermission(Auth::user()->user_group_id,'reason','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
           
               
               $reason = Reason::where("reason","like","%".$search."%")
                                       ->orWhere("status","like","%".$search."%")
                       ->paginate(); 
               
               $data = array();
               $data['reason'] = $reason;
                //Basic Page Settings
               
                $basicPageVariables = ZnUtilities::basicPageVariables("Reasons","Search results", "Reasons","1");
                $data = array_merge($data,$basicPageVariables);
               
                $data['breadcrumbs']['All Reasons'] = array("link"=>'/reason',"active"=>'0');
               $data['breadcrumbs']['Search'] = array("link"=>"","active"=>'1'); 
               
                $data['search'] = $search;

                   $data['submenus'] = $this->_submenus('index');
                return View::make('admin.reasons.list',$data);
               
           
          
        }
        
        public function reasonActions()
        {
             if(!User::checkPermission(Auth::user()->user_group_id,'reason','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $search = Input::get('search');
            if($search!='')
            {
                return Redirect::to('/reasonSearch/'.$search);
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
                   case 'Deactive':
                    {
                        foreach($cid as $id)
                        {
                            DB::table('reasons')
                                ->where('reason_id', $id)
                                ->update(array('status' =>  'Deactive'));
                        }
                       
                       return Redirect::to('/reason/')->with('success_message', 'Rows Updated!');

                        break;
                    }
                    case 'Active':
                    {
                        foreach($cid as $id)
                        {
                            DB::table('reasons')
                                ->where('reason_id', $id)
                                ->update(array('status' =>  'Active'));
                        }
                        
                       return Redirect::to('/reason/')->with('success_message', 'Rows Updated!');
                    }
                    
                    case 'delete':
                    {
                       
                        
                        foreach($cid as $id)
                        {
                            DB::table('reasons')
                                ->where('reason_id', $id)
                                ->delete();
                        }
                        
                        return Redirect::to('/reason/')->with('success_message', 'Rows Delete!');
                        break;
                    }
                } //end switch
            } // end if statement
            return Redirect::to('/reason');
            }
        }
        
        
              private function _submenus($active)
    {   
        $data = array();
        $data['Reasons'] = array("link" => '/reason', "active" => $active=='index'?'1':'0' ,"icon" => 'fa-user-plus');
        return $data;
    }


}
?>