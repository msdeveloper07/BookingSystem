<?php

Class Attachment extends Eloquent
{
    protected $primaryKey = 'attachment_id';
    protected $table = 'message_attachments';
    public $timestamps = false;
   
    public function message()
    {
        return $this->belongsTo('Message','message_id');
    }
   
}
