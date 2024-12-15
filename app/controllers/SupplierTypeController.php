<?php

class SupplierTypeController extends BaseController {

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
            if(!User::checkPermission(Auth::user()->user_group_id,'supplier_type','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();

            $data['supplier'] = SupplierType::where('supplier_type_parent_id','0')->paginate('20');
           

            $basicPageVariables = ZnUtilities::basicPageVariables("SupplierType"," All Supplier Type", "suppliertype","1");
            $data = array_merge($data,$basicPageVariables);
            
            
            $data['breadcrumbs']['All Supplier Type'] = array("link"=>'/suppliertype',"active"=>'0');
           
            ZnUtilities::push_js_files('components/users.js');
            
             //$data['active_nav'] = 'users';
            $data['submenus'] = $this->_submenus('suppliertype');   
			return View::make('admin.suppliertype.list',$data);
	}

      
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'supplier_type','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
		$data = array();
                 $data['supplier'] = SupplierType::where('supplier_type_parent_id','0')->get();
                 
                 
               // $data['userGroups'] = Usergroup::all();
                
                $basicPageVariables = ZnUtilities::basicPageVariables("SupplierType","Add New Supplier Type", "add_suppliertype","1");
                $data = array_merge($data,$basicPageVariables);
                
                $data['breadcrumbs']['All Supplier Type'] = array("link"=>'/suppliertype',"active"=>'0');
                $data['breadcrumbs']['Add New Supplier Type'] = array("link"=>"","active"=>'1'); 
            
              $data['submenus'] = $this->_submenus('suppliertype');         return View::make('admin.suppliertype.create',$data);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'supplier_type','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
          
            $validator = Validator::make(
                array(
                    'supplier' => Input::get('supplier_type'),
                    
                    ),
                array(
                    'supplier' => 'required',
                    
                    )
            );
          if($validator->passes())
            {
             
                $supplier_type = new SupplierType();
                $supplier_type->supplier_type = Input::get('supplier_type');
                  $supplier_type->supplier_type_parent_id = Input::get('sub_type');
                
               
                $supplier_type->save();
                
                
             
            
                          return Redirect::to('suppliertype')->with('success_message', 'Supplier Type Create successfully!');
            }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('suppliertype/create')->withErrors($validator)->withInput();;
            }
            
 }
         

        public function edit($id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'supplier_type','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            		$data = array();

                //Throw exception if project id does not exists
                $data['suppliertype'] = SupplierType::findOrFail($id);
               // $data['userGroups'] = Usergroup::all();
                
                $data['suppliers']  = SupplierType::where('supplier_type_parent_id','0')->get();
		
                $data['supplier_type_parent_id'] = SupplierType::where('supplier_type_id',$id)->pluck('supplier_type_parent_id');
                
                //ZnUtilities::pa($data['parent_id']);  die();
              
                // Load Component JS
                 ZnUtilities::push_js_files("jquery.validate.min.js");
                 ZnUtilities::push_js_files("admin_components/users.js");
                

                
                $js = "$('.delete_form').submit(function() {
                        var c = confirm('Are you sure you want to delete this user?');
                        return c; 
                    });";
                 ZnUtilities::push_js($js);
                
                $basicPageVariables = ZnUtilities::basicPageVariables("SupplierType","Edit Supplier Type", "suppliertype","1");
                $data = array_merge($data,$basicPageVariables);
                
                 $data['breadcrumbs']['All Supplier Type'] = array("link"=>'/users',"active"=>'0');
                 $data['breadcrumbs']['Edit Supplier Type'] = array("link"=>"","active"=>'1'); 
               
               $data['submenus'] = $this->_submenus('suppliertype');         return View::make('admin.suppliertype.edit',$data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
            if(!User::checkPermission(Auth::user()->user_group_id,'supplier_type','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
           // ZnUtilities::pa($_POST);die();
            //skip email's unique validation if email is not changed
            $supplier_type = SupplierType::find($id);
           $validator = Validator::make(
                array(
                    'supplier_type' => Input::get('supplier_type'),
                    
                    ),
                array(
                    'supplier_type' => 'required',
                    
                    )
            );
          if($validator->passes())
            {
             
               
                $supplier_type->supplier_type = Input::get('supplier_type');
                 $supplier_type->supplier_type_parent_id = Input::get('parent_supplier_type');
            
               
                $supplier_type->save();
                
                return Redirect::to('suppliertype')->with('success_message', 'Supplier Type Updated Successfully');
            }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('suppliertype/'.$id.'/edit/')->withErrors($validator)->withInput();
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
             if(!User::checkPermission(Auth::user()->user_group_id,'supplier_type','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            // delete
		$suppliertype = SupplierType::find($id);
		$suppliertype->delete();

		// redirect
		return Redirect::to('suppliertype')->with('success_message', 'User deleted successfully!');
	}

        
        public function viewSubTypes($id)
        {
            
               if(!User::checkPermission(Auth::user()->user_group_id,'supplier_type','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();

            $data['supplier'] = SupplierType::where('supplier_type_parent_id',$id)->paginate(20);
            
           $data['supplier_type'] = SupplierType::where('supplier_type_id',$id)->pluck('supplier_type');

            $basicPageVariables = ZnUtilities::basicPageVariables("SupplierType"," All Supplier Type", "suppliertype","1");
            $data = array_merge($data,$basicPageVariables);
            
            
            $data['breadcrumbs']['All Supplier Type'] = array("link"=>'/suppliertype',"active"=>'0');
           
            ZnUtilities::push_js_files('components/users.js');
            
             //$data['active_nav'] = 'users';
            $data['submenus'] = $this->_submenus('suppliertype');   
			return View::make('admin.suppliertype.viewsubtypes',$data);
            
        }




        public function suppliertypeSearch($search)
        {
            if(!User::checkPermission(Auth::user()->user_group_id,'supplier_type','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
           
               
               $suppliertype = SupplierType::where("supplier_type","like","%".$search."%")
                           
                            ->paginate(); 
               
               $data = array();
               $data['supplier'] = $suppliertype;
                //Basic Page Settings
               
                $basicPageVariables = ZnUtilities::basicPageVariables("SupplierType","Search results", "suppliertype","1");
                $data = array_merge($data,$basicPageVariables);
               
                $data['breadcrumbs']['All Supplier Type'] = array("link"=>'/suppliertype',"active"=>'0');
               $data['breadcrumbs']['Search'] = array("link"=>"","active"=>'1'); 
               
                $data['search'] = $search;

               $data['submenus'] = $this->_submenus('suppliertype');         return View::make('admin.suppliertype.list',$data);
               
           
          
        }
        
        public function suppliertypeActions()
        {
             if(!User::checkPermission(Auth::user()->user_group_id,'supplier_type','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $search = Input::get('search');
            if($search!='')
            {
                return Redirect::to('/suppliertypeSearch/'.$search);
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
                            DB::table('supplier_type')
                                ->where('supplier_type_id', $id)
                                ->delete();
                        }
                        
                        return Redirect::to('/suppliertype/')->with('success_message', 'Rows Delete!');
                        break;
                    }
                } //end switch
            } // end if statement
            return Redirect::to('/suppliertype');
            }
        }
 private function _submenus($active)
    {   
        $data = array();
        
        
        if(User::checkPermission(Auth::user()->user_group_id,'suppliers','manage'))
            $data['Supplier Types'] = array("link" => '/suppliertype', "active" => $active=='suppliertype'?'1':'0' ,"icon" => 'fa-list');
        
        if(User::checkPermission(Auth::user()->user_group_id,'suppliers','manage'))
            $data['Items'] = array("link" => '/suppliertypeitem', "active" => $active=='suppliertypeitem'?'1':'0' ,"icon" => 'fa-list');
        
        
        return $data;
    }
   
}
