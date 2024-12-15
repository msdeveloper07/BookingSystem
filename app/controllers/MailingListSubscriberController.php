<?php

class MailingListSubscriberController extends BaseController {

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
        if (!User::checkPermission(Auth::user()->user_group_id, 'mailingList_subscriber', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $data = array();

        $data['mailinglist_subscriber'] = MailingListSubscriber::with('mailinglist')->paginate(20);



        $basicPageVariables = ZnUtilities::basicPageVariables("MailingListSubscribers", " All MailingListSubscribers", "mailinglistsubscribers", "1");
        $data = array_merge($data, $basicPageVariables);


        $data['breadcrumbs']['MailingListSubscribers'] = array("link" => '/mailinglistsubscribers', "active" => '0');

        ZnUtilities::push_js_files('components/mailinglistsubscriber.js');

       // $data['active_nav'] = 'mailingList_subscriber';
        
           $data['submenus'] = $this->_submenus('mailinglistsubscribers');
        return View::make('admin.mailingList_subscriber.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'mailingList_subscriber', 'manage')) {
            return Redirect::to('/permissionDenied');
        }
        $data = array();
        $basicPageVariables = ZnUtilities::basicPageVariables("MailingListSubscribers", " Add new MailingListSubscriber", "mailinglistsubscribers", "1");
        $data = array_merge($data, $basicPageVariables);
        $data['mailinglist'] = MailingList::all();


        $data['breadcrumbs']['All MailingListSubscribers'] = array("link" => '/mailinglistsubscribers', "active" => '0');
        $data['breadcrumbs']['New MailingListSubscriber'] = array("link" => "", "active" => '1');
        
           $data['submenus'] = $this->_submenus('mailinglistsubscribers');
        return View::make('admin.mailingList_subscriber.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'mailingList_subscriber', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

//         ZnUtilities::pa($_POST);
////            
//         die;
        $validator = Validator::make(
                        array(
                    'mailinglist_subscriber_name' => Input::get('mailinglist_subscriber_name'),
                    'mailinglist_subscriber_email' => Input::get('mailinglist_subscriber_email'),
                    'mailinglists' => Input::get('mailinglists'),
                        ), array(
                    'mailinglist_subscriber_name' => 'required',
                    'mailinglist_subscriber_email' => 'required',
                    'mailinglists' => 'required',
                        )
        );

        if ($validator->passes()) {
            $mailinglists = Input::get('mailinglists');
            foreach ($mailinglists as $ml) {
                if ($ml) {
                    $mailinglist = new MailingListSubscriber();
                    $mailinglist->mailinglist_subscriber_name = Input::get('mailinglist_subscriber_name');
                    $mailinglist->mailinglist_subscriber_email = Input::get('mailinglist_subscriber_email');
                    $mailinglist->mailinglist_id = $ml;


                    $mailinglist->save();
                }
            }


            return Redirect::to('mailinglistsubscribers')->with('success_message', 'Mailing List Subscribercreated successfully!');
        } else {
            //$messages = $validator->messages();
            return Redirect::to('mailinglistsubscribers/create')->withErrors($validator)->withInput();
        }
    }

    public function edit($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'mailingList_subscriber', 'manage')) {
            return Redirect::to('/permissionDenied');
        }



        $data = array();

        //Throw exception if project id does not exists
        $data['mailinglist_subscriber'] = MailingListSubscriber::find($id);
        $email = $data['mailinglist_subscriber']->mailinglist_subscriber_email;
        $name = $data['mailinglist_subscriber']->mailinglist_subscriber_name;
        $data['mailinglists'] = MailingListSubscriber::where('mailinglist_subscriber_email', $email)->where('mailinglist_subscriber_name', $name)->lists('mailinglist_id');

        $data['mailinglist'] = MailingList::all();
//ZnUtilities::pa($data['mailingLists']);die();

        $basicPageVariables = ZnUtilities::basicPageVariables("MailingListSubscribers", "Edit MailingListSubscriber", "mailinglistsubscribers", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All MailingListSubscribers'] = array("link" => '/mailinglistsubscribers', "active" => '0');
        $data['breadcrumbs']['Edit MailingListSubscribers'] = array("link" => "", "active" => '1');
        
           $data['submenus'] = $this->_submenus('mailinglistsubscribers');
        return View::make('admin.mailingList_subscriber.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'mailingList_subscriber', 'manage')) {
            return Redirect::to('/permissionDenied');
        }
        
   

        $validator = Validator::make(
                        array(
                    'mailinglist_subscriber_name' => Input::get('mailinglist_subscriber_name'),
                    'mailinglist_subscriber_email' => Input::get('mailinglist_subscriber_email'),
                    'mailinglists' => Input::get('mailinglists'),
                        ), array(
                    'mailinglist_subscriber_name' => 'required',
                    'mailinglist_subscriber_email' => 'required',
                    'mailinglists' => 'required',
                        )
        );

        if ($validator->passes()) {
            $subscriber = MailingListSubscriber::find($id);
            $subscriberlist = MailingListSubscriber::where('mailinglist_subscriber_email', $subscriber->mailinglist_subscriber_email)->where('mailinglist_subscriber_name', $subscriber->mailinglist_subscriber_name)->lists('mailinglist_id');
            $mailinglists = Input::get('mailinglists');
            foreach ($mailinglists as $ml) {
                if (in_array($ml, $subscriberlist)) {
                    $mailinglist = MailingListSubscriber::find($ml);
                } else {
                    $mailinglist = new MailingListSubscriber();
                }
                $mailinglist->mailinglist_subscriber_name = Input::get('mailinglist_subscriber_name');
                $mailinglist->mailinglist_subscriber_email = Input::get('mailinglist_subscriber_email');
                $mailinglist->mailinglist_id = $ml;
                $mailinglist->save();
            }

            return Redirect::to('mailinglistsubscribers')->with('success_message', 'Mailing List Subscribercreated successfully!');
        } else {
            //$messages = $validator->messages();
            return Redirect::to('mailinglistsubscribers/' . $id . '/edit/')->withErrors($validator)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'mailingList_subscriber', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        // delete
        $mailingList = MailingListSubscriber::find($id);
        $mailingList->delete();

        // redirect
        return Redirect::to('mailinglistsubscribers')->with('success_message', 'Mailing List Subscriberdeleted successfully!');
    }

    public function mailingListSubscriberSearch($search) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'mailingList_subscriber', 'manage')) {
            return Redirect::to('/permissionDenied');
        }



        $mailinglist = MailingListSubscriber::where("mailinglist_subscriber_name", "like", "%" . $search . "%")
                ->paginate();

        $data = array();
        $data['mailinglist_subscriber'] = $mailinglist;
        //Basic Page Settings

        $basicPageVariables = ZnUtilities::basicPageVariables("MailingListSubscribers", "Search Results", "mailinglistsubscribers", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All MailingListSubscribers'] = array("link" => '/mailinglistsubscribers', "active" => '0');
        $data['breadcrumbs']['Search'] = array("link" => "", "active" => '1');

        $data['search'] = $search;
        
           $data['submenus'] = $this->_submenus('mailinglistsubscribers');
        return View::make('admin.mailingList_subscriber.list', $data);
    }

    public function mailingListSubscriberActions() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'mailingList_subscriber', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $search = Input::get('search');
        if ($search != '') {
            return Redirect::to('/mailinglistsubscriberSearch/' . $search);
        } else {


            //die(Input::get('bulk_action')   );

            $cid = Input::get('cid');
            $bulk_action = Input::get('bulk_action');
            if ($bulk_action != '') {
                switch ($bulk_action) {
//                   
                    case 'delete': {

                            foreach ($cid as $id) {
                                DB::table('mailinglist_subscribers')
                                        ->where('mailinglist_subscriber_id', $id)
                                        ->delete();
                            }

                            return Redirect::to('/mailinglistsubscribers/')->with('success_message', 'Rows Delete!');
                            break;
                        }
                } //end switch
            } // end if statement
            return Redirect::to('/mailinglistsubscribers');
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
