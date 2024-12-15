<?php

class MailingListController extends BaseController {

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
        if (!User::checkPermission(Auth::user()->user_group_id, 'mailingList', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $data = array();

        $data['mailinglist'] = MailingList::paginate('20');


        $basicPageVariables = ZnUtilities::basicPageVariables("MailingLists", " All MailingLists", "mailinglists", "1");
        $data = array_merge($data, $basicPageVariables);


        $data['breadcrumbs']['MailingLists'] = array("link" => '/mailinglists', "active" => '0');

        ZnUtilities::push_js_files('components/mailingList.js');

        $data['active_nav'] = 'mailingList';
        
           $data['submenus'] = $this->_submenus('mailinglists');
        return View::make('admin.mailingList.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'mailingList', 'manage')) {
            return Redirect::to('/permissionDenied');
        }
      $data = array();
        $basicPageVariables = ZnUtilities::basicPageVariables("MailingLists", " Add new MailingList", "mailinglists", "1");
        $data = array_merge($data, $basicPageVariables);

        
        $data['breadcrumbs']['All MailingLists'] = array("link" => '/mailinglists', "active" => '0');
        $data['breadcrumbs']['New MailingList'] = array("link" => "", "active" => '1');
        
           $data['submenus'] = $this->_submenus('mailinglists');
        return View::make('admin.mailingList.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'mailingList', 'manage')) {
            return Redirect::to('/permissionDenied');
        }


        $validator = Validator::make(
                        array(
                    'mailinglist_name' => Input::get('mailinglist_name'),
                    
                        ), array(
                    'mailinglist_name' => 'required',
                    
                        )
        );

        if ($validator->passes()) {
            $mailingList = new MailingList();
            
            $mailingList->mailinglist_name = Input::get('mailinglist_name');
            

            $mailingList->save();



            return Redirect::to('mailinglists')->with('success_message', 'Mailing List created successfully!');
        } else {
            //$messages = $validator->messages();
            return Redirect::to('mailinglists/create')->withErrors($validator)->withInput();
        }
    }

    public function edit($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'mailingList', 'manage')) {
            return Redirect::to('/permissionDenied');
        }



        $data = array();

        //Throw exception if project id does not exists
        $data['mailinglist'] = MailingList::findOrFail($id);


        $basicPageVariables = ZnUtilities::basicPageVariables("MailingLists", "Edit MailingList", "mailinglists", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All MailingLists'] = array("link" => '/mailinglists', "active" => '0');
        $data['breadcrumbs']['Edit MailingLists'] = array("link" => "", "active" => '1');
        
           $data['submenus'] = $this->_submenus('mailinglists');
        return View::make('admin.mailingList.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'mailingList', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        //skip email's unique validation if email is not changed
        $mailingList = MailingList::find($id);

        $validator = Validator::make(
                        array(
                    'mailinglist_name' => Input::get('mailinglist_name'),
                        ), array(
                    'mailinglist_name' => 'required',
                        )
        );


        if ($validator->passes()) {

            $mailingList->mailinglist_name = Input::get('mailinglist_name');



            // $user->user_status  = Input::get('user_status');
            $mailingList->save();



            return Redirect::to('mailinglists')->with('success_message', 'Mailing List Updated Successfully');
        } else {
            //$messages = $validator->messages();
            return Redirect::to('mailinglist/' . $id . '/edit/')->withErrors($validator)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'mailingList', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        // delete
        $mailingList = MailingList::find($id);
        $mailingList->delete();

        // redirect
        return Redirect::to('mailinglists')->with('success_message', 'Mailing List deleted successfully!');
    }

    public function mailingListSearch($search) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'mailingList', 'manage')) {
            return Redirect::to('/permissionDenied');
        }



        $mailingList = MailingList::where("mailinglist_name", "like", "%" . $search . "%")
                ->paginate();

        $data = array();
        $data['mailinglist'] = $mailingList;
        //Basic Page Settings

        $basicPageVariables = ZnUtilities::basicPageVariables("MailingLists", "Search Results", "mailinglists", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All MailingLists'] = array("link" => '/mailinglists', "active" => '0');
        $data['breadcrumbs']['Search'] = array("link" => "", "active" => '1');

        $data['search'] = $search;
        
           $data['submenus'] = $this->_submenus('mailinglists');
        return View::make('admin.mailingList.list', $data);
    }

    public function mailingListActions() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'mailingList', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $search = Input::get('search');
        if ($search != '') {
            return Redirect::to('/mailinglistSearch/' . $search);
        } else {


            //die(Input::get('bulk_action')   );

            $cid = Input::get('cid');
            $bulk_action = Input::get('bulk_action');
            if ($bulk_action != '') {
                switch ($bulk_action) {
//                   
                    case 'delete': {

                            foreach ($cid as $id) {
                                DB::table('mailinglists')
                                        ->where('mailinglist_id', $id)
                                        ->delete();
                            }

                            return Redirect::to('/mailinglist/')->with('success_message', 'Rows Delete!');
                            break;
                        }
                } //end switch
            } // end if statement
            return Redirect::to('/mailinglists');
        }
    }
         private function _submenus($active)
    {   
        $data = array();
        $data['Back'] = array("link" => '/faq', "active" => $active=='faqs'?'1':'0' ,"icon" => 'fa-angle-left' ,);
        $data['FAQ'] = array("link" => '/faq', "active" => $active=='faqs'?'1':'0' ,"icon" => 'fa-question');
        $data['Email Template'] = array("link" => '/emailTemplates', "active" => $active=='email_templates'?'1':'0' ,"icon" => 'fa-file-o');
        $data['Mailing Lists'] = array("link" => '/mailinglists', "active" => $active=='mailinglists'?'1':'0' ,"icon" => 'fa-list');
        $data['Mailing List Subscribers'] = array("link" => '/mailinglistsubscribers', "active" => $active=='mailinglistsubscribers'?'1':'0' ,"icon" => 'fa-user-plus');
        $data['Messages'] = array("link" => '/messages', "active" => $active=='messages'?'1':'0' ,"icon" => 'fa-envelope');
        $data['Newsletters'] = array("link" => '/newsletters', "active" => $active=='newsletter'?'1':'0' ,"icon" => 'fa-list');
        $data['Tasks'] = array("link" => '/tasks', "active" => $active=='tasks'?'1':'0' ,"icon" => 'fa-list');
        $data['Currency Convert '] = array("link" => '/currency/create', "active" => $active=='convert_currency'?'1':'0' ,"icon" => 'fa-gear');
        return $data;
    }
}
