<?php

Class PackageSupplier extends Eloquent
{
    protected $primaryKey = 'package_supplier_id';
    protected $table = 'package_suppliers';
    public $timestamps = false;
    
   public function supplierInfo()
    {
        return $this->hasMany('Supplier','supplier_id','supplier_id');
    }
    
    
}