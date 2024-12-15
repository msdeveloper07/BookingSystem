<?php

class BrandVariablesController extends BaseController {

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
            if(!User::checkPermission(Auth::user()->user_group_id,'brandvariables','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();

            $data['brand_variables'] = BrandVariable::orderBy('brand_variable_id','desc')->paginate(20);
           

            $basicPageVariables = ZnUtilities::basicPageVariables("BrandVariables","All Brand Variables", "brandvariables","1");
            $data = array_merge($data,$basicPageVariables);
            
            
            $data['breadcrumbs']['All Brand Variables'] = array("link"=>'/brandvariables',"active"=>'0');
           
            ZnUtilities::push_js_files('components/brands.js');
            
             $data['active_nav'] = 'brandvariables';
             
                          $data['submenus'] = $this->_submenus('brandvariables');
			return View::make('admin.brandvariables.list',$data);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'brandvariables','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            
            ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/brands.js');
        ZnUtilities::push_js_files('jquery.contenthover.min.js');
                            
        ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
        $editor_js = '$(function() {
                       // $("#content").wysihtml5();
                       CKEDITOR.replace("brand_desc");
                       });';
        ZnUtilities::push_js($editor_js);
            
		$data = array();
                
                
                
                $basicPageVariables = ZnUtilities::basicPageVariables("BrandVariables"," Add New Brand Variable", "brandvariables","1");
                $data = array_merge($data,$basicPageVariables);
                
                $data['breadcrumbs']['All Brand Variables'] = array("link"=>'/brandvariables',"active"=>'0');
                $data['breadcrumbs']['New Brand Variable'] = array("link"=>"","active"=>'1'); 
            
                  $data['submenus'] = $this->_submenus('brandvariables');
               return View::make('admin.brandvariables.create',$data);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($brand_id = '0')
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'brandvariables','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
    
            $validator = Validator::make(
                array(
                    'variable_name' => Input::get('variable_name'),
                   ),
                array(
                    'variable_name' => 'required',
                  )
            );
            
            if($validator->passes())
            {
               
                $brandvariables = new BrandVariable();
                $brandvariables->variable_name = Input::get('variable_name');
                $brandvariables->save();
                
                if($brand_id >= 1){
                    return Redirect::to('brands/settings/'.$brand_id)->with('success_message', 'Brand Variable Create successfully!');
                    
                }
                
                return Redirect::to('brandvariables')->with('success_message', 'Brand Variable created successfully!');
            }
            else
            {
               
                return Redirect::to('brandvariables/create')->withErrors($validator)->withInput();;
            }
            
	}

        public function edit($id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'brandvariables','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            
               ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/brands.js');
        ZnUtilities::push_js_files('jquery.contenthover.min.js');
        ZnUtilities::push_js_files('pekeUpload.js');
            

                     $data = array();

                //Throw exception if project id does not exists
                $data['brand_variables'] = BrandVariable::findOrFail($id);
               
                
                $basicPageVariables = ZnUtilities::basicPageVariables("BrandVariables","Edit Brand Variable", "brandvariables","1");
                $data = array_merge($data,$basicPageVariables);
                
                 $data['breadcrumbs']['All Brand Variables'] = array("link"=>'/brandvariables',"active"=>'0');
                 $data['breadcrumbs']['Edit Brand Variable'] = array("link"=>"","active"=>'1'); 
               
                   $data['submenus'] = $this->_submenus('brandvariables');
                return View::make('admin.brandvariables.edit',$data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
            if(!User::checkPermission(Auth::user()->user_group_id,'brandvariables','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            //skip email's unique validation if email is not changed
         
          
            $validator = Validator::make(
                array(
                    'variable_name' => Input::get('variable_name'),
                   ),
                array(
                    'variable_name' => 'required',
                  )
            );
            
            if($validator->passes())
            {
               
                $brandvariables = BrandVariable::find($id);
                $brandvariables->variable_name = Input::get('variable_name');
                $brandvariables->save();
                
            
              
                return Redirect::to('brandvariables')->with('success_message', 'Brand Variable Updated Successfully');
            }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('brandvariables/'.$id.'/edit/')->withErrors($validator)->withInput();
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
             if(!User::checkPermission(Auth::user()->user_group_id,'brandvariables','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            // delete
		$brands = BrandVariable::find($id);
		$brands->delete();

		// redirect
		return Redirect::to('brandvariables')->with('success_message', 'Brand Variable deleted successfully!');
	}

        
      
        
       
       public function brandVariableSearch($search)
        {
            if(!User::checkPermission(Auth::user()->user_group_id,'brandvariables','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
           
               
               $brands = BrandVariable::where("variable_name","like","%".$search."%")
                       
                           
                            ->paginate(); 
               
               $data = array();
               $data['brand_variables'] = $brands;
                //Basic Page Settings
               
                $basicPageVariables = ZnUtilities::basicPageVariables("BrandVariables","Search Results", "brandvariables","1");
                $data = array_merge($data,$basicPageVariables);
               
                $data['breadcrumbs']['All Brand Variables'] = array("link"=>'/brandvariables',"active"=>'0');
               $data['breadcrumbs']['Search'] = array("link"=>"","active"=>'1'); 
               
                $data['search'] = $search;
                
                  $data['submenus'] = $this->_submenus('brandvariables');
                return View::make('admin.brandvariables.list',$data);
               
           
          
        }
        
        public function brandVariableActions()
        {
             if(!User::checkPermission(Auth::user()->user_group_id,'brandvariables','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $search = Input::get('search');
            if($search!='')
            {
                return Redirect::to('/brandvariableSearch/'.$search);
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
                            DB::table('brand_variables')
                                ->where('brand_variable_id', $id)
                                ->delete();
                        }
                        
                        return Redirect::to('/brandvariables/')->with('success_message', 'Rows Delete!');
                        break;
                    }
                } //end switch
            } // end if statement
            return Redirect::to('/brandvariables');
            }
        }

        
       
        
   
    
      private function _submenus($active)
    {   
        $data = array();
        
     
        
        
        if(User::checkPermission(Auth::user()->user_group_id,'brandvariables','manage'))
        $data['Brand Variables'] = array("link" => '/brandvariables', "active" => $active=='brandvariables'?'1':'0' ,"icon" => 'fa-list');
        
        
        return $data;
    }
}
