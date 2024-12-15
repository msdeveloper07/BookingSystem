<?php

Class Address extends Eloquent
{
    protected $primaryKey = 'address_id';
    protected $table = 'address';
    public $timestamps = false;
    
   public function user()
   {
       return hasOne('User','address_id');
   }
   
   public function supplier()
   {
       return hasOne('Supplier','address_id');
   }
   
   
}
