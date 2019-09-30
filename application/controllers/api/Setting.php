<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Setting extends REST_Controller
{
    private $arr_result = array();

    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
    }

    public function ambil_setting_all_get()
    {
        // $input = $this->post();

        $q = $this->Mo_sb->mengambil('setting',array('setting_id'=> 7) ) ;

        if ($q->num_rows() > 0) {

            $this->arr_result   = array(
                    'prilude'   => array(
                        'status'    => 'berhasil',
                        'data'      => $q->result(),
                )
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
            exit;
        }

    }
