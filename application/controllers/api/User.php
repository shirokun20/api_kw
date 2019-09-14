<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class User extends REST_Controller
{
    private $key_user_id             = "user_id";
    private $ker_user_role_id        = "user_role_id";
    private $key_user_status_id      = "user_status_id";
    private $key_email               = "email";
    private $key_phone               = "phone";
    private $key_password            = "password";
    private $key_fullname            = "full_name";
    private $key_wa_number           = "wa_number";
    private $key_register_date       = "register_date";
    private $key_last_login          = "last_login";
    private $key_verification_number = "verification_number";
    private $arr_result        = array();

    private $field_list = array('user_id', 'user_role_id', 'user_status_id',
        'email', 'password', 'full_name','wa_number','register_date','last_login', 'verification_number');
    private $required_field = array('email', 'full_name', 'password', 'phone');
    private $md5_field      = 'password';
    private $primary_key    = 'user_id';

    private $user_id      = "";
    private $email        = "";
    private $file_image64 = "";
    private $secret_key   = "";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Muser');
    }

    /*
    Untuk melakukan registrasi pengguna baru
    INPUT
    - int user_role_id
    - string email
    - string password
    - string full_name
     */
    public function register_post()
    {
        $data          = array();
        $is_error      = false;
        $error_message = "";

        for ($i = 0; $i < count($this->field_list); $i++) {
            if (isset($_REQUEST[$this->field_list[$i]])) {
                $data[$this->field_list[$i]] = $this->input->post($this->field_list[$i]);
            }
        }

        for ($i = 0; $i < count($this->required_field); $i++) {
            if (!isset($_REQUEST[$this->required_field[$i]])) {
                $is_error      = true;
                $error_message = $this->required_field[$i] . " tidak boleh kosong";
            }
        }

        if ($is_error) {
            $this->arr_result = array(
                'prilude' => array(
                    'status'  => 'warning',
                    'message' => $error_message,
                ),
            );
        } else {
            //validasi alamat email, jika ada duplikasi
            $query = array(
                $this->key_email => $_REQUEST[$this->key_email],
            );

            $user_data = $this->Muser->find($query, 'row');

            if (count($user_data) == 0) {
                $secret_key                      = mt_rand(100000, 999999);
                $data[$this->ker_user_role_id]   = '2';
                $data[$this->key_user_status_id] = '1';
                $data[$this->key_register_date]  = date('Y-m-d');
                $data[$this->key_last_login]     = date('Y-m-d H:i:s');
                $data[$this->key_password]       = md5($_REQUEST[$this->key_password]);

                if ($this->Muser->create($data)) {
                    $query = array(
                        $this->key_email => $_REQUEST[$this->key_email],
                    );

                    $data_user = $this->Muser->find($query, 'row');

                    $this->arr_result = array(
                        'prilude' => array(
                            'status'  => 'ok',
                            'message' => 'Pendaftaran berhasil dilakukan',
                            'data'    => $data_user,
                        ),
                    );

                    //kirim verifikasi pendaftaran ke email
                    // $tag                = "USER_REGISTER";
                    // $bracket            = "{{full_name}},{{verification_code}}";
                    // $bracket_data       = $_REQUEST[$this->key_fullname].",".$secret_key;

                    // $this->Memail_template->send($_REQUEST[$this->key_email],$tag,$bracket,$bracket_data);
                } else {
                    $this->arr_result = array(
                        'prilude' => array(
                            'status'  => 'error',
                            'message' => 'Ada masalah saat melakukan pendaftaran',
                            'data'    => array(),
                        ),
                    );
                }
            } else {
                $this->arr_result = array(
                    'prilude' => array(
                        'status'  => 'error',
                        'message' => 'Alamat email telah terdaftar, silakan gunakan alamat email lain. Atau klik lupa password jika Anda lupa password.',
                        'data'    => array(),
                    ),
                );
            }
        }
        $this->response($this->arr_result);
    }

    /*
    Verifikasi kode yang dikirim oleh pengguna
    INPUT
    - email => alamat email pengguna
    - code => Kode terdiri dari 6 digit Angka
     */
    public function validate_secret_key_post()
    {
        $this->secret_key = md5($this->input->post('code'));
        $this->email      = $this->input->post($this->key_email);

        $query_user = array(
            $this->key_secret_key => $this->secret_key,
            $this->key_email      => $this->email,
        );

        $data_user = $this->Muser->find($query_user, 'row');

        if (count($data_user) == 0) {
            $this->arr_result = array(
                'prilude' => array(
                    'status'  => 'error',
                    'message' => 'Kode Anda tidak valid, mohon untuk menginputkan kode valid.',
                ),
            );
        } else {
            $this->arr_result = array(
                'prilude' => array(
                    'status'  => 'success',
                    'message' => 'Kode valid',
                    'data'    => array(
                        'data_user' => $data_user,
                    ),
                ),
            );
        }

        $this->response($this->arr_result);
    }

    /*
    Untuk mengirim ulang kode verifikasi ketika pengguan melakukan registrasi/pendaftaran
    INPUT
    - email => alamat email pengguna
     */
    public function generate_resend_code_register_post()
    {
        $this->email = $this->input->post($this->key_email);

        //validasi alamat email pengguna
        $query_user = array(
            $this->key_email => $this->email,
        );

        $data_user = $this->Muser->find($query_user, 'row');

        if (count($data_user) == 0) {
            $this->arr_result = array(
                'prilude' => array(
                    'status'  => 'error',
                    'message' => 'Alamat email Anda tidak terdaftar. Silakan untuk melakukan pendaftaran terlebih dahulu.',
                ),
            );
        } else {
            //kirim email reset password dulu
            $to                = $this->email;
            $tag               = "USER_REGISTER";
            $bracket           = "{{full_name}},{{verification_code}}";
            $verification_code = mt_rand(100000, 999999);

            $data_update = array(
                $this->key_secret_key => md5($verification_code),
            );

            $where_update = array(
                $this->key_email => $this->email,
            );

            if ($this->Muser->update($data_update, $where_update)) {
                $bracket_data = $data_user->full_name . "," . $verification_code;

                $this->Memail_template->send($to, $tag, $bracket, $bracket_data);

                $this->arr_result = array(
                    'prilude' => array(
                        'status'  => 'success',
                        'message' => 'Kami telah mengirim kode verfikasi ke alamat email Anda. Silakan periksa kotak masuk atau kotak spam email Anda.',
                    ),
                );
            } else {
                $this->arr_result = array(
                    'prilude' => array(
                        'status'  => 'error',
                        'message' => 'Ada masalah saat mengirim permintaan kirim ulang kode. Silakan untuk coba kembali.',
                    ),
                );
            }
        }

        $this->response($this->arr_result);
    }

    public function cekKuota_get()
    {
        $query = array(
            'setting_name' => 'SMS_TEMPLATE',
        );
        $templateSMS = $this->Msetting->findByName('SMS_TEMPLATE')->setting_value . "";
        $isi         = str_replace(' ', '%20', $templateSMS);
        $query       = array(
            'setting_name' => 'USER_KEY_SMS',
        );
        $userkeySMS = $this->Msetting->findByName('USER_KEY_SMS')->setting_value . "";

        $query = array(
            'setting_name' => 'USER_KEY_PASS',
        );
        $userkeyPASS = $this->Msetting->findByName('USER_KEY_PASS')->setting_value . "";

        $linkNa = "https://reguler.zenziva.net/apps/smsapibalance.php?userkey=$userkeySMS&passkey=$userkeyPASS";
        //$linkNa = "https://reguler.zenziva.net/apps/smsapi.php?userkey=".$userkeySMS."&passkey=".$userkeyPASS."&nohp=".$noHp."&pesan=".$isi.$kodeVerif;
        $vair = simplexml_load_string(file_get_contents($linkNa));
        return $vair;
    }

    public function cek_kuota_sms_post()
    {
        $this->arr_result = array(
            'prilude' => array(
                'status'  => 'success',
                'message' => 'Kami telah mengirim kode verfikasi ke alamat email Anda. Silakan periksa kotak masuk atau kotak spam email Anda.',
                'data'    => $this->cekKuota_get(),
            ),
        );

        $this->response($this->arr_result);
    }

    /*
    Untuk mengirim ulang kode verifikasi ketika pengguan melakukan registrasi/pendaftaran
    INPUT
    - phone => phone pengguna
     */
    public function generate_resend_code_sms_register_post()
    {
        $this->phone = $this->input->post($this->key_phone);
        $this->email = $this->input->post($this->key_email);

        $verification_code = mt_rand(100000, 999999);

        $data_update = array(
            $this->key_secret_key => md5($verification_code),
        );

        $where_update = array(
            $this->key_email => $this->email,
        );

        if ($this->Muser->update($data_update, $where_update)) {
            $this->arr_result = array(
                'prilude' => array(
                    'status'  => 'success',
                    'message' => 'Kami telah mengirim kode verfikasi ke Nomor Handphone Anda. Silakan periksa kotak masuk atau kotak spam email Anda.',
                    'hasil'   => $this->test_get($this->phone, $verification_code),
                ),
            );
        } else {
            $this->arr_result = array(
                'prilude' => array(
                    'status'  => 'error',
                    'message' => 'Ada masalah saat mengirim permintaan kirim ulang kode. Silakan untuk coba kembali.',
                ),
            );
        }

        $this->response($this->arr_result);
    }

    public function test_get($noHp, $kodeVerif)
    {
        $query = array(
            'setting_name' => 'SMS_TEMPLATE',
        );
        $templateSMS = $this->Msetting->findByName('SMS_TEMPLATE')->setting_value . "";
        $isi         = str_replace(' ', '%20', $templateSMS);
        $query       = array(
            'setting_name' => 'USER_KEY_SMS',
        );
        $userkeySMS = $this->Msetting->findByName('USER_KEY_SMS')->setting_value . "";

        $query = array(
            'setting_name' => 'USER_KEY_PASS',
        );
        $userkeyPASS = $this->Msetting->findByName('USER_KEY_PASS')->setting_value . "";

        //$linkNa = "https://reguler.zenziva.net/apps/smsapibalance.php?userkey=$userkeySMS&passkey=$userkeyPASS";
        $linkNa = "https://reguler.zenziva.net/apps/smsapi.php?userkey=" . $userkeySMS . "&passkey=" . $userkeyPASS . "&nohp=" . $noHp . "&pesan=" . $isi . $kodeVerif;
        $vair   = simplexml_load_string(file_get_contents($linkNa));
        return $vair;
    }

    /*
    Untuk mengenerate kode unik ketika dia akan melakukan perubahan password
    INPUT
    - email => Alamat email pengguna yang akan di reset password-nya
     */
    public function generate_forget_password_code_post()
    {
        $this->email = $this->input->post($this->key_email);

        //validasi alamat email pengguna
        $query_user = array(
            $this->key_email => $this->email,
        );

        $data_user = $this->Muser->find($query_user, 'row');

        if (count($data_user) == 0) {
            $this->arr_result = array(
                'prilude' => array(
                    'status'  => 'error',
                    'message' => 'Alamat email Anda tidak terdaftar. Silakan untuk melakukan pendaftaran terlebih dahulu.',
                ),
            );
        } else {
            //kirim email reset password dulu
            $to                = $this->email;
            $tag               = "RESET_PASSWORD";
            $bracket           = "{{full_name}},{{verification_code}}";
            $verification_code = mt_rand(100000, 999999);

            $data_update = array(
                $this->key_secret_key => md5($verification_code),
            );

            $where_update = array(
                $this->key_email => $this->email,
            );

            if ($this->Muser->update($data_update, $where_update)) {
                $bracket_data = $data_user->full_name . "," . $verification_code;

                $this->Memail_template->send($to, $tag, $bracket, $bracket_data);

                $this->arr_result = array(
                    'prilude' => array(
                        'status'  => 'success',
                        'message' => 'Kami telah mengirim kode verfikasi ke alamat email Anda. Silakan periksa kotak masuk atau kotak spam email Anda.',
                    ),
                );
            } else {
                $this->arr_result = array(
                    'prilude' => array(
                        'status'  => 'error',
                        'message' => 'Ada masalah saat mengirim permintaan reset password. Silakan coba lagi dalam beberapa saat. Kami mohon maaf atas ketidak nyamanan ini.',
                    ),
                );
            }
        }

        $this->response($this->arr_result);
    }
}
