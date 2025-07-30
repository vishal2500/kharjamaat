<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class UmoorM extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    function check_db()
    {
        $sql = " SELECT * from `login`";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function get_raza($umoor)
    {
        $sql = "SELECT * FROM `raza` 
                WHERE `active` = 1 
                AND `razaType` IN (SELECT `id` FROM `raza_type` WHERE `umoor` = '$umoor') 
                ORDER BY `time-stamp` DESC";

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    function delet_raza_type($id)
    {
        $data = array(
            'active' => 0,
        );

        $this->db->where('id', $id);
        $this->db->update('raza_type',$data);
        return $this->db->affected_rows() > 0;
    }
    function add_new_razatype($razaname)
    {
        $data = array(
            'name' => $razaname,
            'fields' => '',
        );

        if (!empty($data)) {
            $this->db->insert('raza_type', $data);
            $lastId = $this->db->insert_id(); // Get the last inserted ID

            // Update the 'fields' column with the new ID
            $updatedField = '{"id":' . $lastId . ',"name":"' . $razaname . '","fields":[]}';
            $this->db->where('id', $lastId);
            $this->db->update('raza_type', array('fields' => $updatedField));

            return $this->db->affected_rows() > 0;
        } else {
            return false;
        }
    }


    function approve_raza($raza_id, $remark)
    {
        
        $remark = str_replace(array("\r", "\n"), '', $remark);
        $data = array(
            'coordinator-status' => 1,
            'status' => 1,
            'remark' => $remark
        );


        if (!empty($data)) {
            $this->db->where('id', $raza_id);
            $this->db->update('raza', $data);
            return $this->db->affected_rows() > 0;
        } else {
            return false;
        }
    }
    function reject_raza($raza_id, $remark)
    {
        
        $remark = str_replace(array("\r", "\n"), '', $remark);
        $data = array(
            'coordinator-status' => 2,
            'status' => 4,
            'remark' => $remark
        );


        if (!empty($data)) {
            $this->db->where('id', $raza_id);
            $this->db->update('raza', $data);
            return $this->db->affected_rows() > 0;
        } else {
            return false;
        }
    }

    public function get_user_by_raza_id($raza_id)
    {
        $this->db->select('u.*');
        $this->db->from('raza r');
        $this->db->join('user u', 'r.user_id = u.ITS_ID');
        $this->db->where('r.id', $raza_id);

        $query = $this->db->get();
        echo print_r($query);

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return null;
        }
    }
    public function get_razatype($umoor)
    {
        $sql = "SELECT * FROM `raza_type` WHERE `umoor` = '$umoor'";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    

    public function get_razatype_byid($id)
    {
        $sql = "SELECT * from `raza_type` where `id`= $id ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function update_raza_type($id, $raza)
    {
        $data = array(
            'fields' => $raza
        );
        if (!empty($data)) {
            $this->db->where('id', $id);
            $this->db->update('raza_type', $data);
            return $this->db->affected_rows() > 0;
        } else {
            return false;
        }
    }
    


}