<?php

Class SupplierItem extends Eloquent
{
    protected $primaryKey = 'supplier_item_id';
    protected $table = 'supplier_items';
    public $timestamps = false;
    
   public function suptype()
   {
       return $this->hasOne('SupplierType','supplier_type_id','supplier_type_id');
   }
   
    public function supplierItems()
   {
       return $this->hasMany('SupplierTypeItem','supplier_type_item_id','supplier_type_item_id');
   }
}
