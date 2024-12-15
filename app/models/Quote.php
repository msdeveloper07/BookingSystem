<?php

Class Quote extends Eloquent
{
    protected $primaryKey = 'quote_id';
    protected $table = 'quotes';
    public $timestamps = false;
   
    public function location_to ()
    {
        return $this->hasOne('Location','location_id','location_to');
    }
    public function location_from ()
    {
        return $this->hasOne('Location','location_id','location_from');
    }
    
    public function contact()
    {
        return $this->belongsTo('Contact','contact_id');
    }
   
   
}
