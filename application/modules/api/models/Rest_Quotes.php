<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
 * InvoicePlane
 *
 * @author		InvoicePlane Developers & Contributors
 * @copyright	Copyright (c) 2012 - 2017 InvoicePlane.com
 * @license		https://invoiceplane.com/license.txt
 * @link		https://invoiceplane.com
 */

/**
 * Class Rest Quotes
 */
class Rest_Quotes extends Response_Model
{
    public $table = 'ip_quotes';
    public $primary_key = 'ip_quotes.quote_id';

    public function insert($data)
    {        
      return  $this->db->insert('ip_quotes', $data);
    }
}
