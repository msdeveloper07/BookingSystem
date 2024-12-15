<?php

Class BookingItinerary extends Eloquent
{
    protected $primaryKey = 'booking_itinerary_id';
    protected $table = 'booking_itineraries';
    public $timestamps = false;
    
}