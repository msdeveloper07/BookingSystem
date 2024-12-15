<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/hello', function()
{
	return View::make('hello');
});
Route::get('/', 'HomeController@getIndex');
Route::get('/editleads/{contact_id}', 'ContactsController@editLead');
Route::post('/leads/{contact_id}/update', 'ContactsController@updateLeads');

Route::resource('api', 'BookingApiController');
Route::get('/api_example', 'HomeController@getcontacts');


Route::controller('login', 'LoginController');
Route::get('logout', array("as"=>"logout","uses"=>'LoginController@doLogout'));
Route::get('/forgotPassword', 'LoginController@forgotPassword');
Route::post('/processForgotPassword', 'LoginController@processForgotPassword');

//---Users--Route---//
Route::resource('users', 'UsersController');
Route::get('userSearch/{search}', 'UsersController@userSearch');
Route::post('userActions', 'UsersController@userActions');

//---Position--Route---//
Route::resource('position', 'PositionController');
Route::get('postionSearch/{search}', 'PositionController@positionSearch');
Route::post('positionActions', 'PositionController@positionActions');

//---SupplierType--Route---//
Route::resource('suppliertype', 'SupplierTypeController');
Route::get('suppliertypeSearch/{search}', 'SupplierTypeController@suppliertypeSearch');
Route::post('suppliertypeActions', 'SupplierTypeController@suppliertypeActions');
Route::get('suppliertypes/subtypes/{supplier_type_id}', 'SupplierTypeController@viewSubTypes');

//---SupplierTypeItem--Route---//
Route::resource('suppliertypeitem', 'SupplierTypeItemController');
Route::get('suppliertypeitemSearch/{search}', 'SupplierTypeItemController@supplieritemSearch');
Route::post('suppliertypeitemActions', 'SupplierTypeItemController@supplieritemActions');

Route::get('suppliertype/additem/{supp_id}', 'SupplierTypeItemController@addItem');
Route::post('suppliertype/saveitems', 'SupplierTypeItemController@saveItems');





//---Suppliers--Route---//
Route::resource('supplier', 'SuppliersController');
Route::get('supplierSearch/{search}', 'SuppliersController@supplierSearch');
Route::post('supplierActions', 'SuppliersController@supplierActions');
Route::get('ajax/getsupplierItemInfo/{supplier_id?}', 'AjaxController@getsupplierItemInfo');
Route::get('ajax/getsupplierItemPriceInfo/{supplier_id}', 'AjaxController@getsupplierItemPriceInfo');
Route::get('ajax/getpreet/{supplier_id}', 'SuppliersController@getpreet');
Route::post('suadditem','SuppliersController@addItem');

Route::get('supplier_detail/{suppid}','SuppliersController@Supplier_detail');
Route::post('supplier/supplierStore','SuppliersController@supplierStore');
Route::get('supplier/contacts/{suppid}','SuppliersController@Contacts');
//Route::get('supplier/contacts/{{suppid}}', 'SuppliersController@contacts');
Route::post('supplier/contact/{suppid}','SuppliersController@contactStore');

Route::get('supplier/items/{supplid}','SuppliersController@supplierItems');
Route::post('supplier/items/{suppid}','SuppliersController@supplierItemsStore');

Route::get('payment_method/{supplid}','SuppliersController@Payment_Method');
Route::post('payment_method_store/{supplid}','SuppliersController@Payment_Method_Store');


Route::get('commissions','SuppliersController@Show_Commission');
Route::get('Commission/{supplid}','SuppliersController@Commission');
Route::post('commission_store/{supplid}','SuppliersController@Commission_Store');

//---Edit---Suppliers--Route--//
Route::get('suppliercontacts_edit/{suppid}','SuppliersController@Contacts_Edit');
//Route::get('supplier/contacts/{{suppid}}', 'SuppliersController@contacts');
Route::post('suppliercontact_update/{suppid}','SuppliersController@Contact_Update');

Route::get('supplieritems_edit/{supplid}','SuppliersController@Supplier_Items_Edit');
Route::post('supplier_item_update/{suppid}','SuppliersController@Supplier_Items_Update');

Route::get('payment_method_edit/{supplid}','SuppliersController@Payment_Method_Edit');
Route::post('payment_method_update/{supplid}','SuppliersController@Payment_Method_Update');

Route::resource('image', 'ImageController');
Route::get('profile', 'ImageController@profile');
Route::post('updateprofile','AjaxController@manageProfile');	

Route::get('commissions','SuppliersController@Show_Commission');
Route::get('Commission_edit/{supplid}','SuppliersController@Commission_Edit');
Route::post('commission_update/{supplid}','SuppliersController@Commission_Update');


//---UsersGroups--Route---//
Route::resource('usergroup', 'UsersGroupsController');
Route::get('usergroupSearch/{search}', 'UsersGroupsController@usergroupSearch');
Route::post('usergroupActions', 'UsersGroupsController@usergroupActions');

//---Permission--Route---//
Route::resource('permission', 'PermissionsController');
Route::get('permissionSearch/{search}', 'PermissionsController@permissionSearch');
Route::post('permissionActions', 'PermissionsController@permissionActions');

//---Faq--Route---//
Route::resource('faq', 'FaqController');
Route::get('faqSearch/{search}', 'FaqController@faqSearch');
Route::post('faqActions', 'FaqController@faqActions');
Route::get('result/{id}','FaqController@result');
Route::get('faqs/uploadcsv','FaqController@uploadCsv');
Route::post('faqs/saveimportcsv','FaqController@saveImportCsv');

//------airline---------//
Route::resource('airlines', 'AirlinesController');
Route::post('airlineActions', 'AirlinesController@airlineActions');
Route::get('airlineSearch/{search}', 'AirlinesController@airlineSearch');
Route::get('airline/properties/{hotel_id}', 'AirlinesController@properties');
Route::post('airline/saveproperties/{hotel_id}', 'AirlinesController@saveProperties');
Route::get('airline/editproperties/{hotel_id}', 'AirlinesController@editProperties');
Route::post('airline/updateproperties/{hotel_id}', 'AirlinesController@updateProperties');


//---Message--Route---//
Route::resource('message_folders', 'MessageFolderController');
Route::post('message_folderActions', 'MessageFolderController@message_folderActions');

Route::resource('email_template_folders', 'EmailTemplateFolderController');
Route::post('email_template_folderActions', 'EmailTemplateFolderController@email_template_folderActions');

Route::resource('emailTemplates', 'EmailTemplatesController');
Route::get('/emailTemplates/folder/{folder_id?}', 'EmailTemplatesController@index');

Route::post('emailTemplateActions', 'EmailTemplatesController@emailTemplateActions');

Route::get('chooseTemplate/{lead_id}/{folder?}', 'EmailTemplatesController@chooseTemplate');
Route::get('chooseTemplate/confirm/{lead_id}/{template_id}', 'EmailTemplatesController@confirm');
Route::post('emailTemplates/send', 'EmailTemplatesController@send');
Route::get('emailTemplates/removeAttachment/{template_id}', 'EmailTemplatesController@removeAttachment');
Route::get('/makeFavorite/{template_id}', 'EmailTemplatesController@makeFavorite');
Route::get('/removeFavorite/{template_id}', 'EmailTemplatesController@removeFavorite');


//Route::get('/messages/incoming', 'MessagesController@incoming');
Route::get('/messages/messagePost/{id}', 'MessagesController@getMessagePost');
Route::post('/messages/trackOpens/', 'MessagesController@trackOpens');
Route::post('/messages/incoming', 'MessagesController@incoming');
Route::get('/messages/sent', 'MessagesController@sent');
Route::get('/messages/new', 'MessagesController@newEmail');
Route::post('/messages/new', 'MessagesController@sendEmail');
Route::get('/messages/leadMessages/{lead_id}', 'MessagesController@leadMessages');
Route::get('/messages/downloadAttachment/{message_id}', 'MessagesController@downloadAttachment');
Route::post('/messages/createLead/{message_id}', 'MessagesController@createLead');
Route::post('/messages/updateLeadEmail/{message_id}', 'MessagesController@updateLeadEmail');
Route::get('/messages/unlinkLead/{message_id}', 'MessagesController@unlinkLead');
Route::post('/messages/associate/{message_id}', 'MessagesController@associateToLead');
Route::post('messageActions', 'MessagesController@messageActions');
Route::get('/messages/resend/{id}', 'MessagesController@resend');
Route::post('/messages/forward/{id}', 'MessagesController@forward');
Route::get('/messages/showSingle/{id}/{type?}', 'MessagesController@showSingle');
Route::get('/messages/folder/{folder_id?}/{message_status?}/{action?}', 'MessagesController@index');

Route::resource('messages', 'MessagesController');

Route::get('ajax/getMessageContent/{message_id}','AjaxController@getMessageContent');
Route::get('ajax/getMessageThread/{message_id}','AjaxController@getMessageThread');
Route::get('ajax/getTemplateContent/{template_id}','AjaxController@getTemplateContent');
//--End--Message--Route---//


//--Items--//
Route::resource('items', 'ItemsController');
Route::post('itemActions', 'ItemsController@itemActions');
Route::get('itemSearch/{search}', 'ItemsController@itemSearch');


//---Currency--Route---//
Route::resource('currency', 'CurrencyController');
Route::post('/convertCurrency', 'CurrencyController@currencyConverted');
Route::post('/convertCurrencyModal', 'CurrencyController@currencyConvertedModal');

//---UsersGroupPermissions--Route---//
Route::resource('usergrouppermission', 'UsersGroupPermissionsController');


//---Contacts--Route---//
Route::resource('contacts', 'ContactsController');
Route::post('contactActions', 'ContactsController@contactActions');
Route::get('contactSearch/{search}', 'ContactsController@contactSearch');

//---MailingList--Route---//
Route::resource('mailinglists', 'MailingListController');
Route::get('mailinglistSearch/{search}', 'MailingListController@mailingListSearch');
Route::post('mailinglistActions', 'MailingListController@mailingListActions');

//---MailingListSubscriber--Route---//
Route::resource('mailinglistsubscribers', 'MailingListSubscriberController');
Route::get('mailinglistsubscriberSearch/{search}', 'MailingListSubscriberController@mailingListSubscriberSearch');
Route::post('mailinglistsubscriberActions', 'MailingListSubscriberController@mailingListSubscriberActions');

//---News Letters--//
Route::resource('newsletters','NewslettersController');
Route::post('newsletterActions','NewslettersController@newsletterActions');


//---Locations--Route---//
Route::resource('locations','LocationsController');
Route::get('locationSearch/{search}','LocationsController@locationSearch');
Route::post('locationActions','LocationsController@locationActions');


                                          
//---Payment Types--//
Route::resource('paymenttypes','PaymentTypesController');
Route::post('paymenttypeActions','PaymentTypesController@paymentTypeActions');
Route::get('paymenttypeSearch/{search}','PaymentTypesController@paymentTypeSearch');

Route::resource('reason','ReasonsController');
Route::post('reasonActions','ReasonsController@reasonActions');
Route::get('reasonSearch/{search}','ReasonsController@reasonSearch');

//---ToDoList---//
Route::resource('todolist','ToDoListController');
Route::get('todolistSearch/{search}','ToDoListController@todoListSearch');
Route::post('todolistActions','ToDoListController@todoListActions');


//---Packages--Route---//

Route::resource('package', 'PackagesController');
Route::get('packageSearch/{search}', 'PackagesController@packageSearch');
Route::post('packageActions', 'PackagesController@packageActions');
Route::get('package/{package_id}/show','PackagesController@showPackages');
Route::get('ajax/getLocationInfo/{location_id}', 'AjaxController@getLocationInfo');
Route::get('packageImage/{package_id}', 'PackagesController@packageImage');
Route::get('deletePackageImage/{package_id}/{package_image_id}', 'PackagesController@deletePackageImage');
Route::get('packageImages/{package_id}', 'PackagesController@update');
Route::get('package/gallery/{package_id}', 'PackagesController@packageGalleryForm');
Route::post('package/updateGallery', 'PackagesController@packageGalleryUpdate');
Route::get('package/features/{package_id}', 'PackagesController@packageFeature');
Route::post('package/savefeatures/{package_id}', 'PackagesController@featureSave');
Route::get('packages/removeitem/{subtype_id}/{package_id}', 'PackagesController@deleteItems');

Route::get('package/items/{package_id}', 'PackagesController@packageItems');
Route::post('package/items/{package_id}', 'PackagesController@savePackageItems');
Route::get('package/quotes/{package_id}', 'PackagesController@quotes');
Route::get('package/itinerary/{package_id}','PackagesController@itinerary');
Route::post('package/saveItinerary','PackagesController@saveItinerary');
Route::get('package/itinerary/edit/{package_id}/{itinerary?}','PackagesController@editItinerary');
Route::post('package/updateItinerary','PackagesController@updateItinerary');
Route::get('package/itinerary/remove/{package_itinerary_id}','PackagesController@removeItinerary');
Route::get('package/itinerary/remove/image/{package_itinerary_image_id}','PackagesController@removeItineraryImage');
Route::get('package/suppliers/{package_id}', 'PackagesController@suppliers');
Route::post('package/savesupplier/{package_id?}', 'PackagesController@saveSuppliers');

Route::get('ajax/removeImageFromGallery/{package_image_id}', 'AjaxController@removeImageFromGallery');

Route::get('ajax/getsubtype', 'AjaxController@getSubType');
Route::get('ajax/getsubtypeinfo/{supplier_type_id}', 'AjaxController@getSubTypeInfo');



Route::resource('quotes', 'QuotesController');
Route::post('quotesActions', 'QuotesController@quoteActions');
Route::get('quotesSearch/{search}', 'QuotesController@quoteSearch');

Route::get('quotes/{quote_id}/show','QuotesController@showQuotes');
Route::get('quotes/create/{package_id?}','QuotesController@create');
Route::get('quotes/dates/{quote_id}','QuotesController@dates');
Route::post('quotes/saveDates','QuotesController@saveDates');
//Route::get('quotes/items/{quote_id}','QuotesController@items');
//Route::post('quotes/saveItems','QuotesController@saveItems');
Route::get('quotes/features/{quote_id}', 'QuotesController@quoteFeature');
Route::post('quotes/savefeatures/{quote_id}', 'QuotesController@featureSave');
Route::get('quotes/items/{quote_id}','QuotesController@quoteItems');
Route::post('quotes/saveitems/{quote_id}','QuotesController@saveQuoteItems');
Route::get('quotes/itinerary/{quote_id}','QuotesController@itinerary');
Route::post('quotes/saveItinerary','QuotesController@saveItinerary');
Route::get('quotes/itinerary/edit/{quote_id}/{itinerary?}','QuotesController@editItinerary');
Route::post('quotes/updateItinerary','QuotesController@updateItinerary');
Route::get('quotes/itinerary/remove/{quote_itinerary_id}','QuotesController@removeItinerary');
Route::get('quotes/itinerary/remove/image/{quote_itinerary_image_id}','QuotesController@removeItineraryImage');
Route::get('quotes/bookings/{quote_id}','QuotesController@bookings');
 Route::get('quotes/suppliers/{quote_id}', 'QuotesController@suppliers');
Route::post('quotes/savesuppliers/{quote_id?}', 'QuotesController@saveSuppliers');

Route::get('allquotes', 'QuotesController@showAllQuotes');
Route::post('quotes/contacts/{quote_id}', 'ContactsController@store');
Route::post('quotes/consearch', 'AjaxController@contacts');




Route::resource('bookings', 'BookingsController');
Route::get('bookings/create/{quote_id?}','BookingsController@create');
 Route::post('bookingActions', 'BookingsController@bookingActions');
 Route::get('bookingsSearch/{search}', 'BookingsController@bookingSearch');

Route::get('bookings/{quote_id}/show','BookingsController@showBookings');
Route::get('bookings/{quote_id}/invoice','BookingsController@invoiceBookings');
Route::get('bookings/{quote_id}/preview','BookingsController@invoiceBookingsPreview');
Route::get('bookings/create/{package_id?}','BookingsController@create');
Route::get('bookings/fromQuote/{quote_id}','BookingsController@createFromQuote');
Route::get('bookings/dates/{booking_id}','BookingsController@dates');
Route::post('bookings/saveDates','BookingsController@saveDates');
Route::get('bookings/features/{booking_id}', 'BookingsController@bookingFeature');
Route::post('bookings/savefeatures/{booking_id}', 'BookingsController@featureSave');

//Route::get('bookings/items/{booking_id}','BookingsController@items');
//Route::post('bookings/saveItems','BookingsController@saveItems');
Route::get('bookings/itinerary/{booking_id}','BookingsController@itinerary');
Route::post('bookings/saveItinerary','BookingsController@saveItinerary');
Route::get('bookings/itinerary/edit/{booking_id}/{itinerary?}','BookingsController@editItinerary');

Route::post('bookings/updateItinerary','BookingsController@updateItinerary');
Route::get('bookings/itinerary/remove/{booking_itinerary_id}','BookingsController@removeItinerary');
Route::get('bookings/itinerary/remove/image/{bookings_itinerary_image_id}','BookingsController@removeItineraryImage');


Route::get('bookings/suppliers/{booking_id}', 'BookingsController@suppliers');
Route::post('bookings/savesuppliers/{booking_id?}', 'BookingsController@saveSuppliers'); 
Route::get('bookings/items/{booking_id}','BookingsController@bookingItems');
Route::post('bookings/saveitems/{booking_id}','BookingsController@saveBookingItems');



Route::get('bookings/cancelbooking/{booking_id}', 'BookingsController@cancelBooking');
Route::post('bookings/savecancelbooking/{booking_id?}', 'BookingsController@saveCancelBooking');

Route::get('bookings/tasks/{booking_id}', 'BookingsController@tasks');

Route::post('bookings/savetasks/{booking_id}/{task_id?}', 'BookingsController@saveTasks');

Route::get('bookings/tasks/edit/{booking_id}/{task_id}', 'BookingsController@editTask');
Route::post('bookings/updatetasks/{booking_id}/{task_id?}', 'BookingsController@updateTasks');

Route::post('bookings/tasks/savecomment/{booking_id?}/{task_id}', 'BookingsController@saveComment');

Route::get('bookings/taskSearch/{search}/{booking_id}','BookingsController@taskSearch');
Route::post('bookings/taskActions/{booking_id}','BookingsController@taskActions');

Route::get('bookings/payments/{booking_id}','BookingsController@payments');
Route::post('bookings/savepayments/{booking_id}','BookingsController@savePayments');
Route::get('allbookings', 'BookingsController@showAllBookings');

Route::get('sort/bookings/{cancel?}/{process?}', 'BookingsController@sortBookings');
Route::get('bookings/activebooking/{id}', 'BookingsController@changeStatus');


//------------------Things TO DO----//

Route::resource('thingstodo', 'ThingsToDoController');
Route::get('thingstodoSearch/{search}','ThingsToDoController@thingstodoSearch');
Route::Post('thingstodoActions','ThingsToDoController@thingstodoActions');






//---Task--Route---//
Route::resource('tasks','TaskController');
Route::get('taskSearch/{search}','TaskController@taskSearch');
Route::post('taskActions','TaskController@taskActions');

Route::get('staffnewTaskComment/{task_id}','TaskController@staffNewComment');
Route::get('showTaskUser','TaskController@showUserTask');
Route::post('staffstoreComment/{task_id}','TaskController@staffStoreComment');

Route::get('newTaskComment/{task_id}','TaskController@newComment');
Route::post('storeComment/{task_id}','TaskController@storeComment');
 //---ToDoList---//
Route::resource('todolist','ToDoListController');
Route::get('todolistSearch/{search}','ToDoListController@todoListSearch');
Route::post('todolistActions','ToDoListController@todoListActions');


//---Itinerary--//
Route::resource('itinerary','ItineraryController');
Route::post('itineraryActions','ItineraryController@itineraryActions');
Route::get('itinerarySearch/{search}','ItineraryController@itinerarySearch');



Route::get('ajax/getItemInfo/{user_id}', 'AjaxController@getItemInfo');
Route::get('ajax/getsupplierSubTypeInfo/{supplier_type_id}', 'AjaxController@getsupplierSubTypeInfo');
Route::get('ajax/getsupplierSubTypesInfo', 'AjaxController@getsupplierSubTypesInfo');

Route::get('ajax/getsupplierInfo/{supplier_sub_type_id}', 'AjaxController@getsupplierInfo');
Route::get('ajax/getsupplierTypeItems/{package_id}/{supplier_type_id}', 'AjaxController@getsupplierTypeItems');

Route::get('ajax/getsupplierTypeSuppliers/{package_id}/{supplier_type_id}', 'AjaxController@getsupplierTypeSuppliers');
Route::get('ajax/getsupplierItems/{package_id}/{supplier_type_id}', 'AjaxController@getsupplierItems');

Route::get('ajax/getsupplierTypeQuoteSuppliers/{quote_id}/{supplier_type_id}', 'AjaxController@getsupplierTypeQuoteSuppliers');
Route::get('ajax/getsupplierTypeBookingSuppliers/{booking_id}/{supplier_type_id}', 'AjaxController@getsupplierTypeBookingSuppliers');

//---Setting--Route---//
Route::resource('settings', 'SettingsController');
Route::post('settings/update', 'SettingsController@update');

//-----Hotels---//
Route::resource('hotels', 'HotelsController');
Route::get('hotel/properties/{hotel_id}', 'HotelsController@properties');
Route::post('hotel/saveproperties/{hotel_id}', 'HotelsController@saveProperties');
Route::post('hotelActions', 'HotelsController@hotelActions');
Route::get('hotelSearch/{search}', 'HotelsController@hotelSearch');
Route::get('hotel/editproperties/{hotel_id}', 'HotelsController@editProperties');
Route::post('hotel/updateproperties/{hotel_id}', 'HotelsController@updateProperties');   

//---Brands---//
Route::resource('brands','BrandsController');
Route::post('brandActions','BrandsController@brandActions');
Route::get('brandSearch/{search}','BrandsController@brandSearch');

Route::get('brands/settings/{brand_id}', 'BrandsController@settings');
Route::post('brands/savesettings/{brand_id}', 'BrandsController@saveSettings');
Route::post('brands/updatesettings/{brand_id}', 'BrandsController@updateSettings');

Route::post('addvariables/{brand_id}', 'BrandVariablesController@store');


//---Brands Variables---//
Route::resource('brandvariables','BrandVariablesController');
Route::post('brandvariableActions','BrandVariablesController@brandVariableActions');
Route::get('brandvariableSearch/{search}','BrandVariablesController@brandVariableSearch');

//---Brand Setting---//
Route::resource('brandsettings', 'BrandSettingsController');
Route::post('brandsettings/update', 'BrandSettingsController@update');
Route::post('addvariables/{setting}', 'BrandVariablesController@store');


                                            



Route::get('activate/{code}',function($code){
  //echo  $code;
    
   $user = User::where('activation_code', '=', $code)->first();
   if(isset($user)){
   $user_id =  $user->id;
   //$user = User::find($user_id);
   Auth::login($user);
   
            $data['navigation']  = '0';


   $update = DB::table('users')
           ->where('activation_code',$code)
           ->update(array('user_status'=>'active','activation_code'=>''));
   
    if($update)
    {
        ZnUtilities::push_js_files('jquery.validate.min.js');
        $js = "$(function() {
                 $('#changePassword_form').validate();
             });";

        ZnUtilities::push_js($js);
           $data['page_title'] = "Change Password";

        return View::make('changePassword',$data);
    } 
   }
   else
   {
       //Auth::logout();
       return Redirect::to('/')->with('error_message', 'Invalid activation code. Please contact support for more details'); 
   }
   
        
});

Route::post('/changePassword',function(){
  
   $password = Hash::make(Input::get('password'));
         $data['navigation']  = '0';

   $update = DB::table('users')
           ->where('id',Auth::user()->id)
           ->update(array('password'=>$password,'reset_password_code'=>'','last_login'=>date('Y-m-d H:i:s')));
   
   
   return Redirect::to('/')->with('success_message', 'Password Saved!'); 

        
});

Route::get('changePassword',function(){
         $data = array();
         $data['initiator'] = "resetPassword";

        $basicPageVariables = ZnUtilities::basicPageVariables("Profile"," Change Password", "","1");
        $data = array_merge($data,$basicPageVariables);
        
         $data['breadcrumbs']['Profile'] = array("link"=>'profile',"active"=>'0');
         $data['breadcrumbs']['Change Password'] = array("link"=>"","active"=>'1'); 
            
         
          ZnUtilities::push_js_files('jquery.validate.min.js');
          $js = "$(function() {
                    $('#changePassword_form').validate();
                });";
          ZnUtilities::push_js($js);    
           $data['page_title'] = "Change Password";

        return View::make('changePassword',$data);
});





Route::get('/password/reset/{reset_code}',function($reset_code){
    
    
    $user = User::where('reset_password_code', '=', $reset_code)->first();
    if(isset($user)){
        $data['navigation']  = '0';
        $data['reset_code']  = $reset_code;
        
        
        $user_id =  $user->id;
       // $user = User::find($user_id);
        Auth::login($user);
        
       
        ZnUtilities::push_js_files('jquery.validate.min.js');
        $js = "$(function() {
                 $('#changePassword_form').validate();
             });";

        ZnUtilities::push_js($js);
           $data['page_title'] = "Change Password";

        return View::make('changePassword',$data);
    }
    else
    {
         return Redirect::to('login')->with('error_message', 'Invalid Request'); 
    }
});

Route::get("/permissionDenied", function(){

    $data = ZnUtilities::basicPageVariables("Permission Denied",'', '');
    return View::make('permissionDenied',$data);
   
});