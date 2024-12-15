<?php

class PackagesController extends BaseController {
    public $submenu = array();
    
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
        if (!User::checkPermission(Auth::user()->user_group_id, 'package', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $data = array();

        $data['package'] = Package::with('loc_from', 'loc_to', 'packageImages')->orderBy('package_id','DESC')->paginate(20);


        $basicPageVariables = ZnUtilities::basicPageVariables("Packages", "All Packages", "package", "1");
        $data = array_merge($data, $basicPageVariables);


        

        ZnUtilities::push_js_files('components/packages.js');

        $data['submenus'] = $this->_submenus('index');

        return View::make('admin.packages.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'package', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $data = array();

        ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/packages.js');
        ZnUtilities::push_js_files('jquery.contenthover.min.js');
        ZnUtilities::push_js_files('pekeUpload.js');
        $upload_js = '
            jQuery(document).ready(function(){
                $("#upload-button").pekeUpload({
                theme:"bootstrap", 
                url:"/packageimage-upload.php", 
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
                      CKEDITOR.replace("description");
                      });';
               ZnUtilities::push_js($editor_js);
        //
$url = 'http://leadsdev.bookings-gateway.com/api';
        $ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); $output = curl_exec($ch);

$output = utf8_decode($output);
//echo "<pre>" ;

$json_array = json_decode($output);

///echo json_last_error();
        


//print_r($json_array);
//echo "</pre>" ;

curl_close($ch);


$data['contacts'] =  $json_array;

        $basicPageVariables = ZnUtilities::basicPageVariables("Packages", "Add New Package", "add_package", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Packages'] = array("link" => '/package', "active" => '0');
        $data['breadcrumbs']['Add New Packages'] = array("link" => "", "active" => '1');

        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.packages.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'package', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        // $date_to = Input::get('date_to');
        //ZnUtilities::pa($date_to); die();
        $validator = Validator::make(
                        array(
                    'package_name' => Input::get('package_name'),
                    'location_to' => Input::get('location_to'),
                    'location_from' => Input::get('location_from'),
                    'date_from' => Input::get('date_from'),
                  //  'date_to' => Input::get('date_to'),
                   'adult_cost' => Input::get('adult_cost'),
                   'child_cost' => Input::get('child_cost'),
                    'total_cost' => Input::get('total_cost'),
                    'number_of_adults' => Input::get('number_of_adults'),
                    'number_of_children' => Input::get('number_of_children'),
                        ), array(
                    'package_name' => 'required',
                    'location_to' => 'required',
                    'location_from' => 'required',
                 //   'date_to' => 'required',
                    'date_from' => 'required',
                   // 'adult_cost' => 'required',
//                    'number_of_adults' => 'required',
//                    'number_of_children' => 'required',
                        )
        );
         $adult_cost =input::get('adult_cost');
        $child_cost =input::get('child_cost');
        $number_of_adults = input::get('number_of_adults');
        $number_of_children = input::get('number_of_children');
        if($adult_cost!='' or $adult_cost!='0'){
            $total_adult_cost = $adult_cost * $number_of_adults;
        }
        if($child_cost!='' or $child_cost!='0'){
            $total_child_cost = $child_cost * $number_of_children;
        }
        $total_cost = $total_child_cost + $total_adult_cost ; 
        
        if ($validator->passes()) {
          
            
            $days=input::get('number_of_days');
                  $date = new DateTime(input::get('date_from'));
        $date->add(new DateInterval('P' . $days . 'D'));
        $date_to = $date->format('Y-m-d');  
                   


            $package = new Package();
            $package->package_name = input::get('package_name');
            $package->location_to = input::get('location_to');
            $package->location_from = input::get('location_from');
            $package->number_of_days = input::get('number_of_days');
            $package->date_to = $date_to;
            $package->date_from = ZnUtilities::format_date(input::get('date_from'),'1');
//            if(Input::get('adult_cost')=='')
//                       { 
//                       $package->adult_cost = '0';
//                   }
//                   else{
//                      $package->adult_cost = input::get('adult_cost');
//                   }
//                   if(Input::get('child_cost')=='')
//                       { 
//                       $package->child_cost ='0';
//                   }
//                   else{
//                     $package->child_cost = input::get('child_cost');
//                   }
//                   
//           
//            $package->total_cost = $total_cost;
            $package->number_of_children = input::get('number_of_children');
            $package->number_of_adults = input::get('number_of_adults');
            $package->package_currency = input::get('package_currency');
            $package->package_desc = input::get('description');

            $package->save();



            $attachments = Input::get('specs_location');
            if (is_array($attachments)) {
                foreach ($attachments as $a):
                    $package->package_main_image = $a;
                    $package->save();
                endforeach;
            }


            return Redirect::to('package/gallery/'.$package->package_id)->with('success_message', 'Packages Create successfully!');
        }
        else {
            //$messages = $validator->messages();
            return Redirect::to('package/create')->withErrors($validator)->withInput();
            ;
        }
    }

    public function edit($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'package', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $data = array();

        //Throw exception if project id does not exists
        $data['package'] = Package::findOrFail($id);
        $url = 'http://leadsdev.bookings-gateway.com/api';
        $ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); $output = curl_exec($ch);

$output = utf8_decode($output);
//echo "<pre>" ;

$json_array = json_decode($output);

///echo json_last_error();
        


//print_r($json_array);
//echo "</pre>" ;

curl_close($ch);


$data['contacts'] =  $json_array;



        ZnUtilities::push_js_files("jquery.validate.min.js");

        ZnUtilities::push_js_files("chosen.jquery.min.js");

        ZnUtilities::push_js_files('components/packages.js');
        
        ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
         $editor_js = '$(function() {
                      CKEDITOR.replace("description");
                      });';
               ZnUtilities::push_js($editor_js);

        $basicPageVariables = ZnUtilities::basicPageVariables("Packages", "Edit Packages", "package", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Packages'] = array("link" => '/package', "active" => '0');
        $data['breadcrumbs']['Edit Packages'] = array("link" => "", "active" => '1');
         $data['submenus'] = $this->_submenus('index');
        return View::make('admin.packages.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'package', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $packageImage = Input::get('file');

        if ($packageImage) {


            $attachments = Input::get('specs_location');
            if (is_array($attachments)) {
                foreach ($attachments as $a):
                    $attachment = new PackageImage();
                    //$attachment->attachment = '/attachments/'.$a;
                    $attachment->attachment = $a;



                    $file_name_parts = explode('/attachments/', $attachment->attachment);


                    $file_name_parts2 = explode('-', $file_name_parts[0]);
                    unset($file_name_parts2[0]);
                    $attachment_name = implode('-', $file_name_parts2);

                    $attachment->attachment_name = $attachment_name;
                    $attachment->package_id = $id;
                    $attachment->save();
                endforeach;
            }


            return Redirect::to('package')->with('success_message', 'Packages Create successfully!');
        }

        //skip email's unique validation if email is not changed
        $packages = Package::find($id);
        $validator = Validator::make(
                        array(
                    'package_name' => Input::get('package_name'),
                    'location_to' => Input::get('location_to'),
                    'location_from' => Input::get('location_from'),
                    
                     'date_from' => Input::get('date_from'),
                 //   'date_to' => Input::get('date_to'),
//                    'adult_cost' => Input::get('adult_cost'),
//                    'child_cost' => Input::get('child_cost'),
//                    'total_cost' => Input::get('total_cost'),
                    'number_of_adults' => Input::get('number_of_adults'),
                    'number_of_children' => Input::get('number_of_chlidren'),
                        ), array(
                    'package_name' => 'required',
                    'location_to' => 'required',
                    'location_from' => 'required',
                 //   'date_to' => 'required',
                    'date_from' => 'required',
                    //'adult_cost' => 'required',
                    //'number_of_adults' => 'required',
                    //'number_of_children' => 'required',
                        )
        );
        $adult_cost =input::get('adult_cost');
       
        $child_cost =input::get('child_cost');
        
        $number_of_adults = input::get('number_of_adults');
        $number_of_children = input::get('number_of_children');
        $total_cost = input::get('total_cost');
       
        if ($validator->passes()) {



            $packages->package_name = input::get('package_name');
            $packages->location_to = input::get('location_to');
            $packages->location_from = input::get('location_from');
            $packages->number_of_days = input::get('number_of_days');
           // $packages->date_to = input::get('date_to');
            $packages->date_from = ZnUtilities::format_date(input::get('date_from'),'1');
//            if($adult_cost=='')
//                  { 
//                  $packages->adult_cost = '0';
//                  }
//                   else{
//                      $packages->adult_cost = $adult_cost;
//                   }
////                  
//                   if($child_cost=='')
//                       { 
//                       $packages->child_cost ='0';
//                   }
//                   else{
//                     $packages->child_cost = $child_cost;
//                   }
//                   
//            $packages->total_cost = $total_cost;
            $packages->number_of_children = input::get('number_of_children');
            $packages->number_of_adults = input::get('number_of_adults');
            $packages->package_desc = input::get('description');
            $packages->save();



            return Redirect::to('package/gallery/' . $packages->package_id)->with('success_message', 'Packages Updated Successfully');
        } else {
            //$messages = $validator->messages();
            return Redirect::to('package/' . $id . '/edit/')->withErrors($validator)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'package', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        // delete
        $package = Package::find($id);
        $package->delete();

        // redirect
        return Redirect::to('package')->with('success_message', 'Packages deleted successfully!');
    }

    
     public function suppliers($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'package', 'manage')) {
            return Redirect::to('/permissionDenied');
        }
        
           $data = array();
      
        $data['supplier'] = Supplier::get();
         $data['package'] = Package::findOrFail($id);
         
         $data['packagesuppliers'] = PackageSupplier::where('package_id',$id)->get();
  
         $data['count'] = count($data['packagesuppliers']);
        $js = "indexCounter = '" . $data['count'] . "'";
        ZnUtilities::push_js($js);
    
        
        ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/packages.js');

        $basicPageVariables = ZnUtilities::basicPageVariables("Supplier", "Add New Supplier", "add_supplier", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Supplier'] = array("link" => '/supplier', "active" => '0');
        $data['breadcrumbs']['Add New Supplier'] = array("link" => "", "active" => '1');

       $data['submenus'] = $this->_submenus('index');
    return View::make('admin.packages.supplier', $data);
        
    }
    
    public function  saveSuppliers($id)
    {
         if(!User::checkPermission(Auth::user()->user_group_id,'package','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
             PackageSupplier::where('package_id',$id)->delete();
            $validator = Validator::make(
                array(
                    'supplier_type_id' => Input::get('supplier_type_id'),
                    'supplier_sub_type_id' => Input::get('supplier_sub_type_id'),
                    'supplier_id' => Input::get('supplier_id'),
               
                   
                   
                    ),
                array(
                    'supplier_type_id' => 'required',
                    'supplier_sub_type_id' => 'required',
                    'supplier_id' => 'required',
                
                   
                  
                    )
            );
            
         if($validator->passes())
            {
                $supplier_type = Input::get('supplier_type_id');
                $supplier_type_parent_id = Input::get('supplier_sub_type_id');
                $supplier_id = Input::get('supplier_id');
                
            foreach($supplier_type as $k => $v)
            {  $package_supplier= PackageSupplier::where('package_id',$id)->where('supplier_id',$supplier_id[$k])->first();
                if(!$package_supplier){
                $package_supplier = new PackageSupplier();
                $package_supplier->supplier_type_id = $supplier_type_parent_id[$k];
                $package_supplier->supplier_type_parent_id = $v;
                $package_supplier->package_id = $id;
                $package_supplier->supplier_id = $supplier_id[$k];
                $package_supplier->save();
                }
            }   
                return Redirect::to('package/suppliers/'.$id)->with('success_message', 'Itinerary created successfully!');
            }
            else
            {
               
                return Redirect::to('package/suppliers/'.$id)->withErrors($validator)->withInput();;
            }
    }
    

    public function packageSearch($search) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'package', 'manage')) {
            return Redirect::to('/permissionDenied');
        }


        $keyword = $search;
        $data['keyword'] = $keyword;


        $package = DB::table('packages as p');
        $package->leftJoin('locations as l', 'l.location_id', '=', 'p.location_from');
//                $package->leftJoin('locations as l','l.location_id','=','e.location_id');



        if ($keyword != '') {
            $keyword = trim($keyword);
            $package->orWhere(function ($package) use ($keyword) {


                $package->where("p.package_name", "like", "%" . $keyword . "%")
//                                    ->orwhere("l.location_title","like","%".$keyword."%")
//                                   ->orwhere("e.hotel_estimate_cost_child","like","%".$keyword."%")
                        ->orwhere("l.location_name", "like", "%" . $keyword . "%");
            });
        }


        $data['package'] = $package->paginate(10);




        $basicPageVariables = ZnUtilities::basicPageVariables("Packages", "Search results", "package", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Packages'] = array("link" => '/package', "active" => '0');
        $data['breadcrumbs']['Search'] = array("link" => "", "active" => '1');

        $data['search'] = $search;
         $data['submenus'] = $this->_submenus('index');
        return View::make('admin.packages.search_result', $data);
    }

    public function packageActions() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'package', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $search = Input::get('search');
        if ($search != '') {
            return Redirect::to('/packageSearch/' . $search);
        } else {


            //die(Input::get('bulk_action')   );

            $cid = Input::get('cid');
            $bulk_action = Input::get('bulk_action');
            if ($bulk_action != '') {
                switch ($bulk_action) {

                    case 'delete': {


                            foreach ($cid as $id) {
                                DB::table('packages')
                                        ->where('package_id', $id)
                                        ->delete();
                            }

                            return Redirect::to('/package/')->with('success_message', 'Rows Delete!');
                            break;
                        }
                } //end switch
            } // end if statement
            return Redirect::to('/package');
        }
    }

    public function packageImage($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'package', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $data = array();



        $data['packageInfo'] = Package::find($id);

        $data['package'] = PackageImage::where('package_id', $id)->get()->count();

        $package_image = PackageImage::where('package_id', $id)->get()->toArray();



        $ugarray = array();
        $imgarray = array();
        foreach ($package_image as $p) {
            $ugarray[] = $p['attachment'];
            $imgarray[] = $p['package_image_id'];
        }

        $data['package_image'] = $ugarray;
        $data['package_image_id'] = $imgarray;

        //ZnUtilities::pa($data['package_image']);die();
//            ZnUtilities::push_js_files("jquery.validate.min.js");

        ZnUtilities::push_js_files("chosen.jquery.min.js");

        ZnUtilities::push_js_files('components/packages.js');

        //


        ZnUtilities::push_js_files('jquery.contenthover.min.js');


        ZnUtilities::push_js_files('pekeUpload.js');
        $upload_js = '
                jQuery(document).ready(function(){
                $("#upload-button").pekeUpload({
                theme:"bootstrap", 
                url:"/packageimage-upload.php", 
                allowed_number_of_uploads:999,
                allowedExtensions:"jpg|jpeg|gif|png",
                onFileSuccess:function(file,response){
                    var data = JSON.parse(response)
                    var file_extension = data.raw_name.substr((~-data.raw_name.lastIndexOf(".") >>> 0) + 2);
                    $("#file_holder").show();
                    $("#upload_image").show();

                    $("#prev_upload tbody").append(\'<tr id="\'+data.file_id+\'" class="document_files"><td data-title="File Type"><img class="property_image" src="\'+data.logo_image+\'" height="22px"/><span class="file-type"> \'+file_extension+\'</span> </td><td data-title="Filename">\'+data.raw_name+\'</td><td data-title="Remove File"><a class="remove" href="javascript:void(0);" onclick=javascript:remove_div("\'+data.file_id+\'"); ><span class="fa fa-times"></span> Remove</a><input type="hidden" name="specs_location[]" value="\'+data.file_name+\'" /></td></tr> \');
                    $(".file").remove();
                    $("#addImage").show();

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
                     $("#addImage").hide();
                     $("#progress_bar").html("");
                }
                       
           }

            ';
        ZnUtilities::push_js($upload_js);



        //----End Upload Main Image--//
        $basicPageVariables = ZnUtilities::basicPageVariables("Packages", "Edit Packages", "package", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Packages'] = array("link" => '/package', "active" => '0');
        $data['breadcrumbs']['Edit Packages'] = array("link" => "", "active" => '1');

         $data['submenus'] = $this->_submenus('index');
        return View::make('admin.packages.showImage', $data);
    }

    public function deletePackageImage($package_id, $id) {



        $package = PackageImage::find($id);
        $package->delete();

        // redirect
        return Redirect::to('packageImage/' . $package_id)->with('success_message', 'Packages Image deleted successfully!');
    }

    public function packageGalleryForm($package_id) {
         $data = array();
         
         $package = Package::where('package_id',$package_id)->with('packageImages')->first();
         
         $data['package'] = $package;
         
        ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/packages.js');
        ZnUtilities::push_js_files('jquery.contenthover.min.js');
        ZnUtilities::push_js_files('pekeUpload.js');
        $upload_js = '
            jQuery(document).ready(function(){
                $("#upload-button").pekeUpload({
                theme:"bootstrap", 
                url:"/packageimage-upload.php", 
                allowed_number_of_uploads:999,
                allowedExtensions:"jpg|jpeg|gif|png",
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
        //


        $basicPageVariables = ZnUtilities::basicPageVariables("Packages", "Package Gallery", "add_package", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Packages'] = array("link" => '/package', "active" => '0');
        $data['breadcrumbs'][$package->package_name] = array("link" => "/package/".$package->package_name."/edit", "active" => '1');
         $data['submenus'] = $this->_submenus('index');
        return View::make('admin.packages.gallery', $data);
    }
    
    public function packageGalleryUpdate()
    {
        $package_id = Input::get('package_id');
        $attachments = Input::get('specs_location');
            if (is_array($attachments)) {
                foreach ($attachments as $a):
                    $image = new PackageImage();
                    $image->attachment = $a;
                    $file_name_parts = explode('/attachments/',$image->attachment);
                    $file_name_parts2 = explode('-',$file_name_parts[0]);
                    unset($file_name_parts2[0]);
                    $attachment_name =  implode('-',$file_name_parts2);
                    $image->attachment_name = $attachment_name;
                    $image->package_id = $package_id;
                    $image->save();
                endforeach;
            }
            
        return Redirect::to('/package/gallery/' . $package_id)->with('success_message', 'Packages Image Uploaded Successfully!');

    }
    
    public function packageItems($package_id) {
//        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
//            return Redirect::to('/permissionDenied');
//        }

        //ZnUtilities::pa($suppid); die();


        $data = array();

        $data['package'] = Package::find($package_id);
        $data['items'] = PackageItem::where('package_id',$package_id)->get();
        $data['packagesuppliers'] = PackageSupplier::where('package_id',$package_id)->groupby('supplier_type_parent_id')->distinct()->get();
        
     
         
         $data['count'] = count($data['items']);
        $js = "indexCounter = '" . $data['count'] . "'";
        ZnUtilities::push_js($js);
         
   
          
        ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/packages.js');

        $basicPageVariables = ZnUtilities::basicPageVariables("Packages", "Package Items", "add_package", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Packages'] = array("link" => '/packages', "active" => '0');
        $data['breadcrumbs']['Package Item'] = array("link" => "", "active" => '1');

        //return View::make('admin.supplier.create',$data);
         $data['submenus'] = $this->_submenus('index');
        return View::make('admin.packages.item', $data);
    }
    
    public function savePackageItems($package_id) {
//        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
//            return Redirect::to('/permissionDenied');
//        }

     
        $data = array();

        PackageItem::where('package_id',$package_id)->delete();
        $data['package'] = Package::find($package_id);
        
        
       $supplier_item = Input::get('supplier_item');
       $supplier_type = Input::get('supplier_type');
       $cost = Input::get('cost');
       $extra_notes = Input::get('extra_notes');
       $supplier_id = Input::get('supplier_id');      
       //$new_item = Input::get('supplier_new_item');

       foreach($supplier_type as $k => $v){
           $supplier_items=PackageItem::where('package_id',$package_id)->where('supplier_type_item_id',$supplier_item[$k])->where('supplier_id',$supplier_id[$k])->first();
           if(!$supplier_items){
              $item = new PackageItem();
              $item->package_id = $package_id;
              $item->supplier_type_id = $v;
              $item->supplier_type_item_id = $supplier_item[$k];
              $item->cost = $cost[$k];
              $item->supplier_id = $supplier_id[$k];
              $item->extra_notes = $extra_notes[$k];
              $item->save();
           }   
             }
            
       return Redirect::to('/package/items/'.$package_id)->with('success_message', 'Package Item Created successfully!');
       
    }

    public function quotes($package_id)
    {
//        if (!User::checkPermission(Auth::user()->user_group_id, 'package', 'manage')) {
//            return Redirect::to('/permissionDenied');
//        }

        $data = array();
        $data['quotes'] = Quote::where('package_id',$package_id)->get();
        $data['package'] = Package::find($package_id);

        $basicPageVariables = ZnUtilities::basicPageVariables("Packages", "All Quotes", "package", "1");
        $data = array_merge($data, $basicPageVariables);
        
        ZnUtilities::push_js_files('components/quotes.js');

        $data['breadcrumbs']['All Packages'] = array("link" => '/package', "active" => '0');
        $data['breadcrumbs']['Related Quotes'] = array("link" => "", "active" => '1');

         $data['submenus'] = $this->_submenus('index');
        return View::make('admin.packages.quotes', $data);
    }
    
    public function itinerary($package_id)
    {
          $data = array();

        $data['package'] = Package::find($package_id);
        $data['itineraries'] = PackageItinerary::where('package_id',$package_id)->orderby('days','ASC')->get();
        $data['thingstodo'] = ThingToDo::get();
        $data['day'] = $data['package']->number_of_days;
                
        ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/packages.js');
        ZnUtilities::push_js_files("components/itinerary.js");
        ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
         ZnUtilities::push_js_files('itineraryImageUpload.js');
                 $upload_js = '
            jQuery(document).ready(function(){
                $("#upload-button").pekeUpload({
                theme:"bootstrap", 
                url:"/itineraryImage-upload.php", 
                allowed_number_of_uploads:999,
                allowedExtensions:"jpg|jpeg|gif|png",
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

                  $editor_js = '$(function() {
                      CKEDITOR.replace("extra_note");
                      });';
               ZnUtilities::push_js($editor_js);


        $basicPageVariables = ZnUtilities::basicPageVariables("Packages", "Package Items", "add_package", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Packages'] = array("link" => '/packages', "active" => '0');
        $data['breadcrumbs']['Package Item'] = array("link" => "", "active" => '1');
$data['submenus'] = $this->_submenus('index');
        //return View::make('admin.supplier.create',$data);
        return View::make('admin.packages.itinerarylist', $data);
    }
    
    public function  saveItinerary()
    {
         if(!User::checkPermission(Auth::user()->user_group_id,'itinerary','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $validator = Validator::make(
                array(
                    'Itinerary_title' => Input::get('itinerary_title'),
                    'day' => Input::get('day'),
                   
                   
                    ),
                array(
                    'Itinerary_title' => 'required',
                    'day' => 'required',
                   
                  
                    )
            );
            
         if($validator->passes())
            {
         
               $new_thingstodo = Input::get('new_thingstodo');
               $thingstodo = Input::get('thingstodo'); 
               $things=array();
              
             foreach($new_thingstodo as $k=>$b)
             {
                     if($b){
                        $new = new ThingToDo();
                        $new->thing_todo = $b;
                        $new->save();
                        $things[]=$new->thing_todo_id;
                  }
                  else{
                    $things[] =  $thingstodo[$k]; 
                  }
              }
                
                $package_id = Input::get('package_id');
              
                $itineraries = implode(', ', $things) ;
                $itinerary = new PackageItinerary();
                $itinerary->things_todo = $itineraries;
                $itinerary->package_id = Input::get('package_id');
                $itinerary->itinerary_title = Input::get('itinerary_title');
                $itinerary->days = Input::get('day');
                $itinerary->extra_notes = Input::get('extra_note');
                $itinerary->save();
                 
                $attachments = Input::get('specs_location');
              if (is_array($attachments)) {
                foreach ($attachments as $a):
                    $attachment = new PackageItineraryImage();
                    //$attachment->attachment = '/attachments/'.$a;
                 //   $attachment1= $a;



//                    $file_name_parts = explode('/images/itinerary/', $attachment1);
//
//
//                    $file_name_parts2 = explode('-', $file_name_parts[0]);
//                    unset($file_name_parts2[0]);
//                    $attachment_name = implode('-', $file_name_parts2);

                    $attachment->image = $a; 
                    
                    $attachment->package_id=$package_id;
                    $attachment->package_itinerary_id=$itinerary->package_itinerary_id;
                    $attachment->days=Input::get('day');
                    $attachment->save();
                    endforeach;
              }
                return Redirect::to('package/itinerary/'.$package_id)->with('success_message', 'Itinerary created successfully!');
            }
            else
            {
               
                return Redirect::to('package/itinerary/'.$package_id)->withErrors($validator)->withInput();;
            }
    }
    
     public function editItinerary($package_id,$itinerary)
    {
          $data = array();

        $data['package'] = Package::find($package_id);
        $data['itineraries'] = PackageItinerary::where('package_id',$package_id)->get();
        $data['thingstodo'] = ThingToDo::get();
        $data['day'] = $data['package']->number_of_days; 
        if(!empty($itinerary)){
         $data['package_itinerary']= PackageItinerary::where('package_itinerary_id',$itinerary)->first();
         $data['package_itinerary_thingstodo']= explode(',', $data['package_itinerary']->things_todo);
          $data['count']= count($data['package_itinerary_thingstodo']);
        }
            
        ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/packages.js');
        ZnUtilities::push_js_files("components/itinerary.js");
        ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
         ZnUtilities::push_js_files('itineraryImageUpload.js');
                 $upload_js = '
            jQuery(document).ready(function(){
                $("#upload-button").pekeUpload({
                theme:"bootstrap", 
                url:"/itineraryImage-upload.php", 
                allowed_number_of_uploads:999,
                allowedExtensions:"jpg|jpeg|gif|png",
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

                  $editor_js = '$(function() {
                      CKEDITOR.replace("extra_note");
                      });';
               ZnUtilities::push_js($editor_js);


        $basicPageVariables = ZnUtilities::basicPageVariables("Packages", "Package Items", "add_package", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Packages'] = array("link" => '/packages', "active" => '0');
        $data['breadcrumbs']['Package Item'] = array("link" => "", "active" => '1');
$data['submenus'] = $this->_submenus('index');
        //return View::make('admin.supplier.create',$data);
        return View::make('admin.packages.edit_itinerarylist', $data);
    }
    
    public function  updateItinerary()
    {
         if(!User::checkPermission(Auth::user()->user_group_id,'itinerary','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $validator = Validator::make(
                array(
                    'Itinerary_title' => Input::get('itinerary_title'),
                    'day' => Input::get('day'),
                   
                   
                    ),
                array(
                    'Itinerary_title' => 'required',
                    'day' => 'required',
                   
                  
                    )
            );
            
         if($validator->passes())
            {
         
               $new_thingstodo = Input::get('new_thingstodo');
               $thingstodo = Input::get('thingstodo'); 
               $things=array();
              
             foreach($new_thingstodo as $k=>$b)
             {
                     if($b){
                        $new = new ThingToDo();
                        $new->thing_todo = $b;
                        $new->save();
                        $things[]=$new->thing_todo_id;
                  }
                  else{
                    $things[] =  $thingstodo[$k]; 
                  }
              }
                $package_itinerary_id=Input::get('package_itinerary_id');
                $package_id = Input::get('package_id');
              
                $itineraries = implode(', ', $things) ;
                $itinerary = PackageItinerary::find($package_itinerary_id);
                $itinerary->things_todo = $itineraries;
                $itinerary->package_id = Input::get('package_id');
                $itinerary->itinerary_title = Input::get('itinerary_title');
                $itinerary->days = Input::get('day');
                $itinerary->extra_notes = Input::get('extra_note');
                $itinerary->save();
                 
                $attachments = Input::get('specs_location');
                
              if (is_array($attachments)) {
                 $delete = PackageItineraryImage::where('package_itinerary_id',$package_itinerary_id)->where('days',Input::get('day'))->delete();
                foreach ($attachments as $a):
                    $attachment = new PackageItineraryImage();
                    //$attachment->attachment = '/attachments/'.$a;
                 //   $attachment1= $a;



//                    $file_name_parts = explode('/images/itinerary/', $attachment1);
//
//
//                    $file_name_parts2 = explode('-', $file_name_parts[0]);
//                    unset($file_name_parts2[0]);
//                    $attachment_name = implode('-', $file_name_parts2);

                    $attachment->image = $a; 
                    
                    $attachment->package_id=$package_id;
                    $attachment->package_itinerary_id=$itinerary->package_itinerary_id;
                    $attachment->days=Input::get('day');
                    $attachment->save();
                    endforeach;
              }
                return Redirect::to('package/itinerary/'.$package_id)->with('success_message', 'Itinerary created successfully!');
            }
            else
            {
               
                return Redirect::to('package/itinerary/'.$package_id)->withErrors($validator)->withInput();;
            }
    }
    
    
    public function removeItinerary($package_itinerary_id)
    {
        $package_itinerary = PackageItinerary::find($package_itinerary_id);
        $package_id = $package_itinerary->package_id;
        $package_itinerary->delete();
        
        return Redirect::to('package/itinerary/'.$package_id)->with('success_message', 'Itinerary remove successfully!');
    }
    
    public function removeItineraryImage($package_itinerary_image_id)
    {
        $package_itinerary_image = PackageItineraryImage::find($package_itinerary_image_id);
        $package_id = $package_itinerary_image->package_id;
        $package_itinerary_image->delete();
        
        return Redirect::to('package/itinerary/'.$package_id)->with('success_message', 'Itinerary image remove successfully!');
    }
    
   
    
    public function showPackages($package_id){
        $data = array();
         
        $data['package'] = Package::find($package_id);

        //ZnUtilities::pa($data['package']);

        $date_from = $data['package']->date_from;
        $days = $data['package']->number_of_days;

        $date = new DateTime($date_from);
        $date->add(new DateInterval('P' . $days . 'D'));
        $date_to = $date->format('Y-m-d');

        $data['date_to'] = $date_to;
        $data['items'] = PackageItem::where('package_id', $package_id)->groupby('supplier_id')->distinct()->get();
        
        $data['itineraries'] = PackageItinerary::where('package_id',$package_id)->get();

        ZnUtilities::push_js_files('components/packages.js');

        $basicPageVariables = ZnUtilities::basicPageVariables("Packages", "Show Package", "show_package", "1");
        $data = array_merge($data, $basicPageVariables);
         $data['submenus'] = $this->_submenus('index');
        $data['breadcrumbs']['All Quotes'] = array("link" => '/quote', "active" => '0');
        $data['breadcrumbs']['Edit Quote'] = array("link" => "", "active" => '1');

        return View::make('admin.packages.show', $data);
        
        
    }
    public function packageFeature($package_id){
        
       $data = array();

        $data['package'] = Package::find($package_id); 
        $data['suppliers'] = Supplier::all();
        $data['supplier_type'] = SupplierType::with('subTypes')->where('supplier_type_parent_id','0')->get();
        
        
            ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/packages.js');
        
        $basicPageVariables = ZnUtilities::basicPageVariables("Feature", "Feature", "feature", "1");
        $data = array_merge($data, $basicPageVariables);
         $data['submenus'] = $this->_submenus('index');
        

        return View::make('admin.packages.features', $data);
    }
    public function featureSave($id) {
       // ZnUtilities::pa($_POST);die();
        $package_id=Input::get('package_id');
        $feature=Input::get('features');
        
        
        
        PackageFeature::where('package_id',$package_id)->delete();
        
        foreach($feature as $k=>$f){
            
            foreach($f as $K=>$F){
                
                $newfeature = new PackageFeature();
                        $newfeature->package_id = $package_id;
                        $newfeature->supplier_parent_id = $k;
                        $newfeature->supplier_id = $K;
                        $newfeature->items = json_encode($F);
                        $newfeature->save();
                
            }
                
                }
                return Redirect::to('package/itinerary/'.$package_id)->with('success_message', 'Featured added successfully!');
    }
     public function deleteItems($subtype_id,$package_id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'package', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        // delete
        $package = PackageFeature::where('package_id',$package_id)->where('supplier_id',$subtype_id)->delete();
      

        // redirect
        return Redirect::to('package/features/'.$package_id)->with('success_message', 'Items deleted successfully!');
    }
    
     private function _submenus($active)
    {   
        $data = array();
        $data['Packages'] = array("link" => '/package', "active" => $active=='index'?'1':'0' ,"icon" => 'fa-list');
        return $data;
    }
}
