<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}

require APPPATH . 'libraries/REST_Controller.php';

require APPPATH . 'libraries/Format.php';

class Mitra extends REST_Controller {
    private $arr_result = array();

    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        $this->load->model('Mmitra');

    }
    public function ambil_mitra_all_get()
    {
        $input = $this->get();

        if ($this->get('pd') !== null) {

            $q           = $this->Mmitra->allProduct($this->get('pd'));

        } else {

            $q           = $this->Mmitra->allProduct();

        }

        if ($q->num_rows() > 0) {

            $this->arr_result = array(
                'prilude' => array(
                    'status'        => 'berhasil',
                    'data'          => $q->result(),
                    'pesan'         => 'Berhasil mengambil data',
                ),
            );

        } else {

            $this->arr_result = array(
                'prilude' => array(
                    'status' => 'gagal',
                    'pesan'  => 'Data Tidak Ada',
                ),
            );

        }

        $this->response($this->arr_result);
        // exit;
    }


}

/* End of file Mitra.php */
/* Location: ./application/controllers/api/Mitra.php */