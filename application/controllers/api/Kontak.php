<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Kontak extends REST_Controller
{
    private $arr_result = array();

    public function __construct()
    {
        parent::__construct();
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        $this->load->library('Libkirim_email');
        $this->load->library('Libzenzifa');
        date_default_timezone_set("Asia/Bangkok");
        header("Access-Control-Allow-Methods: PUT, GET, POST");

    }

    public function kontak_data_get()
    {
            $json_2 = array();
            $name = $this->Mo_sb->mengambil('setting', array('setting_name' => 'OFFICIAL_NAME'))->row();
            $email = $this->Mo_sb->mengambil('setting', array('setting_name' => 'OFFICIAL_EMAIL'))->row();
            $phone = $this->Mo_sb->mengambil('setting', array('setting_name' => 'PHONE_NUMBER'))->row();
            $time = $this->Mo_sb->mengambil('setting', array('setting_name' => 'OFFICE_TIME'))->row();
            $address = $this->Mo_sb->mengambil('setting', array('setting_name' => 'OFFICE_ADDRESS'))->row();
            $lat = $this->Mo_sb->mengambil('setting', array('setting_name' => 'LATITUDE'))->row();
            $lng = $this->Mo_sb->mengambil('setting', array('setting_name' => 'LONGITUDE'))->row();
            $partner = $this->Mo_sb->mengambil('setting', array('setting_name' => 'OFFICIAL_PARTNER'))->row();



            // foreach ($q2->result() as $apaYah) {
                $r2 = array();
                $r2['official_name']    = $name->setting_value;
                $r2['official_email']   = $email->setting_value;
                $r2['phone_number']     = $phone->setting_value;
                $r2['office_time']      = $time->setting_value;
                $r2['office_address']   = $address->setting_value;
                $r2['lat']   = $lat->setting_value;
                $r2['lng']   = $lng->setting_value;
                $r2['Official_partner']   = $partner->setting_value;
                $json_2[]    = $r2;

            // }
            // $r['detail'] = $json_2;
        $this->arr_result = array(
            'prilude' => array(
                'data' => $json_2,
            ),
        );
        $this->response($this->arr_result);
        exit();
    }

}
