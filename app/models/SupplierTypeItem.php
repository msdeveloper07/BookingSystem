<?php

Class SupplierTypeItem extends Eloquent
{
    protected $primaryKey = 'supplier_type_item_id';
    protected $table = 'supplier_type_items';
    public $timestamps = false;
    
   public function suptype()
   {
       return $this->hasOne('SupplierType','supplier_type_id','supplier_type_id');
   }
    public function supSubType()
   {
       return $this->hasOne('SupplierType','supplier_type_id','supplier_type_parent_id');
   }
}
