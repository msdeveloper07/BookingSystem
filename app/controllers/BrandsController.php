<?php

class BrandsController extends BaseController {

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
            if(!User::checkPermission(Auth::user()->user_group_id,'brands','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();

            $data['brands'] = Brand::orderBy('brand_id','desc')->paginate(20);
           

            $basicPageVariables = ZnUtilities::basicPageVariables("Brands"," All Brands", "brands","1");
            $data = array_merge($data,$basicPageVariables);
            
            
            $data['breadcrumbs']['Brands'] = array("link"=>'/brands',"active"=>'0');
           
            ZnUtilities::push_js_files('components/brands.js');
            
             $data['active_nav'] = 'brands';
             
                          $data['submenus'] = $this->_submenus('brands');
			return View::make('admin.brands.list',$data);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'brands','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            
            ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/brands.js');
        ZnUtilities::push_js_files('jquery.contenthover.min.js');
        ZnUtilities::push_js_files('pekeUpload.js');
        $upload_js = '
            jQuery(document).ready(function(){
                $("#upload-button").pekeUpload({
                theme:"bootstrap", 
                url:"/brandimage-upload.php", 
                allowed_number_of_uploads:1,
                allowedExtensions:"jpg|jpeg|gif|png",
                onFileSuccess:function(file,response){
                    var data = JSON.parse(response)
                    var file_extension = data.raw_name.substr((~-data.raw_name.lastIndexOf(".") >>> 0) + 2);
                    $("#file_holder").show();
                    $("#prev_upload tbody").append(\'<tr id="\'+data.file_id+\'" class="document_files"><td data-title="File Type"><img class="property_image" src="\'+data.logo_image+\'" height="22px"/><span class="file-type"> \'+file_extension+\'</span> </td><td data-title="Filename">\'+data.raw_name+\'</td><td data-title="Remove File"><a class="remove" href="javascript:void(0);" onclick=javascript:remove_div("\'+data.file_id+\'"); ><span class="glyphicon glyphicon glyphicon-remove"></span> Remove</a><input type="hidden" name="specs_location[]" value="\'+data.file_name+\'" /></td></tr> \');
                    $(".file").remove();
                    $("#prev_upload table").show();
                    
                    var numItems = $(".property_image").length ; 
    
                    if(numItems==1)
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
                       CKEDITOR.replace("brand_desc");
                       });';
        ZnUtilities::push_js($editor_js);
            
		$data = array();
                
                
                
                $basicPageVariables = ZnUtilities::basicPageVariables("Brands"," Add New Brand", "brands","1");
                $data = array_merge($data,$basicPageVariables);
                
                $data['breadcrumbs']['All Brands'] = array("link"=>'/brands',"active"=>'0');
                $data['breadcrumbs']['New Brand'] = array("link"=>"","active"=>'1'); 
            
                  $data['submenus'] = $this->_submenus('brands');
               return View::make('admin.brands.create',$data);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'brands','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
           // ZnUtilities::pa($_POST);
            
//            
           // die;
            $validator = Validator::make(
                array(
                    'brand_title' => Input::get('brand_title'),
                    'brand_desc' => Input::get('brand_desc'),
                    
                    ),
                array(
                    'brand_title' => 'required',
                    'brand_desc' => 'required',
                 
                   
                    )
            );
            
            if($validator->passes())
            {
                $activation_code= ZnUtilities::random_string('alphanumeric','50');
                 
                
                
                $brands = new Brand();
                $brands->brand_title = Input::get('brand_title');
                $brands->description = Input::get('brand_desc');
               
                //$brands->brands_status  = Input::get('brands_status');
                
                  $attachments = Input::get('specs_location');
            if (is_array($attachments)) {
                foreach ($attachments as $a):
                    $brands->logo = $a;
                    
                endforeach;
            }
                
              
                $brands->save();
                
            
                
                return Redirect::to('brands/settings/'.$brands->brand_id)->with('success_message', 'FAQ created successfully!');
            }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('brands/create')->withErrors($validator)->withInput();;
            }
            
	}

        public function edit($id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'brands','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            
               ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/brands.js');
        ZnUtilities::push_js_files('jquery.contenthover.min.js');
        ZnUtilities::push_js_files('pekeUpload.js');
        $upload_js = '
            jQuery(document).ready(function(){
                $("#upload-button").pekeUpload({
                theme:"bootstrap", 
                url:"/brandimage-upload.php", 
                allowed_number_of_uploads:1,
                allowedExtensions:"jpg|jpeg|gif|png",
                onFileSuccess:function(file,response){
                    var data = JSON.parse(response)
                    var file_extension = data.raw_name.substr((~-data.raw_name.lastIndexOf(".") >>> 0) + 2);
                    $("#file_holder").show();
                    $("#prev_upload tbody").append(\'<tr id="\'+data.file_id+\'" class="document_files"><td data-title="File Type"><img class="property_image" src="\'+data.logo_image+\'" height="22px"/><span class="file-type"> \'+file_extension+\'</span> </td><td data-title="Filename">\'+data.raw_name+\'</td><td data-title="Remove File"><a class="remove" href="javascript:void(0);" onclick=javascript:remove_div("\'+data.file_id+\'"); ><span class="glyphicon glyphicon glyphicon-remove"></span> Remove</a><input type="hidden" name="specs_location[]" value="\'+data.file_name+\'" /></td></tr> \');
                    $(".file").remove();
                    $("#prev_upload table").show();
                    
                    var numItems = $(".property_image").length ; 
    
                    if(numItems==1)
                    {
                        $("#upload-doc-div").hide();
                        $("#brand-image").hide();
                        
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
                      $("#brand-image").show();
                }
                       
           }

            ';
        ZnUtilities::push_js($upload_js);
                            
        ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
        $editor_js = '$(function() {
                       // $("#content").wysihtml5();
                       CKEDITOR.replace("brand_desc");
                       });';
        ZnUtilities::push_js($editor_js);
            

                     $data = array();

                //Throw exception if project id does not exists
                $data['brands'] = Brand::findOrFail($id);
               
                
                $basicPageVariables = ZnUtilities::basicPageVariables("Brands","Edit Brand", "brands","1");
                $data = array_merge($data,$basicPageVariables);
                
                 $data['breadcrumbs']['All Brands'] = array("link"=>'/brands',"active"=>'0');
                 $data['breadcrumbs']['Edit Brands'] = array("link"=>"","active"=>'1'); 
               
                   $data['submenus'] = $this->_submenus('brands');
                return View::make('admin.brands.edit',$data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
            if(!User::checkPermission(Auth::user()->user_group_id,'brands','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            //skip email's unique validation if email is not changed
         
          
                  $validator = Validator::make(
                array(
                    'brand_title' => Input::get('brand_title'),
                    'brand_desc' => Input::get('brand_desc'),
                    
                    ),
                array(
                    'brand_title' => 'required',
                    'brand_desc' => 'required',
                 
                   
                    )
            );
            
            if($validator->passes())
            {
                $activation_code= ZnUtilities::random_string('alphanumeric','50');
                 
                
                
                  $brands = Brand::find($id);
                $brands->brand_title = Input::get('brand_title');
                $brands->description = Input::get('brand_desc');
               
                //$brands->brands_status  = Input::get('brands_status');
                
                  $attachments = Input::get('specs_location');
                  if($attachments)
                  {
            if (is_array($attachments)) {
                foreach ($attachments as $a):
                    $brands->logo = $a;
                    
                endforeach;
            }
                  }
                  
                  else
                  {
                      
                  }
                
              
                $brands->save();
                
            
              
                return Redirect::to('brands')->with('success_message', 'Brand Updated Successfully');
            }
            else
            {
                //$messages = $validator->messages();
                return Redirect::to('brands/'.$id.'/edit/')->withErrors($validator)->withInput();
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
             if(!User::checkPermission(Auth::user()->user_group_id,'brands','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            // delete
		$brands = Brand::find($id);
		$brands->delete();

		// redirect
		return Redirect::to('brands')->with('success_message', 'FAQ deleted successfully!');
	}

        
      
        
       
       public function brandSearch($search)
        {
            if(!User::checkPermission(Auth::user()->user_group_id,'brands','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
           
               
               $brands = Brand::where("brand_title","like","%".$search."%")
                       
                           
                            ->paginate(); 
               
               $data = array();
               $data['brands'] = $brands;
                //Basic Page Settings
               
                $basicPageVariables = ZnUtilities::basicPageVariables("Brands","Search Results", "brands","1");
                $data = array_merge($data,$basicPageVariables);
               
                $data['breadcrumbs']['All Brands'] = array("link"=>'/brands',"active"=>'0');
               $data['breadcrumbs']['Search'] = array("link"=>"","active"=>'1'); 
               
                $data['search'] = $search;
                
                  $data['submenus'] = $this->_submenus('brands');
                return View::make('admin.brands.list',$data);
               
           
          
        }
        
        
        
        public function brandActions()
        {
             if(!User::checkPermission(Auth::user()->user_group_id,'brands','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $search = Input::get('search');
            if($search!='')
            {
                return Redirect::to('/brandSearch/'.$search);
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
                            DB::table('brands')
                                ->where('brand_id', $id)
                                ->delete();
                        }
                        
                        return Redirect::to('/brands/')->with('success_message', 'Rows Delete!');
                        break;
                    }
                } //end switch
            } // end if statement
            return Redirect::to('/brands');
            }
        }

        
        public function settings($brand_id)
	{
            // Check if user has permission to access this page
            if(!User::checkPermission(Auth::user()->user_group_id,'settings','manage'))
            {
                 return Redirect::to('admin/permissionDenied');
            };
            
            $data = array();
            $data['brand_settings'] = BrandSetting::where('brand_id',$brand_id)->with('variable_name')->paginate();
            $data['brand_variable'] = BrandVariable::orderBy('brand_variable_id','desc')->get();
            $data['brands'] = Brand::find($brand_id);
            
        
            
            
            $basicPageVariables = ZnUtilities::basicPageVariables("Brands"," All Settings", "settings","1");
             $data = array_merge($data,$basicPageVariables);
            
            $data['breadcrumbs']['All Settings'] = array("link"=>'/brandsettings',"active"=>'1');
            
                  ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/brands.js');
        ZnUtilities::push_js_files('jquery.contenthover.min.js');
            
             ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
        $editor_js = '$(function() {
                       // $("#content").wysihtml5();
                       CKEDITOR.replace("#.ckeditor");
                       });';
        ZnUtilities::push_js($editor_js);
           
            // Load User Specific Javascripts
           //   ZnUtilities::push_js_files('components/settings.js');
            
             $data['submenus'] = $this->_submenus('index');
            return View::make('admin.brands.settings',$data);
	}
        
        public function saveSettings($brand_id)
        {
            // Check if user has permission to access this page
            if(!User::checkPermission(Auth::user()->user_group_id,'settings','manage'))
            {
                 return Redirect::to('admin/permissionDenied');
            };
         
           
            
             $validator = Validator::make(
                array(
                    'brand_variable' => Input::get('brand_variable'),
                    'setting_value' => Input::get('setting_value'),
                   
                    ),
                array(
                    'brand_variable' => 'required',
                    'setting_value' => 'required',
                 
                    )
            );
             
             if($validator->passes())
            {
                $setting = new BrandSetting();
                $setting->brand_variable_id = Input::get('brand_variable');
                $setting->values = Input::get('setting_value');
                $setting->brand_id = $brand_id;
                $setting->save();
                 
                return Redirect::to('brands/settings/'.$brand_id)->with('success_message', 'Brand Setting saved successfully!');
             }
             else
             {
                return Redirect::to('brands/settings/'.$brand_id)->withErrors($validator)->withInput();
             }
        }
        
        public function updateSettings($brand_id)
        {
            // Check if user has permission to access this page
            if(!User::checkPermission(Auth::user()->user_group_id,'settings','manage'))
            {
                 return Redirect::to('admin/permissionDenied');
            };
            
             $validator = Validator::make(
                array(
                    'setting_value' => Input::get('setting_value'),
                    ),
                array(
                    'setting_value' => 'required',
                    )
            );
             
         
             
             if($validator->passes())
            {
                foreach(Input::get('setting_value') as $key=>$s)
                {
                    $setting = BrandSetting::find($key);
                    $setting->values = $s[0];
                    $setting->save();
                }
               
                 
                return Redirect::to($_SERVER['HTTP_REFERER'])->with('success_message', 'Setting updated successfully!');
             }
             else
             {
                return Redirect::to($_SERVER['HTTP_REFERER'])->withErrors($validator)->withInput();
             }
        }
        
   
    
      private function _submenus($active)
    {   
        $data = array();
        
     
        
        
        if(User::checkPermission(Auth::user()->user_group_id,'brands','manage'))
        $data['Brands'] = array("link" => '/brands', "active" => $active=='brands'?'1':'0' ,"icon" => 'fa-bold');
        
        
        return $data;
    }
}
