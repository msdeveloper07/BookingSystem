<?php

Class BookingInvoice extends Eloquent
{
    protected $primaryKey = 'booking_invoice_id';
    protected $table = 'bookings_invoice';
    public $timestamps = false;
    
}