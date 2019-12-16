<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mproduct_category extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

     //mendaptakan semua data category product tanpa filter apapun.
    private function _relasi()
    {
        $this->db->join('category c', 'c.category_id = p.category_id', 'left');
        $this->db->join('product_image pi', 'pi.product_id = p.product_id', 'left');
        $this->db->group_by('p.product_id');
    }

    public function all_category($category_id = null)
    {
        $this->_relasi();
        if ($category_id != null) {
            $this->db->where('p.category_id', $category_id);
        }
        return $this->db->get('product p');
    }
}
