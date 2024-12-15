<html>
    <body>
        <table style="width:100%;" cellspacing="0" cellpadding="5">
            
          
         
        
        <tr>
            <td colspan="4"><div style="width:100%; border-bottom: 1px solid #000;"><div style=" width:30%; text-align:right; margin-left: 70%;"> <h3>Sender Address</h3> </div></div></td>
        </tr>
        <tr>
            <td colspan="4"><div style=" float:left;width:100%; border-bottom: 1px solid #000;"><div style=" margin-left: 70%; width:20%; "><strong>Invoice Number:</strong></div><div style=" margin-left: 70%; width:20%; "><strong>Date:</strong>{{date('y-m-d')}}</div><div style=" width:30%; margin-left: 5%; text-align:justify;"><strong>TO:</strong><p style="padding: 0px; margin: 0px;">Demo User,</p> <p style="padding: 0px; margin: 0px;">#303 red street london</p> <p style="padding: 0px; margin: 0px;">T +90899494</p> <p style="padding: 0px; margin: 0px;">F +45534552</p></div></div></td>
        </tr>
        <tr>
            <td colspan="4"><div style="width:100%;border-bottom: 1px solid #000;"><h3><center>INVOICE</center></h3></div></td>
        </tr>
        <?php $location_from = Location::where('location_id', $booking->location_from)->pluck('location_name'); ?>
        <?php $location_to = Location::where('location_id', $booking->location_to)->pluck('location_name'); ?>
      
        <tr style="width:100%;">
       
            <td style="width:15%;  border-bottom: 1px solid #000;"><strong>Total Person</strong></td>
            <td style="width:50%; border-bottom: 1px solid #000;"><strong>Description</strong></td>
            <td style="width:15%; border-bottom: 1px solid #000; text-align:right; "><strong>Unit Price</strong></td>
            <td style="width:20%;text-align:right; border-bottom: 1px solid #000; "><strong>Total Price</strong></td> 
       
        </tr>

       <tr>
            <td style="width:15%; border-bottom: 1px solid #000;"><strong>{{$package->number_of_adults+$package->number_of_children}}</strong></td>
            <td style="width:50%; border-bottom: 1px solid #000;">Confirming your booking with us for the&nbsp;<b>{{$location_to}}</b>&nbsp;from&nbsp;<b>{{$location_from}}</b>&nbsp;on&nbsp;<b>{{ZnUtilities::format_date($quote->date_from,'2')}}</b>&nbsp;for&nbsp;<b>{{$quote->number_of_days}}</b>&nbsp;days.</td>
            <td style="width:15%; border-bottom: 1px solid #000;text-align:right; "><strong>{{$package->total_cost}}</strong></td>
            <td style="width:20%; border-bottom: 1px solid #000;text-align:right; "><strong>{{$package->total_cost*$package->number_of_adults+$package->number_of_children}}</strong></td> 
        </tr>
         <tr>
            <td colspan="4 " style="width:100%;text-align: center;border-bottom: 1px solid #000;"><h4></h4></td>
        </tr>
        <tr style="width:100%;">
                
              <td style="width:25%; border-bottom: 1px solid #000;"><strong>Supplier</strong></td>
              <td style="width:25%; border-bottom: 1px solid #000;"><strong>Extra item</strong></td>
              <td style="width:25%; border-bottom: 1px solid #000;"><strong>Item Cost</strong></td>
              <td style="width:25%; border-bottom: 1px solid #000;"><strong>Extra Notes</strong></td>
        </tr>
       
   @foreach($items as $item)
         <?php $supplier = SupplierType::where('supplier_type_id', $item->supplier_type_id)->pluck('supplier_type'); ?>
         <?php $supplier_name = Supplier::where('supplier_id', $item->supplier_id)->pluck('supplier_name'); ?>
          <?php $supplier_total_items=BookingItem::where('supplier_id',$item->supplier_id)->where('booking_id',$item->booking_id)->get();?>
           
         <tr style="width:100%;">
                        
                   <td>{{$supplier_name}}</td>
                @foreach($supplier_total_items as $s)
                <?php $supplier_item_name = SupplierTypeItem::where('supplier_type_item_id', $s->supplier_type_item_id)->pluck('supplier_item_name'); ?>

                   <td >{{$supplier_item_name}}</td>
                   <td>{{$s->cost}}</td>
                   <td>{{$s->extra_notes}}</td>
        </tr>
        @endforeach
        
        @endforeach
       
          
        <tr>
          <?php $supplier_total_items=BookingItem::where('supplier_id',$item->supplier_id)->where('booking_id',$item->booking_id)->pluck('cost');?>
            <td colspan="4" style="width:100%;text-align:right;border-top: 1px solid #000"><h4>Sub Total:{{$supplier_total_items}}</h4></td>
        </tr>
      <tr>
             <td colspan="4" style="width:100%;text-align:right;border-bottom: 1px solid #000;"><h4>Total:{{$supplier_total_items+$package->total_cost*$package->number_of_adults+$package->number_of_children}}</h4></td>
      </tr>
       
        
    
        
       
        <tr>
            <td colspan="12"><div style="width:100%; margin-top: 2%;"><p style="padding: 0px; margin: 0px;">May we wish you have a thoroughly enjoyable and memorable occasion.</p><p style="padding: 0px; margin: 0px;">If we can be of any further assistance, please do not hesitate to contact us.</p></div></td>
        </tr>
        <tr>
            <td colspan="12"><div style="width:100%; margin-top: 0px;"><p style="padding: 0px; margin-top: 0px;">Yours sincerely</p></div></td>
        </tr>
        <tr>
            <td colspan="12"><div style="width:100%; margin-top: 0px;"><p style="padding: 0px; margin-top: 0px;">The Booking Team</p></div></td>
        </tr>
     


        </table>
    </body>
</html>