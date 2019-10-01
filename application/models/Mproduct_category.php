<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mproduct_category extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    //mendaptakan semua data category tanpa filter apapun.
    public function allCategory($limit = null , $page = null)
    {
        $this->db->select('c.*');
        $this->db->select('s.services_name,s.image');
        $this->db->join('services s', 's.services_id = c.services_id');
        $result = $this->db->get('category c');
        return $result;
    }

}
