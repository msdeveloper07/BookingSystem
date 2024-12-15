<?php

Class Newsletter extends Eloquent
{
    protected $primaryKey = 'newsletter_id';
    protected $table = 'newsletters';
    public $timestamps = false;
    
    public function mailinglist_name()
    {
        return $this->hasOne('MailingList','mailinglist_id','mailinglist_id');
    }
    
    
}