<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

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

    // Get one
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
