<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_model extends CI_Model
{
    function get_datatables($table, $column_order, $column_search, $order, $where = array(), $options = [])
    {
        $this->_get_datatables_query($table, $column_order, $column_search, $order, $where, $options);

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }

        $query = $this->db->get();


        if ($query !=  FALSE && $query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    private function _get_datatables_query($table, $column_order, $column_search, $order, $where, $options = [])
    {

        if (isset($options['select']) && $options['select'] != '') {
            $this->db->select($options['select']);
        } else {
            $this->db->select('*');
        }

        // echo '<pre>';
        // print_r($column_order[$this->input->post('order')['0']['column']]);
        // exit;
        if ($this->input->post('order') == "") {
            $this->db->order_by(key($order), $order[key($order)]);
        } else if ($this->input->post('order')) {
            $this->db->order_by($column_order[$this->input->post('order')['0']['column']], $this->input->post('order')['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }


        $this->db->from($table);

        if (isset($options['join']) && !empty($options['join'])) {
            foreach ($options['join'] as $joinKey => $joinVal) {

                $this->db->join($joinKey, $joinVal[0], $joinVal[1]);
            }
        }

        $i = 0;

        foreach ($column_search as $item) {
            if (isset($this->input->post('search')['value'])) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $this->input->post('search')['value']);
                } else {
                    $this->db->or_like($item, $this->input->post('search')['value']);
                }

                if (count($column_search) - 1 === $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (is_array($where) && count($where) > 1) {
            foreach ($where as $whKey => $whVal) {
                $this->db->where($whKey, $whVal);
            }
        } else {
            $this->db->where($where);
        }


        if (isset($options['like']) && !empty($options['like'])) {
            foreach ($options['like'] as $key => $value) {
                $this->db->like($key, $value);
            }
        }
    }

    public function count_all($table, $where = array())
    {
        if (is_array($where) && count($where) > 1) {
            foreach ($where as $whKey => $whVal) {
                $this->db->where($whKey, $whVal);
            }
        } else {
            $this->db->where($where);
        }
        $this->db->from($table);
        return $this->db->count_all_results();
    }

    function count_filtered($table, $column_order, $column_search, $order, $where = array(), $optns = [])
    {
        $this->_get_datatables_query($table, $column_order, $column_search, $order, $where, $optns);

        $query = $this->db->get();
        if ($query !=  FALSE && $query->num_rows() > 0) {
            $res =  $query->num_rows();
        } else {
            $res = 0;
        }

        return $res;
    }

    public function get_all_categories()
    {
        return $this->db->get_where(CATEGORY, ['status' => 'NO'])->result();
    }

    public function get_editData($id)
    {
        return $this->db->get_where(PRODUCTS, ['id' => $id, 'trash' => 'NO'])->row();
    }

    public function get_viewData($id)
    {
        $this->db->select('p.*, c.category as category_name');
        $this->db->from(PRODUCTS . ' p');
        $this->db->join(CATEGORY . ' c', 'c.id = p.category_id', 'left');
        $this->db->where('p.id', $id);
        $this->db->where('p.trash', 'NO');

        return $this->db->get()->row();
    }

    public function insert_product($data)
    {
        return $this->db->insert(PRODUCTS, $data);
    }

    public function update_product($data, $id)
    {
        $this->db->where('id', $id);
        return $this->db->update(PRODUCTS, $data);
    }
}
