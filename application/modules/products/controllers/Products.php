<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Products extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        isLogin();
        $this->load->model('Product_model');
        $this->load->library('form_validation');
        $this->load->library('upload');
        $this->load->library('image_lib');
    }

    public function index()
    {
        $data['ajaxurl'] = 'products/products/get_products';
        $data['page_title'] = 'Products List';
        $data['site_title'] = 'Jewellery - List Products';

        $this->load->view('list', $data);
    }

    public function get_products()
    {

        $table = PRODUCTS . ' as products';

        $column_order = array('products.product_name', 'category.category', 'products.price', 'products.created_at', null);
        $column_search = array('products.product_name', 'category.category', 'products.price', 'DATE_FORMAT(products.created_at,"%d-%m-%Y")');

        $order = array('products.id' => 'desc');
        $where = ['products.trash' => 'NO'];

        $options['select'] = 'products.id, products.product_name, products.price, products.created_at, category.category';
        $options['join'] = [
            CATEGORY . ' as category' => ['category.id = products.category_id AND category.status = "NO"', 'INNER'],
        ];

        $listProducts = $this->Product_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);


        $finalDatas = [];
        $i = 0;

        if (!empty($listProducts)) {
            foreach ($listProducts as $ltKey => $ltVal) {
                $i++;
                $action = '';
                $id = hideval($ltVal->id);

                $action .= " " . anchor('products/products/add/' . $id, '<i class="fa fa-edit"></i>', array('class' => 'resptarget', 'title' => 'Edit'));
                $action .= " " . anchor('products/products/view/' . $id, '<i class="fa fa-eye" style="color: green;" ></i>', array('title' => 'View'));
                $action .= " " . anchor('#', '<i class="fa fa-trash" style="color: red;"></i>', array('class' => 'deleteProduct', 'title' => 'Delete', 'delt-id' => $id));


                $rows = [];
                $rows[] = $ltVal->product_name;
                $rows[] = $ltVal->category;
                $rows[] = $ltVal->price;
                $rows[] = date('d-m-Y', strtotime($ltVal->created_at));
                $rows[] = $action;
                $finalDatas[] = $rows;
            }
        }

        // echo '<pre>';
        // print_r($finalDatas);
        // exit;
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->Product_model->count_all($table, $where),
            "recordsFiltered" => $this->Product_model->count_filtered($table, $column_order, $column_search, $order, $where, $options),
            "data" => $finalDatas,
        );
        echo json_encode($output);
    }

    public function delete()
    {
        $id = $this->input->post('id');

        $decrypted_id = unhideval($id);
        if ($decrypted_id) {
            $data = ['trash' => 'YES'];

            $this->db->where('id', $decrypted_id);
            $updated = $this->db->update(PRODUCTS, $data);

            if ($updated) {
                $this->session->set_flashdata('success', 'Product Deleted successfully!');
                echo json_encode(['status' => 'success', 'message' => 'Product Deleted successfully!']);
            } else {
                $this->session->set_flashdata('error', 'Failed to Delete!');
                echo json_encode(['status' => 'error', 'message' => 'Failed to Delete.']);
            }
        } else {
            $this->session->set_flashdata('error', 'Invalid Product ID !');
            echo json_encode(['status' => 'error', 'message' => 'Invalid Product    ID.']);
        }
    }

    public function add($id = '')
    {
        $editData = [];
        $decrypted_id = unhideval($id);
        $data['site_title'] = 'Jewellery - Add Product';

        if (!empty($decrypted_id)) {
            $data['site_title'] = 'Jewellery - Edit Product';
            $data['editData'] = $this->Product_model->get_editData($decrypted_id);
        }
        $data['categories'] = $this->Product_model->get_all_categories();
        $data['page_title'] = 'Add Product';

        $this->load->view('add', $data);
    }


    public function save($id = '')
    {

        $decrypted_id = unhideval($id);
        $uploadConfig = [
            'upload_path' => './assets/images/uploads/products/',
            'allowed_types' => 'jpg|jpeg|png|gif',
            'max_size' => 2048,
            'encrypt_name' => TRUE
        ];
        $this->upload->initialize($uploadConfig);

        $imagePath = $this->input->post('existing_product_image');

        if (!empty($_FILES['product_image']['name'])) {
            if (!$this->upload->do_upload('product_image')) {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('products/products/add');
            } else {
                $uploadData = $this->upload->data();
                $imagePath = './assets/images/uploads/products/' . $uploadData['file_name'];

                $this->resize_image($imagePath);
            }
        }

        $productData = [
            'product_name' => $this->input->post('product_name'),
            'description' => $this->input->post('description'),
            'price' => $this->input->post('price'),
            'category_id' => $this->input->post('category_id'),
            'image' => $imagePath,
        ];

        if (!empty($decrypted_id)) {
            $this->Product_model->update_product($productData, $decrypted_id);
            $this->session->set_flashdata('success', 'Product Details Updated successfully!');
        } else {
            $this->Product_model->insert_product($productData);
            $this->session->set_flashdata('success', 'Product Details Added successfully!');
        }

        redirect('products/products');
    }

    public function view($id = '')
    {
        $viewData = [];
        $decrypted_id = unhideval($id);
        if (!empty($decrypted_id)) {
            $data['viewData'] = $this->Product_model->get_viewData($decrypted_id);
        }
        $data['page_title'] = 'View Product';
        $data['site_title'] = 'Jewellery - View Product';
   
        $this->load->view('view', $data);
    }

    private function resize_image($imagePath)
    {
        $config['image_library'] = 'gd2';
        $config['source_image'] = './' . $imagePath;
        $config['new_image'] = './' . $imagePath;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 500;
        $config['height'] = 500;

        $this->image_lib->initialize($config);

        if (!$this->image_lib->resize()) {
            $this->session->set_flashdata('error', $this->image_lib->display_errors());
            redirect('products/products/add');
        }
    }
}
