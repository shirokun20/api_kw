<?php
defined('BASEPATH') OR exit('No direct script access allowed');


use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';

require APPPATH . 'libraries/Format.php';

class Test extends REST_Controller {

    private $arr_result  = array();

	public function __construct()
	{
		parent::__construct();
        header("Access-Control-Allow-Origin: *");
        $this->load->library('Libkirim_email');
	}

	public function index_get()
	{
		$this->arr_result = array(
			'prilude' => array(
				'status' => 'ini Contoh Get',
			)
		);
        $this->response($this->arr_result);
	}

	public function index_post()
	{
		$this->arr_result = array(
			'prilude' => array(
				'status' => 'ini Contoh Post',
			)
		);
        $this->response($this->arr_result);
	}

	public function mengirim_post()
	{
		$input = $this->post();
		$datanya['email'] = $input['email'];
		$datanya['subject'] = $input['subject'];
		$datanya['message'] = $input['message'];
		$q = $this->libkirim_email->kirim($datanya);
		$this->arr_result = array(
			'prilude' => array(
				'status' => $q['status'],
			)
		);
        $this->response($this->arr_result);	
	}
}

/* End of file Test.php */
/* Location: ./application/controllers/api/Test.php */
