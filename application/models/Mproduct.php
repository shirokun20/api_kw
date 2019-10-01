<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mproduct extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mproduct_category');
    }

    //mendaptakan semua data produk tanpa filter apapun.
    public function allProduct($product_id = null)
    {
        $this->db->select('p.*');
        $this->db->select('pi.image_link');
        $this->db->join('product_image pi', 'pi.product_id = p.product_id');
        $this->db->group_by('p.product_id');
        if($product_id !== null){
            $this->db->where('p.product_id', $product_id);
        }

        $result = $this->db->get('product p');
        return $result;
    }

    public function product_image($product_id = null)
    {
        $this->db->select('pi.*');

        if($product_id !== null){
            $this->db->where('pi.product_id', $product_id);
        }

        $result = $this->db->get('product_image pi');
        return $result;
    }


    //mendaptakan semua data produk best seller tanpa filter apapun.
    public function product_best_seller_get($product_id = null)
    {
        $this->db->select('p.*');
        $this->db->select('pi.image_link');
        $this->db->join('product_image pi', 'pi.product_id = p.product_id');

        if($product_id !== null){
            $this->db->where('p.product_id', $product_id);
        }

        $result = $this->db->get('product p');
        return $result;
    }

    //mendaptakan semua data produk best rekomended  tanpa filter apapun.
    public function product_recomended_get($product_id = null)
    {
        $this->db->select('p.*');
        $this->db->select('pi.image_link');
        $this->db->join('product_image pi', 'pi.product_id = p.product_id');

        if($product_id !== null){
            $this->db->where('p.product_id', $product_id);
        }

        $result = $this->db->get('product p');
        return $result;
    }

}
