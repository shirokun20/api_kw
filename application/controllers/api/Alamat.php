<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Alamat extends REST_Controller
{

	    public function alamat_post()
    {
    	$input                      = $this->post();
        $data['user_address']       = @$input['user_address'];
        $data['latitude']           = @$input['latitude'];
        $data['longitude']          = @$input['longitude'];
        $data['district_id']        = @$input['district_id'];
        $data['is_home']      		= 1;
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