<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}

require APPPATH . 'libraries/REST_Controller.php';

require APPPATH . 'libraries/Format.php';

class Product extends REST_Controller
{
    private $arr_result = array();

    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        $this->load->model('Mproduct');

    }

    public function ambil_product_all_get()
    {
        $input = $this->get();

        if ($this->get('pd') !== null) {

            $q           = $this->Mproduct->allProduct($this->get('pd'));
            $query_image = $this->Mproduct->product_image($this->get('pd'));

        } else {

            $q = $this->Mproduct->allProduct();
            $query_image = $this->Mproduct->product_image();


        }

        if ($q->num_rows() > 0) {

            $this->arr_result = array(
                'prilude' => array(
                    'status'        => 'berhasil',
                    'data'          => $q->result(),
                    'product_image' => $query_image->result(),
                    'pesan'         => 'Berhasil mengambil data',
                ),
            );

        } else {

            $this->arr_result = array(
                'prilude' => array(
                    'status' => 'gagal',
                    'pesan'  => 'Data Tidak Ada',
                ),
            );

        }

        $this->response($this->arr_result);
        exit;
    }

    public function product_()
    {
        if (!$this->get('id')) {
            //query parameter, example, websites?id=1
            $this->response(null, 400);
        }

        $website = $this->wm->get_website($this->get('id'));

        if ($website) {
            $this->response($website, 200); // 200 being the HTTP response code
        } else {
            $this->response(array(), 500);
        }
    }

}
