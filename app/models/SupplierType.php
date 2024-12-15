<?php

Class SupplierType extends Eloquent
{
    protected $primaryKey = 'supplier_type_id';
    protected $table = 'supplier_types';
    public $timestamps = false;
    
    public function subTypes()
    {
        return $this->hasMany('SupplierType','supplier_type_parent_id','supplier_type_id');
    }
    
    public function items()
    {
        return $this->hasMany('SupplierTypeItem','supplier_type_id');
    }
}
