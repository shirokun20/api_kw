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

    private function switch_cart($user_id, $type, $isLogin, $data = NULL)
    {
        switch ($type) {
            case 'TAMBAH_ITEM':
                $hasil            = json_decode($data['barangNya'], true);
                $hasil['user_id'] = $user_id;
                $hasil['ip_address'] = $this->getUserIpAddr();
                $where = array(
                    'product_id' => $hasil['product_id'],
                    'is_cart'    => '1',
                );
                if ($user_id === md5($this->getUserIpAddr())) {
                    $where['ip_address'] = $this->getUserIpAddr();
                } else {
                    $where['user_id'] = $user_id;
                }
                $cek              = $this->Mo_sb->mengambil('cart_product', $where);
                if ($cek->num_rows() == false) {
                    $q = $this->Mo_sb->menambah('cart_product', $hasil);
                } else {
                    $q = $this->Mo_sb->mengubah('cart_product', $where, $hasil);
                }
                return $q;
                break;
            case 'TAMBAH_SUDAH_ADA':
                $hasil = json_decode($data['barangNya'], true);
                $q     = $this->Mo_sb->mengubah('cart_product', array(
                    'user_id'    => $user_id,
                    'product_id' => $hasil['product_id'],
                    'is_cart'    => '1'
                ), $hasil);
                return $q;
                break;
            case 'HAPUS_ITEM':

                $data = array(
                    'product_id' => $data['product_id'],
                    'is_cart'    => '1'
                );

                if ($user_id !== md5($this->getUserIpAddr())) {
                    $data['user_id'] = $user_id;
                }else{
                    $where['ip_address'] = $this->getUserIpAddr();
                }
                $q = $this->Mo_sb->menghapus('cart_product', $data);
                return $q;
                break;
            case 'AMBIL_CART':
                $where = array(
                    'is_cart' => '1',
                );
                if ($isLogin == true) {
                    $this->Mo_sb->mengubah('cart_product', array(
                        'is_cart' => '1',
                        'ip_address' => $this->getUserIpAddr()
                    ), array(
                        'user_id' => $user_id,
                        'ip_address' => '',
                    ));
                }
                if ($user_id === md5($this->getUserIpAddr())) {
                    $where['ip_address'] = $this->getUserIpAddr();
                } else {
                    $where['user_id'] = $user_id;
                }
            	$q = $this->Mo_sb->mengambil('cart_product', $where);
            	$json = array();
            	foreach ($q->result() as $key) {
            		$r = array();
            		$r['product_id'] = $key->product_id;
            		$r['product_name'] = $key->product_name;
            		$r['price'] = (int) $key->price;
            		$r['diskon'] = (int) $key->diskon;
            		$r['kategori'] = $key->kategori;
            		$r['image_product'] = $key->image_product;
            		$r['qty'] = (int) $key->qty;
            		$json[] = $r;
            	}

            	return array(
            		'barangNya' => $json, 
            	);

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
        $userNya = 0;
        $userID           = $this->Mo_sb->mengambil('user', array('md5(user_id)' => $input['user_id']));
        if ($userID->num_rows() == true) {
            $userNya = $userID->row()->user_id;
            $isLogin = true;
        }else{
            $userNya = md5($this->getUserIpAddr());
            $isLogin = false;
        }
        $hasil            = $this->switch_cart($userNya, $input['type'], $isLogin, $input);
        $this->arr_result = array(
            'prilude' => array(
                'status' => 'berhasil',
            ),
        );
        $this->response($this->arr_result);
    }

    function getUserIpAddr(){
      if(!empty($_SERVER['HTTP_CLIENT_IP'])){
          //ip from share internet
          $ip = $_SERVER['HTTP_CLIENT_IP'];
      }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
          //ip pass from proxy
          $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      }else{
          $ip = $_SERVER['REMOTE_ADDR'];
      }
      return $ip;
  }

    public function ambil_get()
    {
    	$input = $this->get();
        $userNya = 0;
        $userID           = $this->Mo_sb->mengambil('user', array('md5(user_id)' => $input['user_id']));
        if ($userID->num_rows() == true) {
            $userNya = $userID->row()->user_id;
            $isLogin = true;
        }else{
            $userNya = md5($this->getUserIpAddr());
            $isLogin = false;
        }
        $hasil = $this->switch_cart($userNya, $input['type'], $isLogin);
    	$this->arr_result = array(
    	    'prilude' => array(
    	        'data' => $hasil,
    	    ),
    	);
    	$this->response($this->arr_result);
    }
}

/* End of file Cart.php */
/* Location: ./application/controllers/api/Cart.php */
