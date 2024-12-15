<?php

Class BrandSetting extends Eloquent
{
    protected $primaryKey = 'brand_setting_id';
    protected $table = 'brand_settings';
    public $timestamps = false;
    
    public function variable_name()
    {
        return $this->hasOne('BrandVariable','brand_variable_id','brand_variable_id');
    }
    
    
}