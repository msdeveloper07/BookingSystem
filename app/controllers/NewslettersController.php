<?php

class NewslettersController extends BaseController {

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
    
	public function index()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'newsletter','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();

            $data['newsletter'] = Newsletter::with('mailinglist_name')->paginate(10);
           

            $basicPageVariables = ZnUtilities::basicPageVariables("Newsletters","All Newsletter", "newsletter","1");
            $data = array_merge($data,$basicPageVariables);
            
            
            $data['breadcrumbs']['All Newsletter'] = array("link"=>'/newsletters',"active"=>'0');
           
             $data['submenus'] = $this->_submenus('newsletter');
            ZnUtilities::push_js_files('components/newsletters.js');
            
          
            
			return View::make('admin.newsletters.list',$data);
	}
        
	public function create()
	{
           if(!User::checkPermission(Auth::user()->user_group_id,'newsletter','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
             $data = array();
                
                $data['mailinglist'] = MailingList::get();
                
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

                $basicPageVariables = ZnUtilities::basicPageVariables("Newsletters"," Add New Newsletter", "newsletter","1");
                $data = array_merge($data,$basicPageVariables);
                
                
                ZnUtilities::push_js_files("jquery.validate.min.js");
                ZnUtilities::push_js_files('components/newsletters.js');
                ZnUtilities::push_js_files('jquery.contenthover.min.js');
               
                $data['breadcrumbs']['News Letters'] = array("link"=>'/newsletter',"active"=>'1');
                $data['breadcrumbs']['New Newsletter'] = array("link"=>'/newsletter/create',"active"=>'1');

               
                ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
                $editor_js = '$(function() {
                       // $("#content").wysihtml5();
                       CKEDITOR.replace("content");
                       });';
                ZnUtilities::push_js($editor_js);
        
             
                 $data['submenus'] = $this->_submenus('newsletter');
                return View::make('admin.newsletters.create',$data);
        }
                
       
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
            if(!User::checkPermission(Auth::user()->user_group_id,'newsletter','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $validator = Validator::make(
                array(
                    'mailinglist_id' => Input::get('mailinglist_id'),
                    'content' => Input::get('content'),
                  
                   
                    ),
                array(
                    'mailinglist_id' => 'required',
                    'content' => 'required',
                  
                    )
            );
            
            if($validator->passes())
            {
               
            $content = Input::get('content');
            $mailinglist_id = Input::get('mailinglist_id');
            
         if($mailinglist_id)
           {
                
                $mailinglist_Subscriber = MailingListSubscriber::where('mailinglist_id',$mailinglist_id)->get();
            
            $mail_send = 0;
              foreach($mailinglist_Subscriber as $m)
                {
                    //$name  = $m->mailingList_subscriber_name;
                    $new_content = str_replace('{name}',$m->mailinglist_subscriber_name,$content);
                    
            Mailgun::send('emails.newsletter.newsletter',
                    array(
                        'content'=>$new_content,
                       
                    ),
                    
                    function($message) use ($m)
            {
              
                    $message->to($m->mailinglist_subscriber_email,$m->mailinglist_subscriber_name)
                                ->subject('Welcome');
                    
                }
              );
                  $mail_send++;  
            }  
            if($mail_send != 0)
            {
            
            $newsletter = new Newsletter();
            $newsletter->newsletter_status = 'Success';
            $newsletter->mailinglist_id = Input::get('mailinglist_id');
            $newsletter->processed_on = date('Y-m-d H:i:s');
            $newsletter->save();
                
             
                return Redirect::to('newsletters')->with('success_message', 'Newsletter created successfully!');
            }
           }
            return Redirect::to('newsletters/create')->with('error_message', 'Newsletter Not Send successfully!');
            }
            else
            {
                return Redirect::to('newsletters/create')->withErrors($validator)->withInput();;
            }
            
	}
        
         public function newsletterActions()
        {
             if(!User::checkPermission(Auth::user()->user_group_id,'newsletter','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $search = Input::get('search');
            if($search!='')
            {
                return Redirect::to('/newsletterSearch/'.$search);
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
                            DB::table('newsletters')
                                ->where('newsletter_id', $id)
                                ->delete();
                        }
                        
                        return Redirect::to('/newsletter/')->with('success_message', 'Rows Delete!');
                        break;
                    }
                } //end switch
            } // end if statement
            return Redirect::to('/newsletters');
            }
        }

	public function destroy($id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'newsletter','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
         
		$newsletter = Newsletter::find($id);
                
		$newsletter->delete();

		// redirect
		return Redirect::to('newsletters')->with('success_message', 'Newsletter deleted successfully!');
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
?>