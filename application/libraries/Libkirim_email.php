<?php  

/**
 * 
 */
class Libkirim_email
{
	var $ci;
	
	function __construct()
	{
        $this->ci = get_instance();
	}


	function kirim($datanya)
	{
		$this->ci->load->library('email');
        $config['protocol']     = "smtp";
        $config['smtp_host']    = 'smtp.sendgrid.net';
        $config['smtp_port']    = '587';
        $config['smtp_user']    = 'apikey';
        $config['smtp_pass']    = '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';
        $config['charset']      = "utf-8";
        $config['mailtype']     = "html";
        $config['smtp_timeout'] = "7200000";
        $config['smtp_crypto']  = 'tls';
        $config['newline']      = "\r\n";
        $this->ci->email->initialize($config);
        $this->ci->email->from('kirito.20998@gmail.com', 'Klik Wow Official');
        $this->ci->email->to($datanya['email']);
        $this->ci->email->subject($datanya['subject']);
        $this->ci->email->message($datanya['message']);
        if ($this->ci->email->send()) {
            return array('status' => 'berhasil');
        } else {
            return array('status' => 'gagal');
        }
	}


}