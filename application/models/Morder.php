<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Morder extends CI_Model
{
    public function cariMitra($lat = null, $lng = null ,$product_id = null)
    {
        $this->db->select('m.*');
        $this->db->limit(5);
        if ($lat != null && $lng != null) {
            $this->db->select('(6371 * acos( cos( radians(' . $lat . ') ) * cos( radians( m.latitude ) ) *
        cos( radians( m.longitude ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( m.latitude ) ) ) ) AS distance');
            $this->db->order_by('distance', 'ASC');
            $this->db->having('distance >=', 0);
        }
        if($product_id != null){
            $this->db->where( $product_id );
        }
        return $this->db->get('merchant m');
    }

    public function cariMitraDekat($merchant_id , $lat= null , $long = null)
    {
        $this->db->select('m.*');
        $this->db->select('(6371 * acos( cos( radians(' . $lat . ') ) * cos( radians( m.latitude ) ) *
        cos( radians( m.longitude ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( m.latitude ) ) ) ) AS distance');
        $this->db->order_by('distance', 'ASC');
        $this->db->having('distance >=', 0);
        if($merchant_id != null){
            $this->db->where( $merchant_id );
        }

        return $this->db->get('merchant m');
    }

    public function cariMitraBYproduk($produk = null)
    {
        $this->db->select('m.*');
       
        if($produk != null){
            $this->db->where( $produk );
        }

        $this->db->join('merchant_product mp', 'mp.merchant_id = m.merchant_id', 'left');
        return $this->db->get('merchant m');
    }

    public function noUnik($userid = null)
    {
        $year = date('Y');
        $this->db->select('MAX(RIGHT(no_order,5)) AS kd_max');
        if ($userid != null) {
            $this->db->where('buyer_user_id', $userid);
            $this->db->where('YEAR(created_time)', $year);
        }
        $q  = $this->db->get('product_order');
        $kd = "";
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $k) {
                $tmp = ((int) $k->kd_max) + 1;
                $kd  = sprintf("%05s", $tmp);
            }
        } else {
            $kd = "00001";
        }
        // date_default_timezone_set('Asia/Jakarta');
        return 'ORDER/' . $userid . '/' . $year . '/' . $kd;
    }

    private function _getHistory()
    {
        $this->db->select('po.*');
        $this->db->select('os.status_name');
        $this->db->select('(SELECT cp.image_product from cart_product cp where cp.no_order = po.no_order limit 1) as image_product');
        $this->db->join('order_status os', 'os.order_status_id = po.order_status_id', 'left');
        $this->db->group_by('po.no_order');
        $this->db->order_by('po.no_order', 'desc');
    }

    public function getHistory($where = null)
    {
        $this->_getHistory();
        if ($where != null) {
            $this->db->where($where);
        }
        return $this->db->get('product_order po');
    }

    public function getterdekat($lat = null , $lng = null , $product_id)
    {
        $this->db->select('m.*');
        // $this->db->limit(5);
        if ($lat != null && $lng != null) {
            $this->db->select('(6371 * acos( cos( radians(' . $lat . ') ) * cos( radians( m.latitude ) ) *
        cos( radians( m.longitude ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( m.latitude ) ) ) ) AS distance');
            $this->db->order_by('distance', 'ASC');
            $this->db->having('distance >=', 0);
        }
        $where  = array('merchant_product.product_id' => $product_id);
        $this->db->where_in($where);
      
       $this->db->join('merchant m', 'm.merchant_id = merchant_product.merchant_id', 'right');
       return $this->db->get('merchant_product');
    }

    public function getUseMitra($product_id)
    {
        $this->db->select('
                SELECT m.merchant_id, mp.jumlah_produk FROM merchant  m
       INNER JOIN (SELECT product_id,merchant_id, count(product_id) as jumlah_produk
                     FROM merchant_product where product_id = '5'
                    GROUP BY merchant_id) mp ON mp.merchant_id = m.merchant_id
            ');
        
        return $this->db->get('merchant_product mp');

    }
}

/* End of file Morder.php */
/* Location: ./application/models/Morder.php */
