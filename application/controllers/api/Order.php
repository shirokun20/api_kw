<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';

require APPPATH . 'libraries/Format.php';

class Order extends REST_Controller
{

    private $arr_result = array();

    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        $this->load->model('Morder');
    }

    public function index($offset = 0)
    {

    }

    public function at_get()
    {
        $input = $this->get();
        $this->db->select('*');
        $this->db->order_by('created_time', 'desc');
        $this->db->limit(1);
        $q                = $this->Mo_sb->mengambil('product_order', array('md5(buyer_user_id)' => $input['user_id']));
        $this->arr_result = array(
            'prilude' => array(
                'detail' => $q->result(),
            ),
        );
        $this->response($this->arr_result);
        exit;
    }

    public function searc_mitra_dekat_get()
    {
        $input            = $this->get();
        $lat              = @$input['lat'];
        $lng              = @$input['lng'];
        $q                = $this->Morder->cariMitra($lat, $lng);
        $this->arr_result = array(
            'prilude' => array(
                'detail' => $q->result(),
                'jm'	 => $this->Mo_sb->mengambil('setting', array('setting_name' => 'JARAK_MAKSIMUM'))->result(),
            ),
        );
        $this->response($this->arr_result);
        exit;
    }

    public function amkw_get()
    {
    	$q = $this->Mo_sb->mengambil('shipping_method', array('shipping_method_id >' => 1));
    	$q2 = $this->Mo_sb->mengambil('shipping_timing');
    	$this->arr_result = array(
            'prilude' => array(
                'metode_kirim' => $q->result(),
                'waktu_kirim'  => $q2->result(),
            ),
        );
        $this->response($this->arr_result);
        exit;
    }

}

/* End of file Order.php */
/* Location: ./application/controllers/api/Order.php */
