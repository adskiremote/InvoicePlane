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
 * Class Mdl_Products
 */
class Rest_Products extends Response_Model
{
    public $table = 'ip_products';
    public $primary_key = 'ip_products.product_id';

    public function insert($data)
    {        
      return  $this->db->insert('ip_products', $data);
    }
}
