<?php

class BookingsController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
      return Redirect::to('/allbookings');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($quote_id = null) {
        $data = array();
        $data['quote'] = Quote::find($quote_id);
        $data['quotes'] = Quote::all();
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


$data['contacts'] =  $json_array->data;
       

        ZnUtilities::push_js_files('components/bookings.js');
        ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
         $editor_js = '$(function() {
                      CKEDITOR.replace("description");
                      });';
               ZnUtilities::push_js($editor_js);

        $basicPageVariables = ZnUtilities::basicPageVariables("Bookings", "Add New Booking", "add_booking", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Bookings'] = array("link" => '/booking', "active" => '0');
        $data['breadcrumbs']['Add New Booking'] = array("link" => "", "active" => '1');
        
        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.bookings.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        if(Input::get('quote_id')==''){
            
            $validator = Validator::make(
                        array(
                    'quote_title' => Input::get('quote_title'),
                    'quoted_cost' => Input::get('quoted_cost'),
                    'discount' => Input::get('discount'),
                    'location_from' => Input::get('location_from'),
                    'location_to' => Input::get('location_to'),
                   'description' => Input::get('description'),
                  
                
                        ), array(
//                    'quote_title' => 'required',
//                    'quoted_cost' => 'required',
//                    'discount' => 'required',
//                    'location_from' => 'required',
//                    'location_to' => 'required',
//                    'description' => 'required',
//                     'location_from' => 'required',
               
                        )
        );
          if ($validator->passes()) {
        $booking = new Booking();
        
        $booking->booking_title = Input::get('booking_title');
        $booking->booking_cost = Input::get('booking_cost');
        $booking->quote_id = Input::get('quote_id');
        $booking->discount = Input::get('discount');
        $booking->location_from = Input::get('location_from');
        $booking->location_to = Input::get('location_to');
        $booking->booking_status = 'active';
        $booking->created_on = date("Y-m-d H:i:s");
        $booking->created_by = Auth::user()->id;
        $booking->updated_on = date("Y-m-d H:i:s");
        $booking->updated_by = Auth::user()->id;
         $booking->number_of_children = input::get('number_of_children');
        $booking->number_of_adults = input::get('number_of_adults');
        $booking->booking_currency = input::get('package_currency');
        
        $booking->booking_desc = Input::get('description');
        $booking->save();
        
          $name = explode(" ", Input::get('contact_name'));
        $contact = new Contact();
        $contact->first_name = $name[0];
        $contact->last_name = (!empty($name[1])?$name[1]:'');
        $contact->save();

        $booking->contact_id = $contact->contact_id;
        $booking->save();
        
        return Redirect::to('bookings/dates/'.$booking->booking_id)->with('success_message', 'Quote Create successfully!');
        
        
        
          }
        else {
            //$messages = $validator->messages();
            return Redirect::to('bookings/create')->withErrors($validator)->withInput();
            
        }
            
        }
        else{
        $booking = new Booking();
        $booking->booking_title = Input::get('booking_title');
        $booking->quote_id = Input::get('quote_id');
        $booking->booking_cost = Input::get('booking_cost');
        $booking->discount = Input::get('discount');
        $booking->location_from = Input::get('location_from');
        $booking->location_to = Input::get('location_to');
        $booking->booking_status = 'active';
        $booking->created_on = date("Y-m-d H:i:s");
        $booking->created_by = Auth::user()->id;
        $booking->updated_on = date("Y-m-d H:i:s");
        $booking->updated_by = Auth::user()->id;
        
          $booking->number_of_children = input::get('number_of_children');
        $booking->number_of_adults = input::get('number_of_adults');
        $booking->booking_currency = input::get('package_currency');
        
        $booking->booking_desc = Input::get('description');
        $booking->save();

        $quote = Quote::find(Input::get('quote_id'));
        $booking->date_from = $quote->date_from;
        $booking->number_of_days = $quote->number_of_days;
        $booking->date_to = $quote->date_to;
        $booking->save();
        

        $quoteFeature = QuoteFeature::where('quote_id', Input::get('quote_id'))->get();
        foreach($quoteFeature as $pf){
            $bookingFeature = new BookingFeature();
            $bookingFeature->booking_id = $booking->booking_id;
            $bookingFeature->supplier_parent_id = $pf->supplier_parent_id;
            $bookingFeature->supplier_id = $pf->supplier_id;
            $bookingFeature->items = $pf->items;
            $bookingFeature->save();
        }

        $quoteItinerary = QuoteItinerary::where('quote_id', Input::get('quote_id'))->get(); 
        foreach ($quoteItinerary as $p) {
            $bookingItinerary = new BookingItinerary();
            $bookingItinerary->booking_id = $booking->booking_id;
            $bookingItinerary->itinerary_title = $p->itinerary_title;
            $bookingItinerary->days = $p->days;
            $bookingItinerary->things_todo = $p->things_todo;
            $bookingItinerary->extra_notes = $p->extra_notes;
            $bookingItinerary->save();
            
            $quoteItineraryImage = QuoteItineraryImage::where('quote_id', Input::get('quote_id'))->where('quote_itinerary_id',$p->quote_itinerary_id)->get(); 
        if($quoteItineraryImage){
            foreach ($quoteItineraryImage as $q) {
            $bookingItineraryImage = new BookingItineraryImage();
            $bookingItineraryImage->booking_id = $booking->booking_id;
            
            $bookingItineraryImage->booking_itinerary_id = $bookingItinerary->booking_itinerary_id;
            $bookingItineraryImage->days = $q->days;
            $bookingItineraryImage->image = $q->image;
            
            $bookingItineraryImage->save();
        }
        }
        
        
    }    
        


        $name = explode(" ", Input::get('contact_name'));
        $contact = new Contact();
        $contact->first_name = $name[0];
        $contact->last_name = (!empty($name[1])?$name[1]:'');
        $contact->save();

        $booking->contact_id = $contact->contact_id;
        $booking->save();
        return Redirect::to('/bookings/dates/' . $booking->booking_id);
        }
        
    }

    
    
     public function createFromQuote($quote_id) {
         
        $quote = Quote::find($quote_id); 
        $package = Package::find($quote->quote_id);
        $quote_items = QuoteItem::where('quote_id',$quote->quote_id)->get();
        $quote_itinerary = QuoteItinerary::where('quote_id',$quote->quote_id)->get();
        
        $booking = new Booking();
        $booking->package_id = $quote->package_id;
        $booking->contact_id = $quote->contact_id;
        $booking->quote_id = $quote->quote_id;
        $booking->booking_title = $quote->quote_title;
        $booking->booking_cost = $quote->quoted_cost;
        $booking->discount = $quote->discount;
        $booking->booking_status = $quote->quote_status;
        $booking->date_from = $quote->date_from;
        $booking->date_to = $quote->date_to;
        $booking->number_of_days = $quote->number_of_days;
        $booking->location_from = $quote->location_from;
        $booking->location_to = $quote->location_to;
        $booking->source_from = "Quotes";
        $booking->created_on = date('Y-m-d H:i:s');
        $booking->created_by = Auth::user()->id;
        $booking->updated_on = date("Y-m-d H:i:s");
        $booking->updated_by = Auth::user()->id;
        $booking->booking_desc = $quote->quote_desc;
        $booking->save();


        $quote_items = QuoteItem::where('quote_id', $quote->quote_id)->get();
        
        foreach ($quote_items as $p) {
            $booking_items = new BookingItem();
            $booking_items->booking_id = $booking->booking_id;
            $booking_items->package_id = $p->package_id;
            $booking_items->quote_id = $p->quote_id;
            $booking_items->supplier_type_id = $p->supplier_type_id;
            $booking_items->supplier_type_item_id = $p->supplier_type_item_id;
            $booking_items->cost = $p->cost;
            $booking_items->extra_notes = $p->extra_notes;
            $booking_items->supplier_id = $p->supplier_id;
            $booking_items->save();
        }
        
        $quote_supplier = QuoteSupplier::where('quote_id', $quote->quote_id)->get();
        
        foreach ($quote_supplier as $p) {
            $booking_supplier = new BookingSupplier();
            $booking_supplier->booking_id = $booking->booking_id;
            $booking_supplier->package_id = $p->package_id;
            $booking_supplier->quote_id = $p->quote_id;
            $booking_supplier->supplier_type_id = $p->supplier_type_id;
            $booking_supplier->supplier_type_parent_id = $p->supplier_type_parent_id;
            $booking_supplier->supplier_id = $p->supplier_id;
            
            $booking_supplier->save();
            
            
        }

        $quoteItinerary = QuoteItinerary::where('quote_id', $quote->quote_id)->get(); 
        foreach ($quoteItinerary as $p) {
            $bookingItinerary = new BookingItinerary();
            $bookingItinerary->booking_id = $booking->booking_id;
            $bookingItinerary->quote_id = $p->quote_id;
            $bookingItinerary->package_id = $p->package_id;
            $bookingItinerary->itinerary_title = $p->itinerary_title;
            $bookingItinerary->days = $p->days;
            $bookingItinerary->things_todo = $p->things_todo;
            $bookingItinerary->extra_notes = $p->extra_notes;
            $bookingItinerary->save();
            $quoteItineraryImage = QuoteItineraryImage::where('quote_id', $quote->quote_id)->where('quote_itinerary_id',$p->quote_itinerary_id)->get(); 
        if($quoteItineraryImage){
            foreach ($quoteItineraryImage as $q) {
            $bookingItineraryImage = new BookingItineraryImage();
            $bookingItineraryImage->booking_id = $booking->booking_id;
            
            $bookingItineraryImage->booking_itinerary_id = $bookingItinerary->booking_itinerary_id;
            $bookingItineraryImage->days = $q->days;
            $bookingItineraryImage->image = $q->image;
            
            $bookingItineraryImage->save();
        }
        }
        }

        return Redirect::to('/bookings/'.$booking->booking_id.'/edit');
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
        $data['booking'] = Booking::where('booking_id', $id)->first();
        $data['bookings'] = Booking::all();
        $data['contacts'] = Contact::all();
        

        
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


//$data['contacts'] =  $json_array->data;
//ZnUtilities::pa($data['contacts']);die();

        ZnUtilities::push_js_files('components/bookings.js');
        ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
         $editor_js = '$(function() {
                      CKEDITOR.replace("description");
                      });';
               ZnUtilities::push_js($editor_js);

        $basicPageVariables = ZnUtilities::basicPageVariables("Bookings", "Edit Booking", "edit_booking", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Bookings'] = array("link" => '/booking', "active" => '0');
        $data['breadcrumbs']['Edit Booking'] = array("link" => "", "active" => '1');
        
        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.bookings.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
   public function update($booking_id) {
        $booking = Booking::find($booking_id);
        $booking->package_id = Input::get('package_id');
        $booking->booking_title = Input::get('booking_title');
        $booking->booking_cost = Input::get('booking_cost');
        $booking->discount = Input::get('discount');
        $booking->updated_on = date("Y-m-d H:i:s");
        $booking->updated_by = Auth::user()->id;
         $booking->number_of_children = input::get('number_of_children');
        $booking->number_of_adults = input::get('number_of_adults');
        $booking->booking_currency = input::get('package_currency');
        
        $booking->booking_desc = Input::get('description');
        
//        $name = explode(" ", Input::get('contact_name'));
//        $contact = new Contact();
//        $contact->first_name = $name[0];
//        $contact->last_name = (!empty($name[1])?$name[1]:'');
//        $contact->save();
//        $booking->contact_id = $contact->contact_id;
        
         $booking->contact_id = Input::get('contact_name');
        $booking->save();

   
                 return Redirect::to('/bookings/'.$booking_id.'/edit');

        
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

    public function dates($booking_id) {
        $data = array();
        $data['booking'] = Booking::find($booking_id);
        


        ZnUtilities::push_js_files('components/bookings.js');

        $basicPageVariables = ZnUtilities::basicPageVariables("Bookings", "Booking Dates", "add_booking", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Bookings'] = array("link" => '/booking', "active" => '0');
        $data['breadcrumbs']['Add New Booking'] = array("link" => "", "active" => '1');
        
        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.bookings.dates', $data);
    }

    public function saveDates() {
        
        $booking_id = Input::get('booking_id');
        

        $date_from = ZnUtilities::format_date(Input::get('date_from'), '1');
        $days = Input::get('number_of_days');
        
        $date = new DateTime($date_from);
        $date->add(new DateInterval('P' . $days . 'D'));
        $date_to = $date->format('Y-m-d');
        
        $booking = Booking::find($booking_id);
        $booking->date_from = $date_from;
        $booking->date_to = $date_to;
        $booking->number_of_days = $days;
        $booking->save();
        
        return Redirect::to('/bookings/features/' . $booking->booking_id);
    }

    public function items($booking_id) {
        $data = array();

        $data['booking'] = Booking::find($booking_id);
        $data['package'] = Package::where('package_id', $data['booking']->package_id)->first();
        $data['items'] = BookingItem::where('booking_id', $booking_id)->get();
        $data['suppliertype'] = SupplierType::all();


        ZnUtilities::push_js_files('components/bookings.js');
        ZnUtilities::push_js_files('components/packages.js');


        $basicPageVariables = ZnUtilities::basicPageVariables("Bookings", "Booking Dates", "add_booking", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Bookings'] = array("link" => '/booking', "active" => '0');
        $data['breadcrumbs']['Items'] = array("link" => "", "active" => '1');
        
        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.bookings.items', $data);
    }

    public function saveItems() {
//        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
//            return Redirect::to('/permissionDenied');
//        }

      //  $data = array();

        $booking_id = Input::get('booking_id');

        BookingItem::where('booking_id', $booking_id)->delete();
        $booking = Booking::find($booking_id);
        
       
        $package_id = $booking->package_id;
        $supplier_item = Input::get('supplier_item');
        $supplier_type = Input::get('supplier_type');
        $cost = Input::get('cost');
        $extra_notes = Input::get('extra_notes');
        //$new_item = Input::get('supplier_new_item');

        foreach ($supplier_type as $k => $v) {
            $item = new BookingItem();
            $item->package_id = $package_id;
            $item->booking_id = $booking_id;
            $item->supplier_type_id = $v;
            $item->supplier_type_item_id = $supplier_item[$k];
            $item->cost = $cost[$k];
            $item->extra_notes = $extra_notes[$k];
            $item->save();
        }

        return Redirect::to('/bookings/items/' .$booking_id)->with('success_message', 'Booking Item Created successfully!');
    }

 
     
    public function itinerary($booking_id)
    {
          $data = array();

        $booking = Booking::find($booking_id);
        $data['booking'] = $booking;
        $data['day'] = $data['booking']->number_of_days;
        $data['itineraries'] = BookingItinerary::where('booking_id',$booking_id)->orderBy('days','asc')->get();
        $data['thingstodo'] = ThingToDo::get();
                
        ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/bookings.js');
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


        $basicPageVariables = ZnUtilities::basicPageVariables("Booking Itinerary", "Booking Itinerary", "add_package", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Packages'] = array("link" => '/packages', "active" => '0');
        $data['breadcrumbs']['Package Item'] = array("link" => "", "active" => '1');

        //return View::make('admin.supplier.create',$data);
        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.bookings.itinerarylist', $data);
    }
    
    public function  saveItinerary()
    {
         if(!User::checkPermission(Auth::user()->user_group_id,'itinerary','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
             $booking_id = Input::get('booking_id');
            
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
                
                $booking_id = Input::get('booking_id');
                $booking = Booking::find($booking_id);
              
                $itineraries = implode(', ', $things) ;
                $itinerary = new BookingItinerary();
                $itinerary->things_todo = $itineraries;
                $itinerary->booking_id = $booking->booking_id;
                $itinerary->itinerary_title = Input::get('itinerary_title');
                $itinerary->days = Input::get('day');
                $itinerary->extra_notes = Input::get('extra_note');
                $itinerary->save();
                
                
                 $attachments = Input::get('specs_location');
            
                foreach ($attachments as $a):
                   
                    $attachment = new BookingItineraryImage();
                   
                    $attachment->image = $a; 
                    
                    $attachment->booking_id=$booking->booking_id;
                    $attachment->booking_itinerary_id=$itinerary->booking_itinerary_id;
                    $attachment->days=Input::get('day');
                    $attachment->save();
                    endforeach;
                
                return Redirect::to('bookings/itinerary/'.$booking_id)->with('success_message', 'Itinerary created successfully!');
            }
            else
            {
               
                return Redirect::to('bookings/itinerary/'.$booking_id)->withErrors($validator)->withInput();;
            }
    }
    
     public function editItinerary($booking_id,$itinerary)
    { $data = array();

        $booking = Booking::find($booking_id);
        $data['booking'] = $booking;
        
        $data['day'] = $data['booking']->number_of_days;
        $data['itineraries'] = BookingItinerary::where('booking_id',$booking_id)->get();
        
        $data['thingstodo'] = ThingToDo::get();
        
        if(!empty($itinerary)){
         $data['booking_itinerary']= BookingItinerary::where('booking_itinerary_id',$itinerary)->first();
         $data['booking_itinerary_thingstodo']= explode(',', $data['booking_itinerary']->things_todo);
          $data['count']= count($data['booking_itinerary_thingstodo']);
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
        return View::make('admin.bookings.edit_itinerarylist', $data);
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
                $booking_itinerary_id=Input::get('booking_itinerary_id');
                $booking_id = Input::get('booking_id');
                $itineraries = implode(', ', $things) ;
                $itinerary = BookingItinerary::find($booking_itinerary_id);
                $itinerary->things_todo = $itineraries;
                $itinerary->booking_id = $booking_id;
               
                $itinerary->itinerary_title = Input::get('itinerary_title');
                $itinerary->days = Input::get('day');
                $itinerary->extra_notes = Input::get('extra_note');
                $itinerary->save();
                 
                $attachments = Input::get('specs_location');
                
              if (is_array($attachments)) {
                 $delete = BookingItineraryImage::where('booking_itinerary_id',$booking_itinerary_id)->where('days',Input::get('day'))->delete();
                foreach ($attachments as $a):
                    $attachment = new BookingItineraryImage();
                    

                    $attachment->image = $a; 
                    
                    $attachment->booking_id = $booking_id;
                    $attachment->booking_itinerary_id = $booking_itinerary_id;
                    $attachment->days=Input::get('day');
                    $attachment->save();
                    endforeach;
              }
                return Redirect::to('/bookings/itinerary/'.$booking_id)->with('success_message', 'Itinerary created successfully!');
            }
            else
            {
               
                return Redirect::to('/bookings/itinerary/'.$booking_id)->withErrors($validator)->withInput();;
            }
    }
    
    public function removeItinerary($booking_itinerary_id)
    {
        $booking_itinerary = BookingItinerary::find($booking_itinerary_id);
        $booking_id = $booking_itinerary->booking_id;
        $booking_itinerary->delete();
        
        return Redirect::to('bookings/itinerary/'.$booking_id)->with('success_message', 'Itinerary created successfully!');
    }
    
      public function removeItineraryImage($booking_itinerary_image_id)
    {
        $booking_itinerary_image = BookingItineraryImage::find($booking_itinerary_image_id);
        $booking_id = $booking_itinerary_image->booking_id;
        $booking_itinerary_image->delete();
        
        return Redirect::to('bookings/itinerary/'.$booking_id)->with('success_message', 'Itinerary  Image remove successfully!');
    }
   
    public function showBookings($booking_id){
        $data = array();
         $data['booking'] = Booking::find($booking_id);
       
        $data['items'] = BookingItem::where('booking_id', $booking_id)->get();
        $data['itineraries'] = BookingItinerary::where('booking_id',$booking_id)->orderBy('days','asc')->get();

        ZnUtilities::push_js_files('components/bookings.js');

        $basicPageVariables = ZnUtilities::basicPageVariables("Bookings", "Show Booking", "show_booking", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Quotes'] = array("link" => '/bookings', "active" => '0');
        $data['breadcrumbs']['Edit Quote'] = array("link" => "", "active" => '1');

        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.bookings.show', $data);
        
        
    }
    public function invoiceBookingsPreview($booking_id){
        $data = array();
         $data['booking'] = Booking::find($booking_id);
        $data['package'] = Package::find($data['booking']->package_id);
        $data['quote'] = Quote::find($data['booking']->quote_id);

       // ZnUtilities::pa($data['quote']);die();

        $date_from = $data['booking']->date_from;
        $days = $data['booking']->number_of_days;

        $date = new DateTime($date_from);
        $date->add(new DateInterval('P' . $days . 'D'));
        $date_to = $date->format('Y-m-d');

        $data['date_to'] = $date_to;
        $data['items'] = BookingItem::where('booking_id', $booking_id)->get();
        $data['itineraries'] = BookingItinerary::where('booking_id',$booking_id)->orderBy('days','asc')->get();

        ZnUtilities::push_js_files('components/bookings.js');

      $path = "pdf_new";
            
          $view_content = View::make('admin.bookings.invoice',$data);
          return $pdf= PDF::load($view_content, 'A4', 'portrait')->show(); 


//           if (!is_dir($path)){
//           mkdir($path, 0777);  
//              }
//$data=array();
//$outputName = str_random(10); 
//$pdfPath = $path.'/'.$outputName.'.pdf';
//File::put($pdfPath, PDF::load($view_content, 'A4', 'portrait')->output());
//
//Mailgun::send('emails.invoice', $data, function($message) use ($pdfPath){
//    $message->from('sdhillon.zs@gmail.com');
//    $message->to('shrikar.verosion@gmail.com');
//    $message->attach($pdfPath);
//});

        
        
    }
    public function invoiceBookings($booking_id){
        $data = array();
              $data = array();
         $data['booking'] = Booking::find($booking_id);
        $data['package'] = Package::find($data['booking']->package_id);
        $data['quote'] = Quote::find($data['booking']->quote_id);

       // ZnUtilities::pa($data['quote']);die();

        $date_from = $data['booking']->date_from;
        $days = $data['booking']->number_of_days;

        $date = new DateTime($date_from);
        $date->add(new DateInterval('P' . $days . 'D'));
        $date_to = $date->format('Y-m-d');

        $data['date_to'] = $date_to;
        $data['items'] = BookingItem::where('booking_id', $booking_id)->get();
        $data['itineraries'] = BookingItinerary::where('booking_id',$booking_id)->orderBy('days','asc')->get();

        ZnUtilities::push_js_files('components/bookings.js');

      $path = "pdf_new";
            
          $view_content = View::make('admin.bookings.invoice',$data);
         // return $pdf= PDF::load($view_content, 'A4', 'portrait')->show(); 


           if (!is_dir($path)){
           mkdir($path, 0777);  
              }
$data=array();
$outputName = "INVOICE123BOOK-".$booking_id; 
$pdfPath = $path.'/'.$outputName.'.pdf';
if(file_exists($pdfPath)!=1){

File::put($pdfPath, PDF::load($view_content, 'A4', 'portrait')->output());



$invoice = new BookingInvoice();
$invoice->booking_invoice_pdf = $pdfPath;
$invoice->created_on =date('Y-m-d H:i:s');
$invoice->booking_id = $booking_id;
$invoice->save();
}

$pdf = public_path().'/'.$pdfPath;




Mailgun::send('emails.invoice', $data, function($message) use ($pdf){
    $message->from('sdhillon.zs@gmail.com');
    $message->to('gurpreet.verosion@gmail.com');
    $message->attach($pdf);
});

$invoices = BookingInvoice::where('booking_id',$booking_id)->first();
$invoices->sent_on = date('Y-m-d H:i:s');
$invoices->save();
return Redirect::to('/bookings/'.$booking_id.'/show')->with('success_message', 'Invoices Send successfully!');
        
        
    }
    
    
     public function suppliers($booking_id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'package', 'manage')) {
            return Redirect::to('/permissionDenied');
        }
        
           $data = array();
      
       
         $data['booking'] = Booking::findOrFail($booking_id);
      
         $data['booking_suppliers'] = BookingSupplier::where('booking_id',$booking_id)->get();
  
        $data['count'] = count($data['booking_suppliers']);
        $js = "indexCounter = '" . $data['count'] . "'";
        ZnUtilities::push_js($js);
    
        
        ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/bookings.js');

        $basicPageVariables = ZnUtilities::basicPageVariables("Supplier", "Add New Supplier", "add_supplier", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Supplier'] = array("link" => '/supplier', "active" => '0');
        $data['breadcrumbs']['Add New Supplier'] = array("link" => "", "active" => '1');

//       $data['submenus'] = $this->_submenus('index');
        
        $data['submenus'] = $this->_submenus('index');
    return View::make('admin.bookings.suppliers', $data);
        
    }
    
    public function  saveSuppliers($id)
    {
         if(!User::checkPermission(Auth::user()->user_group_id,'package','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
           
            $package_id = BookingSupplier::where('booking_id',$id)->pluck('package_id');
            $quote_id = BookingSupplier::where('booking_id',$id)->pluck('quote_id');
            
            BookingSupplier::where('booking_id',$id)->delete();
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
                $booking_supplier = new BookingSupplier();
                $booking_supplier->supplier_type_id = $supplier_type_parent_id[$k];
                $booking_supplier->supplier_type_parent_id = $v;
                $booking_supplier->quote_id = $quote_id;
                $booking_supplier->package_id = $package_id;
                $booking_supplier->booking_id = $id;
                $booking_supplier->supplier_id = $supplier_id[$k];
                
                $booking_supplier->save();
                
            }   
                return Redirect::to('bookings/suppliers/'.$id)->with('success_message', 'Suppliers created successfully!');
            }
            else
            {
               
                return Redirect::to('bookings/suppliers/'.$id)->withErrors($validator)->withInput();;
            }
    }
    
    
 public function bookingItems($booking_id) {
        $data = array();

       $data['booking'] = Booking::find($booking_id);
       $data['items'] = BookingItem::where('booking_id', $booking_id)->get();
       $data['booking_suppliers'] = BookingSupplier::where('booking_id',$booking_id)->groupby('supplier_type_parent_id')->distinct()->get();
        
        
        $data['count'] = count($data['items']);
        $js = "indexCounter = '" . $data['count'] . "'";
        ZnUtilities::push_js($js);

        ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/bookings.js');
       
        $basicPageVariables = ZnUtilities::basicPageVariables("Bookings", "Booking Items", "add_booking_items", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Bookings'] = array("link" => '/booking', "active" => '0');
        $data['breadcrumbs']['Items'] = array("link" => "", "active" => '1');
        
        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.bookings.items', $data);
    }

    public function saveBookingItems($booking_id) {
//        if (!User::checkPermission(Auth::user()->user_group_id, 'suppliers', 'manage')) {
//            return Redirect::to('/permissionDenied');
//        }

        $booking = Booking::find($booking_id);
        BookingItem::where('booking_id', $booking_id)->delete();
       
        $package_id = $booking->package_id;
        $quote_id = $booking->quote_id;
        $supplier_item = Input::get('supplier_item');
        $supplier_type = Input::get('supplier_type');
        $cost = Input::get('cost');
        $extra_notes = Input::get('extra_notes');
        $supplier_id = Input::get('supplier_id');
        
        foreach ($supplier_type as $k => $v) {
            $item = new BookingItem();
            $item->package_id = $package_id;
            $item->booking_id = $booking_id;
            $item->quote_id = $quote_id;
            $item->supplier_type_id = $v;
            $item->supplier_type_item_id = $supplier_item[$k];
            $item->cost = $cost[$k];
            $item->extra_notes = $extra_notes[$k];
            $item->supplier_id = $supplier_id[$k];
            
            $item->save();
        }
        
       

        return Redirect::to('/bookings/items/' .$booking_id)->with('success_message', 'Booking Item Created successfully!');
    }
  public function cancelBooking($booking_id)
    {
          $data = array();

        $booking = Booking::find($booking_id);
        $data['booking'] = $booking;
     
            $reason = CancelBooking::find($booking_id);
         $data['reason'] = $reason;   
                
        ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/reasons.js');
          ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
                    $editor_js = '$(function() {
        
                       CKEDITOR.replace("reason_description");
                       });';
                ZnUtilities::push_js($editor_js);
      
        $basicPageVariables = ZnUtilities::basicPageVariables("Bookings", "Add Payment Types", "add_payments", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Booking'] = array("link" => '/booking', "active" => '0');
        $data['breadcrumbs']['Add Payment'] = array("link" => "", "active" => '1');

        //return View::make('admin.supplier.create',$data);
        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.bookings.cancelbooking', $data);
    }
    
    
     public function  saveCancelBooking($id)
    {
       
            $validator = Validator::make(
                array(
                  'reason' => Input::get('reason'),
                    ),
                array(
                  'reason' => 'required',
                    )
            );
            
         if($validator->passes())
            {
          
                $reasons = Input::get('reason');
                $reason_description = Input::get('reason_description');
                $c_id = CancelBooking::where('booking_id',$id)->pluck('cancel_booking_id');
                
                  $booking = Booking::find($id);
                  
              if($c_id){
                  
                $reason = CancelBooking::find($c_id);
             
                $reason->reason = $reasons;
                $reason->date = date('y-m-d');
                $reason->reason_description = $reason_description;
                $reason->booking_id = $id;
              
                $booking->booking_status = 'cancel';
                
              }
              else{
                $reason = new CancelBooking();
                $reason->reason = $reasons;
                $reason->date = date('y-m-d');
                $reason->reason_description = $reason_description;
                $reason->booking_id = $id;
                $booking->booking_status = 'cancel';
              }
                $reason->save();
                $booking->save();
                
                return Redirect::to('bookings/cancelbooking/'.$id)->with('success_message', 'Cancel Booking Process Start successfully!');
            }
            else
            {  
                return Redirect::to('bookings/cancelbooking/'.$id)->withErrors($validator)->withInput();;
            }
    }
	

     public function tasks($booking_id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'task','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
		  $data = array();
                  
                   $data['booking'] = Booking::find($booking_id);
                   $data['task'] = BookingTask::where('booking_id',$booking_id)->with('user_name','taskComment')->paginate(10);
                
                
                $data['assign_to'] = User::where('user_group_id','=',2)->get();
                
                ZnUtilities::push_js_files("jquery.validate.min.js");
                ZnUtilities::push_js_files("components/task.js");
                
                  ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
                    $editor_js = '$(function() {
                       // $("#content").wysihtml5();
                       CKEDITOR.replace("task_description");
                       });';
                ZnUtilities::push_js($editor_js);

                


                $basicPageVariables = ZnUtilities::basicPageVariables("Task","Booking Tasks", "Task","1");
                $data = array_merge($data,$basicPageVariables);
                
                $data['breadcrumbs']['All Task'] = array("link"=>'bookings/tasks/',"active"=>'0');
                $data['breadcrumbs']['New Task'] = array("link"=>"","active"=>'1'); 
            
                $data['submenus'] = $this->_submenus('index');
               return View::make('admin.bookings.tasks',$data);
	}
        
         public function editTask($booking_id , $task_id)
	{
             if(!User::checkPermission(Auth::user()->user_group_id,'task','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
		  $data = array();
                  
                   $data['booking'] = Booking::find($booking_id);
                   $data['task'] = BookingTask::where('booking_id',$booking_id)->with('user_name','taskComment')->paginate('10');
                    $data['tasks'] = BookingTask::findOrFail($task_id);
                
                $data['assign_to'] = User::where('user_group_id','=',2)->get();
                
                $data['task_status'] = BookingTask::where('booking_task_id',$task_id)->pluck('task_status');
                
                 $data['commentCount'] = TaskComment::where('booking_task_id',$task_id)->get()->count();
                
                
                $taskComment = TaskComment::where('booking_task_id',$task_id)->orderBy('comment_id','desc')->get()->toArray();
                
                  $taskscomm = array();
                $taskdate = array();
                $username = array();
                foreach($taskComment as $t)
                {
                    $taskscomm[] = $t['comments'];
                    $taskdate[] = $t['comment_date'];
                    $username[] = $t['user_id'];
                }
           
                
                $data['taskComment'] = $taskscomm;
                
                $data['commentdate'] = $taskdate;
                
                $data['username'] = $username;
                
                $data['uname'] = TaskComment::with('username')->get();
                
                
                ZnUtilities::push_js_files("jquery.validate.min.js");
                ZnUtilities::push_js_files("components/task.js");
                
                  ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
                    $editor_js = '$(function() {
                       // $("#content").wysihtml5();
                       CKEDITOR.replace("task_description");
                       });';
                ZnUtilities::push_js($editor_js);

                $basicPageVariables = ZnUtilities::basicPageVariables("Task","Booking Tasks", "Task","1");
                $data = array_merge($data,$basicPageVariables);
                
                $data['breadcrumbs']['All Task'] = array("link"=>'bookings/tasks/',"active"=>'0');
                $data['breadcrumbs']['New Task'] = array("link"=>"","active"=>'1'); 
            
                $data['submenus'] = $this->_submenus('index');
               return View::make('admin.bookings.edit_tasks',$data);
	}
        
        public function saveTasks($booking_id)
	
                {
             if(!User::checkPermission(Auth::user()->user_group_id,'task','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
     
            $validator = Validator::make(
                array(
                    'task_title' => Input::get('task_title'),
                    'task_description' => Input::get('task_description'),
                    'assign_to' => Input::get('assign_to'),
                    'due_date' => Input::get('due_date'),
                   
                    ),
                array(
                    'task_title' => 'required',
                    'task_description' => 'required',
                    //'assign_to' => 'required',
                    'due_date' => 'required',
                  
                    )
            );
            
            if($validator->passes())
            {
                 
                $task = new BookingTask();
                $task->task_title = Input::get('task_title');
                $task->task_description = Input::get('task_description');
                $task->assign_to = Input::get('assign_to');
                $task->due_date = Input::get('due_date');
                $task->assign_date = date('Y/m/d');
                $task->task_status = 'Open';
                $task->booking_id = $booking_id;
                
                //ZnUtilities::pa(date('Y/m/d h:i:s a')); die();
              
                $task->save();
                
             
                return Redirect::to('bookings/tasks/'.$booking_id)->with('success_message', 'Task created successfully!');
            }
            else
            {
                return Redirect::to('bookings/tasks/'.$booking_id)->withErrors($validator)->withInput();;
            }
            
	}
        public function updateTasks($booking_id,$task_id)
	
                {
             if(!User::checkPermission(Auth::user()->user_group_id,'task','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
           $task = BookingTask::find($task_id);
           
            $validator = Validator::make(
                array(
                    'task_title' => Input::get('task_title'),
                    'task_description' => Input::get('task_description'),
                    'assign_to' => Input::get('assign_to'),
                    'due_date' => Input::get('due_date'),
                   
                    ),
                array(
                    'task_title' => 'required',
                    'task_description' => 'required',
                    //'assign_to' => 'required',
                    'due_date' => 'required',
                  
                    )
            );
            
            if($validator->passes())
            {
               
                $task->task_title = Input::get('task_title');
                $task->task_description = Input::get('task_description');
                $task->assign_to = Input::get('assign_to');
                $task->due_date = Input::get('due_date');
                $task->assign_date = date('Y/m/d');
               // $task->task_status = 'Open';
                $task->booking_id = $booking_id;
                
                //ZnUtilities::pa(date('Y/m/d h:i:s a')); die();
              
                $task->save();
                
                return Redirect::to('bookings/tasks/edit/'.$booking_id.'/'.$task_id)->with('success_message', 'Task created successfully!');
            }
            else
            {
               
                return Redirect::to('bookings/tasks/edit/'.$booking_id.'/'.$task_id)->withErrors($validator)->withInput();;
            }
            }
        
        
        public function saveComment($booking_id ,$task_id)
	{
       
            
           if(!User::checkPermission(Auth::user()->user_group_id,'task','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
         
//           $task = TaskComment::find($task_id);
           $tasks = BookingTask::find($task_id);
            $validator = Validator::make(
                array(
                    'comment' => Input::get('comments'),
                    'task_status' => Input::get('task_status'),
                  
                   
                    ),
                array(
                    'comment' => 'required',
                    'task_status' => 'required',
                
                  
                    )
            );
            
            if($validator->passes())
            {
                //ZnUtilities::pa(Input::get('comments')); die();
                 
                $name = Input::get('comments');
             
                $task_comment = new TaskComment();
                  $task_comment->comments = $name;
                $task_comment->booking_task_id = $task_id;
                $task_comment->comment_date = date('Y/m/d h:i:s a', time());
                $task_comment->user_id = Auth::user()->id;
                
                //ZnUtilities::pa(date('Y/m/d h:i:s a')); die();
              
                $task_comment->save();
                
                $tasks->task_status = Input::get('task_status');
                
                $tasks->save();
                
             
                return Redirect::to('bookings/tasks/'.$booking_id)->with('success_message', 'Task Comment created successfully!');
            }
            else
            {
               
                return Redirect::to('bookings/tasks/'.$booking_id)->withErrors($validator)->withInput();;
            }
            
	}
        
           public function taskActions($booking_id)
        {
                  if(!User::checkPermission(Auth::user()->user_group_id,'task','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $search = Input::get('search');
            if($search!='')
            {
                return Redirect::to('bookings/taskSearch/'.$search.'/'.$booking_id);
            }
            else{
                
                
            //die(Input::get('bulk_action')   );
            
            $cid = Input::get('cid');
//            ZnUtilities::pa($cid);die();
            $bulk_action = Input::get('bulk_action');
            if($bulk_action!='')
            {
                switch($bulk_action)
                {
                   
                    
                    case 'delete':
                    {
                       
                        
                        foreach($cid as $id)
                        {
                            DB::table('booking_tasks')
                                ->where('booking_task_id', $id)
                                ->delete();
                        }
                        
                        return Redirect::to('bookings/tasks/'.$booking_id)->with('success_message', 'Rows Delete!');
                        break;
                    }
                } //end switch
            } // end if task_descriptionment
            return Redirect::to('bookings/tasks/'.$booking_id);
            }
        }
        
         public function taskSearch($search,$booking_id)
        {
            if(!User::checkPermission(Auth::user()->user_group_id,'task','manage'))
            {
                return Redirect::to('/permissionDenied');
            }
            
            $data = array();
         
               $keyword = $search;
                $data['keyword'] = $keyword; 
                

                $booking_task  = DB::table('booking_tasks as t');
                $booking_task->leftJoin('users as u','u.id','=','t.assign_to');
                $package->leftJoin('locations as l','l.location_id','=','e.location_id');
                
               
                
                if($keyword!=''){
                    $keyword = trim($keyword);
                    $booking_task->orWhere(function ($booking_task) use ($keyword) 
                        {
                        
                            
                            $booking_task->where("t.task_title","like","%".$keyword."%")
                                     ->orwhere("t.task_description","like","%".$keyword."%")
                                     ->orwhere("t.task_status","like","%".$keyword."%")
                                   ->orwhere("u.name","like","%".$keyword."%");
//                                   ->orwhere("e.hotel_estimate_cost_child","like","%".$keyword."%")
                                 
                        });
                }
                 
               // $data['task'] = $booking_task->paginate(10);
                 
                 
                   $data['booking'] = Booking::find($booking_id);
                   $data['task'] = BookingTask::where('booking_id',$booking_id)->with('user_name','taskComment')->with($booking_task)->paginate(10);
                
                
                $data['assign_to'] = User::where('user_group_id','=',2)->get();
                 
                //Basic Page Settings
               
                $basicPageVariables = ZnUtilities::basicPageVariables("Task","Search results", "Task","1");
                $data = array_merge($data,$basicPageVariables);
               
                $data['breadcrumbs']['All Task'] = array("link"=>'bookings/tasks/'.$bookig_id,"active"=>'0');
               $data['breadcrumbs']['Search'] = array("link"=>"","active"=>'1'); 
               
                $data['search'] = $search;
                $data['submenus'] = $this->_submenus('index');
                return View::make('admin.bookings.tasks',$data);
               
        }
        
        public function payments($booking_id)
    {
          $data = array();

        $booking = Booking::find($booking_id);
        $data['booking'] = $booking;
        $payments = BookingPayment::where('booking_id',$booking_id)->orderBy('booking_payment_id', 'DESC')->get();
       $due = 0;
        $data['payments'] = $payments;
        $payment = array($payments);
     
     foreach($payments as $key){
      $payment[] = $key->amount;
      }
  $due = array_sum($payment); 
                
      $data['due'] = $due ;
//       ZnUtilities::pa($f); die;
                
        ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/bookings.js');
        ZnUtilities::push_js_files("components/itinerary.js");
          ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
                    $editor_js = '$(function() {
      
                       CKEDITOR.replace("comment");
                       });';
                ZnUtilities::push_js($editor_js);
      
        $basicPageVariables = ZnUtilities::basicPageVariables("Bookings", "Add Payment Types", "add_payments", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Booking'] = array("link" => '/booking', "active" => '0');
        $data['breadcrumbs']['Add Payment'] = array("link" => "", "active" => '1');

        //return View::make('admin.supplier.create',$data);
        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.bookings.payments', $data);
    }
    
    public function  savePayments($booking_id)
    {
       
            
            $validator = Validator::make(
                array(
                    'payment_type' => Input::get('payment_type'),
                    'amount' => Input::get('amount'),
                   
                   
                    ),
                array(
                    'payment_type' => 'required',
                     'amount' => 'required', 
                    )
            );
            
         if($validator->passes())
            {
          $date_from = ZnUtilities::format_date(Input::get('due_date'), '1');
         
                $payments = new BookingPayment();
                $payments->booking_id = $booking_id;
                $payments->payment_type = Input::get('payment_type');
               if(Input::get('payment_type')!='Full&Final'){
                $payments->next_due = $date_from;
                }
                $payments->submit_on = date('y-m-d');
                $payments->amount = Input::get('amount');
                $payments->comment = Input::get('comment');
               $payments->save();
                
                return Redirect::to('bookings/payments/'.$booking_id)->with('success_message', 'Payment Process created successfully!');
             }
            else
            {
               
                return Redirect::to('bookings/payments/'.$booking_id)->withErrors($validator)->withInput();;
            }
    }
   

 public function showAllBookings()
    {
//        if (!User::checkPermission(Auth::user()->user_group_id, 'package', 'manage')) {
//            return Redirect::to('/permissionDenied');
//        }

        $data = array();
        
        $data['bookings'] = Booking::orderBy('booking_id','desc')->get();
        
           ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/bookings.js');
        
        $basicPageVariables = ZnUtilities::basicPageVariables("Bookings", "All Bookings", "booking", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Bookings'] = array("link" => '/allbookings', "active" => '0');
        
        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.bookings.showbookings', $data);
    }
    
       public function bookingActions() {
    

        $search = Input::get('search');
        if ($search != '') {
            return Redirect::to('/bookingsSearch/' . $search);
        } else {


            //die(Input::get('bulk_action')   );
            // $package_id =  Input::get("package_id");
            $cid = Input::get('cid');
         
            
            
            $bulk_action = Input::get('bulk_action');
            
            if ($bulk_action != '') {
                switch ($bulk_action) {

                    case 'delete': {
                        
                            foreach($cid as $id) {
                                DB::table('bookings')
                                        ->where('booking_id', $id)
                                        ->delete();
                            }

                            return Redirect::to('/allbookings')->with('success_message', 'Rows Delete!');
                            break;
                        }
                } //end switch
            } // end if statement
            return Redirect::to('/allbookings');
        }
    }
    
    public function bookingSearch($search) {
//        if (!User::checkPermission(Auth::user()->user_group_id, 'package', 'manage')) {
//            return Redirect::to('/permissionDenied');
//        }


        $keyword = $search;
        $data['keyword'] = $keyword;


        $booking = DB::table('bookings as p');
//        $quote->leftJoin('locations as l', 'l.location_id', '=', 'p.location_from');
//                $package->leftJoin('locations as l','l.location_id','=','e.location_id');



        if ($keyword != '') {
            $keyword = trim($keyword);
            $booking->orWhere(function ($booking) use ($keyword) {


                $booking->where("p.booking_title", "like", "%" . $keyword . "%");

            });
        }


        $data['bookings'] = $booking->paginate(10);




        $basicPageVariables = ZnUtilities::basicPageVariables("Bookings", "Search results", "bookings", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Bookings'] = array("link" => '/allbookings', "active" => '0');
        $data['breadcrumbs']['Search'] = array("link" => "", "active" => '1');

        $data['search'] = $search;
         $data['submenus'] = $this->_submenus('index');
        return View::make('admin.Bookings.showbookings', $data);
    }
    
    
    
    
    public function sortBookings($cancel = null,$process = null)
    {
         
   
        $data = array();
        if($cancel == 'canceled')
        {
        $data['bookings'] = Booking::where('booking_status','cancel')->get();
        $data['action'] = 'canceled';
             
        }
        else if($process == 'process')
        {
        $data['bookings'] = Booking::where('booking_status','!=','cancel')->get();
         $data['action'] = 'process';
        }
        else
        {
           $data['bookings'] = Booking::all(); 
           $data['action'] = 'all';
        }
        
        
        
           ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/bookings.js');
        
        $basicPageVariables = ZnUtilities::basicPageVariables("Bookings", "All Bookings", "booking", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Bookings'] = array("link" => '/allbookings', "active" => '0');
        
        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.bookings.showbookings', $data);
    }
    
    public function changeStatus($id)
    {
        $data = array();
        
        $booking = Booking::find($id);
        $booking->booking_status = 'active';
        $booking->save();
        
        
        $data['bookings'] = Booking::where('booking_status','!=','cancel')->get();
        $data['action'] = 'process';
           ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("chosen.jquery.min.js");
        ZnUtilities::push_js_files('components/bookings.js');
        
        $basicPageVariables = ZnUtilities::basicPageVariables("Bookings", "All Bookings", "booking", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Bookings'] = array("link" => '/allbookings', "active" => '0');
        
        $data['submenus'] = $this->_submenus('index');
        return View::make('admin.bookings.showbookings', $data);
    }
    
        
     public function bookingFeature($booking_id){
        
       $data = array();

        $data['booking'] = Booking::find($booking_id); 
        $data['suppliers'] = Supplier::all();
        $data['supplier_type'] = SupplierType::with('subTypes')->where('supplier_type_parent_id','0')->get();
        
        ZnUtilities::push_js_files('components/bookings.js');
        
        $basicPageVariables = ZnUtilities::basicPageVariables("Feature", "Feature", "feature", "1");
        $data = array_merge($data, $basicPageVariables);
         $data['submenus'] = $this->_submenus('index');
        

        return View::make('admin.bookings.features', $data);
    }
    public function featureSave($id) {
       // ZnUtilities::pa($_POST);die();
        $quote_id=Input::get('booking_id');
        $feature=Input::get('features');
        BookingFeature::where('booking_id',$id)->delete();
        
        
        foreach($feature as $k=>$f){
            
            foreach($f as $K=>$F){
                
                $newfeature = new BookingFeature();
                        $newfeature->booking_id = $id;
                        $newfeature->supplier_parent_id = $k;
                        $newfeature->supplier_id = $K;
                        $newfeature->items = json_encode($F);
                        $newfeature->save();
                
            }
                
                }
                return Redirect::to('bookings/itinerary/'.$id)->with('success_message', 'Featured added successfully!');
    }

    
    
      private function _submenus($active)
    {   
        $data = array();
        $data['Bookings'] = array("link" => '/allbookings', "active" => $active=='index'?'1':'0' ,"icon" => 'fa-shopping-cart');
        return $data;
    }

}
