<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Morder extends CI_Model
{
    public function cariMitra($lat = null, $lng = null)
    {
        $this->db->select('m.*');
        $this->db->limit(5);
        if ($lat != null && $lng != null) {
            $this->db->select('(6371 * acos( cos( radians(' . $lat . ') ) * cos( radians( m.latitude ) ) *
        cos( radians( m.longitude ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( m.latitude ) ) ) ) AS distance');
            $this->db->order_by('distance', 'ASC');
            $this->db->having('distance >=', 0);
        }
        return $this->db->get('merchant m');
    }
}

/* End of file Morder.php */
/* Location: ./application/models/Morder.php */
