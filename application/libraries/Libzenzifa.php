<?php

/**
 *
 */
class Libzenzifa
{
    public $userkey = "8ijqk6";
    public $passkey = "etmrunaxoo";
    public $url     = "https://reguler.zenziva.net/apps/smsapi.php";

    public function kirimSmsOrder($data)
    {
        $telepon    = $data['phone'];
        $message    = "Ada orderan dengan no order: " . $data['no_order']; 
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $this->url);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS,
            'userkey=' . $this->userkey . '&passkey=' . $this->passkey . '&nohp=' . $telepon . '&pesan=' . urlencode($message));
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        $results = curl_exec($curlHandle);
        curl_close($curlHandle);
        $XMLdata = new SimpleXMLElement($results);
        $status  = $XMLdata->message[0];
        return $status;
    }
    

    public function kirimSms($data)
    {
        $telepon    = $data['phone'];
        $message    = "K0de Pendaftaran Klik Wow Anda adalah ".$data['verification_number'].", RAHASIAKAN K0de Anda.";
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $this->url);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS,
            'userkey=' . $this->userkey . '&passkey=' . $this->passkey . '&nohp=' . $telepon . '&pesan=' . urlencode($message));
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        $results = curl_exec($curlHandle);
        curl_close($curlHandle);
        $XMLdata = new SimpleXMLElement($results);
        $status  = $XMLdata->message[0];
        return $status;
    }

}
