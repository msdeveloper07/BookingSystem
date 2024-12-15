<?php

class SuppliersController extends BaseController {

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
        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $data = array();

        
        $data['supplier'] = Supplier::with('supptype', 'suppitem', 'loc','suppSubType','suppTypeName','suppAssociation')->orderBy('supplier_id','desc')->paginate(10);
       
        $basicPageVariables = ZnUtilities::basicPageVariables("Supplier", " All Supplier", "supplier", "1");
        $data = array_merge($data, $basicPageVariables);


        $data['breadcrumbs']['All Supplier'] = array("link" => '/supplier', "active" => '0');

        ZnUtilities::push_js_files('components/suppliers.js');

        //$data['active_nav'] = 'users';
       
        $data['submenus'] = $this->_submenus('suppliers');        return View::make('admin.suppliers.list', $data);
    }

   

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $data = array();
        $data['supplier'] = Supplier::get();
        ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/suppliers.js');

        $basicPageVariables = ZnUtilities::basicPageVariables("Supplier", "Add New Supplier", "add_supplier", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Supplier'] = array("link" => '/supplier', "active" => '0');
        $data['breadcrumbs']['Add New Supplier'] = array("link" => "", "active" => '1');

        //$data['submenus'] = $this->_submenus('suppliers');        return View::make('admin.supplier.create',$data);
        $data['submenus'] = $this->_submenus('suppliers');        return View::make('admin.suppliers.general', $data);
    }

    public function Contacts($suppid) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
            return Redirect::to('/permissionDenied');
        }
        $data = array();

        $data['supplier'] = Supplier::find($suppid);
        $data['address'] = Address::find($data['supplier']->address_id);


        ZnUtilities::push_js_files("jquery.validate.min.js");

        ZnUtilities::push_js_files("chosen.jquery.min.js");

        ZnUtilities::push_js_files('components/suppliers.js');

        $basicPageVariables = ZnUtilities::basicPageVariables("Supplier", "Add Contacts", "add_supplier", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Supplier'] = array("link" => '/supplier', "active" => '0');
        $data['breadcrumbs']['Add Contacts'] = array("link" => "", "active" => '1');

        //$data['submenus'] = $this->_submenus('suppliers');        return View::make('admin.supplier.create',$data);
        $data['submenus'] = $this->_submenus('suppliers');        return View::make('admin.suppliers.contact', $data);
    }

    public function contactStore($suppid) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $validator = Validator::make(
                        array(
                    
                    'address' => Input::get('address'),
                    'city' => Input::get('city'),
                    'telephone' => Input::get('telephone'),
                    'fax' => Input::get('fax'),
                        ), array(
                    
                    'address' => 'required',
                    'city' => 'required',
                    'telephone' => 'required',
                    'fax' => 'required',
                        )
        );
        if ($validator->passes()) {

            $address = new Address();
            $address->address = Input::get('address');
            $address->city = Input::get('city');
            $address->state = Input::get('state');
            $address->country = Input::get('country');
            $address->telephone = Input::get('telephone');
            $address->fax = Input::get('fax');
           
            $address->official_email = Input::get('official_email');
            $address->save();

            $supplier = Supplier::find($suppid);
            $supplier->address_id = $address->address_id;
            $supplier->save();

            //return Redirect::to('/supplier/lcontacts/'.$supplier->supplier_id)->with('success_message', 'Supplier Create successfully!');
            return Redirect::to('/payment_method/' . $suppid)->with('success_message', 'Supplier Contact Create successfully!');
        } else {
            //$messages = $validator->messages();
            return Redirect::to('/supplier/contacts/' . $suppid)->withErrors($validator)->withInput();
            ;
        }
    }

   
    public function supplierStore() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
            return Redirect::to('/permissionDenied');
        }
$supplier_type = Input::get('supplier_type_id');
 

        $validator = Validator::make(
                        array(
                    'supplier_name' => Input::get('supplier_name'),
                    
                        ), array(
                    'supplier_name' => 'required',
                    
                        )
        );
        if ($validator->passes()) {
            $supplier = new Supplier();
            $supplier->supplier_name = Input::get('supplier_name');
             $supplier->save();
             
             foreach($supplier_type as $st){
             $suppliers = new SupplierTypeAssociation();
             $suppliers->supplier_id=$supplier->supplier_id;
             $suppliers->supplier_type_id = $st;
             $suppliers->save();
             }
            
            return Redirect::to('/supplier/contacts/' . $supplier->supplier_id)->with('success_message', 'Supplier Create successfully!');
        } else {

            return Redirect::to('/supplier/create')->withErrors($validator)->withInput();
        }
    }

    public function supplierItems($suppid) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        //ZnUtilities::pa($suppid); die();


        $data = array();

        $supplier = Supplier::find($suppid);
        $data['supplier'] = $supplier;
        $data['supplierTypeItems'] = SupplierTypeItem::where('supplier_type_id', $supplier->supplier_type_id)->get();
        $data['supplierItems'] = SupplierItem::where('supplier_id', $suppid)->get();
        
        
        
        ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/supplier.js');

        $basicPageVariables = ZnUtilities::basicPageVariables("Supplier", "Supplier Item", "add_supplier", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Supplier'] = array("link" => '/supplier', "active" => '0');
        $data['breadcrumbs']['Add New Supplier Item'] = array("link" => "", "active" => '1');

        //$data['submenus'] = $this->_submenus('suppliers');        return View::make('admin.supplier.create',$data);
        $data['submenus'] = $this->_submenus('suppliers');        return View::make('admin.suppliers.item', $data);
    }

    public function supplierItemsStore($suppid) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        //$supplier = Supplier::find($suppid);
        $validator = Validator::make(
                        array(
                    'supplier_item' => Input::get('supplier_item'),
                    'supplier_cost' => Input::get('cost'),
                        ), array(
                    'supplier_item' => 'required',
                    'supplier_cost' => 'required',
                        //'supplier_item' => 'required',
                        )
        );
        if ($validator->passes()) {
            SupplierItem::where('supplier_id', $suppid)->delete();
            
            $supplier = Supplier::find($suppid);

            $supplier_item = Input::get('supplier_item');
            $cost = Input::get('cost');
            $new_item = Input::get('supplier_new_item');
            $extra_notes = Input::get('extra_notes');


            foreach ($supplier_item as $k => $v) {

                $SupplierItem = new SupplierItem();
                if ($new_item[$k]) {
                    $new = new SupplierTypeItem();
                    $new->supplier_item_name = $new_item[$k];
                    $new->supplier_type_id = $supplier->supplier_type_id;
                    $new->save();


                    $new_item_id = $new->supplier_type_item_id;

                    $SupplierItem->supplier_id = $suppid;
                    $SupplierItem->supplier_type_id = $supplier->supplier_type_id;
                    $SupplierItem->supplier_type_item_id = $new_item_id;
                    $SupplierItem->cost = $cost[$k];
                    $SupplierItem->extra_notes = $extra_notes[$k];
                    $SupplierItem->save();
                } else {
                    $SupplierItem->supplier_id = $suppid;
                    $SupplierItem->supplier_type_id = $supplier->supplier_type_id;
                    $SupplierItem->supplier_type_item_id = $supplier_item[$k];
                    $SupplierItem->cost = $cost[$k];
                    $SupplierItem->extra_notes = $extra_notes[$k];
                    $SupplierItem->save();
                }
            }




            return Redirect::to('/payment_method/' . $suppid)->with('success_message', 'Supplier Item Create successfully!');
        } else {
            //$messages = $validator->messages();
            return Redirect::to('/supplier/items/' . $suppid)->withErrors($validator)->withInput();
        }
    }

    public function Payment_Method($supplid) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $data = array();
        $data['supplier'] = Supplier::find($supplid);

        ZnUtilities::push_js_files("jquery.validate.min.js");

        ZnUtilities::push_js_files("chosen.jquery.min.js");

        ZnUtilities::push_js_files('components/suppliers.js');

        $basicPageVariables = ZnUtilities::basicPageVariables("Supplier", "Payment Method", "add_supplier", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Supplier'] = array("link" => '/supplier', "active" => '0');
        $data['breadcrumbs']['Payment Method'] = array("link" => "", "active" => '1');

        //$data['submenus'] = $this->_submenus('suppliers');        return View::make('admin.supplier.create',$data);
        $data['submenus'] = $this->_submenus('suppliers');        return View::make('admin.suppliers.payment_method', $data);
    }

    public function Payment_Method_Store($supplid) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        //ZnUtilities::pa($supplid); die();
        $supplier = Supplier::find($supplid);
        $validator = Validator::make(
                        array(
                        // 'supplier' => Input::get('supplier_name'),
                        ), array(
                        //'supplier' => 'required',
                        )
        );
        if ($validator->passes()) {




            $supplier = new Supplier();

            //$supplier->supplier_name = Input::get('supplier_name');
            //$supplier->supplier_location_id = Input::get('location_id');
            //$supplier->save();
            //ZnUtilities::pa($supplier->supplier_id); die();

            return Redirect::to('/Commission/' . $supplid)->with('success_message', 'Payment  Create successfully!');
        } else {

            return Redirect::to('/payment_method/' . $supplier->supplier_id)->withErrors($validator)->withInput();
        }
    }

    public function Commission($supplid) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        //ZnUtilites::pa($supplid); die();
        $data = array();
        $data['supplier'] = Supplier::find($supplid);




        ZnUtilities::push_js_files("jquery.validate.min.js");

        ZnUtilities::push_js_files("chosen.jquery.min.js");

        ZnUtilities::push_js_files('components/suppliers.js');

        $basicPageVariables = ZnUtilities::basicPageVariables("Supplier", "Commission", "commissions", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Supplier'] = array("link" => '/supplier', "active" => '0');
        $data['breadcrumbs']['Commission'] = array("link" => "", "active" => '1');

        //$data['submenus'] = $this->_submenus('suppliers');        return View::make('admin.supplier.create',$data);
        $data['submenus'] = $this->_submenus('suppliers');        return View::make('admin.suppliers.commission', $data);
    }

    public function Commission_Store($supplid) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $supplier = Supplier::find($supplid);

        $validator = Validator::make(
                        array(
                    'payment_method' => Input::get('paypal'),
                    'commission' => Input::get('citem'),
                        ), array(
                    'payment_method' => 'required',
                    'commission' => 'required',
                        )
        );
        if ($validator->passes()) {




            $supplier = new Commission();
            if (input::get('paypal') == 1) {
                $supplier->payment_method = "paypal";
            } else {
                $supplier->payment_method = "credit_card";
            }
            $supplier->supplier_id = $supplid;
            //$supplier->payment_method = Input::get('location_id');
            $supplier->commision_of_item = Input::get('citem');


            $supplier->save();


            //return Redirect::to('/supplier/lcontacts/'.$supplier->supplier_id)->with('success_message', 'Supplier Create successfully!');
            return Redirect::to('/supplier')->with('success_message', 'Payment  Create successfully!');
        } else {

            return Redirect::to('/Commission/' . $supplier->supplier_id)->withErrors($validator)->withInput();
        }
    }

    public function Show_Commission() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $data = array();

        $data['supplier'] = Commission::with('supplierinfo')->paginate('20');


        $basicPageVariables = ZnUtilities::basicPageVariables("Supplier", " All Supplier Commission Details", "commissions", "1");
        $data = array_merge($data, $basicPageVariables);


        $data['breadcrumbs']['All Supplier Commission Details'] = array("link" => '/supplier', "active" => '0');

        ZnUtilities::push_js_files('components/suppliers.js');

        //$data['active_nav'] = 'users';

        $data['submenus'] = $this->_submenus('suppliers');        return View::make('admin.suppliers.showcommission', $data);
    }

    /**

      /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
            return Redirect::to('/permissionDenied');
        }
      

        $validator = Validator::make(
                        array(
                    'supplier' => Input::get('supplier_type'),
                        ), array(
                    'supplier' => 'required',
                        )
        );
        if ($validator->passes()) {



            $supplier_itemid = Supplier::where('supplier_item_id', Input::get('supplier_item'))->pluck('supplier_id');

            if ($supplier_itemid) {
                $supplier = Supplier::find($supplier_itemid);
            } else {
                $supplier = new Supplier();
            }
            $supplier->supplier_type_id = Input::get('supplier_type');
            $supplier->supplier_cost = Input::get('cost');
            $supplier->supplier_item_id = Input::get('supplier_item');


            $supplier->save();


            return Redirect::to('supplier')->with('success_message', 'Supplier Create successfully!');
        } else {
            //$messages = $validator->messages();
            return Redirect::to('supplier/create')->withErrors($validator)->withInput();
            ;
        }
    }

    public function edit($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $data = array();


        $data['supplier'] = Supplier::findOrFail($id);
        $data['supplier_type_association'] = SupplierTypeAssociation::where('supplier_id',$id)->lists('supplier_type_id');
      


        // Load Component JS
        ZnUtilities::push_js_files("jquery.validate.min.js");

        ZnUtilities::push_js_files("chosen.jquery.min.js");

        ZnUtilities::push_js_files('components/suppliers.js');

        $basicPageVariables = ZnUtilities::basicPageVariables("Supplier", "Edit Supplier", "supplier", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Supplier'] = array("link" => '/users', "active" => '0');
        $data['breadcrumbs']['Edit Supplier'] = array("link" => "", "active" => '1');

        $data['submenus'] = $this->_submenus('suppliers');        
        return View::make('admin.suppliers.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        //skip email's unique validation if email is not changed
        
        
        
        $supplier_type=Input::get('supplier_type_id');
        $supplier = Supplier::find($id);

        $validator = Validator::make(
                        array(
                    'supplier_name' => Input::get('supplier_name'),
                   
                        ), array(
                    'supplier_name' => 'required',
                    
                        )
        );
        if ($validator->passes()) {
     
            $supplier->supplier_name = Input::get('supplier_name');
             $supplier->save();
              DB::table('supplier_type_associations')
                                        ->where('supplier_id', $id)
                                        ->delete();
             foreach($supplier_type as $st){
             $suppliers = new SupplierTypeAssociation();
             $suppliers->supplier_id=$supplier->supplier_id;
             $suppliers->supplier_type_id = $st;
             $suppliers->save();
             }


            return Redirect::to('/supplier/'.$supplier->supplier_id.'/edit/')->with('success_message', 'Supplier Create successfully!');
        } else {

            return Redirect::to('/supplier/'.$supplier->supplier_id.'/edit/')->withErrors($validator)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
  

  

  

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        // delete
        $supplier = Supplier::find($id);
        $supplier->delete();

        // redirect
        return Redirect::to('supplier')->with('success_message', 'User deleted successfully!');
    }

    public function supplierSearch($search) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
            return Redirect::to('/permissionDenied');
        }



        $keyword = $search;
        $data['keyword'] = $keyword;


        $tpc = DB::table('suppliers as s');
        $tpc->leftJoin('locations as l', 'l.location_id', '=', 's.supplier_location_id');

        if ($keyword != '') {
            $keyword = trim($keyword);
            $tpc->orWhere(function ($tpc) use ($keyword) {


                $tpc->where("s.supplier_name", "like", "%" . $keyword . "%")
                        ->orwhere("s.supplier_email", "like", "%" . $keyword . "%")
                        ->orwhere("s.supplier_phone", "like", "%" . $keyword . "%")
                        ->orwhere("l.location_name", "like", "%" . $keyword . "%");
            });
        }



        $data = array();
        $data['supplier'] = $tpc->paginate();
        //Basic Page Settings

        $basicPageVariables = ZnUtilities::basicPageVariables("Supplier", "Search results", "supplier", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Supplier'] = array("link" => '/supplier', "active" => '0');
        $data['breadcrumbs']['Search'] = array("link" => "", "active" => '1');

        $data['search'] = $search;

        $data['submenus'] = $this->_submenus('suppliers');        return View::make('admin.suppliers.search_result', $data);
    }

    public function supplierActions() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $search = Input::get('search');
        if ($search != '') {
            return Redirect::to('/supplierSearch/' . $search);
        } else {


            //die(Input::get('bulk_action')   );

            $cid = Input::get('cid');
            $bulk_action = Input::get('bulk_action');
            if ($bulk_action != '') {
                switch ($bulk_action) {


                    case 'delete': {


                            foreach ($cid as $id) {
                                DB::table('suppliers')
                                        ->where('supplier_id', $id)
                                        ->delete();
                            }

                            return Redirect::to('/supplier/')->with('success_message', 'Rows Delete!');
                            break;
                        }
                } //end switch
            } // end if statement
            return Redirect::to('/supplier');
        }
    }
        
    private function _submenus($active)
    {   
        $data = array();
        if(User::checkPermission(Auth::user()->user_group_id,'suppliers','manage'))
            $data['Suppliers'] = array("link" => '/supplier', "active" => $active=='suppliers'?'1':'0' ,"icon" => 'fa-user');
        
        
        
        return $data;
    }

}
