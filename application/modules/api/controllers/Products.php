<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Products extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Set API request limits
        $this->methods['products_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['products_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['products_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model('products/mdl_products');
    }

    // Get Product Options
    public function options_get()
    {
        // Load additional models
        $this->load->model('families/mdl_families');
        $this->load->model('tax_rates/mdl_tax_rates');
        $this->load->model('units/mdl_units');

        $families = $this->mdl_families->get()->result();
        $taxRates = $this->mdl_tax_rates->get()->result();
        $units = $this->mdl_units->get()->result();

        // NOT WORKING
     //   $this->set_response->addHeader('Access-Control-Allow-Origin: *');
      //  $this->set_response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
      //  $this->set_response->addHeader('Access-Control-Max-Age: 1000');
      //  $this->set_response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

        if(!$families || !$taxRates || !$units) {
            $this->set_response([
                'success' => false,
                'message' => 'Setup your Product Families, Tax Rates and Units in InvoicePlane first'
            ]);
        } else {

        $this->set_response([
            'success' => true,
            'families' => $families,
            'tax_rates' => $taxRates,
            'units' => $units
        ]);
        }
    }

    // Get Products
    public function products_get()
    {
        $id = $this->get('id');
        if ($id === null) {
            // Get all products
            $products = $this->mdl_products->get()->result();

            if ($products) {
                $this->response($products, REST_Controller::HTTP_OK);
            } else {
                 $this->response([
                    'status' => false,
                    'message' => 'No products found'
                    ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $id = (int) $id;
            if ($id <= 0) {
                // Invalid id, set the response and exit.
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }

            $product = $this->mdl_products->get_by_id($id);

            if ($product) {
                $this->set_response($product, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                $this->set_response([
                'status' => false,
                'message' => 'Product could not be found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }
    }
}
