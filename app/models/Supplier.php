<?php

Class Supplier extends Eloquent
{
    protected $primaryKey = 'supplier_id';
    protected $table = 'suppliers';
    public $timestamps = false;
    
   public function supptype()
   {
       return $this->hasOne('SupplierType','supplier_type_id','supplier_type_id');
   }
   
    public function suppItem()
   {
       return $this->hasOne('SupplierItem','supplier_item_id','supplier_item_id');
   }
   
     public function loc()
   {
       return $this->hasOne('Location','location_id','supplier_location_id');
   }
    public function suppSubType()
   {
       return $this->hasOne('SupplierType','supplier_type_id','supplier_sub_type_id');
   }
   
   public function suppTypeName()
   {
       return $this->hasOne('SupplierType','supplier_type_id','supplier_type_id');
   }
   public function suppAssociation()
   {
       return $this->hasMany('SupplierTypeAssociation','supplier_id','supplier_id');
   }
}
