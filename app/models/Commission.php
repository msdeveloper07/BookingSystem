<?php

Class Commission extends Eloquent
{
    protected $primaryKey = 'commission_id';
    protected $table = 'commission';
    public $timestamps = false;
    
    
    public function supplierinfo()
    {
        return $this->hasOne('Supplier','supplier_id','supplier_id');
    }
   
    
    
}