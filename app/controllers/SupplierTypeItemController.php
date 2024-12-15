<?php

class SupplierTypeItemController extends BaseController {

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
            if(!User::checkPermission(Auth::user()->user_group_id,'supplier_type_item','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();
           
            $data['suppliertypeitem'] = SupplierTypeItem::with('suptype','supSubType')->paginate(10);
          

            $basicPageVariables = ZnUtilities::basicPageVariables("Supplier"," All Supplier Type Item", "suppliertypeitem","1");
            $data = array_merge($data,$basicPageVariables);
            
            
            $data['breadcrumbs']['All Supplier Type Item'] = array("link"=>'/suppliertypeitem',"active"=>'0');
           
            ZnUtilities::push_js_files('components/users.js');
            
             //$data['active_nav'] = 'users';
            $data['submenus'] = $this->_submenus('suppliertypeitem');    
			return View::make('admin.suppliertypeitem.list',$data);
	}

      
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'supplier_type_item','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
		$data = array();
                
               $data['suppliertype'] = SupplierType::where('supplier_type_parent_id','0')->get();
                
                
                  ZnUtilities::push_js_files("jquery.validate.min.js");
                  ZnUtilities::push_js_files("bootstrap-tagsinput.min.js");
                // ZnUtilities::push_js_files("admin_components/ugpermission.js");
                  ZnUtilities::push_js_files("chosen.jquery.min.js");
                
                  ZnUtilities::push_js_files('components/suppliertypeitem.js');
                
                $basicPageVariables = ZnUtilities::basicPageVariables("Supplier Type Item","Add New Supplier Type Item", "add_suppliertypeitem","1");
                $data = array_merge($data,$basicPageVariables);
                
                $data['breadcrumbs']['ALL Supplier Type Item'] = array("link"=>'/suppliertypeitem',"active"=>'0');
                $data['breadcrumbs']['Add New Supplier Type Item'] = array("link"=>"","active"=>'1'); 
            
              $data['submenus'] = $this->_submenus('suppliertypeitem');         return View::make('admin.suppliertypeitem.create',$data);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'supplier_type_item','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
         
          
            $validator = Validator::make(
                array(
                    'supplier_type_id' => Input::get('supplier_type_id'),
                   
                    
                    ),
                array(
                   'supplier_type_id' => 'required',
                    
                    )
            );
            if ($validator->passes()) {

            $suppid = Input::get('supplier_type_id');
            $supplier_item = Input::get('supplier_item_name');
            $supp_sub_type = Input::get('supplier_sub_type_id');
            $value = Input::get('keywords');
            $field_type = Input::get('field_type');
           
            foreach ($suppid as $k => $v) {

                foreach ($value as $K => $V) {
                    if ($k == $K) {
                        $new = new SupplierTypeItem();
                        
                        $new->supplier_item_name = ($supplier_item[$k]!='')?$supplier_item[$k]:'';
                        if($supp_sub_type[$k]==''){
                        $new->supplier_type_id = $v;
                        }
                        else{
                        $new->supplier_type_id =$supp_sub_type[$k];
                         }
                        $new->value=$V;
                        $new->field_type=$field_type[$K];
                        $new->save();
                    }
                    
                }
            }
            return Redirect::to('suppliertypeitem')->with('success_message', 'Supplier Item Create successfully!');
        }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('suppliertypeitem/create')->withErrors($validator)->withInput();;
            }
            
 }
         

        public function edit($id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'supplier_type_item','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            		$data = array();

                //Throw exception if project id does not exists
                $suppliertypeitem = SupplierTypeItem::find($id);
                
             //  ZnUtilities::pa($suppliertypeitem);  die();
                
                $data['suppliertypeitem'] = $suppliertypeitem;
                
//                ZnUtilities::pa($suppliertypeitem->supplier_type_id);  die();
                
                $data['suppliertype'] = SupplierType::where('supplier_type_parent_id','0')->get(); 
                $data['suppliersubtype'] = SupplierType::where('supplier_type_parent_id',$suppliertypeitem->supplier_type_parent_id)->get(); 
		
                ZnUtilities::push_js_files("bootstrap-tagsinput.min.js");
              
                // Load Component JS
                 ZnUtilities::push_js_files("jquery.validate.min.js");
                 ZnUtilities::push_js_files('components/suppliertypeitem.js');
                

                
                $js = "$('.delete_form').submit(function() {
                        var c = confirm('Are you sure you want to delete this user?');
                        return c; 
                    });";
                 ZnUtilities::push_js($js);
                
                $basicPageVariables = ZnUtilities::basicPageVariables("Supplier Item","Edit Supplier Item", "supplieritem","1");
                $data = array_merge($data,$basicPageVariables);
                
                 $data['breadcrumbs']['All Supplier Type Item'] = array("link"=>'/suppliertypeitem',"active"=>'0');
                 $data['breadcrumbs']['Edit Supplier Type Item'] = array("link"=>"","active"=>'1'); 
               
               $data['submenus'] = $this->_submenus('suppliertypeitem');         return View::make('admin.suppliertypeitem.edit',$data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	 public function update($id)
                
 {
            if(!User::checkPermission(Auth::user()->user_group_id,'supplier_type_item','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            //skip email's unique validation if email is not changed
            $new = SupplierTypeItem::find($id);
            
            
              
            $validator = Validator::make(
                array(
                    'supplier_type_id' => Input::get('supplier_type_id'),
                    ),
                array(
                   'supplier_type_id' => 'required',
                    
                    )
            );
            
            if ($validator->passes()) {
                
            $suppid = Input::get('supplier_type_id');
            $supplier_item = Input::get('supplier_item_name');
            $supp_sub_type = Input::get('supplier_sub_type_id');
            $value = Input::get('keywords');
            $field_type = Input::get('field_type');
           
            foreach ($suppid as $k => $v) {

                foreach ($value as $K => $V) {
                    if ($k == $K) {
                       
                        
                        $new->supplier_item_name = ($supplier_item[$k]!='')?$supplier_item[$k]:'';
                        if($supp_sub_type[$k]==''){
                        $new->supplier_type_id = $v;
                        }
                        else{
                        $new->supplier_type_id =$supp_sub_type[$k];
                         }
                        $new->value=$V;
                        $new->field_type=$field_type[$K];
                        $new->save();
                    }
                    
                }
            }
            return Redirect::to('suppliertypeitem')->with('success_message', 'Supplier Item Update successfully!');
        }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('suppliertypeitem/'.$id.'/edit/')->withErrors($validator)->withInput();
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
             if(!User::checkPermission(Auth::user()->user_group_id,'supplier_type_item','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            // delete
		$user = SupplierTypeItem::find($id);
		$user->delete();

		// redirect
		return Redirect::to('suppliertypeitem')->with('success_message', 'Supplier Item deleted successfully!');
	}

        
      
        
     
       public function supplieritemSearch($search)
        {
            if(!User::checkPermission(Auth::user()->user_group_id,'supplier_type_item','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $keyword = $search;
                $data['keyword'] = $keyword; 
                

                $tpc  = DB::table('supplier_type_items as t');
                $tpc->leftJoin('supplier_type as c','c.supplier_type_id','=','t.supplier_type_id');
              
               
                
                if($keyword!=''){
                    $keyword = trim($keyword);
                    $tpc->orWhere(function ($tpc) use ($keyword) 
                        {
                        
                            
                            $tpc->where("t.supplier_item_name","like","%".$keyword."%")
                                    ->orwhere("c.supplier_type","like","%".$keyword."%");
                                   
                        });
                }

                
                $data['suppliertypeitem'] = $tpc->paginate(10);
               
//               
//               $users = SupplierTypeItem::where("supplier_item_name","like","%".$search."%")
//                            
//                            ->paginate(); 
//               
//               $data = array();
//               $data['suppliertypeitem'] = $users;
                //Basic Page Settings
               
                $basicPageVariables = ZnUtilities::basicPageVariables("Supplier Item","Search results", "supplieritem","1");
                $data = array_merge($data,$basicPageVariables);
               
                $data['breadcrumbs']['All Supplier Type Item'] = array("link"=>'/suppliertypeitem',"active"=>'0');
               $data['breadcrumbs']['Search'] = array("link"=>"","active"=>'1'); 
               
                $data['search'] = $search;

               $data['submenus'] = $this->_submenus('suppliertypeitem');         return View::make('admin.suppliertypeitem.search_result',$data);
               
           
          
        }
        
        public function supplieritemActions()
        {
             if(!User::checkPermission(Auth::user()->user_group_id,'supplier_type_item','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $search = Input::get('search');
            if($search!='')
            {
                return Redirect::to('/suppliertypeitemSearch/'.$search);
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
                            DB::table('supplier_type_items')
                                ->where('supplier_type_item_id', $id)
                                ->delete();
                        }
                        
                        return Redirect::to('/suppliertypeitem/')->with('success_message', 'Rows Delete!');
                        break;
                    }
                } //end switch
            } // end if statement
            return Redirect::to('/suppliertypeitem');
            }
        }
        
        public function addItem($supplier_type_sub_id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'supplier_type_item','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
		$data = array();
                
                $data['supplier_sub_type_id'] = $supplier_type_sub_id;
                
                $data['suppliertype'] = SupplierType::where('supplier_type_parent_id','0')->get();
                
                
                
                $data['supplier_type_id'] = SupplierType::where('supplier_type_id',$supplier_type_sub_id)->pluck('supplier_type_parent_id');
                
             
                
                  ZnUtilities::push_js_files("jquery.validate.min.js");
                  ZnUtilities::push_js_files("bootstrap-tagsinput.min.js");
                // ZnUtilities::push_js_files("admin_components/ugpermission.js");
                  ZnUtilities::push_js_files("chosen.jquery.min.js");
                
                  ZnUtilities::push_js_files('components/suppliertypeitem.js');
                
                $basicPageVariables = ZnUtilities::basicPageVariables("Supplier Type Item","Add New Supplier Type Item", "add_suppliertypeitem","1");
                $data = array_merge($data,$basicPageVariables);
                
                $data['breadcrumbs']['ALL Supplier Type Item'] = array("link"=>'/suppliertypeitem',"active"=>'0');
                $data['breadcrumbs']['Add New Supplier Type Item'] = array("link"=>"","active"=>'1'); 
            
              $data['submenus'] = $this->_submenus('suppliertypeitem');         return View::make('admin.suppliertypeitem.additems',$data);
	}
        
        public function saveItems()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'supplier_type_item','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
         
          
            $validator = Validator::make(
                array(
                    'supplier_type_id' => Input::get('supplier_type_id'),
                   
                    
                    ),
                array(
                   'supplier_type_id' => 'required',
                    
                    )
            );
            if ($validator->passes()) {

            $suppid = Input::get('supplier_type_id');
            $supplier_item = Input::get('supplier_item_name');
            $supp_sub_type = Input::get('supplier_sub_type_id');
            $value = Input::get('keywords');
            $field_type = Input::get('field_type');
           
            foreach ($supplier_item as $k => $v) {

                foreach ($value as $K => $V) {
                    if ($k == $K) {
                        $new = new SupplierTypeItem();
                        
                        $new->supplier_item_name = ($supplier_item[$k]!='')?$supplier_item[$k]:'';
                        $new->supplier_type_id = $supp_sub_type;
                        $new->value=$V;
                        $new->field_type=$field_type[$K];
                        $new->save();
                    }
                    
                }
            }
            return Redirect::to('suppliertypeitem')->with('success_message', 'Supplier Item Create successfully!');
        }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('suppliertypeitem/create')->withErrors($validator)->withInput();;
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
