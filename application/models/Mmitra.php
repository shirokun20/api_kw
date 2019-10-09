<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mmitra extends CI_Model {

	
    public function allProduct()
    {
        $result = $this->db->get('merchant');
        return $result;
    }

}

/* End of file Mmitra.php */
/* Location: ./application/models/Mmitra.php */