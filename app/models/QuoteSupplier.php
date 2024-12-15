<?php

Class QuoteSupplier extends Eloquent
{
    protected $primaryKey = 'quote_supplier_id';
    protected $table = 'quote_suppliers';
    public $timestamps = false;
    
   public function supplierInfo()
    {
        return $this->hasMany('Supplier','supplier_id','supplier_id');
    }
    
    
}