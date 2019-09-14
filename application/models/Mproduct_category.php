<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
class Mproduct_category extends CI_Model
{
  private $table                    = "sub_category";
  private $key_product_category_id  = "sub_category_id";
  private $key_parent_category_id   = "parent_product_category_id";
  private $key_category_name        = "category_name";
  private $key_category_image       = "image";
  private $key_description          = "description";
  private $icon                     = "icon";

  private $field_list               = array('product_category_id','parent_product_category_id',
                                   'category_name','image','icon','description','is_active');
  private $exception_field         = array();

  function __construct()
  {
    parent::__construct();
  }

  /*
  $query = array(
    'product_category_id' =>  '1'
  )
  $result_type = jenis keluaran yang diinginkan pilihannya adalah 'ROW' dan 'RESULT'
  $option = array()
  */
  function find($query,$result_type,$option=null)
  {
    $select = "";

    for ($i=0;$i<count($this->field_list);$i++)
    {
      if (array_key_exists($this->field_list[$i],$query))
      {
        $this->db->where($this->field_list[$i],$query[$this->field_list[$i]]);
      }
    }

    for ($i=0;$i<count($this->field_list);$i++)
    {
      if (!in_array($this->field_list[$i],$this->exception_field))
      {
        $select.=$this->field_list[$i].",";
      }
    }

    $this->db->select($select);
    if ($option!=null)
    {
      if (array_key_exists('limit',$option))
      {
        $this->db->limit($option['limit']);
      }

      if (array_key_exists('order_by',$option))
      {
        $this->db->order_by($option['order_by']['field'],$option['order_by']['option']);
      }

      if(array_key_exists('page',$option))
      {
        $page = $option['page']*$option['limit'];
        $this->db->limit($option['limit'],$page);
      }
    }

    if ($result_type=="row")
    {
      return $this->db->get($this->table)->row();
    }else
    {
      $data_category      = $this->db->get($this->table)->result();
      $new_data_category  = array();
      foreach ($data_category as $data)
      {
        $sub_category       = $this->findChilds($data->product_category_id);
        $parent_category_id = $this->findParent($data->parent_product_category_id);

        if ($parent_category_id=="")
        {
          $new_array = array(
            'category'               => $data,
            'num_of_child_category'  => count($sub_category)
          );
        }else
        {
          $new_array = array(
            'category'               => $data,
            'num_of_child_category'  => count($sub_category),
            'parent_category_before' => $parent_category_id
          );
        }

        array_push($new_data_category,$new_array);
      }

      return $new_data_category;
    }
  }

 public function findChilds($parent_category_id=null)
  {
    $this->db->select('*');
    $this->db->from($this->table);

    if ($parent_category_id==null)
    {
      $this->db->where($this->key_parent_category_id, 0);
    }else
    {
      $this->db->where($this->key_parent_category_id, $parent_category_id);
    }

    $parent = $this->db->get();

    $categories = $parent->result();
    $i=0;
    $arr_biasa  = "";
    foreach($categories as $p_cat){
        $waw = $this->subCategories($p_cat->product_category_id);
        if (strlen($arr_biasa)==0)
        {
          $arr_biasa = $p_cat->product_category_id.",".$waw;
        }else
        {
          $arr_biasa = $arr_biasa.",".$p_cat->product_category_id.",".$waw;
        }
        $i++;
    }

    $clean=explode(',',$arr_biasa);
    return array_filter($clean);
  }

  public function subCategories($id)
  {
      $this->db->select('*');
      $this->db->from($this->table);
      $this->db->where($this->key_parent_category_id, $id);

      $child = $this->db->get();
      $categories = $child->result();
      $i=0;

      $arr_biasa = "";
      foreach($categories as $p_cat){

          $waw = $this->subCategories($p_cat->product_category_id);
          if (strlen($arr_biasa)==0)
          {
            $arr_biasa = $p_cat->product_category_id.",".$waw;
          }else
          {
            $arr_biasa = $arr_biasa.",".$p_cat->product_category_id.",".$waw;
          }
          $i++;
      }

      return $arr_biasa;
  }

  public function findParent($category_id)
  {
    $where = array(
      $this->key_product_category_id => $category_id
    );

    $data_category = $this->db->get_where($this->table,$where)->row();
    if (count($data_category)>0)
    {
      return $data_category->parent_product_category_id;
    }else
    {
      return "";
    }
  }

  public function findParent_name($category_id)
  {
    $where = array(
      $this->key_product_category_id => $category_id
    );

    $data_category = $this->db->get_where($this->table,$where)->row();
    if (count($data_category)>0)
    {
      return $data_category->category_name;
    }else
    {
      return "";
    }
  }

 /* Untuk melakukan pencarian data kategori dengan berbagai pencarian
     */
    public function finds($keyword , $product_category_id, $option)
    {
        $this->db->select($this->table . '.*');

        if ($product_category_id != 0) {
            $this->db->where_in($this->table . '.' . $this->key_product_category_id, $product_category_id);
        }

        if (trim($keyword) != "") {
            $this->db->like($this->key_category_name, $keyword);
            $this->db->or_like($this->table . '.' . $this->key_description, $keyword);
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

        $this->db->where($this->table . '.parent_product_category_id', '0');

        $data_category      = $this->db->get($this->table)->result();
      $new_data_category  = array();
      foreach ($data_category as $data)
      {
        $sub_category       = $this->findChilds($data->product_category_id);
        $parent_category_id = $this->findParent($data->parent_product_category_id);

        if ($parent_category_id=="")
        {
          $new_array = array(
            'category'               => $data,
            'num_of_child_category'  => count($sub_category)
          );
        }else
        {
          $new_array = array(
            'category'               => $data,
            'num_of_child_category'  => count($sub_category),
            'parent_category_before' => $parent_category_id
          );
        }

        array_push($new_data_category,$new_array);
      }

      return $new_data_category;
        
    }

    public function finds_parent($keyword , $product_category_id, $option)
    {
        $this->db->select($this->table . '.*');

        if ($product_category_id != '') {
            $this->db->where_in($this->table . '.' . $this->key_parent_category_id, $product_category_id);
        }

        if (trim($keyword) != "") {
            $this->db->like($this->key_category_name, $keyword);
            $this->db->or_like($this->table . '.' . $this->key_description, $keyword);
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

        $data_category      = $this->db->get($this->table)->result();
      $new_data_category  = array();
      foreach ($data_category as $data)
      {
        $sub_category       = $this->findChilds($data->product_category_id);
        $parent_category_id = $this->findParent($data->parent_product_category_id);
        $category_name_before = $this->findParent_name($data->parent_product_category_id);

        if ($parent_category_id=="")
        {
          $new_array = array(
            'category'               => $data,
            'num_of_child_category'  => count($sub_category)
          );
        }else
        {
          $new_array = array(
            'category'               => $data,
            'num_of_child_category'  => count($sub_category),
            'parent_category_before' => $parent_category_id,
            'category_name_before'   => $category_name_before
          );
        }

        array_push($new_data_category,$new_array);
      }

      return $new_data_category;
        
    }

}
