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
        $input = $this->get();
        $where['p.category_id'] = $input['cat_id'];
        $where['p.is_active'] = '1';
        $where['p.product_id !='] = $input['product_id'];
        $q = $this->Mproduct_category->all_category($where);
        $this->arr_result = array(
            'prilude' => array(
                'status' => 'berhasil',
                'data'   => $q->result(),
                'pesan'  => 'Berhasil mengambil data',
            ),
        );
        $this->response($this->arr_result);
        exit;
    }

}
