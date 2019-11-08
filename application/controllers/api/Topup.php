<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Topup extends REST_Controller
{

    private $arr_result = array();

    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        date_default_timezone_set("Asia/Bangkok");
    }

    public function metode_kirim_get()
    {
        $q                = $this->Mo_sb->mengambil('top_up_kirim');
        $this->arr_result = array(
            'prilude' => array(
                'data' => $q->result(),
            ),
        );
        $this->response($this->arr_result);
    }
}

/* End of file Topup.php */
/* Location: ./application/controllers/api/Topup.php */
