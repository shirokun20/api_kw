<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Cart extends REST_Controller
{

    private $arr_result = array();

    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
    }

    public function index_get()
    {

    }

    public function index_post()
    {

    }

    private function switch_cart($user_id, $type, $data)
    {
        switch ($type) {
            case 'TAMBAH_ITEM':
                $hasil            = json_decode($data['barangNya'], true);
                $hasil['user_id'] = $user_id;
                $cek              = $this->Mo_sb->mengambil('cart_product', array(
                    'product_id' => $hasil['product_id'],
                    'user_id'    => $hasil['user_id'],
                ));

                if ($cek->num_rows() == false) {
                    $q = $this->Mo_sb->menambah('cart_product', $hasil);
                } else {
                    $q = $this->Mo_sb->mengubah('cart_product', array(
                        'user_id'    => $hasil['user_id'],
                        'product_id' => $hasil['product_id'],
                    ), $hasil);
                }
                return $q;
                break;
            case 'TAMBAH_SUDAH_ADA':
                $hasil = json_decode($data['barangNya'], true);
                $q     = $this->Mo_sb->mengubah('cart_product', array(
                    'user_id'    => $user_id,
                    'product_id' => $hasil['product_id'],
                ), $hasil);
                return $q;
                break;
            case 'HAPUS_ITEM':
                $q = $this->Mo_sb->menghapus('cart_product', array(
                    'product_id' => $data['product_id'],
                    'user_id'    => $user_id,
                ));
                return $q;
                break;
            default:
                return array(
                    'status' => 'gagal',
                );
                break;
        }
    }

    public function masuk_post()
    {
        $input            = $this->post();
        $userID           = $this->Mo_sb->mengambil('user', array('md5(user_id)' => $input['user_id']));
        $hasil            = $this->switch_cart($userID->row()->user_id, $input['type'], $input);
        $this->arr_result = array(
            'prilude' => array(
                'status' => $hasil['status'],
            ),
        );
        $this->response($this->arr_result);
    }
}

/* End of file Cart.php */
/* Location: ./application/controllers/api/Cart.php */
