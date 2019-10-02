<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Layanan extends REST_Controller
{
    private $arr_result = array();
    private $link = 'http://www.prilude.com/apps/klikwaw/kwkonsumen/static/media/';
    public function __construct()
    {
        parent::__construct();
    	header("Access-Control-Allow-Origin: *");
        $this->load->library('Libkirim_email');
    }

    public function index_get()
    {
    	$q = $this->Mo_sb->mengambil('services');
    	$json = array();
    	foreach ($q->result() as $key) {
    		$r = array();
    		$r['services_id'] = $key->services_id;
    		$r['services_name'] = $key->services_name;
    		$r['image'] = $this->link . $key->image;
    		$json[] = $r;
    	}
    	$this->arr_result = array(
            'prilude' => array(
                'data' => $json,
            ),
        );
        $this->response($this->arr_result);
    }

    public function kategori_get()
    {
    	$input = $this->get();
    	$q = $this->Mo_sb->mengambil('category', $input);
    	$json = array();
    	foreach ($q->result() as $key) {
    		$r = array();
    		$r['category_id'] = $key->category_id;
    		$r['category_name'] = $key->category_name;
    		$json[] = $r;
    	}
    	$this->arr_result = array(
            'prilude' => array(
                'data' => $json,
            ),
        );
        $this->response($this->arr_result);
    }

    public function layanan_kategori_get()
    {
        $q = $this->Mo_sb->mengambil('services');
        $json = array();
        foreach ($q->result() as $key) {
            $r = array();
            $r['services_id'] = $key->services_id;
            $r['services_name'] = $key->services_name;
            $r['image'] = $this->link . $key->image;
            $json_2 = array();
            $q2 = $this->Mo_sb->mengambil('category', array('services_id' => $r['services_id']));
            foreach ($q2->result() as $apaYah) {
                $r2 = array();
                $r2['category_id'] = $apaYah->category_id;
                $r2['category_name'] = $apaYah->category_name;
                $json_2[] = $r2;
            }
            $r['detail'] = $json_2;
            $json[] = $r;
        }
        $this->arr_result = array(
            'prilude' => array(
                'data' => $json,
            ),
        );
        $this->response($this->arr_result);
    }
}

/* End of file Layanan.php */
/* Location: ./application/controllers/api/Layanan.php */
