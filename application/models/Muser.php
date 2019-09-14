<?php
class Muser extends CI_Model
{
  private $table              = "user";
  private $field_list         = array('user_id','email','phone','password','full_name','wa_number','regiter_date','last_login','verification_number');
  private $exception_field    = array('password');
  private $key_cashback       = "cashback";
  private $key_user_id        = "user_id";
  
  function __construct()
  {
    parent::__construct();
  }

  /*
  $query = array(
    'user_id' =>  '1'
  )
  $result_type = String
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
    }

    if ($result_type=="row")
    {
      return $this->db->get($this->table)->row();
    }else
    {
      return $this->db->get($this->table)->result();
    }
  }

  function create($data)
  {
    if ($this->db->insert($this->table,$data))
    {
      $user_id=$this->db->insert_id();
      return true;
      
    }else
    {
      return false;
    }
  }

  function update($data,$where)
  {
    if ($this->db->update($this->table,$data,$where))
    {
      return true;
    }else
    {
      return false;
    }
  }

  function update_cashback($amount,$transaction_type,$user_id)
  {
    if ($transaction_type == 'DB')
    {
      $this->db->set($this->key_cashback,$this->key_cashback."-".$amount,false);
    }else
    {
      $this->db->set($this->key_cashback,$this->key_cashback."+".$amount,false);
    }

    $where = array(
      $this->key_user_id => $user_id
    );

    $this->db->where($where);

    if ($this->db->update($this->table))
    {
      return true;
    }else
    {
      return false;
    }
  }

    function insertUserBalanceData($user_id){

        $data=array(
            'user_id' =>  $user_id,
            'amount'  =>  "0"
        );

        if ($this->db->insert('user_balance',$data))
        {
          return true;
        }else
        {
          return false;
        }
    }

}
?>
