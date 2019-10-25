<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';

require APPPATH . 'libraries/Format.php';

class Order extends REST_Controller
{

    private $arr_result = array();

    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        $this->load->model('Morder');
        date_default_timezone_set("Asia/Bangkok");
    }

    public function index($offset = 0)
    {

    }

    public function at_get()
    {
        $input = $this->get();
        $this->db->select('*');
        $this->db->order_by('created_time', 'desc');
        $this->db->limit(1);
        $q                = $this->Mo_sb->mengambil('product_order', array('md5(buyer_user_id)' => $input['user_id']));
        $this->arr_result = array(
            'prilude' => array(
                'detail' => $q->result(),
            ),
        );
        $this->response($this->arr_result);
        exit;
    }

    public function searc_mitra_dekat_get()
    {
        $input            = $this->get();
        $lat              = @$input['lat'];
        $lng              = @$input['lng'];
        $q                = $this->Morder->cariMitra($lat, $lng);
        $this->arr_result = array(
            'prilude' => array(
                'detail' => $q->result(),
                'jm'     => $this->Mo_sb->mengambil('setting', array('setting_name' => 'JARAK_MAKSIMUM'))->result(),
            ),
        );
        $this->response($this->arr_result);
        exit;
    }

    public function amkw_get()
    {
        $q                = $this->Mo_sb->mengambil('shipping_method', array('shipping_method_id >' => 1));
        $q2               = $this->Mo_sb->mengambil('shipping_timing');
        $this->arr_result = array(
            'prilude' => array(
                'metode_kirim' => $q->result(),
                'waktu_kirim'  => $q2->result(),
            ),
        );
        $this->response($this->arr_result);
        exit;
    }

    public function cuckdpo($data, $no_order)
    {
        $user_id = $data['user_id'];

        foreach ($data['detail'] as $key) {
            $q = $this->Mo_sb->mengubah('cart_product', array(
                'product_id' => $key['product_id'],
                'user_id'    => $user_id,
                'is_cart'    => '1',
            ), array(
                'is_cart'  => '2',
                'no_order' => $no_order,
            ));
        }
    }

    public function checkout_post()
    {
        $input                        = $this->post();
        $user_id                      = $input['user_id'];
        $total                        = $input['totalBayar'];
        $q                            = $this->Mo_sb->mengambil('user', array('md5(user_id)' => $user_id));
        $data                         = json_decode($input['bayar'], true);
        $no_order                     = $this->Morder->noUnik($q->row()->user_id);
        $insert['no_order']           = $no_order;
        $insert['created_time']       = date('Y-m-d H:i:s');
        $insert['sub_total']          = $total;
        $insert['order_status_id']    = 1;
        $insert['buyer_user_id']      = $q->row()->user_id;
        $insert['discount']           = 0;
        $insert['shipping_price']     = 0;
        $insert['shipping_price']     = 0;
        $insert['total']              = $total;
        $insert['shipping_time_id']   = $data['checkOutRedux']['shipping_time_id'];
        $insert['shipping_method_id'] = $data['checkOutRedux']['shipping_method_id'];
        $insert['payment_method_id']  = $data['checkOutRedux']['payment_method_id'];
        $insert['cfm']                = $data['checkOutRedux']['cfm'];
        $insert['address']            = $data['checkOutRedux']['alamat'];
        $insert['latitude']           = $data['checkOutRedux']['lokasi']['lat'];
        $insert['longitude']          = $data['checkOutRedux']['lokasi']['lng'];
        if ($data['checkOutRedux']['shipping_time_id'] == '3') {
            $insert['shipping_schedule'] = ($data['checkOutRedux']['tanggal'] . ' ' . $data['checkOutRedux']['waktu'] . ':00');
        }
        $q = $this->Mo_sb->menambah('product_order', $insert);
        if ($q['status'] == 'berhasil') {
            $this->cuckdpo(array(
                'user_id' => $insert['buyer_user_id'],
                'detail'  => $data['cartKlikWow']['barangNya'],
            ), $no_order);
        }
        $this->arr_result = array(
            'prilude' => array(
                'status' => $q['status'],
                'pesan'  => ucwords($q['status']) . ' melakukan order dengan no:' . $no_order,
            ),
        );
        $this->response($this->arr_result);
        exit;
    }

    public function TanggalIndo($date)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }
        $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

        $tahun = substr($date, 0, 4);
        $bulan = substr($date, 5, 2);
        $tgl   = substr($date, 8, 2);

        $result = $tgl . " " . $BulanIndo[(int) $bulan - 1] . " " . $tahun;
        return ($result);
    }

    public function cek_tanggal_null($value = '0000-00-00')
    {
        if ($value != '0000-00-00' || $value != null) {
            return $this->TanggalIndo($value);
        } else {
            return "-";
        }
    }

    public function history_get()
    {
        $input = $this->get();
        $this->db->where('md5(po.buyer_user_id)', $input['user_id']);
        if (@$input['cari'] != null || @$input['cari'] != '') {
            $this->db->group_start();
            $this->db->like('po.no_order', @$input['cari']);
            $this->db->or_like('po.total', @$input['cari']);
            $this->db->group_end();
        }
        if (@$input['order_status_id'] != null) {
            $this->db->where('po.order_status_id', @$input['order_status_id']);
        }
        $q    = $this->Morder->getHistory();
        $json = array();
        foreach ($q->result() as $key) {
            $r                  = array();
            $r['no_order']      = $key->no_order;
            $r['no_order_md5']      = md5($key->no_order);
            $r['created_time']  = $this->cek_tanggal_null($key->created_time);
            $r['total']         = (int) $key->total;
            $r['status_name']   = $key->status_name;
            $r['image_product'] = $key->image_product;
            $json[]             = $r;
        }
        $this->arr_result = array(
            'prilude' => array(
                'data' => $json,
            ),
        );
        $this->response($this->arr_result);
    }


    public function ambilStatus_get()
    {
        $q = $this->Mo_sb->mengambil('order_status');
        $this->arr_result = array(
            'prilude' => array(
                'data' => $q->result(),
            ),
        );
        $this->response($this->arr_result);
    }
}

/* End of file Order.php */
/* Location: ./application/controllers/api/Order.php */
