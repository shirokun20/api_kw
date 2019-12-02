<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Alamat extends REST_Controller
{
    private $arr_result = array();
    
    public function __construct()
    {
        parent::__construct();
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        date_default_timezone_set("Asia/Bangkok");
        header("Access-Control-Allow-Methods: PUT, GET, POST");
    }

    public function index($offset = 0)
    {

    }


    public function add_alamat_post()
    {
    	$input                      = $this->post();
        $userID                     = $this->Mo_sb->mengambil('user', array('md5(user_id)' => $input['user_id']));
        $data['user_id']            = @$userID->row()->user_id;
        $data['user_address']       = @$input['user_address'];
        $data['latitude']           = @$input['latitude'];
        $data['longitude']          = @$input['longitude'];
        $data['district_id']        = @$input['district_id'];
        $data['is_home']      		= 0;
        $q                = $this->Mo_sb->menambah('user_address', $data);
        $this->arr_result = array(
            'prilude' => array(
                'status' => $q['status'],
                'pesan'  => ucwords($q['status']) . ' Tambah Alamat',
            ),
        );
        $this->response($this->arr_result);
        exit;
    }

}