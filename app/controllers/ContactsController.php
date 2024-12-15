<?php

use Illuminate\Support\Facades\Paginator;

class ContactsController extends BaseController {

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
        if (!User::checkPermission(Auth::user()->user_group_id, 'contacts', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $page = Input::get('page','1');
        
        $data = array();
        $url = 'http://leadsdev.bookings-gateway.com/api?page='.$page;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);

        $output = utf8_decode($output);

       
        $json_array = json_decode($output);

        curl_close($ch);

        $data['contacts'] = $json_array->data;
        


        $basicPageVariables = ZnUtilities::basicPageVariables("Contacts", " All contacts", "contacts", "1");
        $data = array_merge($data, $basicPageVariables);
        $data['breadcrumbs']['All Contact'] = array("link" => '/contacts', "active" => '1');

        $data['paginator'] = Paginator::make($json_array->data, $json_array->total, $json_array->per_page);
        

        ZnUtilities::push_js_files('jquery.validate.min.js');
        ZnUtilities::push_js_files('components/contacts.js');

        $js = "$(function() {
                    $('#actions_form').validate();
                });";
        ZnUtilities::push_js($js);

        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.contacts.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'contacts', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $data = array();
        
        $basicPageVariables = ZnUtilities::basicPageVariables("Contacts", "Create new contact", "add_contact", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Contact'] = array("link" => '/contacts', "active" => '0');
        $data['breadcrumbs']['Add'] = array("link" => "", "active" => '1');

        ZnUtilities::push_js_files('jquery.validate.min.js');
        ZnUtilities::push_js_files('components/contacts.js');

        $js = "$(function() {
                    $('#contact_form').validate();
                });";
        ZnUtilities::push_js($js);

        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.contacts.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($quote_id ='null') {
        if (!User::checkPermission(Auth::user()->user_group_id, 'contacts', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        
        
        $validator = Validator::make(
                        array(
                        'job_title' => Input::get('job_title'),
                        'contact_first_name' => Input::get('contact_first_name'),
                        'contact_last_name' => Input::get('contact_last_name'),
                        'contact_email' => Input::get('contact_email'),
                        ), array(
                        'job_title' => 'required',
                        'contact_first_name' => 'required',
                        'contact_last_name' => 'required',
                        'contact_email' => 'required',
                        )
        );

        if ($validator->passes()) {
            
           // ZnUtilities::pa($_POST);
            
            $url = 'http://leadsdev.bookings-gateway.com/api';
            $fields = array(
                'job_title' => urlencode(Input::get('job_title')),
                'contact_first_name' => urlencode(Input::get('contact_first_name')),
                'contact_last_name' => urlencode(Input::get('contact_last_name')),
                'contact_email' => urlencode(Input::get('contact_email')),
                'contact_phone' => urlencode(Input::get('contact_phone')),
                'contact_mobile' => urlencode(Input::get('contact_mobile')),
                'contact_fax' => urlencode(Input::get('contact_fax'))
            );
            
            $fields_string = '';
            foreach($fields as $key=>$value) 
            { 
                $fields_string .= $key.'='.$value.'&'; 
            }
            rtrim($fields_string, '&');
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch,CURLOPT_POST, count($fields));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
            
            $output = curl_exec($ch);
            
            if($quote_id == 'new')
            {
                return Redirect::to('quotes/create')->with('success_message', 'Contact created successfully!');
            }
            
            if($output)
                return Redirect::to('contacts/')->with('success_message', 'Contact created successfully!');
            else
                return Redirect::to('contacts/create')->with('error_message', 'There is problem in adding this contact');
            
        } else {
            //$messages = $validator->messages();
            return Redirect::to('contacts/create')->withErrors($validator)->withInput();
            ;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {

        if (!User::checkPermission(Auth::user()->user_group_id, 'contacts', 'manage')) {
            return Redirect::to('/permissionDenied');
        }
        $data = array();

         $url = 'http://leadsdev.bookings-gateway.com/api/'.$id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);

        $data['contact'] = json_decode($output);
//        ZnUtilities::pa($data['contact']);
//        die;
        
        $basicPageVariables = ZnUtilities::basicPageVariables("Contacts", "Create new contact", "add_contact", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Contact'] = array("link" => '/contacts', "active" => '0');
        $data['breadcrumbs']['Add'] = array("link" => "", "active" => '1');

        ZnUtilities::push_js_files('jquery.validate.min.js');
        ZnUtilities::push_js_files('components/contacts.js');

        $js = "$(function() {
                    $('#contact_form').validate();
                });";
        ZnUtilities::push_js($js);

        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.contacts.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'contacts', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

       $validator = Validator::make(
                        array(
                        'contact_first_name' => Input::get('contact_first_name'),
                        'contact_last_name' => Input::get('contact_last_name'),
                        'contact_email' => Input::get('contact_email'),
                        ), array(
                       
                        'contact_first_name' => 'required',
                        'contact_last_name' => 'required',
                        'contact_email' => 'required',
                        )
        );


        if ($validator->passes()) {
            
            $url = 'http://leadsdev.bookings-gateway.com/api/'.$id;
            $fields = array(
                'job_title' => urlencode(Input::get('job_title')),
                'contact_first_name' => urlencode(Input::get('contact_first_name')),
                'contact_last_name' => urlencode(Input::get('contact_last_name')),
                'contact_email' => urlencode(Input::get('contact_email')),
                'contact_phone' => urlencode(Input::get('contact_phone')),
                'contact_mobile' => urlencode(Input::get('contact_mobile')),
                'contact_fax' => urlencode(Input::get('contact_fax'))
            );
            
           // ZnUtilities::pa($fields); die;
            
            $fields_string = '';
            foreach($fields as $key=>$value) 
            { 
                $fields_string .= $key.'='.$value.'&'; 
            }
            rtrim($fields_string, '&');
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 'Content-Type: application/x-www-form-urlencoded');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_PUT, true); 
           // curl_setopt($ch,CURLOPT_POST, count($fields));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
            
            $output = curl_exec($ch);
           
            if($output)
                return Redirect::to('contacts/')->with('success_message', 'Contact updated successfully!');
            else
                return Redirect::to('contacts/create')->with('error_message', 'There is problem in adding this contact');
            
        } else {
            //$messages = $validator->messages();
            return Redirect::to('contacts/' . $id . '/edit/')->withErrors($validator)->withInput();
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
        if (!User::checkPermission(Auth::user()->user_group_id, 'users', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        // delete
        $user = User::find($id);
        $user->delete();

        // redirect
        return Redirect::to('users')->with('success_message', 'User deleted successfully!');
    }

    public function contactSearch($search) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'contacts', 'manage')) {
            return Redirect::to('/permissionDenied');
        }



        $contact = Contact::where("contact_title", "like", "%" . $search . "%")
                ->orWhere("first_name", "like", "%" . $search . "%")
                ->orWhere("last_name", "like", "%" . $search . "%")
                ->orWhere("email", "like", "%" . $search . "%")
                ->paginate();

        $data = array();
        $data['contacts'] = $contact;
        $data['search'] = $search;
        $basicPageVariables = ZnUtilities::basicPageVariables("Contacts", "Contacts matching term: " . $search, "contacts", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Contact'] = array("link" => '/contacts', "active" => '1');

        ZnUtilities::push_js_files('jquery.validate.min.js');
        ZnUtilities::push_js_files('components/contacts.js');

        $js = "$(function() {
                        $('#actions_form').validate();
                    });";
        ZnUtilities::push_js($js);

        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.contacts.list', $data);
    }

    public function contactActions() {
        $search = Input::get('search');
        if ($search != '') {
            return Redirect::to('/contactSearch/' . $search);
        }

        $cid = Input::get('cid');
        $bulk_action = Input::get('bulk_action');
        if ($bulk_action != '') {
            switch ($bulk_action) {
                case 'blocked': {
                        foreach ($cid as $id) {
                            DB::table('contacts')
                                    ->where('contact_id', $id)
                                    ->update(array('contact_status' => 'deactive'));
                        }

                        return Redirect::to('/contacts/')->with('success_message', 'Rows Updated!');

                        break;
                    }
                case 'active': {
                        foreach ($cid as $id) {
                            DB::table('contacts')
                                    ->where('contact_id', $id)
                                    ->update(array('contact_status' => 'active'));
                        }

                        return Redirect::to('/contacts/')->with('success_message', 'Rows Updated!');
                    }
                case 'delete': {
                        foreach ($cid as $id) {
                            DB::table('contacts')
                                    ->where('contact_id', $id)
                                    ->delete();

                            if (Input::get('associated') == '1') {
                                $contact_for = Input::get("contact_for");
                                $associated_id = Input::get("associated_id");
                                switch ($contact_for) {
                                    case 'clients': {
                                            $client = ClientContact::where('client_id', $associated_id)->where('contact_id', $id)->first();
                                            $client->delete();
                                            break;
                                        }
                                }
                            }
                        }




                        if (Input::get('return'))
                            return Redirect::to(base64_decode(Input::get('return')))->with('success_message', 'Rows Deleted!');



                        return Redirect::to('/contacts/')->with('success_message', 'Rows Deleted!');
                        break;
                    }
            } //end switch
        } // end if statement
        return Redirect::to('/contacts');
    }

    private function _submenus($active) {
        $data = array();
        $data['Contacts'] = array("link" => '/contacts', "active" => $active == 'index' ? '1' : '0', "icon" => 'fa-list-alt');
        return $data;
    }

    public function editLead($id) {

        if (!User::checkPermission(Auth::user()->user_group_id, 'contacts', 'manage')) {
            return Redirect::to('/permissionDenied');
        }
        $data = array();
        $url = 'http://leadsdev.bookings-gateway.com/api/' . $id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);

        $output = utf8_decode($output);
//echo "<pre>" ;

        $json_array = json_decode($output);

///echo json_last_error();
//print_r($json_array);
//echo "</pre>" ;

        curl_close($ch);
        foreach ($json_array as $json) {
            $data['contacts'] = $json;
        }
//ZnUtilities::pa($data['contacts']);die();

        $js = '$(function() {
                 $(".data-mask").inputmask(); 
            });';
        ZnUtilities::push_js($js);


        $basicPageVariables = ZnUtilities::basicPageVariables("Contacts", "Edit Leads contact", "add_contact", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Contact'] = array("link" => '/contacts', "active" => '0');
        $data['breadcrumbs']['Add'] = array("link" => "", "active" => '1');

        ZnUtilities::push_js_files('jquery.validate.min.js');
        ZnUtilities::push_js_files('components/contacts.js');

        $js = "$(function() {
                    $('#contact_form').validate();
                });";
        ZnUtilities::push_js($js);

        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.contacts.editleads', $data);
    }

    public function updateLeads($id) {


        $validator = Validator::make(
                        array(
                    'contact_first_name' => Input::get('contact_first_name'),
                    'contact_last_name' => Input::get('contact_last_name'),
                    'contact_email' => Input::get('contact_email'),
                    'contact_phone' => Input::get('contact_phone'),
                        ), array(
                    'contact_first_name' => 'required',
                    'contact_last_name' => 'required',
                    'contact_email' => 'required',
                    'contact_phone' => 'required',
                        )
        );

        if ($validator->passes()) {
            $post_fields = array(
                'contact_first_name' => Input::get('contact_first_name'),
                'contact_last_name' => Input::get('contact_last_name'),
                'contact_email' => Input::get('contact_email'),
                'contact_phone' => Input::get('contact_phone'),
                'contact_mobile' => Input::get('contact_mobile'),
                'facebook_link' => Input::get('facebook_link'),
                'twitter_link' => Input::get('twitter_link'),
                'job_title' => Input::get('job_title'),
                '_method' => 'PUT'
            );
            $url = "http://leadsdev.bookings-gateway.com/api/update/" . $id;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
            curl_setopt($ch, CURLOPT_POST, 1);
            $result = curl_exec($ch);





            return Redirect::to('/')->with('success_message', 'Contact created successfully!');
        }
    }

    public function show($id)
    {
        
    }
    
}
