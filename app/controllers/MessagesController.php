<?php

use Symfony\Component\DomCrawler\Crawler;
class MessagesController extends \BaseController {

    
	public function index($folder_id = 0, $message_status = 'all', $action = 'all')
	{
             if(!Auth::check())
                {
                    return Redirect::to('login')->with('error_message',"You are either not logged in or you dont have permissions to access this page");
                }
            
                if($message_status!='all')
                {
                    if($message_status!='-1')
                    $status = array($message_status);
                }
                else
                {
                    $status = array('unread','read');
                }
                
                if($action!='all')
                {
                    if($action=='actioned')
                    {
                        $a = '1';
                    }
                    else
                    {
                        $a = 0;
                    }
                    $action_taken = array($a);
                }else
                {
                    $action_taken = array('1','0');
                }
                
                
		$messages = Message::where('parent_id','0')
                        ->where('inbox','1')
                        ->where('folder_id',$folder_id);
                if($message_status!='-1')
                    $messages =     $messages->whereIn('message_status',$status);
                
                $messages =     $messages->whereIn('action_taken',$action_taken)
                        ->orderBy('last_incoming_on','desc')
                        ->paginate();

                // echo ZnUtilities::lastQuery();
                
                $data = array();

                $data['mode'] = 'incoming';
                $data['messages'] = $messages;
                $data['folder_id'] = $folder_id;

                
                $page_title = $message_status == 'all'?'Inbox':'Inbox : Unread Messages'; 
                        
                $basicPageVariables = ZnUtilities::basicPageVariables("Messages",$page_title, "messages","1");
                $data = array_merge($data,$basicPageVariables);
                
                ZnUtilities::push_js_files('components/messages.js');

                $data['breadcrumbs']['Inbox'] = array("link"=>'/messages',"active"=>'1');
                
                $data['submenus'] = $this->_submenus('messages');
                return View::make('admin.messages.all',$data);
                     
	}
	
	
	
        
        public function sent()
	{
             if(!Auth::check())
                {
                    return Redirect::to('login')->with('error_message',"You are either not logged in or you dont have permissions to access this page");
                }
            
                //Subquery
//                $messages = Message::whereIn('message_id', function($query){
//                    $query->select( DB::raw('DISTINCT(parent_id)'))
//                    ->from('messages')
//                    ->where('mode', 'outgoing');
//                })->orderBy('occurred_on','desc')->paginate();
                
           //     $messages = Message::where('mode','outgoing')->orderBy('occurred_on','desc')->paginate();
             $messages = Message::where('parent_id','0')
                        ->where('outbox','1')->orderBy('last_sent_on','desc')->paginate();
                
    
                $data = array();

               
                $data['mode'] = 'outgoing';
                $data['messages'] = $messages;

                
                ZnUtilities::push_js_files('components/messages.js');


                $basicPageVariables = ZnUtilities::basicPageVariables("Messages","Sent Messages", "messages","1");
                $data = array_merge($data,$basicPageVariables);

                $data['breadcrumbs']['Sent Messages'] = array("link"=>'/messages',"active"=>'1');
                
                $data['submenus'] = $this->_submenus('messages');
                return View::make('admin.messages.all',$data);
                     
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
        {
           
            
            
        }


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
             if(!Auth::check())
                {
                    return Redirect::to('login')->with('error_message',"You are either not logged in or you dont have permissions to access this page");
                }
                
          
                
            
           
             $parent_id = Input::get('parent_id');    
                
                
              //Check if valid emails are entered
                Validator::extend('email_to', function($attribute, $value, $parameters)
                {
                    $emails = explode(',',$value);

                    foreach($emails as $email) {
                            if (filter_var(trim($email), FILTER_VALIDATE_EMAIL) === false) return false;
                    }

                    return true;
                });

                //Check for  related variables 
               
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
            $content = Input::get('content');
            $subject = Input::get('subject');
            $parent_message = Message::find($parent_id);
            
            //Update the action for the parent
            $parent_message->action_taken = '1';
            $parent_message->action = 'replied';
            $parent_message->save();
            
            
            
            $url = url();
            
        
            
            $subject = str_replace('{sender_name}', Auth::user()->name, $subject);
            $subject = str_replace('{website_link}', $url, $subject);
             
            $content = str_replace('{sender_name}', Auth::user()->name, $content);
            $content = str_replace('{website_link}', $url, $content);
                
            $from = Config::get('mailgun::from');
            
            //   echo $content;
            $message  = new Message();
           
            $message->subject = '';
            $message->content = $content;
            $message->mode = "outgoing";
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
            $message->original_message_id = $parent_message->original_message_id;
            $message->save();
            
            $attachments = Input::get('specs_location');
            if(is_array($attachments)){
                foreach($attachments as $a):
                    $attachment = new Attachment(); 
                   // $attachment->attachment = '/attachments/'.$a;
                    
                    $pos = strpos($a,'/attachments/');
                    if($pos === false)
                    {
                        $attachment->attachment = '/attachments/'.$a;
                    }
                    else
                    {
                        $attachment->attachment = $a;
                    }
                    
                    
                    
                   //  $file_name_parts = explode('/attachments/',$attachment->attachment);
                   //  ZnUtilities::pa($file_name_parts);
                    
                  // echo $a;
                    $file_name_parts2 = explode('-',$a);
                    unset($file_name_parts2[0]);
                    $attachment_name =  implode('-',$file_name_parts2);

                    //ZnUtilities::pa($attachment_name);
                    //die;
                    
                    
                    $attachment->attachment_name = $attachment_name;
                    $attachment->message_id = $message->message_id;
                    $attachment->save();
                endforeach;
            }
            
            
            $Original_message = Message::find($parent_id);
            $Original_message->message_status='read';
            $Original_message->outbox='1';
            $Original_message->last_sent_on = date('Y-m-d H:i:s');
            $Original_message->save();
            
            
            
            ini_set('max_execution_time','1600');
            
            $response =  Mailgun::send('emails.message', 
                        array('content'=>$content), 
                        function($m) use ($message, $parent_message){
                
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
                             
                            $m->subject($parent_message->subject);
                        }
                    );
                    
                    
                   // ZnUtilities::pa($response);      
         
            $mailgun_message_id = trim($response->http_response_body->id,"<>");       
            $message->reply_message_id = $mailgun_message_id;
            $message->save();
            
            
            // History
           
            
            
            if(Input::get('sender_page'))   
            {
                return Redirect::to($_SERVER['HTTP_REFERER']);
            }
                return Redirect::to("/messages/".$parent_id."#message-".$message->message_id);
            
            
            
         }
        else{
            if(Input::get('sender_page'))   
            {
                   return Redirect::to($_SERVER['HTTP_REFERER'])->withErrors($validator)->withInput();
            }
             return Redirect::to('/messages/'.$parent_id."#reply")->withErrors($validator)->withInput();
          }       
            
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
             if(!Auth::check())
                {
                    return Redirect::to('login')->with('error_message',"You are either not logged in or you dont have permissions to access this page");
                }
            	$message = Message::find($id);
                $message->message_status = 'read';
                $message->save();
            
                //ZnUtilities::pa($message->attachments);
                //die;
                
                ZnUtilities::push_js_files('chosen.jquery.min.js');
                  $choosen_js = '$(function() {
                        $("#lead_id").chosen({width:"95%",search_contains: true});
                       });';
                  
                ZnUtilities::push_js($choosen_js);
                
                $sub_messages = Message::where('parent_id',$id)->orderBy('occurred_on','asc')->get();
                $data = array();
                $data['message'] = $message;
                $data['sub_messages'] = $sub_messages;

                
                
                $favorite_templates_array = array();
                $favorite_templates = FavoriteTemplate::where('user_id',Auth::user()->id)->first(); 

                if($favorite_templates)
                    $favorite_templates_array = explode(',',$favorite_templates->favorite_templates);

            
                $email_template_favorite = array();
                if(count($favorite_templates_array)>0)
                {
                      $email_template_favorite = EmailTemplate::whereIn('email_template_id',$favorite_templates_array)->get();
                      $email_template = EmailTemplate::whereNotIn('email_template_id',$favorite_templates_array)->get();
                }
                else
                {
                       $email_template = EmailTemplate::all();
                }

                $data['email_templates_favorite'] = $email_template_favorite;
                $data['email_templates'] = $email_template;
                
                $content_hover = "$(function() {
                                   
                                      $('.needs_hover').contenthover({
                                           overlay_background:'#000',
                                           overlay_opacity:0.5
                                       });
                                });";
                 ZnUtilities::push_js($content_hover);
                
                ZnUtilities::push_js_files('components/messages.js');
                ZnUtilities::push_js_files('jquery.contenthover.min.js');
                 
                $basicPageVariables = ZnUtilities::basicPageVariables("Message",$message->subject, "messages","1");
                $data = array_merge($data,$basicPageVariables);

                $data['breadcrumbs']['All Messages'] = array("link"=>'/messages',"active"=>'0');
                $data['breadcrumbs']['Message'] = array("link"=>'',"active"=>'1');
 
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
        
                
        ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
        $editor_js = '$(function() {
                       // $("#content").wysihtml5();
                       CKEDITOR.replace("content");
                       });';
        ZnUtilities::push_js($editor_js);
             
                $data['submenus'] = $this->_submenus('messages');
                return View::make('admin.messages.show',$data);
	}

        
        	
        public function showSingle($id,$type = 'child')
	{
             if(!Auth::check())
                {
                    return Redirect::to('login')->with('error_message',"You are either not logged in or you dont have permissions to access this page");
                }
            	$message = Message::find($id);
                $message->message_status = 'read';
                $message->save();
            
                //phpinfo();  
                //ini_set('mbstring.substitute_character', "none");
                
                
                $data = array();
                $data['message'] = $message;
                $data['page_title'] = $message->subject;
                $data['message_type'] = $type;
                
                $data['submenus'] = $this->_submenus('messages');
                return View::make('admin.messages.showSingle',$data);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
            $html = '<div id="content2"><style>'
                    . '.gold{ color:red;}'
                    . '</style>'
                    . '<div class="gold">'
                    . '<p>This is gold<p>'
                    . '<span>This is span</span></div></div>';
            
            echo $html;
            
            echo "<hr />";
            $crawler = new Crawler($html);
            
            
            $crawler->filter('p')->reduce(function (Crawler $node, $i) {
                return false;
            });
            
            
            ZnUtilities::pa($crawler->filter('#content2')->eq(0)->html());
            
            die;
            
            
            $date = 'Mon, 9 Feb 2015 13:19:33 +0000';
            
            $obj = new DateTime($date);
            $tz = $obj->getTimezone();
            echo $tz->getName();
            echo "<br />";
            
            $obj->setTimezone(new DateTimeZone('Europe/London'));
            $obj->format('Y-m-d H:i:sP');
            
            echo "<br />";
            
            echo $date;
            
            die;
            
            //die;
            
            # Instantiate the client.
            $key  = Config::get('mailgun::api_key');
            $domain  = Config::get('mailgun::domain');
              
            $mgClient = new \Mailgun\Mailgun($key);
            $domain = $domain;
            
            
//            $queryString = array(
//                        'message-id'        => '20141219160055.80997.24075@vipsalesteam.com'
//                    );
//                                $result = $mgClient->get("$domain/events", $queryString);
//
//            
//            ZnUtilities::pa($result);
//            die;
            
            
            $processed_messages = array();
            $unprocessed_messages = array();
            $messages = Message::all();
            
            ini_set('max_execution_time', '16600' );
            
            
            foreach($messages as $m){
                
                echo "processing  ".$m->message_id."....<br/>";
                
                if($m->reply_message_id != ''  )
                {
                    $queryString = array(
                        'message-id'        => $m->reply_message_id
                    );

                    # Make the call to the client.
                    $result = $mgClient->get("$domain/events", $queryString);
                    
                    if(isset($result->http_response_body->items[1])){
                        $result_obj = $result->http_response_body->items[1];
                        
                        $m->sender_email = $result_obj->envelope->sender;
                        $m->recipient_email = $result_obj->recipient;

                        if($m->parent_id!=0)
                        {
                            $parent = Message::find($m->parent_id);
                            if($m->mode == 'incoming')
                            {

                                $m->inbox = '1';

                                $parent->inbox = '1';
                                $parent->last_incoming_on = $m->occurred_on;
                            }
                            else if($m->mode == 'outgoing')
                            {
                                $m->outbox = '1';
                                $parent->outbox = '1';
                                $parent->last_sent_on = $m->occurred_on;
                            }
                            $parent->save();
                        }
                        else
                        {
                             if($m->mode == 'incoming')
                            {
                                $m->inbox = '1';
                                $m->last_incoming_on = $m->occurred_on;
                            }
                            else if($m->mode == 'outgoing')
                            {
                                $m->outbox = '1';
                                $m->last_sent_on = $m->occurred_on;
                            }
            
                            $m->action = '1';
                            $m->action_taken = 'Old messages before new System';
                            $m->save();
                        }
                        
                    }
                    else{
                          $unprocessed_messages[] = $m->message_id;
                    }
                    $processed_messages[] = $m->message_id;
                }
                else{
                    $unprocessed_messages[] = $m->message_id;
                }
                
            }
            
            die;
            $date = 'Mon, 24 Nov 2014 17:40:02 +0530';
            
            echo "<br />";
            echo $date;
            
            die;
            
            
             if(!Auth::check())
                {
                    return Redirect::to('login')->with('error_message',"You are either not logged in or you dont have permissions to access this page");
                }
          
               $post = MessagePost::find($id); 
               $data = unserialize($post->message_post);
                
               $pattern = "/([A-Za-z0-9\.\-\_]+)\@([A-Za-z0-9\.\-\_]+)(\.[A-Za-z]{2,5})/";
               preg_match_all($pattern,$data['body-plain'],$emails);
               $sender_email = $emails[0][0];
                        
               ZnUtilities::pa($emails);
               ZnUtilities::pa($sender_email);
        }

       function tab2space($text, $spaces = 4)
        {
        // Explode the text into an array of single lines
        $lines = explode("\n", $text);

        // Loop through each line
        foreach ($lines as $line)
        {
            // Break out of the loop when there are no more tabs to replace
            while (false !== $tab_pos = strpos($line, "\t"))
            {
                // Break the string apart, insert spaces then concatenate
                $start = substr($line, 0, $tab_pos);
                $tab = str_repeat(' ', $spaces - $tab_pos % $spaces);
                $end = substr($line, $tab_pos + 1);
                $line = $start . $tab . $end;
            }

            $result[] = $line;
        }

        return implode("\n", $result);
        }
        
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
            //$date = 'Mon, 24 Nov 2014 17:40:02 +0530';
            $date = 'Mon, 9 Feb 2015 13:19:33 +0000';
            echo $date;
            
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
        
        public function incoming()
        {
            //save the post as string in the database
            
            
            

           // die;
            
            if(Input::get('Message-Id'))
            {
                Log::info('Log message',  array('context' => Input::get('Message-Id') ));
	       
                
                $from_name_array = explode("<",Input::get('From'));
                $from_name = trim($from_name_array[0],' \"');
                
                $date_obj = new DateTime(Input::get('Date'));
                $tz = $date_obj->getTimezone();
                $timezone = $tz->getName();
                if($timezone!='+01:00')
                {
                    $date_obj->setTimezone(new DateTimeZone('Europe/London'));
                }
                
                
                if(Input::get('References')!=''){
                    $reference_array = explode('	',Input::get('References'));
                    $reference = trim($reference_array[0],'><');
               
                    $reply_message = Message::where('reply_message_id',$reference)->first();
                    if($reply_message)
                    {
                        $original_message_id = $reply_message->original_message_id;
                        //$Original_message = Message::where('parent_id','0')->where('original_message_id',$reference)->first();
                        $Original_message = Message::where('original_message_id',$original_message_id)->first();
                        $Original_message->message_status='unread';
                        $Original_message->inbox='1';
                       // $Original_message->last_incoming_on = date('Y-m-d H:i:s');
                        
                        
                        $Original_message->last_incoming_on = $date_obj->format('Y-m-d H:i:s');
                        
                        
                        $Original_message->subject=Input::get('Subject');

                        $Original_message->action_taken = '0';
                        $Original_message->action = '';
                       
                        $Original_message->save();

                        $parent_id = $Original_message->message_id;
                      

                        
//                         if(Input::get('body-html')!='')
//                        {
//                            $crawler = new Crawler(Input::get('body-html'));
//                            $crawler = $crawler->filter('body')->eq(0);
//                            $scrapped_html =  iconv('UTF-8','iso-8859-1//TRANSLIT',$crawler->html());
//                            $scrapped_html =  str_replace('?', '', $scrapped_html);
//                        }
//                       else
//                       {
//                           $scrapped_html = nl2br(Input::get('body-plain'));
//                       }
                        
                        // WE ARE USING stripped-html FOR REPLIES BECAUSE WE DO NOT WANT
                        // TO SEE PREVIOUS MESSAGES WITH THE MESSAGE
                        $scrapped_html = Input::get('stripped-html'); 

                        
                        
                        $message = new Message();
                        $message->parent_id = $parent_id;
                     
                        $message->content = $scrapped_html;
                        $message->subject = Input::get('subject');
                        $message->mode = 'incoming';
                        $message->inbox = '1';
                        $message->incoming_type = 'reply_received';

                        

                        $message->occurred_on = $date_obj->format('Y-m-d H:i:s');
                        
                        $message->sender_name = $from_name;
                        $message->sender_email = Input::get('sender');
                        
                       $message->recipient_name = "Office";
                        $message->recipient_email = Config::get('mailgun::reply_to');
                        
                        if(Input::get('Cc')){
                            $pattern = "/([A-Za-z0-9\.\-\_]+)\@([A-Za-z0-9\.\-\_]+)(\.[A-Za-z]{2,5})/";
                            preg_match_all($pattern,Input::get('Cc'),$emails);
                            $new_emails = array_unique($emails[0]);
                        
                            $message->recipient_cc = $new_emails[0];     

                        }
                        
                        
                        $message->message_status = 'unread';
                        $message->references = Input::get('References');
                        $message->original_message_id = $original_message_id;
                        $message->reply_message_id = trim(Input::get('Message-Id'),'><');
                        $message->save();


                        if(Input::get('attachments')!='')
                        {
                            $attachments = json_decode(Input::get('attachments'));

                            foreach($attachments as $a)
                            {
                                $attachment = new Attachment();
                                $attachment->attachment = $a->url;
                                $attachment->attachment_name = $a->name;
                                // $attachment->attachment_mime = $a->{content-type};
                                $attachment->attachment_size = $a->size;
                                $attachment->message_id = $message->message_id;
                                $attachment->save();

                            }
                        }
                    }
                    else
                    {
                        //forwarded messages
                        //
                        //
//                        $pattern = "/([A-Za-z0-9\.\-\_]+)\@([A-Za-z0-9\.\-\_]+)(\.[A-Za-z]{2,5})/";
//                        preg_match_all($pattern,Input::get('body-plain'),$emails);
//                        $sender_email = $emails[0][0];
                        
                        /*if(Input::get('body-html')!='')
                        {
                            $crawler = new Crawler(Input::get('body-html'));
                            $crawler = $crawler->filter('body')->eq(0);
                            $scrapped_html =  iconv('UTF-8','iso-8859-1//TRANSLIT',$crawler->html());
                            $scrapped_html =  str_replace('?', '', $scrapped_html);
                        }
                       else
                       {
                           $scrapped_html = nl2br(Input::get('body-plain'));
                       }*/

                       // $scrapped_html = strip_tags(Input::get('body-html'), '&nbsp;\r\n\t'); 
                       // $scrapped_html =  nl2br($scrapped_html);

                        $scrapped_html = (Input::get('body-html')!='')?Input::get('body-html'):nl2br(Input::get('body-plain')); 
                        
                        
                        $parent_id = '0';
                       
                        $message_id = trim(Input::get('Message-Id'),'><');

                        Log::info('Log message',  array('context' => "Forwarded Message".$message_id));

                        $message = new Message();
                        $message->parent_id = $parent_id;
                      

                        $message->subject = Input::get('subject');
                        //$message->content = Input::get('stripped-html');
                         $message->content = $scrapped_html;
                        
                       
                        $message->mode = 'incoming';
                        $message->inbox = '1';
                        $message->incoming_type = 'new';

                      

                        $message->occurred_on = $date_obj->format('Y-m-d H:i:s');
                        $message->sender_name = '';
                        $message->sender_email = Input::get('sender');
                        
                        $message->recipient_name = "Office";
                        $message->recipient_email = Config::get('mailgun::reply_to');
                        
                        if(Input::get('Cc')){
                            $pattern = "/([A-Za-z0-9\.\-\_]+)\@([A-Za-z0-9\.\-\_]+)(\.[A-Za-z]{2,5})/";
                            preg_match_all($pattern,Input::get('Cc'),$emails);
                            $new_emails = array_unique($emails[0]);
                            
                            $message->recipient_cc = $new_emails[0];     

                        }
                        
                        
                        $message->message_status = 'unread';

                        $message->original_message_id = trim(Input::get('Message-Id'),'><');
                        $message->reply_message_id = trim(Input::get('Message-Id'),'><');
                         $message->last_incoming_on = date('Y-m-d H:i:s');

                        $message->save();

                        if(Input::get('attachments')!='')
                        {

                            $attachments = json_decode(Input::get('attachments'));

                            foreach($attachments as $a)
                            {
                                $attachment = new Attachment();
                                $attachment->attachment = $a->url;
                                $attachment->attachment_name = $a->name;
                           // $attachment->attachment_mime = $a->{content-type};
                                $attachment->attachment_size = $a->size;
                                $attachment->message_id = $message->message_id;
                                $attachment->save();

                            }
                        }

                        
                    }
                    
                }
                else{
                    // new messages
                    $parent_id = '0';
                 
                    $message_id = trim(Input::get('Message-Id'),'><');
                    
                    Log::info('Log message',  array('context' => $message_id));
                    
                    $scrapped_html = (Input::get('body-html')!='')?Input::get('body-html'):nl2br(Input::get('body-plain')); 

                    
                    $message = new Message();
                    $message->parent_id = $parent_id;
                  
                   
                    $message->subject = Input::get('subject','[No Subject]');
                    //$message->content = Input::get('stripped-html');
                    //$message->content = Input::get('stripped-text');
                    $message->content = $scrapped_html;
                    $message->mode = 'incoming';
                    $message->inbox = '1';
                    $message->incoming_type = 'new';

                    //$date = new DateTime(Input::get('Date'));

                    $message->occurred_on = $date_obj->format('Y-m-d H:i:s');
                    $message->sender_name = $from_name;
                    $message->sender_email = Input::get('sender');
                    
                    $message->recipient_name = "Office";
                    $message->recipient_email = Config::get('mailgun::reply_to');
                    
                    if(Input::get('Cc')){
                            $pattern = "/([A-Za-z0-9\.\-\_]+)\@([A-Za-z0-9\.\-\_]+)(\.[A-Za-z]{2,5})/";
                            preg_match_all($pattern,Input::get('Cc'),$emails);
                            $new_emails = array_unique($emails[0]);
                            
                            $message->recipient_cc = $new_emails[0];     

                        }
                        
                    
                    $message->message_status = 'unread';
                    
                    $message->original_message_id = trim(Input::get('Message-Id'),'><');
                    $message->reply_message_id = trim(Input::get('Message-Id'),'><');
                    $message->last_incoming_on = date('Y-m-d H:i:s');

                    $message->save();
                    
                    if(Input::get('attachments')!='')
                    {
                        
                        $attachments = json_decode(Input::get('attachments'));
                   
                        foreach($attachments as $a)
                        {
                            $attachment = new Attachment();
                            $attachment->attachment = $a->url;
                            $attachment->attachment_name = $a->name;
                       // $attachment->attachment_mime = $a->{content-type};
                            $attachment->attachment_size = $a->size;
                            $attachment->message_id = $message->message_id;
                            $attachment->save();

                        }
                    }
                   
                   
                }
                
                
            $post = json_encode($_POST);
            $message_post = new MessagePost();
            $message_post->message_post = $post;
            $message_post->message_id  = $message->message_id;
            $message_post->save();
            
            Log::info('Log message',  array('post text' => $post ));
            
                    
                Log::info('Log message',  array('context' => 'Test Message'));
            }
            else {
                Log::info('Log message',  array('context' => 'Message_not Send,==='.Input::get('In-Reply-To')));
            }
            
        }

        public function getMessagePost($id)
        {
           echo $id;
            $message_post = MessagePost::find($id);
            //echo $message_post->message_post;
           
        //   ZnUtilities::pa(unserialize($message_post->message_post));
           $data = json_decode($message_post->message_post);
           ZnUtilities::pa($data); 
           
           ZnUtilities::pa(json_decode($message_post->message_post));
            
           
           if(isset($data->attachments)){
                $attachments = json_decode($data->attachments);
                ZnUtilities::pa($attachments);
           }

        }

       
        
        public function downloadAttachment($attachment_id)
        {
             $attachment = Attachment::find($attachment_id);
              //  ZnUtilities::pa($project);
             
             if(strpos($attachment->attachment, "http")!==false)
             {
                 $file = str_replace('https://', "https://api:".Config::get('mailgun::api_key')."@", $attachment->attachment);

                 return Redirect::to($file);

             }
             else{
                     $file= public_path().'/attachments/'.$attachment->attachment;
                     $headers = array(
                              'Content-Type: application/octet-stream',
                            );

                     return Response::download($file, $attachment->attachment_name, $headers);

                 }
                
        }
        
        
        public function messageActions()
        {
            $search = Input::get('search');
            $action = Input::get('action');
            
            if(($search!='')||($action!='')){
                 $keyword = Input::get('search');
                 $action = Input::get('action');
                 $mode = Input::get('mode');
                 
                 
                 $messages  = DB::table('messages as m1');
                 $messages->select('m1.*');
                 if($keyword!=''){
                   $messages->leftJoin('messages as m2','m1.message_id','=','m2.parent_id');
                   $messages->orWhere(function ($messages) use ($keyword,$mode) 
                             {
                                 $messages->where("m1.subject","like","%".$keyword."%")
                                         ->orwhere("m1.content","like","%".$keyword."%")
                                         ->orwhere("m2.subject","like","%".$keyword."%")
                                         ->orWhere("m2.content","like","%".$keyword."%");
                                  if($mode=='incoming')
                                  {
                                      $messages->orwhere('m1.sender_email',"like","%".$keyword."%");
                                      $messages->orwhere('m2.sender_email',"like","%".$keyword."%");
                                  }
                                  else
                                  {
                                      $messages->orwhere('m1.recipient_email',"like","%".$keyword."%");
                                      $messages->orwhere('m1.recipient_cc',"like","%".$keyword."%");
                                      
                                      $messages->orwhere('m2.recipient_email',"like","%".$keyword."%");
                                      $messages->orwhere('m2.recipient_cc',"like","%".$keyword."%");
                                  }
                                 
                                 
                             });
                 }

                 $messages->where('m1.parent_id','=','0');

                 if($action!='')
                    $messages->where('m1.action','=',$action); 
                 
                 if($mode=='incoming'){
                    $messages->where('m1.inbox','=','1'); 
                    $messages->orderBy('m1.last_incoming_on','desc'); 
                 }
                 else if($mode=='outgoing'){
                    $messages->where('m1.outbox','=','1'); 
                    $messages->orderBy('m1.last_sent_on','desc'); 
                 }

                    $messages->groupBy('m1.message_id');
                    

                
                $data['search'] = $search;
                $data['action'] = $action;
                $data['messages'] = $messages->paginate();
                $data['mode'] = $mode;
                
                $basicPageVariables = ZnUtilities::basicPageVariables("Messages","Search Results", "messages","1");
                $data = array_merge($data,$basicPageVariables);
                
                ZnUtilities::push_js_files('components/messages.js');

                $data['breadcrumbs']['Inbox'] = array("link"=>'/messages',"active"=>'1');
                $data['breadcrumbs']['Search Results'] = array("link"=>'/messages',"active"=>'0');

                $data['folder_id'] = 0;
                
                $data['submenus'] = $this->_submenus('messages');
                return View::make('admin.messages.all',$data);
                
                exit();
            }
            
            
            $cid = Input::get('cid');
            $bulk_action = Input::get('bulk_action');
            
            $move_to = Input::get('move_to');
            if($move_to!='')
                $bulk_action = 'move_to_folder';
                
            if($bulk_action!='')
            {
                switch($bulk_action)
                {
                    
                    case 'move_to_folder':
                    {
                        foreach($cid as $id)
                        {
                           //message 
                           Message::where('message_id','=',$id)->update(array('folder_id'=>$move_to)); 
                           //sub messages
                           Message::where('parent_id','=',$id)->update(array('folder_id'=>$move_to)); 
                        }
                        return Redirect::to($_SERVER['HTTP_REFERER'])->with('success_message', 'Message moved to selected folder');
                        break;
                    }
                    
                   
                    case 'delete':
                    {
                         if(!User::checkPermission(Auth::user()->user_group_id,'messages','delete'))
                        {
                            return Redirect::to('/permissionDenied');
                        }
                       

                        foreach($cid as $id)
                        {
                           $message = Message::find($id); 
                           $message->delete();
                           
                           $sub_message = Message::where('parent_id',$id)->get(); 
                           if($sub_message->count()>0)
                           {
                               foreach($sub_message as $m)
                               {
                                   $m->delete();
                               }
                               
                           }
                          
                        }
                        
                            return Redirect::to('/messages/')->with('success_message', 'Rows Delete!');
                        
                        break;
                    }
                    case 'read':
                    {
                        foreach($cid as $id)
                        {
                           $message = Message::where('message_id','=',$id)->update(array('message_status'=>'read')); 
                          // $sub_message = Message::where('parent_id',$id)->update(array('message_status'=>'read')); 
                        }
                        return Redirect::to('/messages/')->with('success_message', 'Rows Updated!');
                        
                        break;
                    }
                    case 'unread':
                    {
                        foreach($cid as $id)
                        {
                           $message = Message::where('message_id','=',$id)->update(array('message_status'=>'unread')); 
                         //  $sub_message = Message::where('parent_id',$id)->update(array('message_status'=>'read')); 
                        }
                        return Redirect::to('/messages/')->with('success_message', 'Rows Updated!');
                        
                        break;
                    }
                    
                    case 'actioned':
                    {
                        foreach($cid as $id)
                        {
                           $message = Message::where('message_id','=',$id)->update(array('action_taken'=>'1')); 
                          // $sub_message = Message::where('parent_id',$id)->update(array('message_status'=>'read')); 
                        }
                        return Redirect::to('/messages/')->with('success_message', 'Rows Updated!');
                        
                        break;
                    }
                    case 'unactioned':
                    {
                        foreach($cid as $id)
                        {
                           $message = Message::where('message_id','=',$id)->update(array('action_taken'=>'0')); 
                         //  $sub_message = Message::where('parent_id',$id)->update(array('message_status'=>'read')); 
                        }
                        return Redirect::to('/messages/')->with('success_message', 'Rows Updated!');
                        
                        break;
                    }
                    
                    
                    
                    case 'viewUnread':
                    {
                        $folder =  Input::get('folder_id'); 
                        
                        
                        return Redirect::to('messages/folder/'.$folder.'/unread')->with('success_message', 'Showing unread messages');
                        
                        break;
                    }
                    case 'viewActioned':
                    {
                        $folder =  Input::get('folder_id'); 
                        return Redirect::to('messages/folder/'.$folder.'/-1/actioned')->with('success_message', 'Showing actioned messages');
                        
                        break;
                    }
                    case 'viewUnactioned':
                    {
                        $folder =  Input::get('folder_id'); 
                        return Redirect::to('messages/folder/'.$folder.'/-1/unactioned')->with('success_message', 'Showing unactioned messages');
                        break;
                    }
                } //end switch
            } // end if statement
            
        }
        
        public function search()
        {
            
          //  ini_set('max_execution_time', '1300');
            
            
             $keyword = Input::get('search');
             $action = Input::get('action');
             
              $messages  = DB::table('messages as m1');
              
              if($keyword!=''){
                $messages->leftJoin('messages as m2','m1.message_id','=','m2.parent_id');
                $messages->orWhere(function ($messages) use ($keyword) 
                          {
                              $messages->where("m1.subject","like","%".$keyword."%")
                                      ->orwhere("m1.content","like","%".$keyword."%")
                                      ->orwhere("m2.subject","like","%".$keyword."%")
                                      ->orWhere("m2.content","like","%".$keyword."%");
                          });
              }
              
              $messages->where('m1.parent_id','=','0');

              if($action!='')
                 $messages->where('m1.action','=',$action); 
               
               
               
               $messages->groupBy('m1.message_id');
               

               $data = array();

//               $data['all_messages'] = $messages->get();
//               
//               echo ZnUtilities::lastQuery();         
             
               
               

                return $messages->paginate();

                
                
               
        }
        
        
     
        
        public function newEmail()
        {
             if(!Auth::check())
                {
                    return Redirect::to('login')->with('error_message',"You are either not logged in or you dont have permissions to access this page");
                }
            
		$data = array();

                $data['mode'] = 'new';
                
                // Email Templates Section Starts
            
                $favorite_templates_array = array();
                $favorite_templates = FavoriteTemplate::where('user_id',Auth::user()->id)->first(); 

                if($favorite_templates)
                    $favorite_templates_array = explode(',',$favorite_templates->favorite_templates);

            
                $email_template_favorite = array();
                if(count($favorite_templates_array)>0)
                {
                      $email_template_favorite = EmailTemplate::whereIn('email_template_id',$favorite_templates_array)->get();
                      $email_template = EmailTemplate::whereNotIn('email_template_id',$favorite_templates_array)->get();
                }
                else
                {
                       $email_template = EmailTemplate::all();
                }

                $data['email_templates_favorite'] = $email_template_favorite;
                $data['email_templates'] = $email_template;
                
                $content_hover = "$(function() {
                                   
                                      $('.needs_hover').contenthover({
                                           overlay_background:'#000',
                                           overlay_opacity:0.5
                                       });
                                });";
                 ZnUtilities::push_js($content_hover);
                
                // Template Section Ends

                $basicPageVariables = ZnUtilities::basicPageVariables("Messages","New Message", "messages","1");
                $data = array_merge($data,$basicPageVariables);
                
                ZnUtilities::push_js_files('components/messages.js');
                ZnUtilities::push_js_files('jquery.contenthover.min.js');
               
                $data['breadcrumbs']['Inbox'] = array("link"=>'/messages',"active"=>'1');
                $data['breadcrumbs']['New Message'] = array("link"=>'/new',"active"=>'1');

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
        
                
        ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
        $editor_js = '$(function() {
                       // $("#content").wysihtml5();
                       CKEDITOR.replace("content");
                       });';
        ZnUtilities::push_js($editor_js);
        
             
                $data['submenus'] = $this->_submenus('messages');
                return View::make('admin.messages.new',$data);
        }
        
       
     public function sendEmail()
        {
         
            
        $subject = Input::get('subject');
        $content = Input::get('content');
        $parent_id = Input::get('parent_id','0');
        
        $url = url();
       
        $subject = str_replace('{sender_name}', Auth::user()->name, $subject);
        $subject = str_replace('{website_link}', $url, $subject);
      
        $content = str_replace('{sender_name}', Auth::user()->name, $content);
        $content = str_replace('{website_link}', $url, $content);
      
        //ZnUtilities::pa($_POST);
        
        //Check if valid emails are entered
        Validator::extend('email_to', function($attribute, $value, $parameters)
        {
            $emails = explode(',',$value);

            foreach($emails as $email) {
                    if (filter_var(trim($email), FILTER_VALIDATE_EMAIL) === false) return false;
            }

            return true;
        });
        
        //Check for lead related variables 
      
        
        $validator = Validator::make(
                        array(
                          'subject' => Input::get('subject'),
                          'email_to' => Input::get('email_to'),
                          'email_cc' => Input::get('email_cc'),
                          'email_bcc' => Input::get('email_bcc'),
                          'content' => Input::get('content')
                        ), array(
                           'subject' => 'required',
                            'email_to' => 'required|email_to',
                            'email_cc' => 'email_to',
                            'email_bcc' => 'email_to',
                            'content' => 'required',
                        )
        );
        
        if ($validator->passes()){
            $from = Config::get('mailgun::from');
        
            //  echo $content;
            $message = new Message();

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
            $message->parent_id = $parent_id;

            $message->recipient_email = input::get('email_to');
            $message->recipient_cc = input::get('email_cc') ;
            $message->recipient_bcc = input::get('email_bcc') ;

            $message->message_status = 'read';
            $message->last_sent_on = date('Y-m-d H:i:s');

            $message->save();


            $attachments = Input::get('specs_location');



            if(is_array($attachments)){
                foreach($attachments as $a):
                    $attachment = new Attachment();
                    //$attachment->attachment = '/attachments/'.$a;
                    $attachment->attachment = $a;



                    $file_name_parts = explode('/attachments/',$attachment->attachment);
                    
                  
                    $file_name_parts2 = explode('-',$file_name_parts[0]);
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

            $response = Mailgun::send('emails.message', array('content' => $content), function($m) use ( $message) {

                         $attachments = Attachment::where('message_id',$message->message_id)->get();

                         foreach($attachments as $attachment){
                            if($attachment->attachment_id){
                                       $m->attach(public_path().'/attachments/'.$attachment->attachment,$attachment->attachment_name);
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
                        'for'=>'Email',
                        'id'=>$message->message_id,
                      );
            Event::fire('history.add', array($history_array));
            
            if(($parent_id!='0')&&($parent_id!=''))
                 return Redirect::to("/messages/".$parent_id."#message-".$message->message_id);

            return Redirect::to('/messages/' . $message->message_id);

            } 
            else{
               return Redirect::to('/messages/new')->withErrors($validator)->withInput();
            }
        
      //  die;
        
       
    }
    
   
     public function resend($id)
     {
       
         
        $m = Message::with('attachments')->find($id); 
//        ZnUtilities::pa($m);
//        die;
//        
        if($m->mode=='outgoing')
        {
            $subject = $m->subject;
            if($m->parent_id!='0'){
                $parent_message = Message::find($m->parent_id);
                $subject = $parent_message->subject;
            }
            
            
            $content = $m->content;
            $parent_id = $m->parent_id=='0'?$m->message_id:$m->parent_id;
          

           // die;
            
            $url = url();
            
            $from = Config::get('mailgun::from');
           
            //  echo $content;
            $message = new Message();

            $message->subject = $subject;
            $message->content = $content;
        
            $message->mode = "outgoing";
            $message->inbox = "0";
            $message->outbox = "1";
            $message->occurred_on = date('Y-m-d H:i:s');
            $message->sender_id = Auth::user()->id;
            $message->sender_email = $from['address'];
            $message->sender_name = Auth::user()->name;
            $message->parent_id = $parent_id;
            
            $message->recipient_email = $m->recipient_email;
            $message->recipient_cc = $m->recipient_cc;
            $message->recipient_bcc = $m->recipient_bcc;

            $message->message_status = 'read';
            
            $message->last_sent_on = date('Y-m-d H:i:s');

            if(isset($parent_message))
            {
                $parent_message->last_sent_on = date('Y-m-d H:i:s');
                $parent_message->save();
            }
            
            $message->save();


            
           
            
            if($m->attachments->count()>0){
                foreach($m->attachments as $a):
                    $attachment = new Attachment();
                    $attachment->attachment = $a->attachment;
                    $attachment->attachment_name = $a->attachment_name;
                    $attachment->message_id = $message->message_id;
                    $attachment->save();
                endforeach;
            }
            
             ini_set('max_execution_time','1600');

            $response = Mailgun::send('emails.message', array('content' => $content), function($m) use ( $message) {

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
                        'for'=>'Email',
                        'id'=>$message->message_id,
                      );
            Event::fire('history.add', array($history_array));
            
            if(($parent_id!='0')&&($parent_id!=''))
                 return Redirect::to("/messages/".$parent_id."#message-".$message->message_id);

            return Redirect::to('/messages/' . $message->message_id);


            
        }
        
      
        
       
      
        
        
          

         


            

    //        ZnUtilities::echoViewContent('emails.message', array('content' => $content));
    //        
    //       die;
           
           
        
      //  die;
        
       
    }
    
    function trackOpens()
    {
        $post = json_encode($_POST);
        $message_post = new MessagePost();
        $message_post->message_post = $post;
        //$message_post->message_id  = $message->message_id;
        $message_post->save();
    }
    

       
     public function forward($id)
     {
       
         
        $m = Message::with('attachments')->find($id); 
//        ZnUtilities::pa($m);
//        die;
//        
        
            $subject = $m->subject;
            if($m->parent_id!='0'){
                $parent_message = Message::find($m->parent_id);
                $subject = $parent_message->subject;
            }
            
            
            $content = $m->content;
            $parent_id = $m->parent_id=='0'?$m->message_id:$m->parent_id;
           

           // die;
            
            $url = url();
            
            $from = Config::get('mailgun::from');
            
            $recipient_email = Input::get('forward_email_to'); 
           
            //  echo $content;
            $message = new Message();

            $message->subject = $subject;
            $message->content = $content;
       
            $message->mode = "outgoing";
            $message->inbox = "0";
            $message->outbox = "1";
            $message->occurred_on = date('Y-m-d H:i:s');
            $message->sender_id = Auth::user()->id;
            $message->sender_email = $from['address'];
            $message->sender_name = Auth::user()->name;
            $message->parent_id = $parent_id;
            
            $message->recipient_email = $recipient_email;
            $message->recipient_cc = "";
            $message->recipient_bcc = "";

            $message->message_status = 'read';
            
            $message->last_sent_on = date('Y-m-d H:i:s');

            if(isset($parent_message))
            {
                $parent_message->last_sent_on = date('Y-m-d H:i:s');
                $parent_message->save();
            }
            
            $message->save();


            
           
            
            if($m->attachments->count()>0){
                foreach($m->attachments as $a):
                    $attachment = new Attachment();
                    $attachment->attachment = $a->attachment;
                    $attachment->attachment_name = $a->attachment_name;
                    $attachment->message_id = $message->message_id;
                    $attachment->save();
                endforeach;
            }
            
             ini_set('max_execution_time','1600');

            $response = Mailgun::send('emails.message', array('content' => $content), function($m) use ( $message) {

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
                        'for'=>'Email',
                        'id'=>$message->message_id,
                      );
            Event::fire('history.add', array($history_array));
            
            if(($parent_id!='0')&&($parent_id!=''))
                 return Redirect::to("/messages/".$parent_id."#message-".$message->message_id);

            return Redirect::to('/messages/' . $message->message_id);


            
        
        
       
    }
             private function _submenus($active)
    {   
        $data = array();
        
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
