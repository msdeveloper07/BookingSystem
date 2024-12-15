<?php

class QuotesController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
     return Redirect::to('/allquotes');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($package_id = null) {
        $data = array();
        
        $data['package'] = Package::find($package_id);
        $data['packages'] = Package::all();
        
       $url = 'http://leadsdev.bookings-gateway.com/api';
       //$url = 'http://leads.dev/api';
      
    
        $ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); $output = curl_exec($ch);

$output = utf8_decode($output);
$json_array = json_decode($output);
curl_close($ch);
$data['contacts'] =  $json_array->data;

   

        ZnUtilities::push_js_files('components/quotes.js');
        ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
         $editor_js = '$(function() {
                      CKEDITOR.replace("description");
                      });';
               ZnUtilities::push_js($editor_js);

        $basicPageVariables = ZnUtilities::basicPageVariables("Quotes", "Add New Quote", "add_quote", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Quotes'] = array("link" => '/quote', "active" => '0');
        $data['breadcrumbs']['Add New Quote'] = array("link" => "", "active" => '1');
        
        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.quotes.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        if(Input::get('package_id')==''){
            
            $validator = Validator::make(
                        array(
                    'quote_title' => Input::get('quote_title'),
                    'quoted_cost' => Input::get('quoted_cost'),
                    'discount' => Input::get('discount'),
                    'location_from' => Input::get('location_from'),
                    'location_to' => Input::get('location_to'),
                   'description' => Input::get('description'),
                  
                
                        ), array(
                    'quote_title' => 'required',
                    'quoted_cost' => 'required',
                    'discount' => 'required',
                    'location_from' => 'required',
                    'location_to' => 'required',
                    'description' => 'required',
                     'location_from' => 'required',
               
                        )
        );
          if ($validator->passes()) {
        $quote = new Quote();
        
        $quote->quote_title = Input::get('quote_title');
        $quote->package_id = Input::get('package_id');
        $quote->quoted_cost = Input::get('quoted_cost');
        $quote->discount = Input::get('discount');
        $quote->location_from = Input::get('location_from');
        $quote->location_to = Input::get('location_to');
        $quote->quote_status = 'active';
        $quote->created_on = date("Y-m-d H:i:s");
        $quote->created_by = Auth::user()->id;
        $quote->updated_on = date("Y-m-d H:i:s");
        $quote->updated_by = Auth::user()->id;
       
        $quote->number_of_children = input::get('number_of_children');
        $quote->number_of_adults = input::get('number_of_adults');
        $quote->quote_currency = input::get('package_currency');
        
        $quote->quote_desc = Input::get('description');
        $quote->save();
        
          $name = explode(" ", Input::get('contact_name'));
        $contact = new Contact();
        $contact->first_name = $name[0];
        $contact->last_name = (!empty($name[1])?$name[1]:'');
        $contact->save();

        $quote->contact_id = $contact->contact_id;
        $quote->save();
        
        return Redirect::to('quotes/dates/'.$quote->quote_id)->with('success_message', 'Quote Create successfully!');
        
        
        
          }
        else {
            //$messages = $validator->messages();
            return Redirect::to('quotes/create')->withErrors($validator)->withInput();
            
        }
            
        }
        else{
        $quote = new Quote();
        $quote->package_id = Input::get('package_id');
        $quote->quote_title = Input::get('quote_title');
        $quote->quoted_cost = Input::get('quoted_cost');
        $quote->discount = Input::get('discount');
        $quote->location_from = Input::get('location_from');
        $quote->location_to = Input::get('location_to');
        $quote->quote_status = 'active';
        $quote->created_on = date("Y-m-d H:i:s");
        $quote->created_by = Auth::user()->id;
        $quote->updated_on = date("Y-m-d H:i:s");
        $quote->updated_by = Auth::user()->id;
        $quote->quote_desc = Input::get('description');
        
        $quote->number_of_children = input::get('number_of_children');
        $quote->number_of_adults = input::get('number_of_adults');
        $quote->quote_currency = input::get('package_currency');
        
        $quote->save();

        $package = Package::find(Input::get('package_id'));
        $quote->date_from = $package->date_from;
        $quote->number_of_days = $package->number_of_days;
        $quote->date_to = $package->date_to;
        $quote->save();
        
//        $package_suppliers= PackageSupplier::where('package_id',Input::get('package_id'))->get();
//        foreach($package_suppliers as $ps){
//            $quote_supllier = new QuoteSupplier();
//            $quote_supllier->package_id = Input::get('package_id');
//            $quote_supllier->quote_id = $quote->quote_id;
//            $quote_supllier->supplier_id=$ps->supplier_id;
//            $quote_supllier->supplier_type_id=$ps->supplier_type_id;
//            $quote_supllier->supplier_type_parent_id=$ps->supplier_type_parent_id;
//            $quote_supllier->save();
//        }
//
//        $package_items = PackageItem::where('package_id', Input::get('package_id'))->get();
//        foreach ($package_items as $p) {
//            $quote_items = new QuoteItem();
//            $quote_items->quote_id = $quote->quote_id;
//            $quote_items->package_id = $p->package_id;
//            $quote_items->supplier_id = $p->supplier_id;
//            $quote_items->supplier_type_id = $p->supplier_type_id;
//            $quote_items->supplier_type_item_id = $p->supplier_type_item_id;
//            $quote_items->cost = $p->cost;
//            $quote_items->extra_notes = $p->extra_notes;
//            $quote_items->save();
//        }
        $packageFeature = PackageFeature::where('package_id', Input::get('package_id'))->get();
        foreach($packageFeature as $pf){
            $quoteFeature = new QuoteFeature();
            $quoteFeature->quote_id = $quote->quote_id;
            $quoteFeature->supplier_parent_id = $pf->supplier_parent_id;
            $quoteFeature->supplier_id = $pf->supplier_id;
            $quoteFeature->items = $pf->items;
            $quoteFeature->save();
        }

        $packageItinerary = PackageItinerary::where('package_id', Input::get('package_id'))->get(); 
        foreach ($packageItinerary as $p) {
            $quoteItinerary = new QuoteItinerary();
            $quoteItinerary->quote_id = $quote->quote_id;
            $quoteItinerary->package_id = $p->package_id;
            $quoteItinerary->itinerary_title = $p->itinerary_title;
            $quoteItinerary->days = $p->days;
            $quoteItinerary->things_todo = $p->things_todo;
            $quoteItinerary->extra_notes = $p->extra_notes;
            $quoteItinerary->save();
            $packageItineraryImage = PackageItineraryImage::where('package_id', Input::get('package_id'))->where('package_itinerary_id',$p->package_itinerary_id)->get(); 
        if($packageItineraryImage){
            foreach ($packageItineraryImage as $q) {
            $quoteItineraryImage = new QuoteItineraryImage();
            $quoteItineraryImage->quote_id = $quote->quote_id;
            
            $quoteItineraryImage->quote_itinerary_id = $quoteItinerary->quote_itinerary_id;
            $quoteItineraryImage->days = $q->days;
            $quoteItineraryImage->image = $q->image;
            
            $quoteItineraryImage->save();
        }
        }
        
        
    }    
        


        $name = explode(" ", Input::get('contact_name'));
        $contact = new Contact();
        $contact->first_name = $name[0];
        $contact->last_name = (!empty($name[1])?$name[1]:'');
        $contact->save();

        $quote->contact_id = $contact->contact_id;
        $quote->save();
        return Redirect::to('/quotes/dates/' . $quote->quote_id);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $data = array();
        $data['quote'] = Quote::where('quote_id', $id)->first();
        $data['packages'] = Package::all();
//        $url = 'http://leadsdev.bookings-gateway.com/api';
        $url = 'http://leadsdev.bookings-gateway.com/api';
         $data['contacts'] = Contact::all();
        
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
//$data['contacts'] =  $json_array->data;



        ZnUtilities::push_js_files('components/quotes.js');
        ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
         $editor_js = '$(function() {
                      CKEDITOR.replace("description");
                      });';
               ZnUtilities::push_js($editor_js);

        $basicPageVariables = ZnUtilities::basicPageVariables("Quotes", "Edit Quote", "edit_quote", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Quotes'] = array("link" => '/quote', "active" => '0');
        $data['breadcrumbs']['Edit Quote'] = array("link" => "", "active" => '1');
        
        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.quotes.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $quote = Quote::find($id);
        $quote->package_id = Input::get('package_id');
        $quote->quote_title = Input::get('quote_title');
        $quote->quoted_cost = Input::get('quoted_cost');
        $quote->discount = Input::get('discount');
        $quote->location_from = Input::get('location_from');
        $quote->location_to = Input::get('location_to');
        $quote->quote_status = 'active';
        $quote->created_on = date("Y-m-d H:i:s");
        $quote->created_by = Auth::user()->id;
        $quote->updated_on = date("Y-m-d H:i:s");
        $quote->updated_by = Auth::user()->id;
         $quote->number_of_children = input::get('number_of_children');
        $quote->number_of_adults = input::get('number_of_adults');
        $quote->quote_currency = input::get('package_currency');
        $quote->quote_desc = Input::get('description');
        

//         $name = explode(" ", Input::get('contact_name'));
//        $contact = new Contact();
//        $contact->first_name = $name[0];
//        $contact->last_name = (!empty($name[1])?$name[1]:'');
//        $contact->save();
//          $quote->contact_id = $contact->contact_id;
        $quote->contact_id = Input::get('contact_name');
        $quote->save();


        return Redirect::to('/quotes/dates/' . $quote->quote_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

    public function dates($quote_id) {
        $data = array();
        $data['quote'] = Quote::find($quote_id);
        

        //ZnUtilities::pa($data['package']);
      ZnUtilities::push_js_files('components/quotes.js');

        $basicPageVariables = ZnUtilities::basicPageVariables("Quotes", "Quote Dates", "add_quote", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Quotes'] = array("link" => '/quote', "active" => '0');
        $data['breadcrumbs']['Add New Quote'] = array("link" => "", "active" => '1');
        
        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.quotes.dates', $data);
    }

    public function saveDates() {
        
        $quote_id = Input::get('quote_id');
        

        $date_from = ZnUtilities::format_date(Input::get('date_from'), '1');
        $days = Input::get('number_of_days');
        
        $date = new DateTime($date_from);
        $date->add(new DateInterval('P' . $days . 'D'));
        $date_to = $date->format('Y-m-d');
        
        $quote = Quote::find($quote_id);
        $quote->date_from = $date_from;
        $quote->date_to = $date_to;
        $quote->number_of_days = $days;
        $quote->save();

        return Redirect::to('/quotes/features/' . $quote_id);
    }

    public function items($quote_id) {
        $data = array();

        $data['quote'] = Quote::find($quote_id);
        $data['package'] = Package::where('package_id', $data['quote']->package_id)->first();
        $data['items'] = QuoteItem::where('quote_id', $quote_id)->get();
        $data['suppliertype'] = SupplierType::all();


        ZnUtilities::push_js_files('components/quotes.js');
        ZnUtilities::push_js_files('components/packages.js');


        $basicPageVariables = ZnUtilities::basicPageVariables("Quotes", "Quote Dates", "add_quote", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Quotes'] = array("link" => '/quote', "active" => '0');
        $data['breadcrumbs']['Items'] = array("link" => "", "active" => '1');
        
        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.quotes.items', $data);
    }

    public function saveItems() {
//        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
//            return Redirect::to('/permissionDenied');
//        }

      //  $data = array();

        $quote_id = Input::get('quote_id');

        QuoteItem::where('quote_id', $quote_id)->delete();
        $quote = Quote::find($quote_id);
        
       
        $package_id = $quote->package_id;
        $supplier_item = Input::get('supplier_item');
        $supplier_type = Input::get('supplier_type');
        $cost = Input::get('cost');
        $extra_notes = Input::get('extra_notes');
        //$new_item = Input::get('supplier_new_item');

        foreach ($supplier_type as $k => $v) {
            $item = new QuoteItem();
            $item->package_id = $package_id;
            $item->quote_id = $quote_id;
            $item->supplier_type_id = $v;
            $item->supplier_type_item_id = $supplier_item[$k];
            $item->cost = $cost[$k];
            $item->extra_notes = $extra_notes[$k];
            $item->save();
        }

        return Redirect::to('/quotes/items/' .$quote_id)->with('success_message', 'Quote Item Created successfully!');
    }

 
     
    public function itinerary($quote_id)
    {
          $data = array();

        $quote = Quote::find($quote_id);
        $data['quote'] = $quote;
        
        $data['day'] = $data['quote']->number_of_days;
        $data['itineraries'] = QuoteItinerary::where('quote_id',$quote_id)->orderby('days','ASC')->get();
        
        $data['thingstodo'] = ThingToDo::get();
               
        ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/quotes.js');
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

        //return View::make('admin.supplier.create',$data);
        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.quotes.itinerarylist', $data);
    }
    
    public function  saveItinerary()
    {
         if(!User::checkPermission(Auth::user()->user_group_id,'itinerary','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $quote_id = Input::get('quote_id');
            
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
              
                $quote_id = Input::get('quote_id');
                
               
                
                $quote = Quote::find($quote_id);
              
                $itineraries = implode(', ', $things) ;
                $itinerary = new QuoteItinerary();
                $itinerary->things_todo = $itineraries;
                $itinerary->package_id = $quote->package_id;
                $itinerary->quote_id = $quote->quote_id;
                $itinerary->itinerary_title = Input::get('itinerary_title');
                $itinerary->days = Input::get('day');
                $itinerary->extra_notes = Input::get('extra_note');
                $itinerary->save();
                
               $attachments = Input::get('specs_location');
            
                foreach ($attachments as $a):
                   
                    $attachment = new QuoteItineraryImage();
                   
                    $attachment->image = $a; 
                    
                    $attachment->quote_id=$quote->quote_id;
                    $attachment->quote_itinerary_id=$itinerary->quote_itinerary_id;
                    $attachment->days=Input::get('day');
                    $attachment->save();
                    endforeach;
             
                return Redirect::to('quotes/itinerary/'.$quote_id)->with('success_message', 'Itinerary created successfully!');
            }
            else
            {
               
                return Redirect::to('quotes/itinerary/'.$quote_id)->withErrors($validator)->withInput();;
            }
    }
    
       public function editItinerary($quote_id,$itinerary)
    {
        $data = array();

        $quote = Quote::find($quote_id);
        $data['quote'] = $quote;
        
        $data['day'] = $data['quote']->number_of_days;
        $data['itineraries'] = QuoteItinerary::where('quote_id',$quote_id)->get();
        
        $data['thingstodo'] = ThingToDo::get();
        
        if(!empty($itinerary)){
         $data['quote_itinerary']= QuoteItinerary::where('quote_itinerary_id',$itinerary)->first();
         $data['quote_itinerary_thingstodo']= explode(',', $data['quote_itinerary']->things_todo);
          $data['count']= count($data['quote_itinerary_thingstodo']);
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

        //return View::make('admin.supplier.create',$data);
        
        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.quotes.edit_itinerarylist', $data);
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
                $quote_itinerary_id=Input::get('quote_itinerary_id');
                $package_id = Input::get('package_id');
                 $quote_id =Input::get('quote_id');
                $itineraries = implode(', ', $things) ;
                $itinerary = QuoteItinerary::find($quote_itinerary_id);
                $itinerary->things_todo = $itineraries;
                $itinerary->quote_id = Input::get('quote_id');
                $itinerary->itinerary_title = Input::get('itinerary_title');
                $itinerary->days = Input::get('day');
                $itinerary->extra_notes = Input::get('extra_note');
                $itinerary->save();
                 
                $attachments = Input::get('specs_location');
                
              if (is_array($attachments)) {
                 $delete = QuoteItineraryImage::where('quote_itinerary_id',$quote_itinerary_id)->where('days',Input::get('day'))->delete();
                foreach ($attachments as $a):
                    $attachment = new QuoteItineraryImage();
                    

                    $attachment->image = $a; 
                    
                    $attachment->quote_id=$quote_id;
                    $attachment->quote_itinerary_id=$quote_itinerary_id;
                    $attachment->days=Input::get('day');
                    $attachment->save();
                    endforeach;
              }
                return Redirect::to('/quotes/itinerary/'.$quote_id)->with('success_message', 'Itinerary created successfully!');
            }
            else
            {
               
                return Redirect::to('/quotes/itinerary/'.$quote_id)->withErrors($validator)->withInput();;
            }
    }
    
    
    public function removeItinerary($quote_itinerary_id)
    {
        $quote_itinerary = QuoteItinerary::find($quote_itinerary_id);
        $quote_id = $quote_itinerary->quote_id;
        $quote_itinerary->delete();
        
        return Redirect::to('quotes/itinerary/'.$quote_id)->with('success_message', 'Itinerary remove successfully!');
    }
    
     public function removeItineraryImage($quote_itinerary_image_id)
    {
        $quote_itinerary_image = QuoteItineraryImage::find($quote_itinerary_image_id);
        $quote_id = $quote_itinerary_image->quote_id;
        $quote_itinerary_image->delete();
        
        return Redirect::to('quotes/itinerary/'.$quote_id)->with('success_message', 'Itinerary  Image remove successfully!');
    }
   
    public function bookings($quote_id)
    {
//        if (!User::checkPermission(Auth::user()->user_group_id, 'package', 'manage')) {
//            return Redirect::to('/permissionDenied');
//        }

        $data = array();
        $data['quote'] = Quote::find($quote_id);
        $data['bookings'] = Booking::where('quote_id',$quote_id)->get();
        
        $basicPageVariables = ZnUtilities::basicPageVariables("Packages", "All Quotes", "package", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Bookings'] = array("link" => '/package', "active" => '0');
        
        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.quotes.bookings', $data);
    }
    
    public function showQuotes($quote_id){
        $data = array();
         $data['quote'] = Quote::find($quote_id);
        $data['package'] = Package::find($data['quote']->package_id);

        //ZnUtilities::pa($data['package']);
        $data['items'] = QuoteItem::where('quote_id', $quote_id)->get();
        $data['itineraries'] = QuoteItinerary::where('quote_id',$quote_id)->get();

        ZnUtilities::push_js_files('components/quotes.js');

        $basicPageVariables = ZnUtilities::basicPageVariables("Quotes", "Show Quote", "show_quote", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Quotes'] = array("link" => '/quote', "active" => '0');
        $data['breadcrumbs']['Edit Quote'] = array("link" => "", "active" => '1');
        
        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.quotes.show', $data);
        
        
    }
    
    public function quoteActions() {
    

        $search = Input::get('search');
        if ($search != '') {
            return Redirect::to('/quotesSearch/' . $search);
        } else {


            //die(Input::get('bulk_action')   );
            // $package_id =  Input::get("package_id");
            $cid = Input::get('cid');
            $bulk_action = Input::get('bulk_action');
            if ($bulk_action != '') {
                switch ($bulk_action) {

                    case 'delete': {


                            foreach ($cid as $id) {
                                DB::table('quotes')
                                        ->where('quote_id', $id)
                                        ->delete();
                            }

                            return Redirect::to('/allquotes')->with('success_message', 'Rows Delete!');
                            break;
                        }
                } //end switch
            } // end if statement
            return Redirect::to('/allquotes');
        }
    }
    public function quoteSearch($search) {
//        if (!User::checkPermission(Auth::user()->user_group_id, 'package', 'manage')) {
//            return Redirect::to('/permissionDenied');
//        }


        $keyword = $search;
        $data['keyword'] = $keyword;


        $quote = DB::table('quotes as p');
//        $quote->leftJoin('locations as l', 'l.location_id', '=', 'p.location_from');
//                $package->leftJoin('locations as l','l.location_id','=','e.location_id');



        if ($keyword != '') {
            $keyword = trim($keyword);
            $quote->orWhere(function ($quote) use ($keyword) {


                $quote->where("p.quote_title", "like", "%" . $keyword . "%");

            });
        }


        $data['quotes'] = $quote->paginate(10);




        $basicPageVariables = ZnUtilities::basicPageVariables("Quotes", "Search results", "quotes", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Packages'] = array("link" => '/allquotes', "active" => '0');
        $data['breadcrumbs']['Search'] = array("link" => "", "active" => '1');

        $data['search'] = $search;
         $data['submenus'] = $this->_submenus('index');
        return View::make('admin.quotes.showquotes', $data);
    }
    
    
    
    public function suppliers($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'package', 'manage')) {
            return Redirect::to('/permissionDenied');
        }
        
           $data = array();
      
        $data['supplier'] = Supplier::get();
         $data['quote'] = Quote::findOrFail($id);
      
         $data['quote_suppliers'] = QuoteSupplier::where('quote_id',$id)->get();
  
        $data['count'] = count($data['quote_suppliers']);
        $js = "indexCounter = '" . $data['count'] . "'";
        ZnUtilities::push_js($js);
    
        
        ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/quotes.js');

        $basicPageVariables = ZnUtilities::basicPageVariables("Supplier", "Add New Supplier", "add_supplier", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Supplier'] = array("link" => '/supplier', "active" => '0');
        $data['breadcrumbs']['Add New Supplier'] = array("link" => "", "active" => '1');

//       $data['submenus'] = $this->_submenus('index');
        $data['submenus'] = $this->_submenus('index');
    return View::make('admin.quotes.suppliers', $data);
        
    }
    
     public function  saveSuppliers($id)
    {
         if(!User::checkPermission(Auth::user()->user_group_id,'package','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
           
            $package_id = QuoteSupplier::where('quote_id',$id)->pluck('package_id');
            
            QuoteSupplier::where('quote_id',$id)->delete();
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
            {
                $quote_supplier = new QuoteSupplier();
                $quote_supplier->supplier_type_id = $supplier_type_parent_id[$k];
                $quote_supplier->supplier_type_parent_id = $v;
                $quote_supplier->quote_id = $id;
                $quote_supplier->package_id = $package_id;
                $quote_supplier->supplier_id = $supplier_id[$k];
                
                $quote_supplier->save();
                
            }   
                return Redirect::to('quotes/suppliers/'.$id)->with('success_message', 'Suppliers created successfully!');
            }
            else
            {
               
                return Redirect::to('quotes/suppliers/'.$id)->withErrors($validator)->withInput();;
            }
    }
	 public function quoteItems($quote_id) {
     
        $data = array();

        $data['quote'] = Quote::find($quote_id);
        $data['package'] = Package::where('package_id', $data['quote']->package_id)->first();
        $data['items'] = QuoteItem::where('quote_id', $quote_id)->get();
        $data['quote_suppliers'] = QuoteSupplier::where('quote_id',$quote_id)->groupby('supplier_type_parent_id')->distinct()->get();
    
//        ZnUtilities::pa();  die();
      
        
        $data['count'] = count($data['items']);
        $js = "indexCounter = '" . $data['count'] . "'";
        ZnUtilities::push_js($js);
        
        ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/quotes.js');
       


        $basicPageVariables = ZnUtilities::basicPageVariables("Quotes", "Quote Items", "add_quote", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Quotes'] = array("link" => '/quote', "active" => '0');
        $data['breadcrumbs']['Items'] = array("link" => "", "active" => '1');
        
        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.quotes.items', $data);
    }

    public function saveQuoteItems($quote_id) {
//        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
//            return Redirect::to('/permissionDenied');
//        }

      //  $data = array();

        $quote = Quote::find($quote_id);

        QuoteItem::where('quote_id', $quote_id)->delete();
        
        
       
        $package_id = $quote->package_id;
        $supplier_item = Input::get('supplier_item');
        $supplier_type = Input::get('supplier_type');
        $cost = Input::get('cost');
        $extra_notes = Input::get('extra_notes');
        $supplier_id = Input::get('supplier_id');
        
        //$new_item = Input::get('supplier_new_item');

        foreach ($supplier_type as $k => $v) {
            $item = new QuoteItem();
            $item->package_id = $package_id;
            $item->quote_id = $quote_id;
            $item->supplier_type_id = $v;
            $item->supplier_type_item_id = $supplier_item[$k];
            $item->cost = $cost[$k];
             $item->supplier_id = $supplier_id[$k];
            $item->extra_notes = $extra_notes[$k];
            $item->save();
        }
    
     
    

        return Redirect::to('/quotes/features/' .$quote_id)->with('success_message', 'Quote Item Created successfully!');
    }
        
     public function showAllQuotes()
    {
//        if (!User::checkPermission(Auth::user()->user_group_id, 'package', 'manage')) {
//            return Redirect::to('/permissionDenied');
//        }

        $data = array();
        $data['quotes'] = Quote::orderBy('quote_id','desc')->get();
       
        $basicPageVariables = ZnUtilities::basicPageVariables("Quotes", "All Quotes", "quote", "1");
        $data = array_merge($data, $basicPageVariables);
        
        ZnUtilities::push_js_files('components/quotes.js');

        $data['breadcrumbs']['All Packages'] = array("link" => '/allquotes', "active" => '0');
        $data['breadcrumbs']['Related Quotes'] = array("link" => "", "active" => '1');

        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.quotes.showquotes', $data);
    }
        
    
     public function quoteFeature($quote_id){
        
       $data = array();

        $data['quote'] = Quote::find($quote_id); 
        $data['suppliers'] = Supplier::all();
        $data['supplier_type'] = SupplierType::with('subTypes')->where('supplier_type_parent_id','0')->get();
        
         ZnUtilities::push_js_files('components/quotes.js');
        
        $basicPageVariables = ZnUtilities::basicPageVariables("Feature", "Feature", "feature", "1");
        $data = array_merge($data, $basicPageVariables);
         $data['submenus'] = $this->_submenus('index');
        

        return View::make('admin.quotes.features', $data);
    }
    public function featureSave($id) {
       // ZnUtilities::pa($_POST);die();
        $quote_id=Input::get('quote_id');
        $feature=Input::get('features');
        QuoteFeature::where('quote_id',$id)->delete();
        
        
        foreach($feature as $k=>$f){
            
            foreach($f as $K=>$F){
                
                $newfeature = new QuoteFeature();
                        $newfeature->quote_id = $id;
                        $newfeature->supplier_parent_id = $k;
                        $newfeature->supplier_id = $K;
                        $newfeature->items = json_encode($F);
                        $newfeature->save();
                
            }
                
                }
                return Redirect::to('quotes/itinerary/'.$id)->with('success_message', 'Featured added successfully!');
    }
    
     private function _submenus($active)
    {   
        $data = array();
        $data['Quotes'] = array("link" => '/allquotes', "active" => $active=='index'?'1':'0' ,"icon" => 'fa-list-ol');
        return $data;
    }
}

    

