<?php

Class Package extends Eloquent
{
    protected $primaryKey = 'package_id';
    protected $table = 'packages';
    public $timestamps = false;
    
  public function loc_from()
  {
      return $this->hasOne('Location','location_id','location_from');
  }
  
   public function loc_to()
  {
      return $this->hasOne('Location','location_id','location_to');
  }
   public function packageImages()
   {
       return $this->hasMany('PackageImage','package_id','package_id');
   }
  
   public function items()
   {
       return $this->hasMany('PackageItem','package_id','package_id');
   }
  
}
