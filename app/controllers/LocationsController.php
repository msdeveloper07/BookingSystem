<?php

class LocationsController extends BaseController {

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
            if(!User::checkPermission(Auth::user()->user_group_id,'location','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();

            $data['location'] = Location::orderBy('location_id','desc')->paginate(10);
           

            $basicPageVariables = ZnUtilities::basicPageVariables("Locations"," All Locations", "locations","1");
            $data = array_merge($data,$basicPageVariables);
            
            
            $data['breadcrumbs']['Locations'] = array("link"=>'/locations',"active"=>'0');
           
            ZnUtilities::push_js_files('components/location.js');
            
             $data['active_nav'] = 'location';
                        
                         $data['submenus'] = $this->_submenus('index');
			return View::make('admin.locations.list',$data);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'location','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
		$data = array();
                
                
                  ZnUtilities::push_js_files("jquery.validate.min.js");
                 ZnUtilities::push_js_files("components/location.js");
               
                
                $basicPageVariables = ZnUtilities::basicPageVariables("Location"," Add new Location", "add_locations","1");
                $data = array_merge($data,$basicPageVariables);
                
                $data['breadcrumbs']['All Locations'] = array("link"=>'/locations',"active"=>'0');
                $data['breadcrumbs']['New Location'] = array("link"=>"","active"=>'1'); 
                
                 $data['submenus'] = $this->_submenus('index');
               return View::make('admin.locations.create',$data);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'location','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
     
            
         
           
            $validator = Validator::make(
                array(
                    'location_name' => Input::get('location_name'),
                    'state' => Input::get('country'),
                    'country' => Input::get('country'),
                   
                    ),
                array(
                    'location_name' => 'required|unique:locations',
//                    'state' => 'required',
                    'country' => 'required',
                  
                    )
            );
            
            if($validator->passes())
            {
               
                 
                
                
                $location = new Location();
                $location->location_name = Input::get('location_name');
                $location->state = Input::get('state');
                $location->country = Input::get('country');
              
                $location->save();
                
             
                return Redirect::to('locations')->with('success_message', 'Location created successfully!');
            }
            else
            {
               
                return Redirect::to('locations/create')->withErrors($validator)->withInput();;
            }
            
	}

        public function edit($id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'location','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            		$data = array();

                //Throw exception if project id does not exists
                $data['location'] = Location::findOrFail($id);
          
                 ZnUtilities::push_js_files("jquery.validate.min.js");
                 ZnUtilities::push_js_files("components/location.js");
                

         
                $basicPageVariables = ZnUtilities::basicPageVariables("Location","Edit Location", "locations","1");
                $data = array_merge($data,$basicPageVariables);
                
                 $data['breadcrumbs']['All Locations'] = array("link"=>'/locations',"active"=>'0');
                 $data['breadcrumbs']['Edit Location'] = array("link"=>"","active"=>'1'); 
               
                  $data['submenus'] = $this->_submenus('index');
                return View::make('admin.locations.edit',$data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
            if(!User::checkPermission(Auth::user()->user_group_id,'location','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            //skip email's unique validation if email is not changed
            $location = Location::find($id);
           
                    $validator = Validator::make(
                array(
                    'location_name' => Input::get('location_name'),
                    'state' => Input::get('country'),
                    'country' => Input::get('country'),
                   
                    ),
                array(
                    'location_name' => 'required',
//                    'state' => 'required',
                    'country' => 'required',
                  
                    )
            );
            
            if($validator->passes())
            {
               
                 
                
                
               
                $location->location_name = Input::get('location_name');
                $location->state = Input::get('state');
                $location->country = Input::get('country');
              
                $location->save();
                
            
                return Redirect::to('locations')->with('success_message', 'Location Updated Successfully');
            }
            else
            {
                
                return Redirect::to('locations/'.$id.'/edit/')->withErrors($validator)->withInput();
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
             if(!User::checkPermission(Auth::user()->user_group_id,'location','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
         
		$location = Location::find($id);
                
		$location->delete();

		// redirect
		return Redirect::to('locations')->with('success_message', 'Location deleted successfully!');
	}

        
      
       public function locationSearch($search)
        {
            if(!User::checkPermission(Auth::user()->user_group_id,'location','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
           
               
               $location = Location::where("location_name","like","%".$search."%")
                                    ->orWhere("state","like","%".$search."%")
                       ->orWhere("country","like","%".$search."%")
                       ->paginate(); 
               
               $data = array();
               $data['location'] = $location;
                //Basic Page Settings
               
                $basicPageVariables = ZnUtilities::basicPageVariables("Location","Search results", "locations","1");
                $data = array_merge($data,$basicPageVariables);
               
                $data['breadcrumbs']['All Locations'] = array("link"=>'/locations',"active"=>'0');
               $data['breadcrumbs']['Search'] = array("link"=>"","active"=>'1'); 
               
                $data['search'] = $search;
                
                 $data['submenus'] = $this->_submenus('index');
                return View::make('admin.locations.list',$data);
               
           
          
        }
        
        public function locationActions()
        {
             if(!User::checkPermission(Auth::user()->user_group_id,'location','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $search = Input::get('search');
            if($search!='')
            {
                return Redirect::to('/locationSearch/'.$search);
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
                            DB::table('locations')
                                ->where('location_id', $id)
                                ->delete();
                        }
                        
                        return Redirect::to('/locations/')->with('success_message', 'Rows Delete!');
                        break;
                    }
                } //end switch
            } // end if statement
            return Redirect::to('/locations');
            }
        }
        
           private function _submenus($active)
    {   
        $data = array();
        $data['Loctions'] = array("link" => '/locations', "active" => $active=='index'?'1':'0' ,"icon" => 'fa-map-marker');
        return $data;
    }

}
?>