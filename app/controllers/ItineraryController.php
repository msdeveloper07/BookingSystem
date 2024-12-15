<?php

class ItineraryController extends BaseController {

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
            if(!User::checkPermission(Auth::user()->user_group_id,'itinerary','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();

            $data['itinerary'] = Itinerary::paginate(10);
           

            $basicPageVariables = ZnUtilities::basicPageVariables("Itinerary"," All Itinerary", "Itinerary","1");
            $data = array_merge($data,$basicPageVariables);
            
            
            $data['breadcrumbs']['Itinerary'] = array("link"=>'/itinerary',"active"=>'0');
           
            ZnUtilities::push_js_files('components/itinerary.js');
            
             $data['active_nav'] = 'location';
            
			return View::make('admin.itinerary.list',$data);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'itinerary','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
		$data = array();
                
                
                
                 ZnUtilities::push_js_files("components/itinerary.js");
                    ZnUtilities::push_js_files("jquery.validate.min.js");
              
                  ZnUtilities::push_js_files("chosen.jquery.min.js");
                
              
               
                
                $basicPageVariables = ZnUtilities::basicPageVariables("Itinerary"," Add new Itinerary", "Itinerary","1");
                $data = array_merge($data,$basicPageVariables);
                
                $data['breadcrumbs']['All Itinerary'] = array("link"=>'/itinerary',"active"=>'0');
                $data['breadcrumbs']['New Itinerary'] = array("link"=>"","active"=>'1'); 
            
               return View::make('admin.itinerary.create',$data);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'itinerary','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
              
                
            
         
           
            $validator = Validator::make(
                array(
                    'Itinerary_title' => Input::get('Itinerary_title'),
                    'datetime' => Input::get('date_time'),
                    'description' => Input::get('description'),
                   
                    ),
                array(
                    'Itinerary_title' => 'required',
                    'datetime' => 'required',
                    'description' => 'required',
                  
                    )
            );
            
            if($validator->passes())
            {
                
             
                  $Itinerary_title = Input::get('Itinerary_title');
                $date = Input::get('date_time');
                $description = Input::get('description');
                $time1 = Input::get('time1');
                $time2 = Input::get('time2');
                $time3 = Input::get('time3');
                
                
             
                foreach ($Itinerary_title as $k => $v) {

                
                
                        $new = new Itinerary();
                        $new->Itinerary_title = $v;
                        $new->datetime = $date[$k];
                        $new->time = $time1[$k].":".$time2[$k]." ".$time3[$k];
                        $new->description = $description[$k];
                        $new->save();
                        
                        
                    }
                
         
            
             
                return Redirect::to('itinerary')->with('success_message', 'Itinerary created successfully!');
            }
            else
            {
               
                return Redirect::to('itinerary/create')->withErrors($validator)->withInput();;
            }
            
	}
        
        

        public function edit($id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'itinerary','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            		$data = array();

                //Throw exception if project id does not exists
                $data['itinerary'] = Itinerary::findOrFail($id);
          
                 ZnUtilities::push_js_files("jquery.validate.min.js");
                 ZnUtilities::push_js_files("components/itinerary.js");
                

         
                $basicPageVariables = ZnUtilities::basicPageVariables("Itinerary","Edit Itinerary", "Itinerary","1");
                $data = array_merge($data,$basicPageVariables);
                
                 $data['breadcrumbs']['All Itinerary'] = array("link"=>'/itinerary',"active"=>'0');
                 $data['breadcrumbs']['Edit Itinerary'] = array("link"=>"","active"=>'1'); 
               
                return View::make('admin.itinerary.edit',$data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
            if(!User::checkPermission(Auth::user()->user_group_id,'itinerary','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            //skip email's unique validation if email is not changed
            $new = Itinerary::find($id);
           
                 $validator = Validator::make(
                array(
                    'Itinerary_title' => Input::get('Itinerary_title'),
                    'datetime' => Input::get('date_time'),
                    'description' => Input::get('description'),
                   
                    ),
                array(
                    'Itinerary_title' => 'required',
                    'datetime' => 'required',
                    'description' => 'required',
                  
                    )
            );
            
            if($validator->passes())
            {
                
             
                  $Itinerary_title = Input::get('Itinerary_title');
                $date = Input::get('date_time');
                $description = Input::get('description');
                $time1 = Input::get('time1');
                $time2 = Input::get('time2');
                $time3 = Input::get('time3');
                
                
             
                foreach ($Itinerary_title as $k => $v) {

                
                
                     
                        $new->Itinerary_title = $v;
                        $new->datetime = $date[$k];
//                        $new->time = $time1[$k].":".$time2[$k]." ".$time3[$k];
                        $new->description = $description[$k];
                        $new->save();
                        
                        
                    }
                
         
            
             
            
                return Redirect::to('itinerary')->with('success_message', 'Itinerary Updated Successfully');
            }
            else
            {
                
                return Redirect::to('itinerary/'.$id.'/edit/')->withErrors($validator)->withInput();
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
             if(!User::checkPermission(Auth::user()->user_group_id,'itinerary','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
         
		$location = Itinerary::find($id);
                
		$location->delete();

		// redirect
		return Redirect::to('location')->with('success_message', 'Itinerary deleted successfully!');
	}

        
      
       public function itinerarySearch($search)
        {
            if(!User::checkPermission(Auth::user()->user_group_id,'itinerary','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
           
               
               $location = Itinerary::where("Itinerary_title","like","%".$search."%")
                                    ->orWhere("datetime","like","%".$search."%")
                       ->orWhere("description","like","%".$search."%")
                       ->paginate(); 
               
               $data = array();
               $data['itinerary'] = $location;
                //Basic Page Settings
               
                $basicPageVariables = ZnUtilities::basicPageVariables("Itinerary","Search results", "Itinerary","1");
                $data = array_merge($data,$basicPageVariables);
               
                $data['breadcrumbs']['All Itinerary'] = array("link"=>'/itinerary',"active"=>'0');
               $data['breadcrumbs']['Search'] = array("link"=>"","active"=>'1'); 
               
                $data['search'] = $search;

                return View::make('admin.itinerary.list',$data);
               
           
          
        }
        
        public function itineraryActions()
        {
             if(!User::checkPermission(Auth::user()->user_group_id,'itinerary','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $search = Input::get('search');
            if($search!='')
            {
                return Redirect::to('/itinerarySearch/'.$search);
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
                            DB::table('itinerarys')
                                ->where('itinerary_id', $id)
                                ->delete();
                        }
                        
                        return Redirect::to('/itinerary/')->with('success_message', 'Rows Delete!');
                        break;
                    }
                } //end switch
            } // end if datetimement
            return Redirect::to('/itinerary');
            }
        }

}
?>