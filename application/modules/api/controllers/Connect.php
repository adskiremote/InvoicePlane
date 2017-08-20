<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Connect extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Set API request limits
        $this->methods['products_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['products_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['products_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model('products/mdl_products');
        $this->load->model('rest_products');
    }

    public function index_get() {
        return $this->set_response(['status' => true]);
    }
}

