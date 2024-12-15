<?php

Class MailingListSubscriber extends Eloquent
{
    protected $primaryKey = 'mailinglist_subscriber_id';
    protected $table = 'mailinglist_subscribers';
    public $timestamps = false;
    
    public function mailingList() {
        return $this->belongsTo('MailingList','mailinglist_id');
    }
}
