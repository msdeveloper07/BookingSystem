<?php

class PaymentTypesController extends BaseController {

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
            if(!User::checkPermission(Auth::user()->user_group_id,'paymenttype','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();

            $data['paymenttype'] = PaymentType::paginate(10);
           

            $basicPageVariables = ZnUtilities::basicPageVariables("PaymentTypes"," All Payment Types", "paymenttypes","1");
            $data = array_merge($data,$basicPageVariables);
            
            
            $data['breadcrumbs']['Payment Types'] = array("link"=>'/paymenttypes',"active"=>'0');
           
            ZnUtilities::push_js_files('components/paymenttypes.js');
            
            // $data['active_nav'] = 'paymenttype';
            
                 $data['submenus'] = $this->_submenus('index');
			return View::make('admin.paymenttypes.list',$data);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'paymenttype','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
		$data = array();
                
                
                  ZnUtilities::push_js_files("jquery.validate.min.js");
                 ZnUtilities::push_js_files("components/paymenttypes.js");
               
                
                $basicPageVariables = ZnUtilities::basicPageVariables("PaymentTypes","Add Payment Type", "add_paymenttypes","1");
                $data = array_merge($data,$basicPageVariables);
                
                $data['breadcrumbs']['All Payment Types'] = array("link"=>'/paymenttypes',"active"=>'0');
                $data['breadcrumbs']['New Payment Type'] = array("link"=>"","active"=>'1'); 
            
               $data['submenus'] = $this->_submenus('index');
               return View::make('admin.paymenttypes.create',$data);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'paymenttype','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
     
            
         
           
            $validator = Validator::make(
                array(
                    'payment_type' => Input::get('payment_type'),
                   
                    'payment_status' => Input::get('payment_status'),
                   
                    ),
                array(
                    'payment_type' => 'required',
                    'payment_status' => 'required',
                  
                    )
            );
            
            if($validator->passes())
            {
               
                 
                
                
                $paymenttype = new PaymentType();
                $paymenttype->payment_type = Input::get('payment_type');
                $paymenttype->payment_status = Input::get('payment_status');
              
                $paymenttype->save();
                
             
                return Redirect::to('paymenttypes')->with('success_message', 'Payment Type created successfully!');
            }
            else
            {
               
                return Redirect::to('paymenttypes/create')->withErrors($validator)->withInput();;
            }
            
	}

        public function edit($id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'paymenttype','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            		$data = array();

                //Throw exception if project id does not exists
                $data['paymenttype'] = PaymentType::findOrFail($id);
          
                 ZnUtilities::push_js_files("jquery.validate.min.js");
                 ZnUtilities::push_js_files("components/paymenttypes.js");
                

         
                $basicPageVariables = ZnUtilities::basicPageVariables("PaymentTypes","Edit Payment Type", "paymenttypes","1");
                $data = array_merge($data,$basicPageVariables);
                
                 $data['breadcrumbs']['All Payment Types'] = array("link"=>'/paymenttypes',"active"=>'0');
                 $data['breadcrumbs']['Edit Payment Type'] = array("link"=>"","active"=>'1'); 
               
                $data['submenus'] = $this->_submenus('index');
                return View::make('admin.paymenttypes.edit',$data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
            if(!User::checkPermission(Auth::user()->user_group_id,'paymenttype','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            //skip email's unique validation if email is not changed
            $paymenttype = PaymentType::find($id);
           
                    $validator = Validator::make(
                array(
                    'payment_type' => Input::get('payment_type'),
                    'payment_status' => Input::get('payment_status'),
                   
                    ),
                array(
                    'payment_type' => 'required',
                    'payment_status' => 'required',
                  
                    )
            );
            
            if($validator->passes())
            {
               
                 
                
                
               
                $paymenttype->payment_type = Input::get('payment_type');
                $paymenttype->payment_status = Input::get('payment_status');
              
                $paymenttype->save();
                
            
                return Redirect::to('paymenttypes')->with('success_message', 'Payment Type Updated Successfully');
            }
            else
            {
                
                return Redirect::to('paymenttypes/'.$id.'/edit/')->withErrors($validator)->withInput();
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
             if(!User::checkPermission(Auth::user()->user_group_id,'paymenttype','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
         
		$paymenttype = PaymentType::find($id);
                
		$paymenttype->delete();

		// redirect
		return Redirect::to('paymenttypes')->with('success_message', 'Payment Type deleted successfully!');
	}

        
      
       public function paymentTypeSearch($search)
        {
            if(!User::checkPermission(Auth::user()->user_group_id,'paymenttype','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
           
               
               $paymenttype = PaymentType::where("payment_type","like","%".$search."%")
                                       ->orWhere("payment_status","like","%".$search."%")
                       ->paginate(); 
               
               $data = array();
               $data['paymenttype'] = $paymenttype;
                //Basic Page Settings
               
                $basicPageVariables = ZnUtilities::basicPageVariables("PaymentTypes","Search results", "paymenttypes","1");
                $data = array_merge($data,$basicPageVariables);
               
                $data['breadcrumbs']['All Payment Types'] = array("link"=>'/paymenttypes',"active"=>'0');
               $data['breadcrumbs']['Search'] = array("link"=>"","active"=>'1'); 
               
                $data['search'] = $search;
                
                 $data['submenus'] = $this->_submenus('index');
                return View::make('admin.paymenttypes.list',$data);
               
           
          
        }
        
        public function paymentTypeActions()
        {
             if(!User::checkPermission(Auth::user()->user_group_id,'paymenttype','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $search = Input::get('search');
            if($search!='')
            {
                return Redirect::to('/paymenttypeSearch/'.$search);
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
                            DB::table('payment_types')
                                ->where('payment_type_id', $id)
                                ->delete();
                        }
                        
                        return Redirect::to('/paymenttypes/')->with('success_message', 'Rows Delete!');
                        break;
                    }
                } //end switch
            } // end if statement
            return Redirect::to('/paymenttypes');
            }
        }
        
           private function _submenus($active)
    {   
        $data = array();
        $data['Payment Types'] = array("link" => '/paymenttypes', "active" => $active=='index'?'1':'0' ,"icon" => 'fa-user-plus');
        return $data;
    }


}
?>