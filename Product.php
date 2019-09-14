<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Product extends REST_Controller
{
  private $arr_result  = array();
  private $primary_key = "product_id";

  private $field_list  = array('product_id','brand_id','product_category_id',
                         'product_code','product_name','description','price',
                         'discount');

  function __construct()
  {
    parent::__construct();
    $this->load->model('Mproduct');
    $this->load->model('Mproduct_category');
    $this->load->model('Mproduct_image');
    $this->load->model('Mproduct_discussion');
  }

  /*
  MENDAPATKAN PRODUCT DENGAN BERBAGAI MACAM CARA PENCARIAN
  INPUT
  - String keyword [tidak wajib]           => kata kunci pencarian
  - int brand_id [tidak wajib]             => merek yang akan dicari
  - int product_category_id [tidak wajib]  => kategori produk yang di cari
  - String order_by                        => nama field yang dijadikan patokan pengurutan
  - String ordering                        => 'DESC' atau 'ASC'
  - int limit                              => jumlah data yang akan ditampilkan
  - int page                               => data tersebut ada dilembaran ke berapa
  OUTPUT
  {
    "prilude": {
        "status": "success",
        "message": "Data produk tersedia",
        "data": [
            {
                "data_product": {
                    "product_id": "1",
                    "brand_id": "1",
                    "product_category_id": "2",
                    "product_code": "-",
                    "product_name": "Megaman Lampu Zenia 3P411 11 Watt 827",
                    "description": "-",
                    "price": "10000",
                    "discount": "0",
                    "parent_category_id": "1",
                    "category_name": "Emergency LED",
                    "category_image": "-"
                },
                "data_image": {
                    "product_image_id": "1",
                    "product_id": "1",
                    "product_image": "data"
                }
            },
            {
                "data_product": {
                    "product_id": "2",
                    "brand_id": "2",
                    "product_category_id": "1",
                    "product_code": "-",
                    "product_name": " Luxmenn Lampu Led",
                    "description": "-",
                    "price": "50000",
                    "discount": "0",
                    "parent_category_id": "0",
                    "category_name": "Electical & Light",
                    "category_image": "-"
                },
                "data_image": []
            },
            {
                "data_product": {
                    "product_id": "3",
                    "brand_id": "2",
                    "product_category_id": "6",
                    "product_code": "-",
                    "product_name": " Luxmenn Lampu Led",
                    "description": "-",
                    "price": "50000",
                    "discount": "0",
                    "parent_category_id": "5",
                    "category_name": "Keramik",
                    "category_image": "-"
                },
                "data_image": []
            },
            {
                "data_product": {
                    "product_id": "4",
                    "brand_id": "2",
                    "product_category_id": "6",
                    "product_code": "-",
                    "product_name": " Luxmenn Lampu Led",
                    "description": "-",
                    "price": "50000",
                    "discount": "0",
                    "parent_category_id": "5",
                    "category_name": "Keramik",
                    "category_image": "-"
                },
                "data_image": []
            }
        ]
    }
  }
  */
  function finds_post()
  {
    $keyword              = "";
    $brand_id             = 0;
    $product_category_id  = 0;
    $order_by             = $this->primary_key;
    $ordering             = "desc";
    $limit                = 100;
    $page                 = 0;

    if (isset($_POST['keyword']))
    {
      $keyword              = $this->input->post('keyword');
    }

    if (isset($_POST['brand_id']))
    {
      $brand_id             = $this->input->post('brand_id');
    }

    if (isset($_POST['product_category_id']))
    {
      $product_category_id  = $this->input->post('product_category_id');
    }

    if (isset($_POST['order_by']))
    {
      $order_by             = $this->input->post('order_by');
    }

    if (isset($_POST['ordering']))
    {
      $ordering             = $this->input->post('ordering');
    }

    if (isset($_POST['limit']))
    {
      $limit                = $this->input->post('limit');
    }

    if (isset($_POST['page']))
    {
      $page                 = $this->input->post('page');
    }

    $option = array(
      'limit'  => $limit,
      'page'   => $page,
      'order'  => array(
        'order_by'  =>  $order_by,
        'ordering'  =>  $ordering
      )
    );

    $product_categories   = $this->Mproduct_category->findChilds($product_category_id);
    if (count($product_categories)==0)
    {
      $product_categories = array($product_category_id);
      $data_product         = $this->Mproduct->finds($keyword,$brand_id,$product_categories,$option);
    }else
    {
      array_push($product_categories,$product_category_id);
      $data_product         = $this->Mproduct->finds($keyword,$brand_id,$product_categories,$option);
    }

    if (count($data_product)==0)
    {
      $this->arr_result = array(
        'prilude'   => array(
          'status'  => 'warning',
          'message' => 'Data produk tidak ditemukan. Silakan ubah kata kunci pencarian atau coba lakukan pencarian di kategori/merk lainnya.'
        )
      );
    }else
    {
      $this->arr_result = array(
        'prilude'   => array(
          'status'  => 'success',
          'message' => 'Data produk tersedia',
          'data'    => $data_product
        )
      );
    }

    $this->response($this->arr_result);
  }

  /*
  Untuk mendapatkan data tunggal dari product
  INPUT
  - semua field yang ada pada table product
  OUTPUT
  {
      "prilude": {
          "status": "success",
          "message": "Data product tersedia",
          "data": {
              "data_product": {
                  "product_id": "1",
                  "brand_id": "1",
                  "product_category_id": "2",
                  "product_code": "-",
                  "product_name": "Megaman Lampu Zenia 3P411 11 Watt 827",
                  "description": "-",
                  "price": "10000",
                  "discount": "0"
              },
              "data_image": [
                  {
                      "product_image_id": "1",
                      "product_id": "1",
                      "product_image": "data"
                  },
                  {
                      "product_image_id": "2",
                      "product_id": "1",
                      "product_image": "dsf"
                  }
              ]
          }
      }
  }
  */
  function find_post()
  {
    $query = array();

    for ($i=0;$i<count($this->field_list);$i++)
    {
      if (isset($_REQUEST[$this->field_list[$i]]))
      {
        $query[$this->field_list[$i]]=$this->input->post($this->field_list[$i]);
      }
    }

    $option = array(
      'limit'     =>  1
    );

    $product_data = $this->Mproduct->find($query,'row',$option);

    if (count($product_data)==0)
    {
      $this->arr_result = array(
        'prilude' =>  array(
          'status'  =>  'warning',
          'message' =>  'Data product yang Anda cari tidak ditemukan. Silakan coba lagi.'
        )
      );
    }else
    {
      $query        = array(
        'product_id'  =>  $product_data->product_id
      );
      $result_type    = "result";

      $data_image     = $this->Mproduct_image->find($query,$result_type);
      $total_diskusi  = count($this->Mproduct_discussion->find($query,$result_type));
      $option_diskusi = array(
        'limit'    =>  '2',
        'order_by' => array(
          'field'  => 'product_discussion_id',
          'option' => 'desc'
        )
      );
      $data_diskusi   = $this->Mproduct_discussion->find($query,$result_type,$option_diskusi);

      $this->arr_result = array(
        'prilude' =>  array(
          'status'  =>  'success',
          'message' =>  'Data product tersedia',
          'data'    =>  array (
            'data_product' => $product_data,
            'data_image'   => $data_image,
            'data_diskusi' => array (
              'total_diskusi'   => $total_diskusi,
              'preview_diskusi' => $data_diskusi
            )
          )
        )
      );
    }

    $this->response($this->arr_result);
  }

  /*
  Menampilkan hanya produk dengan nilai diskon diisi.
  */
  function finds_promo_post()
  {
    $keyword              = "";
    $brand_id             = 0;
    $product_category_id  = 0;
    $order_by             = $this->primary_key;
    $ordering             = "desc";
    $limit                = 100;
    $page                 = 0;

    if (isset($_POST['keyword']))
    {
      $keyword              = $this->input->post('keyword');
    }

    if (isset($_POST['brand_id']))
    {
      $brand_id             = $this->input->post('brand_id');
    }

    if (isset($_POST['product_category_id']))
    {
      $product_category_id  = $this->input->post('product_category_id');
    }

    if (isset($_POST['order_by']))
    {
      $order_by             = $this->input->post('order_by');
    }

    if (isset($_POST['ordering']))
    {
      $ordering             = $this->input->post('ordering');
    }

    if (isset($_POST['limit']))
    {
      $limit                = $this->input->post('limit');
    }

    if (isset($_POST['page']))
    {
      $page                 = $this->input->post('page');
    }

    $option = array(
      'limit'  => $limit,
      'page'   => $page,
      'order'  => array(
        'order_by'  =>  $order_by,
        'ordering'  =>  $ordering
      ),
      'where_not_in'=>array(
        'field'  => 'discount',
        'option' => '0'
      )
    );

    $product_categories   = $this->Mproduct_category->findChilds($product_category_id);
    if (count($product_categories)==0)
    {
      $product_categories = array($product_category_id);
      $data_product         = $this->Mproduct->finds($keyword,$brand_id,$product_categories,$option);
    }else
    {
      array_push($product_categories,$product_category_id);
      $data_product         = $this->Mproduct->finds($keyword,$brand_id,$product_categories,$option);
    }

    if (count($data_product)==0)
    {
      $this->arr_result = array(
        'prilude'   => array(
          'status'  => 'warning',
          'message' => 'Untuk saat ini, kami tidak memiliki promo'
        )
      );
    }else
    {
      $this->arr_result = array(
        'prilude'   => array(
          'status'  => 'success',
          'message' => 'Data produk promo tersedia',
          'data'    => array(
            'num_of_data'  => count($data_product),
            'data_product' => $data_product
          )
        )
      );
    }

    $this->response($this->arr_result);
  }

  /*
  Untuk mendaptakan data produk yang paling banyak di beli
  */
  function find_most_buy_post()
  {
    $product_category_id = 0;
    $keyword             = "";
    $brand_id            = 0;
    $limit               = 0;
    
    if (isset($_POST['limit']))
    {
      $limit                = $this->input->post('limit');
    }

    //mendapatkan daftar id produk terlaris
    $produk_terlaris = $this->Mproduct->find_most_buy();

    $arr_product_id  = array();

    $i=0;
    foreach($produk_terlaris as $terlaris)
    {
      $arr_product_id[$i]=$terlaris->product_id;
      $i++;
    }

    $option = array(
      'where_in'=>array(
        'field'  => 'product_id',
        'option' => $arr_product_id
      )
    );

    $product_categories   = $this->Mproduct_category->findChilds($product_category_id);
    if (count($product_categories)==0)
    {
      $product_categories = array($product_category_id);
      $data_product         = $this->Mproduct->finds($keyword,$brand_id,$product_categories,$option);
    }else
    {
      array_push($product_categories,$product_category_id);
      $data_product         = $this->Mproduct->finds($keyword,$brand_id,$product_categories,$option);
    }

    if (count($data_product)==0)
    {
      $this->arr_result = array(
        'prilude'   => array(
          'status'  => 'warning',
          'message' => 'Untuk saat ini, kami tidak memiliki promo'
        )
      );
    }else
    {
      $this->arr_result = array(
        'prilude'   => array(
          'status'  => 'success',
          'message' => 'Data produk terlaris tersedia',
          'data'    => array(
            'num_of_data'  => count($data_product),
            'data_product' => $data_product
          )
        )
      );
    }

    $this->response($this->arr_result);
  }

    function add_product_favorite_post()
    {
        $data = $this->Mproduct->addProductFavorite();
        if ($data)
        {
          $this->arr_result = array(
            'prilude'   => array(
              'status'  => 'success',
              'message' => 'Produk difavoritkan'
            )
          );
        }else
        {
          $this->arr_result = array(
            'prilude'   => array(
              'status'  => 'error',
              'message' => 'Gagal, Silahkan ulangi beberapa saat'
            )
          );
        }

        $this->response($this->arr_result);
    }
    
    function remove_product_favorite_post()
    {
        $data = $this->Mproduct->removeProductFavorite();
        if ($data)
        {
          $this->arr_result = array(
            'prilude'   => array(
              'status'  => 'success',
              'message' => 'Produk dihapus dari favorit'
            )
          );
        }else
        {
          $this->arr_result = array(
            'prilude'   => array(
              'status'  => 'error',
              'message' => 'Gagal, Silahkan ulangi beberapa saat'
            )
          );
        }

        $this->response($this->arr_result);
    }
    
    function is_product_favorite_post()
    {
        $user_id    = $this->input->post('user_id');
        $product_id = $this->input->post('product_id');
        
        $data = $this->Mproduct->isProductFavorite($user_id, $product_id);
        if (count($data)!=0)
        {
          $this->arr_result = array(
            'prilude'   => array(
              'status'  => 'success',
              'message' => 'Produk favorit'
            )
          );
        }else
        {
          $this->arr_result = array(
            'prilude'   => array(
              'status'  => 'error',
              'message' => 'Bukan produk favorit'
            )
          );
        }

        $this->response($this->arr_result);
    }
    
    function product_favorite_post()
    {
        $user_id    = $this->input->post('user_id');        
        $data = $this->Mproduct->productFavorite($user_id);
        if (count($data)!=0)
        {
          $this->arr_result = array(
            'prilude'   => array(
              'status'  => 'success',
              'message' => 'Produk favorit',
              'data'    => $data
            )
          );
        }else
        {
          $this->arr_result = array(
            'prilude'   => array(
              'status'  => 'error',
              'message' => 'Bukan produk favorit'
            )
          );
        }

        $this->response($this->arr_result);
    }

    function all_product_post()
    {
        $limit  = $this->input->post('limit');
        $page   = $this->input->post('page');
        $data   = $this->Mproduct->allProduct($limit, $page);
        if (count($data)!=0)
        {
          $this->arr_result = array(
            'prilude'   => array(
              'status'  => 'success',
              'message' => 'All produk',
              'data'    => $data
            )
          );
        }else
        {
          $this->arr_result = array(
            'prilude'   => array(
              'status'  => 'error',
              'message' => 'Tidak Ada data'
            )
          );
        }

        $this->response($this->arr_result);
    }

    function auto_complete_data_post()
    {
        $data = $this->Mproduct->autoCompleteData();
        if (count($data)!=0)
        {
          $this->arr_result = array(
            'prilude'   => array(
              'status'  => 'success',
              'message' => 'Product Name',
              'data'    => $data
            )
          );
        }else
        {
          $this->arr_result = array(
            'prilude'   => array(
              'status'  => 'error',
              'message' => 'Tidak Ada data'
            )
          );
        }

        $this->response($this->arr_result);
    }

}
?>
