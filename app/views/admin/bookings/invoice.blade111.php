<html>
    <body>
        <table style="width:100%;" cellspacing="0" cellpadding="5">
        <tr>
            <td colspan="4" style="border-bottom: 1px solid #000;"><div style="width:100%;" ><div style=" width:30%; text-align:right; margin-left: 70%;"> <h3>Sender Address</h3> </div></div></td>
        </tr>
        <tr>
            <td colspan="4" style="border-bottom: 1px solid #000;"><div style=" float:left;width:100%; "><div style=" margin-left: 70%; width:20%; "><strong>Invoice Number:</strong></div><div style=" margin-left: 70%; width:20%; "><strong>Date:</strong>{{date('y-m-d')}}</div><div style=" width:30%; margin-left: 5%; text-align:justify;"><strong>TO:</strong><p style="padding: 0px; margin: 0px;">Demo User,</p> <p style="padding: 0px; margin: 0px;">#303 red street london</p> <p style="padding: 0px; margin: 0px;">T +90899494</p> <p style="padding: 0px; margin: 0px;">F +45534552</p></div></div></td>
        </tr>
        <tr>
            <td colspan="4" style="border-bottom: 1px solid #000;"><div style="width:100%;"><h3><center>INVOICE</center></h3></div></td>
        </tr>
        <?php $location_from = Location::where('location_id', $booking->location_from)->pluck('location_name'); ?>
        <?php $location_to = Location::where('location_id', $booking->location_to)->pluck('location_name'); ?>
      
        <tr style="width:100%;">
       
            <td style="width:15%;  border-bottom: 1px solid #000;"><strong>Total Person</strong></td>
            <td style="width:50%; border-bottom: 1px solid #000;"><strong>Description</strong></td>
            <td style="width:15%; border-bottom: 1px solid #000; text-align:right; "><strong>Unit Price</strong></td>
            <td style="width:20%;text-align:right; border-bottom: 1px solid #000; "><strong>Total Price</strong></td> 
       
        </tr>
        <?php $totalperson = $booking->number_of_children + $booking->number_of_adults;  ?>
       <tr>
            <td style="width:15%; border-bottom: 1px solid #000;"><strong>{{$totalperson}}</strong></td>
            <td style="width:50%; border-bottom: 1px solid #000;">Confirming your booking with us for the&nbsp;<b>{{$location_to}}</b>&nbsp;from&nbsp;<b>{{$location_from}}</b>&nbsp;on&nbsp;<b>{{ZnUtilities::format_date($booking->date_from,'2')}}</b>&nbsp;for&nbsp;<b>{{$booking->number_of_days}}</b>&nbsp;days.</td>
            <td style="width:15%; border-bottom: 1px solid #000;text-align:right; "><strong>{{$booking->total_cost}}</strong></td>
            <td style="width:20%; border-bottom: 1px solid #000;text-align:right; "><strong>{{$booking->booking_cost}}</strong></td> 
        </tr>
        
      
        
         <tr>
            <td colspan="4" style="border-bottom: 0.5px solid #000;"><div style="width:100%;"><h4><center>Feature Details</center></h4></div></td>
        </tr>
        
        <tr style="width:100%; background-color: #B8AF97;">
                
              <td style="width:100%; text-align: center;border-bottom: 1px solid #000;"><strong>Supplier Type</strong></td>
              <td style="width:100%; text-align: center;border-bottom: 1px solid #000;"><strong>Sub Type</strong></td>
              <td style="width:100%; text-align: center;border-bottom: 1px solid #000;"><strong>Item Name</strong></td>
            
            
              
        </tr>
        
        
        <?php  $items = BookingFeature::where('booking_id',$booking->booking_id)->get();  ?>
                    
                    @foreach($items as $i)
                    
                    <?php $stpid = SupplierType::where('supplier_type_id',$i->supplier_parent_id)->pluck('supplier_type');
                    $stid = SupplierType::where('supplier_type_id',$i->supplier_id)->pluck('supplier_type');?>
                <tr>
                
                    <td>{{$stpid}}</td>
                    <td>{{$stid}}</td> 
                
                    <?php  $feature_item = json_decode($i->items); ?>
                     <td>
                    @foreach($feature_item as $t=>$T)
                   
                      @foreach($T as $m=>$M)
                  @foreach($M as $n)
                   {{$m}} :-
                
               &nbsp;  &nbsp;
                   
                    {{$n}}
                  
                
                
                     <br/>
                               @endforeach
                       
                               @endforeach
                               @endforeach
                           </td>  </tr> 
                <tr>
            <td colspan="4 " style="width:100%;text-align: center;border-bottom: 1px solid #000;"><h4></h4></td>
        </tr>
                                
                               @endforeach
                                 
                    <tr>
            <td colspan="4 " style="width:100%;text-align: center;border-bottom: 1px solid #000;"><h4></h4></td>
        </tr>
         <tr>
            <td colspan="4" style="border-bottom: 0.5px solid #000;"><div style="width:100%;"><h4><center>Booking Details</center></h4></div></td>
        </tr>
        
         <?php $location_from =  Location::where('location_id',$booking->location_from)->pluck('location_name') ?>
            <?php $location_to =  Location::where('location_id',$booking->location_to)->pluck('location_name') ?>
  		 <tr style="width:100%;">
       
            <td style="width:10%;  border-bottom: 1px solid #000;"><strong>Location From</strong></td>
            <td style="width:10%; border-bottom: 1px solid #000;"><strong>Location To</strong></td>
            <td style="width:10%; border-bottom: 1px solid #000;  "><strong>Date From</strong></td>
            <td style="width:50%; border-bottom: 1px solid #000; "><strong>Date To</strong></td> 
       
        </tr>

       <tr style="width:100%;">
            <td style="width:10%; border-bottom: 1px solid #000;"><strong>{{$location_from}}</strong></td>
            <td style="width:10%; border-bottom: 1px solid #000;"><strong>{{$location_to}}</strong></td>
            <td style="width:10%; border-bottom: 1px solid #000; "><strong>{{ZnUtilities::format_date($booking->date_from,'2')}}</strong></td>
            <td style="width:50%; border-bottom: 1px solid #000;"><strong>{{ZnUtilities::format_date($booking->date_to,'2')}}</strong></td> 
        </tr>
        
          <tr>
            <td colspan="4"><div style="width:100%; margin-top: 2%;"><p style="padding: 0px; margin: 0px;">May we wish you have a thoroughly enjoyable and memorable occasion.</p><p style="padding: 0px; margin: 0px;">If we can be of any further assistance, please do not hesitate to contact us.</p></div></td>
        </tr>
        <tr>
            <td colspan="4"><div style="width:100%; margin-top: 0px;"><p style="padding: 0px; margin-top: 0px;">Yours sincerely</p></div></td>
        </tr>
        <tr>
            <td colspan="4"><div style="width:100%; margin-top: 0px;"><p style="padding: 0px; margin-top: 0px;">The Booking Team</p></div></td>
        </tr>
        
        </table>
    </body>
</html>