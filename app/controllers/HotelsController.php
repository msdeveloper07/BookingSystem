<?php

class HotelsController extends BaseController {

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
            if(!User::checkPermission(Auth::user()->user_group_id,'hotels','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();

            $data['hotel'] = Hotel::orderBy('hotel_id','desc')->paginate('20');
           

            $basicPageVariables = ZnUtilities::basicPageVariables("Hotels"," All Hotels", "hotels","1");
            $data = array_merge($data,$basicPageVariables);
            
            
            $data['breadcrumbs']['Hotels'] = array("link"=>'/hotels',"active"=>'0');
           
            ZnUtilities::push_js_files('components/hotels.js');
            
             $data['active_nav'] = 'Hotels';
             
                          $data['submenus'] = $this->_submenus('index');
			return View::make('admin.hotels.list',$data);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'hotels','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            
                  ZnUtilities::push_js_files('components/hotels.js');
        ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
        $editor_js = '$(function() {
                       // $("#content").wysihtml5();
                       CKEDITOR.replace("description");
                       });';
        ZnUtilities::push_js($editor_js);
        
		$data = array();
                $basicPageVariables = ZnUtilities::basicPageVariables("Hotels"," Add New Hotel", "add_hotel","1");
                $data = array_merge($data,$basicPageVariables);
                
                $data['breadcrumbs']['All Hotels'] = array("link"=>'/hotels',"active"=>'0');
                $data['breadcrumbs']['New Hotel'] = array("link"=>"","active"=>'1'); 
            
                  $data['submenus'] = $this->_submenus('index');
               return View::make('admin.hotels.create',$data);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'hotels','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
           // ZnUtilities::pa($_POST);
            
//            
           // die;
            $validator = Validator::make(
                array(
                    'hotel_name' => Input::get('hotel_name'),
                    'location' => Input::get('location'),
                    'description' => Input::get('description'),
                 
                    ),
                array(
                    'hotel_name' => 'required',
                    'location' => 'required',
                    'description' => 'required',
                
                 
                   
                    )
            );
            
            if($validator->passes())
            {
                
                 
                
                
                $hotel = new Hotel();
                $hotel->hotel_name = Input::get('hotel_name');
                $hotel->location = Input::get('location');
                $hotel->description = Input::get('description');
                $hotel->nearest_airport = Input::get('nearest_airport');
               
                //$hotel->faq_status  = Input::get('faq_status');
                
              
                $hotel->save();
                
                
              
                
            
                
                return Redirect::to('hotel/properties/'.$hotel->hotel_id)->with('success_message', 'Hotel created successfully!');
            }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('hotels/create')->withErrors($validator)->withInput();;
            }
            
	}
        public function properties($id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'hotels','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();
            
                
            $data['hotel'] = Hotel::find($id);  
            $data['hotel_feature'] = HotelFeature::where('hotel_id',$id)->get();
            
             $supplier_type = SupplierType::where('supplier_type','Hotel')->pluck('supplier_type_id'); 
             $data['supplier_type_item'] = SupplierTypeItem::where('supplier_type_id',$supplier_type)->get();
             
               
             $basicPageVariables = ZnUtilities::basicPageVariables("Hotels"," Add Hotel Property", "add_hotel","1");
                $data = array_merge($data,$basicPageVariables);
                
                $data['breadcrumbs']['All Hotels'] = array("link"=>'/hotels',"active"=>'0');
                $data['breadcrumbs']['New Property'] = array("link"=>"","active"=>'1'); 
            ZnUtilities::push_js_files('components/hotels.js');
                  $data['submenus'] = $this->_submenus('index');
               return View::make('admin.hotels.properties',$data);
	}
        
        public function saveProperties($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'hotels', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $property_raw = Input::get('property');

        $property = array();
        
     
         
        foreach ($property_raw as $k=>$p) {
          
            
            foreach($p as $key => $item)
            {
           
                if (!isset($item['name'])) {
                    unset($p[$key]);
                }
            }
            
              if(count($p)>0)
              {
                  $property[$k] = $p;
              }
            
       
        }
    
        
        $hotel = HotelFeature::where('hotel_id',$id)->delete();

        $hotel = Hotel::find($id);

        $validator = Validator::make(
                        array(
                    'property' => Input::get('property'),
                        ), array(
                    'property' => 'required',
                        )
        );


        if ($validator->passes()) {
            $names = array();
            
             $supplier_type_id = Input::get('supplier_type_id');

            foreach ($property as $k => $p) {

               
                    $hotel = new HotelFeature();

                    $hotel->supplier_type_item_id = $k;
                    $hotel->supplier_type_id = $supplier_type_id;
                    $hotel->hotel_id = $id;

                    $hotel->values = json_encode($p);


                    $hotel->save();
                    
                    foreach($p as $tag)
                    {
                        $names[] = $tag['name'];
                    
                    }
            }


         
            $hotels = Hotel::find($id);
            $hotels->tags = implode(',', $names);
            $hotels->save();


            return Redirect::to('hotels')->with('success_message', 'Hotel Updated Successfully');
        } else {
            //$messages = $validator->messages();
            return Redirect::to('hotel/properties/' . $id)->withErrors($validator)->withInput();
        }
    }
    
    
        
            public function editProperties($id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'hotels','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();
            
                
            $data['hotel'] = Hotel::find($id);  
            $data['hotel_feature'] = HotelFeature::where('hotel_id',$id)->get();
            
             $supplier_type = SupplierType::where('supplier_type','Hotel')->pluck('supplier_type_id'); 
             $data['supplier_type_item'] = SupplierTypeItem::where('supplier_type_id',$supplier_type)->get();
             
               
             $basicPageVariables = ZnUtilities::basicPageVariables("Hotels"," Add Hotel Property", "add_hotel","1");
                $data = array_merge($data,$basicPageVariables);
                
                $data['breadcrumbs']['All Hotels'] = array("link"=>'/hotels',"active"=>'0');
                $data['breadcrumbs']['New Property'] = array("link"=>"","active"=>'1'); 
            ZnUtilities::push_js_files('components/hotels.js');
                  $data['submenus'] = $this->_submenus('index');
               return View::make('admin.hotels.editproperties',$data);
	}

             public function updateProperties($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'hotels', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $property_raw = Input::get('property');

        $property = array();
        
      
         
        foreach ($property_raw as $k=>$p) {
          
            
            foreach($p as $key => $item)
            {
           
                if (!isset($item['name'])) {
                    unset($p[$key]);
                }
            }
            
              if(count($p)>0)
              {
                  $property[$k] = $p;
              }
            
       
        }
    
        $hotel = HotelFeature::where('hotel_id',$id)->delete();

        $hotel = Hotel::find($id);

        $validator = Validator::make(
                        array(
                    'property' => Input::get('property'),
                        ), array(
                    'property' => 'required',
                        )
        );


        if ($validator->passes()) {
            $names = array();
            
             $supplier_type_id = Input::get('supplier_type_id');

            foreach ($property as $k => $p) {

               
                    $hotel = new HotelFeature();

                    $hotel->supplier_type_item_id = $k;
                    $hotel->supplier_type_id = $supplier_type_id;
                    $hotel->hotel_id = $id;

                    $hotel->values = json_encode($p);


                    $hotel->save();
                    
                    foreach($p as $tag)
                    {
                        $names[] = $tag['name'];
                    
                    }
            }


         
            $hotels = Hotel::find($id);
            $hotels->tags = implode(',', $names);
            $hotels->save();


            return Redirect::to('hotels')->with('success_message', 'Hotel Updated Successfully');
        } else {
            //$messages = $validator->messages();
            return Redirect::to('hotel/properties/' . $id)->withErrors($validator)->withInput();
        }
    }
    


        public function edit($id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'hotels','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
               $data['hotel'] = Hotel::find($id);
            
                ZnUtilities::push_js_files('components/hotels.js');
        ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
        $editor_js = '$(function() {
                       // $("#content").wysihtml5();
                       CKEDITOR.replace("description");
                       });';
        ZnUtilities::push_js($editor_js);

                
                $basicPageVariables = ZnUtilities::basicPageVariables("Hotels","Edit Hotel", "hotels","1");
                $data = array_merge($data,$basicPageVariables);
                
                 $data['breadcrumbs']['All Hotels'] = array("link"=>'/hotels',"active"=>'0');
                 $data['breadcrumbs']['Edit Hotels'] = array("link"=>"","active"=>'1'); 
               
                   $data['submenus'] = $this->_submenus('index');
                return View::make('admin.hotels.edit',$data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
            if(!User::checkPermission(Auth::user()->user_group_id,'hotels','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            //skip email's unique validation if email is not changed
           
          
                   $validator = Validator::make(
                array(
                    'hotel_name' => Input::get('hotel_name'),
                    'location' => Input::get('location'),
                    'description' => Input::get('description'),
                  
                     ),
                array(
                    'hotel_name' => 'required',
                    'location' => 'required',
                    'description' => 'required',
                
                    )
            );
            
            if($validator->passes())
            {
                $hotel = Hotel::find($id);
                $hotel->hotel_name = Input::get('hotel_name');
                $hotel->location = Input::get('location');
                $hotel->description = Input::get('description');
                $hotel->nearest_airport = Input::get('nearest_airport');
                $hotel->save();
                   
                return Redirect::to('hotel/properties/'.$hotel->hotel_id)->with('success_message', 'Hotel created successfully!');
            }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('hotels/'.$id.'/edit')->withErrors($validator)->withInput();;
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
             if(!User::checkPermission(Auth::user()->user_group_id,'hotels','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            // delete
		$hotel = Faq::find($id);
		$hotel->delete();

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
        
       
       public function hotelSearch($search)
        {
            if(!User::checkPermission(Auth::user()->user_group_id,'hotels','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
           
               
               $hotel = Hotel::where("hotel_name","like","%".$search."%")
                               ->orwhere("location","like","%".$search."%")
                               ->orwhere("nearest_airport","like","%".$search."%")
                           
                            ->paginate(); 
               
               $data = array();
               $data['hotel'] = $hotel;
                //Basic Page Settings
               
                $basicPageVariables = ZnUtilities::basicPageVariables("Hotels","Search Results", "Hotels","1");
                $data = array_merge($data,$basicPageVariables);
               
                $data['breadcrumbs']['All Faqs'] = array("link"=>'/hotels',"active"=>'0');
               $data['breadcrumbs']['Search'] = array("link"=>"","active"=>'1'); 
               
                $data['search'] = $search;
                ZnUtilities::push_js_files('components/hotels.js');
                
                  $data['submenus'] = $this->_submenus('index');
                return View::make('admin.hotels.list',$data);
               
           
          
        }
        
        public function hotelActions()
        {
             if(!User::checkPermission(Auth::user()->user_group_id,'hotels','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $search = Input::get('search');
            if($search!='')
            {
                return Redirect::to('/hotelSearch/'.$search);
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
                            DB::table('hotels')
                                ->where('hotel_id', $id)
                                ->delete();
                        }
                        
                        return Redirect::to('/hotels/')->with('success_message', 'Rows Delete!');
                        break;
                    }
                } //end switch
            } // end if statement
            return Redirect::to('/hotels');
            }
        }

        
         public function result($id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'hotels','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            		$data = array();

                //Throw exception if project id does not exists
                $data['faq'] = Faq::findOrFail($id);
               
               
              
                // Load Component JS
                 ZnUtilities::push_js_files("jquery.validate.min.js");
                 ZnUtilities::push_js_files("admin_components/hotelss.js");
                

                
                $js = "$('.delete_form').submit(function() {
                        var c = confirm('Are you sure you want to delete this user?');
                        return c; 
                    });";
                 ZnUtilities::push_js($js);
                
                $basicPageVariables = ZnUtilities::basicPageVariables("Hotels","Edit Faq", "faqs","1");
                $data = array_merge($data,$basicPageVariables);
                
                 $data['breadcrumbs']['All Faqs'] = array("link"=>'/hotels',"active"=>'0');
                 $data['breadcrumbs']['Edit Faqs'] = array("link"=>"","active"=>'1'); 
               
                   $data['submenus'] = $this->_submenus('index');
                return View::make('admin.faqs.result',$data);
	}
        
        public function uploadCsv()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'hotels','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();
                
                
                
                $basicPageVariables = ZnUtilities::basicPageVariables("Hotels"," Add new Faq", "add_faq","1");
                $data = array_merge($data,$basicPageVariables);
                
                $data['breadcrumbs']['All Faqs'] = array("link"=>'/hotels',"active"=>'0');
                $data['breadcrumbs']['New Faq'] = array("link"=>"","active"=>'1'); 
            
                  $data['submenus'] = $this->_submenus('index');
               return View::make('admin.faqs.uploadcsv',$data);
	}
        
        public function saveImportCsv()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'hotels','manage'))
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
                  
              
                      
                        $hotel = new Hotel();
                        $hotel->question = $v['Question'];
                        $hotel->answer = $v['Answer'];

                        //$hotel->faq_status  = Input::get('faq_status');


                        $hotel->save();
                        
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
        $data['Hotels'] = array("link" => '/hotels', "active" => $active=='index'?'1':'0' ,"icon" => 'fa-h-square');
        return $data;
    }

}
