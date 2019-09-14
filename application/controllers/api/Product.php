<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';

require APPPATH . 'libraries/Format.php';

class Product extends REST_Controller
{
    private $arr_result  = array();
    private $primary_key = "product_id";
    private $field_list  = array('product_id','product_name', 'description');
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mproduct');
        // $this->load->model('Mproduct_category');
    }

    public function finds_post()
    {

        $keyword             = "";
        $product_id          = 0;
        $order_by            = $this->primary_key;
        $ordering            = "desc";
        $limit               = 100;
        $page                = 0;

        if (isset($_POST['keyword'])) {
            $keyword = $this->input->post('keyword');
        }

        if (isset($_POST['product_id'])) {
            $product_id = $this->input->post('product_id');
        }

        if (isset($_POST['order_by'])) {
            $order_by = $this->input->post('order_by');
        }

        if (isset($_POST['ordering'])) {
            $ordering = $this->input->post('ordering');
        }

        if (isset($_POST['limit'])) {
            $limit = $this->input->post('limit');
        }

        if (isset($_POST['page'])) {
            $page = $this->input->post('page');
        }

        $option = array(
            'limit' => $limit,
            'page'  => $page,
            'order' => array(
                'order_by' => $order_by,
                'ordering' => $ordering,
            ),
        );

        // $product_categories = $this->Mproduct_category->findChilds($sub_category_id);
        // if (count($product_categories) == 0) {
        //     $product_categories = array($sub_category_id);
        //     $data_product       = $this->Mproduct->finds($keyword, $product_categories, $option, $product_id,$collection_id,$seller_id);
        // } else {
        //     array_push($product_categories, $sub_category_id);

            $data_product = $this->Mproduct->finds($keyword, $option, $product_id);
        // }

        if (count($data_product) == 0) {
            $this->arr_result = array(
                'prilude' => array(
                    'status'  => 'warning',
                    'message' => 'Data produk tidak ditemukan. Silakan ubah kata kunci pencarian atau coba lakukan pencarian di kategori / merk lainnya.',
                ),
            );
        } else {
            $this->arr_result = array(
                'prilude' => array(
                    'status'  => 'success',
                    'message' => 'Data produk tersedia',
                    'data'    => $data_product,
                ),
            );
        }

        $this->response($this->arr_result);
    }

    // public function add_product_post(){
    //     $productId = $this->input->post("product_id");
    //     if($this->Mproduct->add_product_post($productId)){
    //         $this->arr_result = array(
    //             'prilude' => array(
    //                 'status'  => 'success',
    //                 'message' => 'success',
    //             ),
    //         );
    //     }else{
    //         $this->arr_result = array(
    //             'prilude' => array(
    //                 'status'  => 'error',
    //                 'message' => 'gagal',
    //             ),
    //         );
    //     }

    //     $this->response($this->arr_result);
    // }
}
