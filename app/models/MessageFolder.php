<?php

Class MessageFolder extends Eloquent
{
    protected $primaryKey = 'message_folder_id';
    protected $table = 'message_folders';
    public $timestamps = false;
}
