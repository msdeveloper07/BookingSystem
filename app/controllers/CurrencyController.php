<?php

class CurrencyController extends BaseController {

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

        return Redirect::to('/currency/create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'currency', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $data = array();

        $data['currency'] = CurrencyConverter::all();

        ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files('components/currencyConverter.js');


        $basicPageVariables = ZnUtilities::basicPageVariables("Currency", "Convert Currency", "convert_currency", "1");
        $data = array_merge($data, $basicPageVariables);


        $data['breadcrumbs']['Convert Currency'] = array("link" => "", "active" => '1');
        
        $data['submenus'] = $this->_submenus('convert_currency');
        return View::make('admin.currencyConverter.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function currencyConverted() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'currency', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        // ZnUtilities::pa($_POST);
//            
        // die;
        $validator = Validator::make(
                        array(
                    'amount' => Input::get('amount'),
                    'from' => Input::get('from'),
                    'to' => Input::get('to'),
                        ), array(
                    'amount' => 'required',
                    'from' => 'required',
                    'to' => 'required',
                        )
        );

        if ($validator->passes()) {
            $amount = urlencode(Input::get('amount'));
            $from = urlencode(Input::get('from'));
            $to = urlencode(Input::get('to'));
            $get = file_get_contents("https://www.google.com/finance/converter?a=$amount&from=$from&to=$to");
            $get = explode("<span class=bld>", $get);
            $get = explode("</span>", $get[1]);
            $converted_amount = preg_replace("/[^0-9\.]/", null, $get[0]);
            $converted_amount = round($converted_amount, 2);

            $data = array();

            $data['currency'] = CurrencyConverter::all();
            $data['converted_amount'] = $converted_amount;
            $data['amount'] = $amount;
            $data['from'] = $from;
            $data['to'] = $to;


            ZnUtilities::push_js_files("jquery.validate.min.js");
            ZnUtilities::push_js_files('components/currencyConverter.js');


            $basicPageVariables = ZnUtilities::basicPageVariables("Currency", "Convert Currency", "convert_currency", "1");
            $data = array_merge($data, $basicPageVariables);


            $data['breadcrumbs']['Convert Currency'] = array("link" => "", "active" => '1');

            return View::make('admin.currencyConverter.create', $data);
        } else {
            //$messages = $validator->messages();
            return Redirect::to('faq/create')->withErrors($validator)->withInput();
            ;
        }
    }
public function currencyConvertedModal() {
       
          
       
        $validator = Validator::make(
                        array(
                    'amount' => Input::get('amount'),
                    'from' => Input::get('from'),
                    'to' => Input::get('to'),
                        ), array(
                    'amount' => 'required',
                    'from' => 'required',
                    'to' => 'required',
                        )
        );

        if ($validator->passes()) {
            $amount = urlencode(Input::get('amount'));
            $from = urlencode(Input::get('from'));
            $to = urlencode(Input::get('to'));
            $get = file_get_contents("https://www.google.com/finance/converter?a=$amount&from=$from&to=$to");
            $get = explode("<span class=bld>", $get);
            $get = explode("</span>", $get[1]);
            $converted_amount = preg_replace("/[^0-9\.]/", null, $get[0]);
            echo $converted_amount = round($converted_amount, 2);

           
           
        } 
    }
    
    public function edit($id) {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        
    }
    
         private function _submenus($active)
    {  $data = array();
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
