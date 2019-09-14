<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Product_category extends REST_Controller
{
    private $table                = "product_category";
    private $key_product_category = "product_category_id";
    private $field_list           = array('product_category_id', 'parent_product_category_id',
        'category_name', 'image', 'description', 'icon', 'is_active');

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mproduct_category');
    }

    /*
    UNTUK MENDAPATKAN DATA KATEGORI PRODUK TANPA PENCARIAN
    INPUT
    - semua field yang ada pada tabel product_category
    OUTPUT
    {
    "prilude": {
    "status": "success",
    "message": "Data kategori tersedia",
    "data": {
    "data_category": [
    {
    "category": {
    "product_category_id": "1",
    "parent_product_category_id": "0",
    "category_name": "tes",
    "image": "tes.png",
    "icon": "ic_etc.png",
    "description": "tes",
    "is_active": "1"
    },
    "num_of_child_category": 0,
    "parent_category_before": "0"
    }
    ]
    }
    }
    }
     */
    public function finds_post()
    {
        $query = array();
        $limit = 100;

        for ($i = 0; $i < count($this->field_list); $i++) {
            if (isset($_REQUEST[$this->field_list[$i]])) {
                $query[$this->field_list[$i]] = $this->input->post($this->field_list[$i]);
            }
        }

        $query = array(
          'parent_product_category_id' =>  '0'
        );

        if (isset($_POST['limit'])) {
            $limit = $this->input->post('limit');
        }

        $option = array(
            'limit' => $limit,
          );

        $category_data = $this->Mproduct_category->find($query, 'result',$option);

        if (count($category_data) == 0) {
            $this->arr_result = array(
                'prilude' => array(
                    'status'  => 'warning',
                    'message' => 'Data data kategori yang Anda cari tidak ditemukan.',
                ),
            );
        } else {
            $this->arr_result = array(
                'prilude' => array(
                    'status'  => 'success',
                    'message' => 'Data kategori tersedia',
                    'data'    => array(
                        'data_category' => $category_data,
                    ),
                ),
            );
        }

        $this->response($this->arr_result);
    }
/*INPUT

String keyword [tidak wajib] => kata kunci pencarian
int product_category_id [tidak wajib] => kategori produk yang di cari
String order_by => nama field yang dijadikan patokan pengurutan
String ordering => 'DESC' atau 'ASC'
int limit => jumlah data yang akan ditampilkan
int page => data tersebut ada dilembaran ke berapa
OUTPUT
{
    "prilude": {
        "status": "success",
        "message": "Data kategori tersedia",
        "data": [
            {
                "category": {
                    "product_category_id": "5",
                    "category_name": "Komputer",
                    "description": "-",
                    "image": "tes.png",
                    "icon": "ic_etc.png",
                    "is_active": "1",
                    "parent_product_category_id": "0"
                },
                "num_of_child_category": 0,
                "parent_category_before": "0"
            },
            {
                "category": {
                    "product_category_id": "4",
                    "category_name": "Handphone",
                    "description": "-",
                    "image": "tes.png",
                    "icon": "ic_etc.png",
                    "is_active": "1",
                    "parent_product_category_id": "0"
                },
                "num_of_child_category": 0,
                "parent_category_before": "0"
            },
            {
                "category": {
                    "product_category_id": "3",
                    "category_name": "Elektronik",
                    "description": "-",
                    "image": "tes.png",
                    "icon": "ic_etc.png",
                    "is_active": "1",
                    "parent_product_category_id": "0"
                },
                "num_of_child_category": 0,
                "parent_category_before": "0"
            },
            {
                "category": {
                    "product_category_id": "2",
                    "category_name": "Vocher Game",
                    "description": "-",
                    "image": "tes.png",
                    "icon": "ic_etc.png",
                    "is_active": "1",
                    "parent_product_category_id": "0"
                },
                "num_of_child_category": 0,
                "parent_category_before": "0"
            },
            {
                "category": {
                    "product_category_id": "1",
                    "category_name": "Pakaian Pria",
                    "description": "tesd",
                    "image": "tes.png",
                    "icon": "ic_etc.png",
                    "is_active": "1",
                    "parent_product_category_id": "0"
                },
                "num_of_child_category": 5,
                "parent_category_before": "0"
            }
        ]
    }
}
 */
    public function find_post()
    {
        $keyword             = "";
        $product_category_id = 0;

        $order_by = $this->key_product_category;
        $ordering = "desc";
        $limit    = 100;
        $page     = 0;

        if (isset($_POST['keyword'])) {
            $keyword = $this->input->post('keyword');
        }

        if (isset($_POST['product_category_id'])) {
            $product_category_id = $this->input->post('product_category_id');
        }

        if (isset($_POST['order_by'])) {
            $order_by = $this->input->post('order_by');
        }

        if (isset($_POST['ordering'])) {
            $ordering = $this->input->post('ordering');
        }

        if (isset($_POST['limit'])) {
            $limit = $this->input->post('limit');
        }

        if (isset($_POST['page'])) {
            $page = $this->input->post('page');
        }

        $option = array(
            'limit' => $limit,
            'page'  => $page,
            'order' => array(
                'order_by' => $order_by,
                'ordering' => $ordering,
            ),
        );

        $data_product_category = $this->Mproduct_category->finds($keyword, $product_category_id, $option);

        if (count($data_product_category) == 0) {
            $this->arr_result = array(
                'prilude' => array(
                    'status'  => 'warning',
                    'message' => 'Data kategori tidak ditemukan. Silakan ubah kata kunci pencarian atau coba lakukan pencarian di kategori/merk lainnya.',
                ),
            );
        } else {
            $this->arr_result = array(
                'prilude' => array(
                    'status'  => 'success',
                    'message' => 'Data kategori tersedia',
                    'data'    => $data_product_category,
                ),
            );
        }

        $this->response($this->arr_result);
    }

/*MENDAPATKAN DATA CATEGORY SESUAI PARENT NYA
INPUT
product_parent_category_id : WAJIB DI ISI
keyword : TIDAK WAJIB
OUTPUT
{
    "prilude": {
        "status": "success",
        "message": "Data sub kategori tersedia",
        "data": [
            {
                "category": {
                    "product_category_id": "8",
                    "category_name": "Fashion Muslim",
                    "description": "test",
                    "image": "tes.png",
                    "icon": "ic_etc.png",
                    "is_active": "1",
                    "parent_product_category_id": "1"
                },
                "num_of_child_category": 2,
                "parent_category_before": "1"
            },
            {
                "category": {
                    "product_category_id": "7",
                    "category_name": "Perawatan Kecantikan",
                    "description": "-",
                    "image": "tes.png",
                    "icon": "ic_etc.png",
                    "is_active": "1",
                    "parent_product_category_id": "1"
                },
                "num_of_child_category": 0,
                "parent_category_before": "1"
            },
            {
                "category": {
                    "product_category_id": "6",
                    "category_name": "Otomotif",
                    "description": "-",
                    "image": "tes.png",
                    "icon": "ic_etc.png",
                    "is_active": "1",
                    "parent_product_category_id": "1"
                },
                "num_of_child_category": 0,
                "parent_category_before": "1"
            }
        ]
    }
}
 */
    public function find_parent_post()
    {
        $parent_product_category_id = 0;
        $keyword                    = "";
        $order_by                   = $this->key_product_category;
        $ordering                   = "desc";
        $limit                      = 100;
        $page                       = 0;

        if (isset($_POST['parent_product_category_id'])) {
            $parent_product_category_id = $this->input->post('parent_product_category_id');
        }

        if (isset($_POST['keyword'])) {
            $keyword = $this->input->post('keyword');
        }

        if (isset($_POST['order_by'])) {
            $order_by = $this->input->post('order_by');
        }

        if (isset($_POST['ordering'])) {
            $ordering = $this->input->post('ordering');
        }

        if (isset($_POST['limit'])) {
            $limit = $this->input->post('limit');
        }

        if (isset($_POST['page'])) {
            $page = $this->input->post('page');
        }

        $option = array(
            'limit' => $limit,
            'page'  => $page,
            'order' => array(
                'order_by' => $order_by,
                'ordering' => $ordering,
            ),
        );

        $data_parent_category = $this->Mproduct_category->finds_parent($keyword, $parent_product_category_id, $option);

        if (count($data_parent_category) == 0) {
            $this->arr_result = array(
                'prilude' => array(
                    'status'  => 'warning',
                    'message' => 'Tidak Memiliki Sub Kategori.',
                ),
            );
        } else {
            $this->arr_result = array(
                'prilude' => array(
                    'status'  => 'success',
                    'message' => 'Data sub kategori tersedia',
                    'data'    => $data_parent_category,
                ),
            );
        }

        $this->response($this->arr_result);
    }


    function tes_post()
    {   

        $query = array();
        $product_category_id = 0;
        $parent_product_category_id = 0;

        $order_by = $this->key_product_category;
        $ordering = "desc";
        $limit    = 100;
        $page     = 0;

        for ($i = 0; $i < count($this->field_list); $i++) {
            if (isset($_REQUEST[$this->field_list[$i]])) {
                $query[$this->field_list[$i]] = $this->input->post($this->field_list[$i]);
            }
        }

        if (isset($_POST['product_category_id'])) {
            $product_category_id = $this->input->post('product_category_id');
        }

        if (isset($_POST['parent_product_category_id'])) {
            $parent_product_category_id = $this->input->post('parent_product_category_id');
        }

        $query = array(
          'parent_product_category_id' =>  $parent_product_category_id,
        );

      $option = array(
            'limit' => $limit,
            'page'  => $page,
            'order' => array(
                'order_by' => $order_by,
                'ordering' => $ordering,
            ),
        );

      // $data_parent_category = $this->Mproduct_category->find($query ,'result',$option);

      $id = $this->input->post('parent_product_category_id');

      $data_parent_category = $this->Mproduct_category->findChilds($id);

        if (count($data_parent_category) == 0) {
            $this->arr_result = array(
                'prilude' => array(
                    'status'  => 'warning',
                    'message' => 'Tidak Memiliki Sub Kategori.',
                ),
            );
        } else {
            $this->arr_result = array(
                'prilude' => array(
                    'status'  => 'success',
                    'message' => 'Data sub kategori tersedia',
                    'data'    => $data_parent_category,
                ),
            );
        }

        $this->response($this->arr_result);
    }

    

    

}
