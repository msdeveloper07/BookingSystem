<?php

class EmailTemplatesController extends \BaseController {

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
    public function index($folder_id = 0) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'email_templates', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $data = array();

        $data['email_templates'] = EmailTemplate::orderBy('email_template_title', 'asc')->where('folder_id',$folder_id)->paginate('20');

        $data['folders'] = EmailTemplateFolder::all();    
        $data['folder_id'] = $folder_id;
        
        
        $basicPageVariables = ZnUtilities::basicPageVariables("Email Templates", "All Email Templates", "email_templates", "1");
        $data = array_merge($data, $basicPageVariables);
        $data['breadcrumbs']['All Email Template'] = array("link" => '/email_templates', "active" => '1');



        ZnUtilities::push_js_files('jquery.validate.min.js');
        ZnUtilities::push_js_files('components/email_templates.js');

        $js = "$(function() {
                    $('#actions_form').validate();
                });";
        ZnUtilities::push_js($js);
        
         $data['submenus'] = $this->_submenus('email_templates');
        return View::make('admin.email_templates.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'email_templates', 'manage')) {
            return Redirect::to('/permissionDenied');
        }


        $data = array();

        $basicPageVariables = ZnUtilities::basicPageVariables("Email Templates", "Create new email_template", "'email_templates", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Email Template'] = array("link" => '/email_templates', "active" => '0');
        $data['breadcrumbs']['Add'] = array("link" => "", "active" => '1');

        ZnUtilities::push_js_files('pekeUpload.js');
        $upload_js = '
            jQuery(document).ready(function(){
                $("#upload-button").pekeUpload({
                theme:"bootstrap", 
                url:"/doc-upload.php", 
                allowed_number_of_uploads:999,
                allowedExtensions:"jpg|jpeg|gif|png|doc|docx|xls|xlsx|pdf|zip|rar",
                onFileSuccess:function(file,response){
                    var data = JSON.parse(response)
                    var file_extension = data.raw_name.substr((~-data.raw_name.lastIndexOf(".") >>> 0) + 2);
                    $("#file_holder").show();
                    $("#prev_upload tbody").append(\'<tr id="\'+data.file_id+\'" class="document_files"><td data-title="File Type"><img class="property_image" src="\'+data.logo_image+\'" height="22px"/><span class="file-type"> \'+file_extension+\'</span> </td><td data-title="Filename">\'+data.raw_name+\'</td><td data-title="Remove File"><a class="remove" href="javascript:void(0);" onclick=javascript:remove_div("\'+data.file_id+\'"); ><span class="glyphicon glyphicon glyphicon-remove"></span> Remove</a><input type="hidden" name="specs_location[]" value="\'+data.file_name+\'" /></td></tr> \');
                    $(".file").remove();
                    $("#prev_upload table").show();
                    
                    var numItems = $(".property_image").length ; 
    
                    if(numItems==999)
                    {
                        $("#upload-doc-div").hide();
                        $("#file_limit").show();
                    }
                       
                    
                    }
                });
           });
           
           function remove_div(div_id)
           {
            $("#"+div_id).remove();
            var numItems = $(".property_image").length ; 
           
            if(numItems < 1)
            {
                 $("#upload-doc-div").show();
                 $("#file_limit").hide();
                 $("#file_holder").hide();
                 $("#progress_bar").html("");
            }
                       
           }

            ';
        ZnUtilities::push_js($upload_js);




        ZnUtilities::push_js_files('jquery.validate.min.js');
        ZnUtilities::push_js_files('components/email_templates.js');

        $js = "$(function() {
                    $('#email_template_form').validate();
                });";
        ZnUtilities::push_js($js);

        ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
        $editor_js = '$(function() {
                       // $("#content").wysihtml5();
                       CKEDITOR.replace("content");
                       });';
        ZnUtilities::push_js($editor_js);

         $data['submenus'] = $this->_submenus('email_templates');
        return View::make('admin.email_templates.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'email_templates', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

         //ZnUtilities::pa($_POST);
//            
        // die;
        $validator = Validator::make(
                        array(
                    'title' => Input::get('email_template_title')
                        ), array(
                    'title' => 'required'
                        )
        );

        if ($validator->passes()) {
            
           
            
            
            $email_template = new EmailTemplate();
            $email_template->email_template_title = Input::get('email_template_title');
            $email_template->content = Input::get('content');
            
             $attachments = Input::get('specs_location');
            if(is_array($attachments)){
               $email_template->attachment = '/attachments/'.$attachments[0];
            }
            
            $email_template->created_by = Auth::user()->id;
            $email_template->created_on = date('Y-m-d H:i:s');
            $email_template->save();
            
            // History
            $history_array = array('title'=>'Template Saved',
                        'content'=>'[New] Email template is saved: '.$email_template->email_template_title,
                        'for'=>'Email Templates',
                        'id'=>$email_template->email_template_id,
                      );
            Event::fire('history.add', array($history_array));


            return Redirect::to('/emailTemplates')->with('success_message', 'Email Template created successfully!');
        } else {
            //$messages = $validator->messages();
            return Redirect::to('/emailTemplates/create')->withErrors($validator)->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'email_templates', 'manage')) {
            return Redirect::to('/permissionDenied');
        }
        $data = array();


        $data['email_template'] = EmailTemplate::findOrFail($id);



        $basicPageVariables = ZnUtilities::basicPageVariables("Email Templates", "Edit Email Template", "email_templates", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Email Templates'] = array("link" => '/email_templates', "active" => '0');
        $data['breadcrumbs']['Edit'] = array("link" => "", "active" => '1');

        ZnUtilities::push_js_files('pekeUpload.js');
        $upload_js = '
            jQuery(document).ready(function(){
                $("#upload-button").pekeUpload({
                theme:"bootstrap", 
                url:"/doc-upload.php", 
                allowed_number_of_uploads:999,
                allowedExtensions:"jpg|jpeg|gif|png|doc|docx|xls|xlsx|pdf|zip|rar",
                onFileSuccess:function(file,response){
                    var data = JSON.parse(response)
                    var file_extension = data.raw_name.substr((~-data.raw_name.lastIndexOf(".") >>> 0) + 2);
                    $("#file_holder").show();
                    $("#prev_upload tbody").append(\'<tr id="\'+data.file_id+\'" class="document_files"><td data-title="File Type"><img class="property_image" src="\'+data.logo_image+\'" height="22px"/><span class="file-type"> \'+file_extension+\'</span> </td><td data-title="Filename">\'+data.raw_name+\'</td><td data-title="Remove File"><a class="remove" href="javascript:void(0);" onclick=javascript:remove_div("\'+data.file_id+\'"); ><span class="glyphicon glyphicon glyphicon-remove"></span> Remove</a><input type="hidden" name="specs_location[]" value="\'+data.file_name+\'" /></td></tr> \');
                    $(".file").remove();
                    $("#prev_upload table").show();
                    
                    var numItems = $(".property_image").length ; 
    
                    if(numItems==999)
                    {
                        $("#upload-doc-div").hide();
                        $("#file_limit").show();
                    }
                       
                    
                    }
                });
           });
           
           function remove_div(div_id)
           {
            $("#"+div_id).remove();
            var numItems = $(".property_image").length ; 
           
            if(numItems < 1)
            {
                 $("#upload-doc-div").show();
                 $("#file_limit").hide();
                 $("#file_holder").hide();
                 $("#progress_bar").html("");
            }
                       
           }

            ';
        ZnUtilities::push_js($upload_js);

        

        ZnUtilities::push_js_files('jquery.validate.min.js');
        ZnUtilities::push_js_files('components/email_templates.js');

      ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
        $editor_js = '$(function() {
                       // $("#content").wysihtml5();
                       CKEDITOR.replace("content");
                       });';
        ZnUtilities::push_js($editor_js);
        
         $data['submenus'] = $this->_submenus('email_templates');
        return View::make('admin.email_templates.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'email_templates', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $validator = Validator::make(
                        array(
                    'title' => Input::get('email_template_title')
                        ), array(
                    'title' => 'required'
                        )
        );

        if ($validator->passes()) {
            $email_template = EmailTemplate::find($id);
            $email_template->email_template_title = Input::get('email_template_title');
            $email_template->content = Input::get('content');
             
            $attachments = Input::get('specs_location');
            if(is_array($attachments)){
               $email_template->attachment = '/attachments/'.$attachments[0];
            }
            $email_template->updated_by = Auth::user()->id;
            $email_template->updated_on = date('Y-m-d H:i:s');
            $email_template->save();

             // History
            $history_array = array('title'=>'Template Saved',
                        'content'=>'[Updated] Email template is saved: '.$email_template->email_template_title,
                        'for'=>'Email Templates',
                        'id'=>$email_template->email_template_id
                      );
            Event::fire('history.add', array($history_array));

            return Redirect::to('emailTemplates/' . $id . '/edit')->with('success_message', 'Email Template updated successfully!');
        } else {
            //$messages = $validator->messages();
            return Redirect::to('emailTemplates/' . $id . '/edit/')->withErrors($validator)->withInput();
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

    public function email_templateSearch($search) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'email_templates', 'manage')) {
            return Redirect::to('/permissionDenied');
        }



        $email_template = EmailTemplate::where("email_template_title", "like", "%" . $search . "%")
                ->orWhere("first_name", "like", "%" . $search . "%")
                ->orWhere("last_name", "like", "%" . $search . "%")
                ->paginate();

        $data = array();
        $data['email_templates'] = $email_template;
        $data['search'] = $search;
        $basicPageVariables = ZnUtilities::basicPageVariables("Email Templates", "Email Templates matching term: " . $search, "email_templates", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Email Template'] = array("link" => '/email_templates', "active" => '1');

        ZnUtilities::push_js_files('jquery.validate.min.js');
        ZnUtilities::push_js_files('components/email_templates.js');

        $js = "$(function() {
                        $('#actions_form').validate();
                    });";
        ZnUtilities::push_js($js);
        
         $data['submenus'] = $this->_submenus('email_templates');
        return View::make('admin.email_templates.list', $data);
    }

    public function emailTemplateActions() {

        $cid = Input::get('cid');
        $bulk_action = Input::get('bulk_action');
        
        $move_to = Input::get('move_to');
            if($move_to!='')
                $bulk_action = 'move_to_folder';
        
        if ($bulk_action != '') {
            switch ($bulk_action) {
                
                 case 'move_to_folder':
                    {
                        foreach($cid as $id)
                        {
                           //message 
                           EmailTemplate::where('email_template_id','=',$id)->update(array('folder_id'=>$move_to)); 
                           
                        }
                        return Redirect::to($_SERVER['HTTP_REFERER'])->with('success_message', 'Templates moved to selected folder');
                        break;
                    }
                
                case 'blocked': {
                        foreach ($cid as $id) {
                             $email_templates = EmailTemplate::find($id);
                            DB::table('email_templates')
                                    ->where('email_template_id', $id)
                                    ->update(array('email_template_status' => 'deactive'));
                            
                             //History
                            $history_array = array('title'=>'Template Saved',
                                        'content'=>'[Updated] Template Set to blocked: '.$email_templates->email_tempalate_title,
                                        'for'=>'Email Templates',
                                        'id'=>$id,
                                      );
                            Event::fire('history.add', array($history_array));
                        }

                        return Redirect::to('/email_templates/')->with('success_message', 'Rows Updated!');

                        break;
                    }
                case 'active': {
                        foreach ($cid as $id) {

                            $email_templates = EmailTemplate::find($id);

                            DB::table('email_templates')
                                    ->where('email_template_id', $id)
                                    ->update(array('email_template_status' => 'active'));
                               //History
                            $history_array = array('title'=>'Template Saved',
                                        'content'=>'[Updated] Template set to active: '.$email_templates->email_tempalate_title,
                                        'for'=>'Email Templates',
                                        'id'=>$id,
                                      );
                            Event::fire('history.add', array($history_array));
                        }

                        return Redirect::to('/email_templates/')->with('success_message', 'Rows Updated!');
                    }
                case 'delete': {
                        if (!User::checkPermission(Auth::user()->user_group_id, 'email_templates', 'delete')) {
                            return Redirect::to('/permissionDenied');
                        }


                        $email_template_titles = array();

                        foreach ($cid as $id) {
                            $email_templates = EmailTemplate::find($id);

                            DB::table('email_templates')
                                    ->where('email_template_id', $id)
                                    ->delete();
                            
                              //History
                            $history_array = array('title'=>'Template Deleted',
                                        'content'=>'[Deleted] Template deleted: '.$email_templates->email_tempalate_title,
                                        'for'=>'Email Templates',
                                        'id'=>$id,
                                      );
                            Event::fire('history.add', array($history_array));
                        }

                        return Redirect::to('/emailTemplates/')->with('success_message', 'Rows Delete');

                        break;
                    }
            } //end switch
        } // end if statement
    }

    public function chooseTemplate($lead_id, $folder = 0) {
        //die;

        $lead = Lead::find($lead_id);
        $favorite_templates_array = array();

        $favorite_templates = FavoriteTemplate::where('user_id',Auth::user()->id)->first(); 
        
        if($favorite_templates)
            $favorite_templates_array = explode(',',$favorite_templates->favorite_templates);
        
//        ZnUtilities::pa($favorite_templates);
//        die;
        
       
        $email_template_favorite = array();
        if(count($favorite_templates_array)>0)
        {
              $email_template_favorite = EmailTemplate::whereIn('email_template_id',$favorite_templates_array)->get();
              $email_template = EmailTemplate::whereNotIn('email_template_id',$favorite_templates_array)->get();
        }
        else
        {
             $email_template = EmailTemplate::where('folder_id',$folder)->get();
             //  echo ZnUtilities::lastQuery();  
        }
        
       // ZnUtilities::pa($email_template_favorite);
       // ZnUtilities::pa($email_template);
       // die;
        
        $data = array();

        $data['lead_id'] = $lead_id;
        $data['email_templates_favorite'] = $email_template_favorite;
        $data['email_templates'] = $email_template;
        $data['folder'] = $folder;

       // ZnUtilities::pa($data['email_templates']);

        $basicPageVariables = ZnUtilities::basicPageVariables("Email Templates", "Choose Email Template", "email_templates", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Email Template'] = array("link" => '/email_templates', "active" => '1');

        ZnUtilities::push_js_files('jquery.contenthover.min.js');
        ZnUtilities::push_js_files('jquery.validate.min.js');
        ZnUtilities::push_js_files('components/email_templates.js');

        $js = "$(function() {
                     $('#actions_form').validate();
                       $('.needs_hover').contenthover({
                            overlay_background:'#000',
                            overlay_opacity:0.5
                        });
                 });";
        ZnUtilities::push_js($js);
        
         $data['submenus'] = $this->_submenus('email_templates');
        return View::make('admin.email_templates.choose', $data);
    }

    public function confirm($lead_id,$email_template_id ) {
        
        $data = array();


        $data['email_template'] = EmailTemplate::findOrFail($email_template_id);
        $data['lead'] = Lead::with('contactCompany')->findOrFail($lead_id);



        $basicPageVariables = ZnUtilities::basicPageVariables("Email Templates", "Edit Email Template", "email_templates", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Email Templates'] = array("link" => '/email_templates', "active" => '0');
        $data['breadcrumbs']['Edit'] = array("link" => "", "active" => '1');

        ZnUtilities::push_js_files('pekeUpload.js');
        $upload_js = '
            jQuery(document).ready(function(){
                $("#upload-button").pekeUpload({
                theme:"bootstrap", 
                url:"/doc-upload.php", 
                allowed_number_of_uploads:999,
                allowedExtensions:"jpg|jpeg|gif|png|doc|docx|xls|xlsx|pdf|zip|rar",
                onFileSuccess:function(file,response){
                    var data = JSON.parse(response)
                    var file_extension = data.raw_name.substr((~-data.raw_name.lastIndexOf(".") >>> 0) + 2);
                    $("#file_holder").show();
                    $("#prev_upload tbody").append(\'<tr id="\'+data.file_id+\'" class="document_files"><td data-title="File Type"><img class="property_image" src="\'+data.logo_image+\'" height="22px"/><span class="file-type"> \'+file_extension+\'</span> </td><td data-title="Filename">\'+data.raw_name+\'</td><td data-title="Remove File"><a class="remove" href="javascript:void(0);" onclick=javascript:remove_div("\'+data.file_id+\'"); ><span class="glyphicon glyphicon glyphicon-remove"></span> Remove</a><input type="hidden" name="specs_location[]" value="\'+data.file_name+\'" /></td></tr> \');
                    $(".file").remove();
                    $("#prev_upload table").show();
                    
                    var numItems = $(".property_image").length ; 
    
                    if(numItems==999)
                    {
                        $("#upload-doc-div").hide();
                        $("#file_limit").show();
                    }
                       
                    
                    }
                });
           });
           
           function remove_div(div_id)
           {
                $("#"+div_id).remove();
                var numItems = $(".property_image").length ; 

                if(numItems < 1)
                {
                     $("#upload-doc-div").show();
                     $("#file_limit").hide();
                     $("#file_holder").hide();
                     $("#progress_bar").html("");
                }
                       
           }

            ';
        ZnUtilities::push_js($upload_js);
        
        ZnUtilities::push_js_files('jquery.validate.min.js');
        ZnUtilities::push_js_files('components/email_templates.js');

       ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
        $editor_js = '$(function() {
                       // $("#content").wysihtml5();
                       CKEDITOR.replace("content");
                       });';
        ZnUtilities::push_js($editor_js);
        
         $data['submenus'] = $this->_submenus('email_templates');
        return View::make('admin.email_templates.confirm', $data);
    }

    public function send() {
        //die("send function");
        
         $lead_id = Input::get('lead_id');
         $email_template_id = Input::get('email_template_id');
         $lead = Lead::find($lead_id);

        $subject = Input::get('email_template_title');
        $content = Input::get('content');
        
        $parent_id = Input::get('parent_id','0');
  
        //Check if valid emails are entered
        Validator::extend('email_to', function($attribute, $value, $parameters)
        {
            $emails = explode(',',$value);

            foreach($emails as $email) {
                    if (filter_var(trim($email), FILTER_VALIDATE_EMAIL) === false) return false;
            }

            return true;
        });
         $validator = Validator::make(
                        array(
                          'email_to' => Input::get('email_to'),
                          'email_cc' => Input::get('email_cc'),
                          'email_bcc' => Input::get('email_bcc'),
                          'content' => Input::get('content')
                        ), array(
                            'email_to' => 'required|email_to',
                            'email_cc' => 'email_to',
                            'email_bcc' => 'email_to',
                            'content' => 'required',
                        ),array(
                            'email_to' => 'The ":attribute" field should containt valid email addresses.',
                            'email_cc' => 'The ":attribute" field should containt valid email addresses.',
                            'email_bcc' => 'The ":attribute" field should containt valid email addresses.',
                           

                        )
        );
         
        if ($validator->passes()){ 
            $url = url();

             $subject = str_replace('{name}', $lead->contact_person, $subject);
             $subject = str_replace('{lead_title}', $lead->lead_title, $subject);
             $subject = str_replace('{sender_name}', Auth::user()->name, $subject);
             $subject = str_replace('{website_link}', $url, $subject);
             $subject = str_replace('{follow_up_on}', ZnUtilities::format_date($lead->follow_up_on, '2'), $subject);
             $subject = str_replace('{follow_up_time}', ZnUtilities::format_date($lead->follow_up_time, '4'), $subject);

             if($lead->interest)
                 $subject = str_replace('{interest}', $lead->interest->interest_title, $subject);
             else
                 $subject = str_replace('{interest}', '', $subject);


             // replace email template variables
             $content = str_replace('{name}', $lead->contact_person, $content);
             $content = str_replace('{lead_title}', $lead->lead_title, $content);
             $content = str_replace('{sender_name}', Auth::user()->name, $content);
             $content = str_replace('{website_link}', $url, $content);
             $content = str_replace('{follow_up_on}', ZnUtilities::format_date($lead->follow_up_on, '2'), $content);
             $content = str_replace('{follow_up_time}', ZnUtilities::format_date($lead->follow_up_time, '4'), $content);

              if($lead->interest)
                 $content = str_replace('{interest}', $lead->interest->interest_title, $content);
             else
                 $content = str_replace('{interest}','', $content);


             //ZnUtilities::pa($_POST);

              $from = Config::get('mailgun::from');

             //  echo $content;
             $message = new Message();
             $message->lead_id = $lead_id;
             $message->subject = $subject;
             $message->content = $content;
             $message->mode = "outgoing";
             $message->inbox = "0";
             $message->outbox = "1";
             $message->occurred_on = date('Y-m-d H:i:s');
             $message->sender_id = Auth::user()->id;
             //$message->sender_email = Auth::user()->email;
             $message->sender_email = $from['address'];
             $message->sender_name = Auth::user()->name;

             $message->recipient_email = input::get('email_to') ;
             $message->recipient_cc = input::get('email_cc') ;
             $message->recipient_bcc = input::get('email_bcc') ;


             $message->parent_id = $parent_id;
             $message->message_status = 'read';

             $message->last_sent_on = date('Y-m-d H:i:s');


             $message->save();


             $attachments = Input::get('specs_location');
             if(is_array($attachments)){
                 foreach($attachments as $a):
                     $attachment = new Attachment();
                     $attachment->attachment = '/attachments/'.$a;

                     $file_name_parts = explode('/attachments/',$attachment->attachment);
                     $file_name_parts2 = explode('-',$file_name_parts[1]);
                     unset($file_name_parts2[0]);
                     $attachment_name =  implode('-',$file_name_parts2);

                     $attachment->attachment_name = $attachment_name;
                     $attachment->message_id = $message->message_id;
                     $attachment->save();
                 endforeach;
             }

     //        ZnUtilities::echoViewContent('emails.message', array('content' => $content));
     //        
     //       die;

                         ini_set('max_execution_time','1600');

                         $response = Mailgun::send('emails.message', array('content' => $content), function($m) use ($lead, $message) {

                          $attachments = Attachment::where('message_id',$message->message_id)->get();

                          foreach($attachments as $attachment){
                             if($attachment->attachment_id){
                                        $m->attach(public_path().$attachment->attachment,$attachment->attachment_name);
                              } 
                           }

                            $to_array = explode(',',$message->recipient_email);
                            $m->to($to_array);

                            if($message->recipient_cc!=''){
                                 $cc_array = explode(',',$message->recipient_cc);
                                 $m->cc($cc_array);     
                            }

                            if($message->recipient_bcc!=''){
                               $bcc_array = explode(',',$message->recipient_bcc);
                               $m->bcc($bcc_array);
                            }

                            $m->subject($message->subject);

     //                        $m->to($lead->company->company_email, $lead->contact_person)->subject($message->subject);
                     }
             );


             $mailgun_message_id = trim($response->http_response_body->id, "<>");


          //   ZnUtilities::pa($response);
             if(($parent_id!='0')&&($parent_id!=''))
             {
                 $original_message = Message::find($parent_id);
                 $message->original_message_id = $original_message->original_message_id;
             }
             else
             {
                 $message->original_message_id = $mailgun_message_id;
             }




             $message->reply_message_id = $mailgun_message_id;
             $message->save();


            // History
            $history_array = array('title'=>'Email Sent',
                       'content'=>'[New] New email is sent out to: '.$message->recipient_email.'<br />'.$message->recipient_cc.' {messageid-'.$message->message_id.'}',
                        'for'=>'Leads',
                        'id'=>$lead->lead_id,
                      );
            Event::fire('history.add', array($history_array));
        
             
             
             
             // echo "email sent to sarabjeetdhillon@gmail.com";
             if(($parent_id!='0')&&($parent_id!=''))
                  return Redirect::to("/messages/".$parent_id."#message-".$message->message_id);

             return Redirect::to('/messages/' . $message->message_id); 

        }
        else{
             return Redirect::to('chooseTemplate/confirm/'.$lead_id.'/'.$email_template_id)->withErrors($validator)->withInput();
          }      
        
        
        
    }

    public function removeAttachment($template_id)
    {
        $template = EmailTemplate::find($template_id);
        $template->attachment = '';
        $template->save();
        return Redirect::to('emailTemplates/'.$template_id.'/edit');
    }
    
    public function makeFavorite($template_id)
    {
        $favorite = FavoriteTemplate::where('user_id',Auth::user()->id)->first();
        
        
        if(!$favorite)
        {
            $favorite = new FavoriteTemplate();
            $favorite->favorite_templates = $template_id;
            $favorite->user_id = Auth::user()->id;
            $favorite->save();
        }
        else
        {
            
            $favorite->favorite_templates = $favorite->favorite_templates.",".$template_id;
            $favorite->save();
        }
        
         // History
         $history_array = array('title'=>'Template Favorite',
                        'content'=>'Template set as Favorite',
                        'for'=>'Email Templates',
                        'id'=>$template_id,
                      );
            Event::fire('history.add', array($history_array));
        
         return Redirect::back()->with('message','Template added to favorites');
        
    }
    public function removeFavorite($template_id)
    {
        $favorite = FavoriteTemplate::where('user_id',Auth::user()->id)->first();
        $array  = explode(',',$favorite->favorite_templates);
        
       // ZnUtilities::pa($array);
        
        if(($key = array_search($template_id, $array)) !== false) {
//            echo $key;
            
              unset($array[$key]);
        }
    
        $favorite = FavoriteTemplate::where('user_id',Auth::user()->id)->first();
        $favorite->favorite_templates = implode(',',$array);
        $favorite->save();
        
        //ZnUtilities::pa($array);

        // History
         $history_array = array('title'=>'Template Unfavorite',
                        'content'=>'Template removed from Favorite',
                        'for'=>'Email Templates',
                        'id'=>$template_id,
                      );
         Event::fire('history.add', array($history_array));
        
        
  //      die;
         return Redirect::back()->with('message','Template removed from favorites');
        
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
