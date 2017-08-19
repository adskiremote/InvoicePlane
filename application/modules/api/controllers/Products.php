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
        $this->load->model('rest_products');
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
                $this->set_response([
                    'status' => true,
                    'products' => $products, REST_Controller::HTTP_OK
                ]);
            
            } else {
                 $this->set_response([
                    'status' => false,
                    'message' => 'No products found'
                    ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $id = (int) $id;
            if ($id <= 0) {
                // Invalid id, set the response and exit.
                $this->set_response(null, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
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

    // Insert products
    public function products_put($id = null) {
        // Build inputs
        $data = [
            'product_sku' => $this->input->input_stream('product_sku', TRUE),
            'product_name' => $this->input->input_stream('product_name', TRUE),
            'family_id' => $this->input->input_stream('family_id', TRUE),
            'product_description' => $this->input->input_stream('product_description', TRUE),
            'purchase_price' => $this->input->input_stream('purchase_price', TRUE),
            'product_price' => $this->input->input_stream('product_price', TRUE),
            'tax_rate_id' => $this->input->input_stream('tax_rate_id', TRUE),
            'unit_id' => $this->input->input_stream('unit_id', TRUE)
        ];

        // Check if product already exists
        

        $result = $this->rest_products->insert($data);
        $response = array('status' => true, 'message' => 'Success:' . $data['product_name'] . ' inserted successfully');
        $this->set_response($response);
        
    }
}
