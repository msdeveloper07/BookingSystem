<?php

Class QuoteInvoice extends Eloquent
{
    protected $primaryKey = 'quote_invoice_id';
    protected $table = 'quotes_invoice';
    public $timestamps = false;
    
}