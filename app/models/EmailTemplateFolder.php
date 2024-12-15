<?php

Class EmailTemplateFolder extends Eloquent
{
    protected $primaryKey = 'email_template_folder_id';
    protected $table = 'email_template_folders';
    public $timestamps = false;
}
