<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Auth_model');
    }

    public function index()
    {
        if ($this->session->userdata('user_id')) {
            redirect('products/products');
        }

        $this->load->view('login');
    }

    public function validate()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $user = $this->Auth_model->validate_user($email, $password);
        if ($user) {
            $this->session->set_userdata('user_id', $user->id);
            $this->session->set_userdata('user_name', $user->name);
            $this->session->set_flashdata('success', 'Login Successfull');

            redirect('products/products');
        } else {
            $this->session->set_flashdata('error', 'Invalid Email or Password');
            redirect('auth');
        }
    }

    public function insert_data()
    {
        $name = 'Admin';
        $email = 'admin@jewellery.com';
        $password = 'Admin@1234';

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $hashed_password
        ];

        $this->db->insert(USERS, $data);

        if ($this->db->affected_rows() > 0) {
            echo "user data inserted successfully.";
        } else {
            echo "Failed to insert user data.";
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }
}
