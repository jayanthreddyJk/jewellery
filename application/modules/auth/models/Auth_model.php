<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    public function validate_user($email, $password)
    {
        $this->db->where('email', $email);
        $query = $this->db->get(USERS);
        
        if ($query->num_rows() == 1) {
            $user = $query->row();
            if (password_verify($password, $user->password)) {
                return $user; 
            }
        }
        return false; 
    }
}
?>
