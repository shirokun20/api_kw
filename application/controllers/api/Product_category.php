<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';

require APPPATH . 'libraries/Format.php';

class Product_category extends REST_Controller
{
    private $arr_result = array();

    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        $this->load->model('Mproduct_category');

    }

    public function ambil_category_all_get()
    {
        if ($this->get('cat_id') !== '') {

            $q = $this->Mproduct_category->all_category($this->get('cat_id'));

        } else {
            $q = $this->Mproduct_category->all_category(20);
        }

        if ($q->num_rows() > 0) {

            $this->arr_result = array(
                'prilude' => array(
                    'status' => 'berhasil',
                    'data'   => $q->result(),
                    'pesan'  => 'Berhasil mengambil data',
                ),
            );

        } else {

            $this->arr_result = array(
                'prilude' => array(
                    'status' => 'gagal',
                    'pesan'  => 'Data Tidak Ada',
                    'data'   => null
                ),
            );

        }

        $this->response($this->arr_result);
        exit;
    }

}
