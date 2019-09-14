<?php

class Mo_sb extends CI_Model
{
    public function menambah($tabel, $data)
    {
        if ($this->db->insert($tabel, $data)) {
            return array(
                'status' => 'berhasil',
                'nilai'  => $this->db->insert_id(),
            );
        } else {
            return array('status' => 'gagal');
        }
    }

    public function menghapus($tabel, $data)
    {
        $this->db->where($data);
        if ($this->db->delete($tabel)) {
            return array('status' => 'berhasil');
        } else {
            return array('status' => 'gagal');
        }
    }

    public function mengubah($tabel, $where, $data)
    {
        $this->db->where($where);
        if ($this->db->update($tabel, $data)) {
            return array('status' => 'berhasil');
        } else {
            return array('status' => 'gagal');
        }
    }

    public function mengambil($tabel, $where = null, $limit = null, $like = null)
    {
        if ($like != null) {
            $this->db->like($like);
        }
        if ($limit != null) {
            $this->db->limit($limit);
        }

        if ($where != null) {
            $this->db->where($where);
        }
        return $this->db->get($tabel);
    }

    public function mengambilOrderBy($tabel, $where = null, $orderBy = null,$type = null)
    {
        if ($where != null) {
            $this->db->where($where);
        }

        if ($orderBy != null) {
            $this->db->order_by($orderBy, $type);
        }
        return $this->db->get($tabel);
    }

    public function mencari($tabel, $cari = null, $where = null)
    {
        if ($cari != null) {
            $this->db->like($cari);
        }
        if ($where != null) {
            $this->db->where($where);
        }
        return $this->db->get($tabel);
    }

    public function mencariByWhere($tabel, $where = null, $cari = null)
    {
        if ($cari != null) {
            $this->db->like($cari);
        }
        if ($where != null) {
            $this->db->where($where);
        }
        return $this->db->get($tabel);
    }

    public function menghitung($tabel, $where = null)
    {
        if ($where != null) {
            $this->db->where($where);
        }
        $this->db->from($tabel);
        return $this->db->count_all_results();
    }

    public function mencarinilaimaksimun($tabel, $field = null, $alias = null, $where = null)
    {
        if ($field != null) {
            $this->db->select_max($field, $alias);
        }
        if ($where != null) {
            $this->db->where($where);
        }
        return $this->db->get($tabel);
    }

    public function mencari_total($tabel, $field, $alias, $where = null)
    {
        $this->db->select('sum('.$field.') as '.$alias);
        if ($where != null) {
            $this->db->where($where);
        }
        return $this->db->get($tabel);
    }


    public function mencari_by_funsi($tabel, $where = null)
    {
        if ($where != null) {
            $where;
        }

        return $this->db->get($tabel);
    }
}

/* End of file Mo_sb.php */
/* Location: ./application/models/Mo_sb.php */
