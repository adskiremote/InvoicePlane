<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Quotes extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Set API request limits
        $this->methods['quotes_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['quotes_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['quotes_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model('quotes/mdl_quotes');
    }

    // Get one
    public function quotes_get()
    {
        $id = $this->get('id');
        if ($id === null) {
            // Get all quotes
            $quotes = $this->mdl_quotes->get()->result();
            if ($quotes) {
                $this->response($quotes, REST_Controller::HTTP_OK);
            } else {
                 $this->response([
                    'status' => false,
                    'message' => 'No quotes found'
                    ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $id = (int) $id;
            if ($id <= 0) {
                // Invalid id, set the response and exit.
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }

            $quote = $this->mdl_quotes->get_by_id($id);

            if ($quote) {
                $this->set_response($quote, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                $this->set_response([
                'status' => false,
                'message' => 'Quote could not be found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }
    }

    // Insert Quotes
    public function quotes_put($id = null) {
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
        $result = $this->rest_quotes->insert($data);
        $response = array('status' => true, 'message' => $data['product_name'] . ' inserted successfully');
        $this->set_response($response);
        
    }

    // Update


    // Create


    // Delete - not necessary for my needs
}
