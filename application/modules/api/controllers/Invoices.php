<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class Invoices extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Set API request limits
        $this->methods['invoices_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['invoices_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['invoices_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model('invoices/mdl_invoices');
    }

    // Get one
    public function invoices_get()
    {

        $id = $this->get('id');
        if ($id === null) {
            // Get all invoices
            $this->mdl_invoices->get();
            $invoices = $this->mdl_invoices->result();

            if ($invoices) {
                $this->response($invoices, REST_Controller::HTTP_OK);
            } else {
                 $this->response([
                    'status' => false,
                    'message' => 'No invoices found'
                    ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $id = (int) $id;
            if ($id <= 0) {
                // Invalid id, set the response and exit.
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }

            $client = $this->mdl_invoices->get_by_id($id);

            if ($client) {
                $this->set_response($client, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                $this->set_response([
                'status' => false,
                'message' => 'Invoice could not be found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }
    }
}
