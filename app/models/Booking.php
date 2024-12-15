<?php

Class Booking extends Eloquent
{
    protected $primaryKey = 'booking_id';
    protected $table = 'bookings';
    public $timestamps = false;
   
    public function package()
    {
        return $this->belongsTo('Package','package_id');
    }
   
    public function quote()
    {
        return $this->belongsTo('Quote','quote_id');
    }
    
    public function contact()
    {
        return $this->belongsTo('Contact','contact_id');
    }
   
}
