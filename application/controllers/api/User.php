<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class User extends REST_Controller
{
    private $arr_result = array();

    public function __construct()
    {
        parent::__construct();
    }

    private function _cek_user($where)
    {
        return $this->Mo_sb->mengambil('user', $where);
    }

    private function validasi()
    {
        $input          = $this->post();
        $data['status'] = '';
        $data['pesan']  = '';
        if (@$input['full_name'] == null) {
            $data['status'] = 'gagal';
            $data['pesan']  = 'Nama tidak boleh kosong!';
        } elseif (@$input['email'] == null) {
            $data['status'] = 'gagal';
            $data['pesan']  = 'Email tidak boleh kosong!';
        } elseif (@$input['phone'] == null) {
            $data['status'] = 'gagal';
            $data['pesan']  = 'No. Hp tidak boleh kosong!';
        } elseif (@$input['password'] == null) {
            $data['status'] = 'gagal';
            $data['pesan']  = 'Password tidak boleh kosong!';
        } elseif (@$input['repassword'] == null) {
            $data['status'] = 'gagal';
            $data['pesan']  = 'Repassword tidak boleh kosong!';
        } elseif (@$input['password'] != @$input['repassword']) {
            $data['status'] = 'gagal';
            $data['pesan']  = 'Repassword tidak cocok dengan password!';
        } else {
            $q = $this->_cek_user(array(
                'email' => $input['email']
            ));

            $q2 = $this->_cek_user(array(
                'phone' => $input['phone']
            ));
            if ($q->num_rows() == true) {
                $data['status'] = 'gagal';
                $data['pesan']  = 'Email sudah digunakan!';
            }elseif ($q2->num_rows() == true) {
                $data['status'] = 'gagal';
                $data['pesan']  = 'No. Hp sudah digunakan!';
            }
        }

        if ($data['status'] == 'gagal') {
            $this->arr_result = array(
                'prilude' => array(
                    'status' => $data['status'],
                    'pesan'  => $data['pesan'],
                ),
            );
            $this->response($this->arr_result, 422);
            exit;
        } else {
            return $input;
        }
    }

    public function daftar_post($user_role_id = 2)
    {
        $hasilkan               = $this->validasi();
        $data['full_name']      = ucwords($hasilkan['full_name']);
        $data['email']          = $hasilkan['email'];
        $data['password']       = md5($hasilkan['password']);
        $data['phone']          = $hasilkan['phone'];
        $data['user_role_id']   = $user_role_id;
        $data['register_date']  = date('Y-m-d H:i:s');
        $data['user_status_id'] = 2;

        $q                = $this->Mo_sb->menambah('user', $data);
        $this->arr_result = array(
            'prilude' => array(
                'status' => $q['status'],
                'pesan'  => ucwords($q['status']) . ' mendaftar',
            ),
        );
        $this->response($this->arr_result);
        exit;
    }

}
