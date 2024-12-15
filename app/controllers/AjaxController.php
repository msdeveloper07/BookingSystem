<?php

/* 
 * This Controller takes care of all ajax related calls that 
 * pulls data from database
 */

class AjaxController extends BaseController
{
    public function getCountiesByStateCode($state_code)
    {
        $counties = County::where('state_code','=',$state_code)->orderBy('county_name','asc')->get();
        echo $counties;
    }
    
         public function getItemInfo($id)
    {
       
             $data = User::where('id',$id)->with('images')->get()->toJson();
        // echo ZnUtilities::lastQuery();
        echo $data; 
    }
    
    public function getsupplierItemInfo($supplier_id=null)
    {
//        $data = SupplierItem::where('supplier_type_id',$supplier_id)->get()->toJson();
//        echo $data;
        
        $data_array = array();
        
        $supplier_types = SupplierType::with('items')->get()->toJson();
        
        echo $supplier_types;
        
        
    }
         public function getsupplierItemPriceInfo($supplier_item_id)
    {
        $data = Supplier::where('supplier_item_id',$supplier_item_id)->get()->toJson();
        echo $data;
    }
        
    
   
      public function getLocationInfo($location_id)
    {
        $data = Location::where('location_id','!=',$location_id)->get()->toJson();
        echo $data;
    }
    
    
     public function getMessageContent($message_id)
    {
       $message = Message::find($message_id)->toJson();
       echo $message; 
    }
    
    public function getTemplateContent($template_id)
    {
       $message = EmailTemplate::find($template_id)->toJson();
       echo $message; 
    }
    
    
    public function getMessageThread($id)
    {
        $message = Message::find($id);
        $message->message_status = 'read';
        $message->save();

        ZnUtilities::push_js_files('chosen.jquery.min.js');
          $choosen_js = '$(function() {
                $("#lead_id").chosen({width:"95%"});
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
        
        return View::make('admin.messages.show_ajax',$data);
    }
    
    public function removeImageFromGallery($package_image_id)
    {
        $image = PackageImage::find($package_image_id);
        $public_path =  public_path();
        //echo $public_path."/packageimages/".$image->attachment;
        unlink($public_path."\\packageimage\\".$image->attachment);
        $image->delete();
        
        return;
    }
    
    public function getsupplierSubTypesInfo()
    {
           $data = array();
        
        $data = SupplierType::get()->toJson();
        
        echo $data;
       
    }
    
      public function getsupplierSubTypeInfo($supplier_type_id)
    {
          $data = array();
        $data = SupplierType::where('supplier_type_parent_id',$supplier_type_id)->get()->toJson();
        echo $data;
    }
    
     public function getsupplierInfo($supplier_sub_type_id)
    {
          $data = array();
        $data = Supplier::where('supplier_sub_type_id',$supplier_sub_type_id)->get()->toJson();
        echo $data;
    }
     public function getsupplierTypeItems($package_id , $supplier_type_id)
    {
          $data = array();
          
        $package_supplier_id = PackageSupplier::select('supplier_type_id')->where('package_id',$package_id)->where('supplier_type_parent_id',$supplier_type_id);
        foreach($package_supplier_id as $p){
        $data[] = SupplierItem::where('supplier_id',$p->package_supplier_id)->with('supplierItems')->get();
        }
      echo $data;
      
    }
    
    public function getsupplierTypeSuppliers($package_id , $supplier_type_id)
    {
          $data = array();
          
        $data = PackageSupplier::where('package_id',$package_id)->where('supplier_type_parent_id',$supplier_type_id)->with('supplierInfo')->get()->toJson();
     
      echo $data;
      
    }
    
    
      public function getsupplierItems($package_id , $supplier_id)
    {
          $data = array();
          
          $data = SupplierItem::where('supplier_id',$supplier_id)->with('supplierItems')->get();
   
         echo $data;
      
    }
    
public function getsupplierTypeQuoteSuppliers($quote_id , $supplier_type_id)
    {
          $data = array();
          
        $data = QuoteSupplier::where('quote_id',$quote_id)->where('supplier_type_parent_id',$supplier_type_id)->with('supplierInfo')->get()->toJson();
     
      echo $data;
      
    }
    
    public function getsupplierTypeBookingSuppliers($booking_id , $supplier_type_id)
    {
          $data = array();
          
        $data = BookingSupplier::where('booking_id',$booking_id)->where('supplier_type_parent_id',$supplier_type_id)->with('supplierInfo')->get()->toJson();
     
      echo $data;
      
    }
    public function manageProfile()
    {
        $user_id = Input::get('user_id');     
        $img = Input::get('img');
   
        
        $user = Upload::find($user_id);
        $user->file_name = $img;
        
        $user->save();
    }
    public function contacts()
    {
        $data = array();
        
$url = 'http://leadsdev.bookings-gateway.com/api';
       
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); $output = curl_exec($ch);

$output = utf8_decode($output);
ZnUtilities::pa($output);  die();
$json_array = json_decode($output);
curl_close($ch);
$contac =  $json_array->data;

$data = $contac::Where("contact_first_name", "like", "%" . $search . "%");

     echo $data;  
     
    }
    
    
     public function getSubType()
    {
          $data = array();
          
        $data = SupplierType::where('supplier_type_parent_id','!=','0')->get()->toJson();
     
      echo $data;
      
    }
     public function getSubTypeInfo($supplier_type_id)
    {
          $data = array();
          
          $parent_id = SupplierType::where('supplier_type_id',$supplier_type_id)->pluck('supplier_type_parent_id');
          
         
        $data = SupplierType::where('supplier_type_parent_id',$parent_id)->get()->toJson();
     
      echo $data;
      
    }
    
    
}
