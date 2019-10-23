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
    public function allProduct($product_id = null, $limit = null)
    {
        $this->db->select('p.*');
        $this->db->select('pi.image_link,c.category_name,s.services_name');
        $this->db->join('product_image pi', 'pi.product_id = p.product_id', 'left');
        $this->db->join('category c', 'c.category_id = p.category_id', 'left');
        $this->db->join('services s', 's.services_id = c.services_id', 'left');
        $this->db->where('is_active', 1);
        $this->db->group_by('p.product_id');
        
        if($product_id !== null){
            $this->db->where('p.product_id', $product_id);
        }

        if ($limit == null) {
            $this->db->limit(12);
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

    //mendaptakan semua data produk berdasarkan layanan
    public function product_by_layanan_get($services_id = null)
    {
        $this->db->select('p.*');
        $this->db->select('pi.image_link,c.services_id,s.services_name');
        $this->db->join('product_image pi', 'pi.product_id = p.product_id');
        $this->db->join('category c', 'c.category_id = p.category_id');
        $this->db->join('services s', 's.services_id = c.services_id');
        $this->db->group_by('p.product_id');


        if($services_id !== null){
            $this->db->where($services_id);
        }

        $result = $this->db->get('product p');
        return $result;
    }

}
