<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mproduct_category extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

     //mendaptakan semua data category product tanpa filter apapun.
    public function all_category($category_id = null)
    {
        $this->db->select('c.*');
        $this->db->select('p.product_id,p.price,p.discount,pi.image_link,s.services_name');
        $this->db->join('product p', 'p.category_id = c.category_id');
        $this->db->join('product_image pi', 'pi.product_id = p.product_id');
        $this->db->join('services s', 's.services_id = c.services_id');
        
        if($category_id !== null){
            $this->db->where('c.category_id', $category_id);
        }

        $result = $this->db->get('category c');
        return $result;
    }
}
