<?php

class AirlinesController extends BaseController {

    public function __construct() {
        $this->beforeFilter(function() {
            if (!Auth::check()) {
                return Redirect::to('login')->with('error_message', "You are either not logged in or you dont have permissions to access this page");
            }
        });



        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'airlines', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $data = array();

        $data['airline'] = Airline::orderBy('airline_id','desc')->paginate(10);


        $basicPageVariables = ZnUtilities::basicPageVariables("Airlines", " All Airlines", "airlines", "1");
        $data = array_merge($data, $basicPageVariables);


        $data['breadcrumbs']['Airlines'] = array("link" => '/airlines', "active" => '0');

        ZnUtilities::push_js_files('components/airlines.js');

        $data['active_nav'] = 'Airlines';

        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.airlines.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'airlines', 'manage')) {
            return Redirect::to('/permissionDenied');
        }



        ZnUtilities::push_js_files('components/airlines.js');
        ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
        $editor_js = '$(function() {
                       // $("#content").wysihtml5();
                       CKEDITOR.replace("description");
                       });';
        ZnUtilities::push_js($editor_js);

        $data = array();
        $basicPageVariables = ZnUtilities::basicPageVariables("Airlines", " Add New Airline", "add_airline", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Airlines'] = array("link" => '/airlines', "active" => '0');
        $data['breadcrumbs']['New Airline'] = array("link" => "", "active" => '1');

        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.airlines.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'airlines', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        // ZnUtilities::pa($_POST);
//            
        // die;
        $validator = Validator::make(
                        array(
                    'airline_name' => Input::get('airline_name'),
                 
                    'description' => Input::get('description'),
               
                        ), array(
                    'airline_name' => 'required',
                 
                    'description' => 'required',
                   
                        )
        );

        if ($validator->passes()) {




            $airline = new Airline();
            $airline->airline_name = Input::get('airline_name');
        
            $airline->description = Input::get('description');
          

            $airline->save();






            return Redirect::to('airline/properties/' . $airline->airline_id)->with('success_message', 'Hotel created successfully!');
        } else {
            //$messages = $validator->messages();
            return Redirect::to('airlines/create')->withErrors($validator)->withInput();
            ;
        }
    }

    public function properties($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'airlines', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $data = array();


        $data['airline'] = Airline::find($id);
             $supplier_type = SupplierType::where('supplier_type','Airline')->pluck('supplier_type_id'); 
             $data['supplier_type_item'] = SupplierTypeItem::where('supplier_type_id',$supplier_type)->get();
            
        $basicPageVariables = ZnUtilities::basicPageVariables("Airlines", " Add Hotel Property", "add_hotel", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Airlines'] = array("link" => '/airlines', "active" => '0');
        $data['breadcrumbs']['New Property'] = array("link" => "", "active" => '1');
        ZnUtilities::push_js_files('components/airlines.js');
        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.airlines.properties', $data);
    }

    public function saveProperties($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'airlines', 'manage')) {
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
    


        $airline = Hotel::find($id);

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

AirlineFeature::where('airline_id',$id)->delete();


            foreach ($property as $k => $p) {

               
                    $airline = new AirlineFeature();

                    $airline->supplier_type_item_id = $k;
                       $airline->supplier_type_id = $supplier_type_id;
                    
                    $airline->airline_id = $id;

                    $airline->values = json_encode($p);


                    $airline->save();
                    
                    foreach($p as $tag)
                    {
                        $names[] = $tag['name'];
                    
                    }
            }


         
            $airlines = Airline::find($id);
            $airlines->tags = implode(',', $names);
            $airlines->save();


            return Redirect::to('airlines')->with('success_message', 'Hotel Updated Successfully');
        } else {
            //$messages = $validator->messages();
            return Redirect::to('hotel/properties/' . $id)->withErrors($validator)->withInput();
        }
    }
    
    
    public function editProperties($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'airlines', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $data = array();


        $data['airline'] = Airline::find($id);
             $supplier_type = SupplierType::where('supplier_type','Airline')->pluck('supplier_type_id'); 
             $data['supplier_type_item'] = SupplierTypeItem::where('supplier_type_id',$supplier_type)->get();
            
        $basicPageVariables = ZnUtilities::basicPageVariables("Airlines", " Add Hotel Property", "add_hotel", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Airlines'] = array("link" => '/airlines', "active" => '0');
        $data['breadcrumbs']['New Property'] = array("link" => "", "active" => '1');
        ZnUtilities::push_js_files('components/airlines.js');
        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.airlines.editproperties', $data);
    }
    
    
     public function updateProperties($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'airlines', 'manage')) {
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
    


        $airline = Hotel::find($id);

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

AirlineFeature::where('airline_id',$id)->delete();


            foreach ($property as $k => $p) {

               
                    $airline = new AirlineFeature();

                    $airline->supplier_type_item_id = $k;
                       $airline->supplier_type_id = $supplier_type_id;
                    
                    $airline->airline_id = $id;

                    $airline->values = json_encode($p);


                    $airline->save();
                    
                    foreach($p as $tag)
                    {
                        $names[] = $tag['name'];
                    
                    }
            }


         
            $airlines = Airline::find($id);
            $airlines->tags = implode(',', $names);
            $airlines->save();


            return Redirect::to('airlines')->with('success_message', 'Hotel Updated Successfully');
        } else {
            //$messages = $validator->messages();
            return Redirect::to('hotel/properties/' . $id)->withErrors($validator)->withInput();
        }
    }
   

    public function edit($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'airlines', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $data['airline'] = Airline::find($id);

            ZnUtilities::push_js_files('components/airlines.js');
        ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
        $editor_js = '$(function() {
                       // $("#content").wysihtml5();
                       CKEDITOR.replace("description");
                       });';
        ZnUtilities::push_js($editor_js);


        $basicPageVariables = ZnUtilities::basicPageVariables("Airlines", "Edit Airline", "airlines", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Airlines'] = array("link" => '/airlines', "active" => '0');
        $data['breadcrumbs']['Edit Airlines'] = array("link" => "", "active" => '1');

        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.airlines.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'airlines', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        //skip email's unique validation if email is not changed


          $validator = Validator::make(
                        array(
                    'airline_name' => Input::get('airline_name'),
                 
                    'description' => Input::get('description'),
               
                        ), array(
                    'airline_name' => 'required',
                 
                    'description' => 'required',
                   
                        )
        );

        if ($validator->passes()) {




            $airline = Airline::find($id);
            $airline->airline_name = Input::get('airline_name');
        
            $airline->description = Input::get('description');
          

            $airline->save();




            return Redirect::to('airline/properties/' . $airline->airline_id)->with('success_message', 'Airline Update successfully!');
        } else {
            //$messages = $validator->messages();
            return Redirect::to('airlines/' . $id . '/edit')->withErrors($validator)->withInput();
            ;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'airlines', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        // delete
        $airline = Faq::find($id);
        $airline->delete();

        // redirect
        return Redirect::to('faq')->with('success_message', 'FAQ deleted successfully!');
    }

    public function Status($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'users', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $user = User::find($id);
        if ($user->user_status == 'active') {
            $user->user_status = 'deactive';
            $msg = 'User Deactivated!';
        } else if (($user->user_status == 'deactive') || ($user->user_status == 'not_activated')) {
            $user->user_status = 'active';
            $msg = 'User Activated!';
        }

        $user->save();

        return Redirect::to('users/' . $id . '/edit')->with('success_message', $msg);
    }

    public function airlineSearch($search) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'airlines', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

      $airline = Airline::where("airline_name", "like", "%" . $search . "%")
               
                ->paginate();

        $data = array();
        $data['airline'] = $airline;
        //Basic Page Settings

        $basicPageVariables = ZnUtilities::basicPageVariables("Airlines", "Search Results", "Airlines", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Airline'] = array("link" => '/airlines', "active" => '0');
        $data['breadcrumbs']['Search'] = array("link" => "", "active" => '1');

        $data['search'] = $search;
        ZnUtilities::push_js_files('components/airlines.js');

        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.airlines.list', $data);
    }

    public function airlineActions() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'airlines', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $search = Input::get('search');
        if ($search != '') {
            return Redirect::to('/airlineSearch/' . $search);
        } else {


            //die(Input::get('bulk_action')   );

            $cid = Input::get('cid');
            $bulk_action = Input::get('bulk_action');
            if ($bulk_action != '') {
                switch ($bulk_action) {
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
                    case 'delete': {

                            foreach ($cid as $id) {
                                DB::table('airlines')
                                        ->where('airline_id', $id)
                                        ->delete();
                            }

                            return Redirect::to('/airlines/')->with('success_message', 'Rows Delete!');
                            break;
                        }
                } //end switch
            } // end if statement
            return Redirect::to('/airlines');
        }
    }

   

    private function _submenus($active) {
        $data = array();
        $data['Airlines'] = array("link" => '/airlines', "active" => $active == 'index' ? '1' : '0', "icon" => 'fa-plane');
        return $data;
    }

}
