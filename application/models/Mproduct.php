<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Mproduct extends CI_Model
{
    private $table                  = "product";
    private $key_product_id         = "product_id";
    private $key_product_name       = "product_name";
    private $table_product_category = "sub_category";
    private $field_list             = array('product_id', 'product_name', 'description');
    private $exception_field        = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mproduct_category');
    }

    //mendaptakan semua data produk tanpa filter apapun.
    public function findAll()
    {
        return $this->db->get('product')->result();
    }
    /*
    Untuk melakukan pencarian data product
     */
    public function finds($keyword, $option, $product_id)
    {
        $this->db->select($this->table . '.*');
        if (trim($keyword) != "") {
            $this->db->like($this->key_product_name, $keyword);
        }

        if ($product_id != 0) {
            $this->db->where_in($this->table . '.' . $this->key_product_id, $product_id);
        }

        if (array_key_exists('order', $option)) {
            $this->db->order_by($this->table . "." . $option['order']['order_by'], $option['order']['ordering']);
        }

        if ($option != null) {
            if (array_key_exists('limit', $option)) {
                $this->db->limit($this->table . "." . $option['limit']);
            }

            if (array_key_exists('order_by', $option)) {
                $this->db->order_by($this->table . "." . $option['order_by']['field'], $option['order_by']['option']);
            }

            if (array_key_exists('page', $option)) {
                $page = $option['page'] * $option['limit'];
                $this->db->limit($option['limit'], $page);
            }

            if (array_key_exists('where_in', $option)) {
                $this->db->where_in($this->table . '.' . $option['where_in']['field'], $option['where_in']['option']);
            }

            if (array_key_exists('where_not_in', $option)) {
                $this->db->where_not_in($this->table . '.' . $option['where_not_in']['field'], $option['where_not_in']['option']);
            }
        }

        $data_product = $this->db->get($this->table)->result();

        $array_data = array();
        foreach ($data_product as $product) {
            $query = array(
                $this->key_product_id => $product->product_id,
            );

            if (count($data_image) == 0) {
                $new_array = array(
                    'data_product' => $product,
                );
            } else {
                $new_array = array(
                    'data_product' => $product,
                );
            }

            array_push($array_data, $new_array);
        }

        //array_push($data_product,$data_image);
        //return $data_image;
        return $array_data;
    }

    public function allProduct($limit = null , $page = null)
    {
        $offset = $page * $limit;
        $sql    = "SELECT * FROM product";

        $result = $this->db->query($sql)->result();
        return $result;
    }

}
