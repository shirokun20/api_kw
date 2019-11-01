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
        header("Access-Control-Allow-Origin: *");
        $this->load->library('Libkirim_email');
        $this->load->library('Libzenzifa');
    }

    private function _cek_user($where = null)
    {
        return $this->Mo_sb->mengambil('user', $where);
    }

    public function profil_user_get()
    {
        $input = $this->get();

        $q = $this->_cek_user(array(
            'md5(user_id)' => $input['user_id'],
        ));

        $this->arr_result = array(
            'prilude' => array(
                'user_detail' => $q->result(),
            ),
        );
        $this->response($this->arr_result);
        exit;
    }

    // public function address_user_get()
    // {
    //     $input = $this->get();

    //     $q = $this->_cek_user_address(array(
    //         'md5(user_id)' => $input['user_id'],
    //     ));

    //     $this->arr_result = array(
    //         'prilude' => array(
    //             'user_address_detail' => $q->result(),
    //         ),
    //     );
    //     $this->response($this->arr_result);
    //     exit;
    // }

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
                'email' => $input['email'],
            ));

            $q2 = $this->_cek_user(array(
                'phone' => $input['phone'],
            ));
            if ($q->num_rows() == true) {
                $data['status'] = 'gagal';
                $data['pesan']  = 'Email sudah digunakan!';
            } elseif ($q2->num_rows() == true) {
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
            $this->response($this->arr_result);
            exit;
        } else {
            return $input;
        }
    }

    private function template_daftar($full_name)
    {
        $subject = 'Verifikasi Akun';

        $message = 'Terimakasih kepada ' . $full_name;
        $message .= ' telah mendaftar di aplikasi KlikWow';

        return array(
            'subject' => $subject,
            'message' => $message,
        );
    }

    public function daftar_post($user_role_id = 2)
    {
        $hasilkan                    = $this->validasi();
        $verification_number         = rand(100000, 999999);
        $data['full_name']           = ucwords($hasilkan['full_name']);
        $data['email']               = $hasilkan['email'];
        $data['password']            = md5($hasilkan['password']);
        $data['phone']               = $hasilkan['phone'];
        $data['user_role_id']        = $user_role_id;
        $data['register_date']       = date('Y-m-d H:i:s');
        $data['verification_number'] = $verification_number;
        $data['user_status_id']      = 2;
        $te                          = $this->template_daftar($data['full_name']);
        $this->libkirim_email->kirim(array(
            'email'   => $data['email'],
            'subject' => $te['subject'],
            'message' => $te['message'],
        ));
        $this->libzenzifa->kirimSms(array(
            'phone'               => $data['phone'],
            'verification_number' => $data['verification_number'],
        ));
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

    private function _login_validate($user_role_id)
    {
        $input          = $this->post();
        $data['status'] = '';
        $data['pesan']  = '';
        if (@$input['email'] == null) {
            $data['status'] = 'gagal';
            $data['pesan']  = 'Email tidak boleh kosong!';
        } elseif (@$input['password'] == null) {
            $data['status'] = 'gagal';
            $data['pesan']  = 'Password tidak boleh kosong!';
        } else {
            $this->db->group_start();
            $this->db->where('email', $input['email']);
            $this->db->or_where('phone', $input['email']);
            $this->db->group_end();
            $this->db->where('user_role_id', $user_role_id);
            $q = $this->_cek_user();
            if ($q->num_rows() != true) {
                $data['status'] = 'gagal';
                $data['pesan']  = 'Akun tidak ditemukan!';
            } elseif (@$q->row()->user_status_id != 1) {
                $data['status'] = 'gagal';
                $data['pesan']  = 'Akun sedang tidak aktif/suspend!';
            } elseif (@$q->row()->password != md5(@$input['password'])) {
                $data['status'] = 'gagal';
                $data['pesan']  = 'Password Salah!';
            }
        }

        if ($data['status'] == 'gagal') {
            $this->arr_result = array(
                'prilude' => array(
                    'status' => $data['status'],
                    'pesan'  => $data['pesan'],
                ),
            );
            $this->response($this->arr_result);
            exit;
        } else {
            return $input;
        }
    }

    public function login_post($user_role_id = 2)
    {
        $hasil = $this->_login_validate($user_role_id);
        $this->db->group_start();
        $this->db->where('email', $hasil['email']);
        $this->db->or_where('phone', $hasil['email']);
        $this->db->group_end();
        $this->db->where('user_role_id', $user_role_id);
        $q                = $this->_cek_user();
        $data['status']   = 'berhasil';
        $data['pesan']    = 'Berhasil masuk ke aplikasi!';
        $hasilnya         = $q->row();
        $this->arr_result = array(
            'prilude' => array(
                'status' => $data['status'],
                'pesan'  => $data['pesan'],
                'detail' => array(
                    'userID'              => md5($hasilnya->user_id),
                    'full_name'           => $hasilnya->full_name,
                    'verification_number' => $hasilnya->verification_number,
                ),
            ),
        );
        $this->response($this->arr_result);
        exit;
    }

    public function logout_post()
    {
        $input = $this->post();
        $q     = $this->Mo_sb->mengubah('user', array('md5(user_id)' => $input['userID']), array(
            'last_login' => date('Y-m-d H:i:s'),
        ));

        $this->arr_result = array(
            'prilude' => array(
                'status' => 'berhasil',
                'pesan'  => 'Berhasil logout',
            ),
        );
        $this->response($this->arr_result);
        exit;
    }

    public function ambil_user_get()
    {
        $input = $this->get();
        $q     = $this->Mo_sb->mengambil('user', array(
            'md5(user_id)' => $input['user_id'],
        ));
        $this->arr_result = array(
            'prilude' => array(
                'data' => $q->result(),
            ),
        );
        $this->response($this->arr_result);
    }

    public function ku_get()
    {
        $input               = $this->get();
        $verification_number = rand(100000, 999999);
        $cebong              = $this->Mo_sb->mengambil('user', array(
            'email' => @$input['email'],
        ));
        $data['verification_number'] = $verification_number;
        if ($cebong->num_rows() == true) {
            $this->libzenzifa->kirimSms(array(
                'phone'               => @$cebong->row()->phone,
                'verification_number' => $data['verification_number'],
            ));
            $this->Mo_sb->mengubah('user', array(
                'email' => $input['email'],
            ), array(
                'verification_number' => $data['verification_number'],
            ));
        }
        $this->arr_result = array(
            'prilude' => array(
                'status' => 'ok',
            ),
        );
        $this->response($this->arr_result);
    }

    public function cekkn_get()
    {
        $input  = $this->get();
        $status = 'gagal';
        $q      = $this->Mo_sb->mengambil('user', array(
            'email' => @$input['email'],
        ));
        if (@$q->num_rows() == true) {
            if ($q->row()->verification_number == $input['vn']) {
                $status = 'berhasil';
                $this->Mo_sb->mengubah('user', array(
                    'email' => $input['email'],
                ), array(
                    'user_status_id' => 1,
                ));
            }
        }

        $this->arr_result = array(
            'prilude' => array(
                'status' => $status,
                'pesan'  => ucwords($status) . ' verifikasi',
            ),
        );
        $this->response($this->arr_result);
    }
}
