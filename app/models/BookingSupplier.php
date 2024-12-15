<?php

Class BookingSupplier extends Eloquent
{
    protected $primaryKey = 'booking_supplier_id';
    protected $table = 'booking_suppliers';
    public $timestamps = false;
    
 
    public function supplierInfo()
    {
        return $this->hasMany('Supplier','supplier_id','supplier_id');
    }
    
    
}