<?php
class Libkw {

    protected $ci;


	public function __construct()
	{
		$this->ci = &get_instance();
        date_default_timezone_set('Asia/Jakarta');
	}

    public function waktu_lalu($timestamp)
    {
        $selisih = time() - strtotime($timestamp);

        $detik  = $selisih;
        $menit  = round($selisih / 60);
        $jam    = round($selisih / 3600);
        $hari   = round($selisih / 86400);
        $minggu = round($selisih / 604800);
        $bulan  = round($selisih / 2419200);
        $tahun  = round($selisih / 29030400);

        if ($detik <= 60) {
            $waktu = $detik . ' detik yang lalu';
        } else if ($menit <= 60) {
            $waktu = $menit . ' menit yang lalu';
        } else if ($jam <= 24) {
            $waktu = $jam . ' jam yang lalu';
        } else if ($hari <= 7) {
            $waktu = $hari . ' hari yang lalu';
        } else if ($minggu <= 4) {
            $waktu = $minggu . ' minggu yang lalu';
        } else if ($bulan <= 12) {
            $waktu = $bulan . ' bulan yang lalu';
        } else {
            $waktu = $tahun . ' tahun yang lalu';
        }

        return $waktu;
    }

    public function TanggalIndo($date)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }
        $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

        $tahun = substr($date, 0, 4);
        $bulan = substr($date, 5, 2);
        $tgl   = substr($date, 8, 2);

        $result = $tgl . " " . $BulanIndo[(int) $bulan - 1] . " " . $tahun;
        return ($result);
    }

    public function TanggalIndoKumplit($date)
    {
        if ($date == null) {
            $date = date('Y-m-d H:i:s');
        }
        $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

        $tahun = substr($date, 0, 4);
        $bulan = substr($date, 5, 2);
        $tgl   = substr($date, 8, 2);
        $jam   = substr($date, 11, 2);
        $menit   = substr($date, 14, 2);
        $detik   = substr($date, 17, 2);
        $result = $tgl . " " . $BulanIndo[(int) $bulan - 1] . " " . $tahun . " " . $jam . ":" . $menit . ":" . $detik;
        return ($result);
    }

    public function tanggal_indo($tanggal, $cetak_hari = false)
    {
        $hari = array(1 => 'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu',
        );

        $bulan = array(1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        );
        $split    = explode('-', $tanggal);
        $tgl_indo = $split[2] . ' ' . $bulan[(int) $split[1]] . ' ' . $split[0];

        if ($cetak_hari) {
            $num = date('N', strtotime($tanggal));
            return $hari[$num] . ', ' . $tgl_indo;
        }
        return $tgl_indo;
    }

    public function tanggal_indo_2($tanggal, $cetak_hari = false)
    {
        $hari = array(1 => 'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu',
        );

        $bulan = array(1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        );
        $split    = explode('-', $tanggal);
        $tgl_indo = $split[2] . '/' . $split[1] . '/' . $split[0];

        if ($cetak_hari) {
            $num = date('N', strtotime($tanggal));
            return $hari[$num] . '&nbsp;,&nbsp;' . $tgl_indo;
        }
        return $tgl_indo;
    }

    public function TanggalIndo2($date)
    {
        $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

        $tahun = substr($date, 0, 4);
        $bulan = substr($date, 5, 2);
        $tgl   = substr($date, 8, 2);

        $result = $tgl . "/" . $bulan . "/" . $tahun;
        return ($result);
    }

    public function tanggalIndoToEng($date)
    {
        $pecah  = explode("/", $date);
        $tahun  = $pecah[2];
        $bulan  = $pecah[1];
        $tgl    = $pecah[0];
        $result = $tahun . "-" . $bulan . "-" . $tgl;
        return ($result);
    }

    public function tanggalEndToIndForm($date)
    {
        $pecah  = explode("-", $date);
        $tahun  = $pecah[0];
        $bulan  = $pecah[1];
        $tgl    = $pecah[2];
        $result = $tgl . "-" . $bulan . "-" . $tahun;
        return ($result);
    }

    public function tanggalEndToIndForm2($date)
    {
        $pecah  = explode("-", $date);
        $tahun  = $pecah[0];
        $bulan  = $pecah[1];
        $tgl    = $pecah[2];
        $result = $tgl . "/" . $bulan . "/" . $tahun;
        return ($result);
    }

    public function tanggalEndToEnd($date)
    {
        $pecah  = explode("-", $date);
        $tahun  = $pecah[0];
        $bulan  = $pecah[1];
        $tgl    = $pecah[2];
        $result = $tahun . "/" . $bulan . "/" . $tgl;
        return ($result);
    }

    public function bulan_indo($date)
    {
        $bulan = array(1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        );

        return $bulan[(int) $date];
    }

    public function ubah_waktu($waktu)
    {
        $tgl_nonformating = $waktu;
        $tgl              = date("D,d M Y g:i:s a", strtotime($tgl_nonformating));
        return $tgl;
    }

    public function text_ucwords($text)
    {
        return ucwords($text);
    }

    public function kelamin($value)
    {
        if ($value == 'M') {
            $kelamin = 'Laki-laki';
        } else {
            $kelamin = 'Perempuan';
        }
        return $kelamin;
    }

    public function kelamin_dua($value)
    {
        if ($value == 'lk') {
            $kelamin = 'Laki-laki';
        } else {
            $kelamin = 'Perempuan';
        }
        return $kelamin;
    }

    public function kelamin_tiga($value)
    {
        if ($value == 'L') {
            $kelamin = 'Laki-laki';
        } else {
            $kelamin = 'Perempuan';
        }
        return $kelamin;
    }

    public function rating($value)
    {
        if ($value >= 4.6 && $value <= 5) {
            return '<i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>';
        } elseif ($value >= 4.1 && $value <= 4.5) {
            return '<i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star-half"></i>';
        } elseif ($value >= 3.6 && $value <= 4) {
            return '<i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star-o"></i>';
        } elseif ($value >= 3.1 && $value <= 3.5) {
            return '<i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star-half"></i>
                    <i class="fa fa-star-o"></i>';
        } elseif ($value >= 2.6 && $value <= 3) {
            return '<i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>';
        } elseif ($value >= 2.1 && $value <= 2.5) {
            return '<i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star-half"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>';
        } elseif ($value >= 1.6 && $value <= 2) {
            return '<i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>';
        } elseif ($value >= 1.1 && $value <= 1.5) {
            return '<i class="fa fa-star"></i>
                    <i class="fa fa-star-half"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>';
        } elseif ($value >= 0.6 && $value <= 1) {
            return '<i class="fa fa-star"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>';
        } elseif ($value >= 0.1 && $value <= 0.5) {
            return '<i class="fa fa-star-half"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>';
        } elseif ($value == 0) {
            return '<i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>';
        } else {
            return 'Jelek Sangat';
        }
    }

    public function rupiah($value)
    {
        return "Rp." . number_format($value, 2, ",", ".");
    }

    public function rupiah_tanpa_rp($value)
    {
        return number_format($value, 2, ",", ".");
    }

    public function rupiah_tanpa_rp_2($value)
    {
        return number_format($value, 0, ",", ".");
    }

    public function penyebut($nilai)
    {
        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp  = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = $this->penyebut($nilai - 10) . " belas";
        } else if ($nilai < 100) {
            $temp = $this->penyebut($nilai / 10) . " puluh" . $this->penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . $this->penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = $this->penyebut($nilai / 100) . " ratus" . $this->penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . $this->penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = $this->penyebut($nilai / 1000) . " ribu" . $this->penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = $this->penyebut($nilai / 1000000) . " juta" . $this->penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = $this->penyebut($nilai / 1000000000) . " milyar" . $this->penyebut(fmod($nilai, 1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = $this->penyebut($nilai / 1000000000000) . " trilyun" . $this->penyebut(fmod($nilai, 1000000000000));
        }
        return $temp;
    }

    public function terbilang($nilai)
    {
        if ($nilai < 0) {
            $hasil = "minus " . trim($this->penyebut($nilai));
        } else {
            $hasil = trim($this->penyebut($nilai)) . " Rupiah";
        }
        return $hasil;
    }

    public function bulan_alfabet($bulan)
    {
        $data = array(
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L',
        );
        return $data[(int) $bulan - 1];
    }

    public function cek_data_null($value = null)
    {
        if ($value != null) {
            return $value;
        } else {
            return "-";
        }
    }

    public function cek_data_ke_2($value = null)
    {
        if ($value != null) {
            return $value;
        } else {
            return "Belum ada Kategori";
        }
    }


    public function cek_no_hp_null($value = null)
    {
        if ($value != null) {
            return "HP.".$value;
        } else {
            return "-";
        }
    }

    public function cek_no_rek_null($value = null)
    {
        if ($value != null) {
            return "REK.".$value;
        } else {
            return "-";
        }
    }

    public function cek_data_null_khusus_driver($value = null)
    {
        if ($value != null) {
            return $value;
        } else {
            return "Belum Lengkap";
        }
    }

    public function cek_rupiah_null($value = null)
    {
        if ($value != null) {
            return $this->rupiah($value);
        } else {
            return $this->rupiah(0);
        }
    }

    public function payment_type($value)
    {
        if ($value == '1') {
            return 'Pembayaran Otomatis';
        } else {
            return 'Pembayaran Manual';
        }
    }

    public function cek_tanggal_null($value = '0000-00-00')
    {
        if ($value != '0000-00-00' || $value != null) {
            return $this->TanggalIndo($value);
        } else {
            return "-";
        }
    }

    public function cek_waktu_null($value)
    {
        if ($value != '0000-00-00 00:00:00' && $value != null) {
            return $this->TanggalIndoKumplit($value);
        } else{
            return "Belum ada";
        }
    }

    public function waktuPlusMinus($tanggal, $apa)
    {
        $date = new DateTime($tanggal);
        $date->modify($apa . ' day');
        $Date2 = $date->format('Y-m-d');
        return $Date2;
    }

    public function angka_to_text($input)
    {
        $input       = number_format($input);
        $input_count = substr_count($input, ',');
        if ($input_count != '0') {
            if ($input_count == '1') {
                return substr($input, 0, -2) . ' Ribu';
            } else if ($input_count == '2') {
                return substr($input, 0, -6) . ' Jt';
            } else if ($input_count == '3') {
                return substr($input, 0, -10) . ' Milyar';
            } else if ($input_count == '4') {
                return substr($input, 0, -13) . ' Trilyun';
            } else {
                return "Tak Terhingga";
            }
        } else {
            return $input;
        }
    }

    public function cutString($value, $jumlah = 1)
    {
        if (strlen($value) > $jumlah) {
            return substr($value, 0, $jumlah) . '....';
        }else{
            return $value;
        }
    }

    public function gambarCek($folder, $value = null)
    {
        if ($value != null && file_exists($folder . $value)) {
            return array('status' => 'ada');
        } else {
            return array('status' => 'tidak');
        }
    }

    public function toJson($data, $code = 200)
    {
        $this->ci->output->set_status_header($code);
        $this->ci->output->set_content_type('application/json');
        $this->ci->output->set_output(json_encode($data));
        $this->ci->output->_display();
    }

}

/* End of file Libkw.php */
/* Location: ./application/libraries/Libkw.php */
