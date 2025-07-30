<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class AdminM extends CI_Model
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
    function get_raza()
    {
        $sql = " SELECT * FROM `raza` where active=1 ORDER BY `raza`.`time-stamp` DESC";
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
    function add_new_razatype($razaname, $umoor)
    {
    $data = array(
        'name' => $razaname,
        'umoor' => $umoor,
        'fields' => json_encode(['id' => null, 'name' => $razaname, 'umoor' => $umoor, 'fields' => []]),
    );

    if (!empty($data)) {
        $this->db->insert('raza_type', $data);
        $lastId = $this->db->insert_id(); 

       
        $updatedField = json_encode(['id' => $lastId, 'name' => $razaname, 'umoor' => $umoor, 'fields' => []]);
        $this->db->where('id', $lastId);
        $this->db->update('raza_type', array('fields' => $updatedField));

        return $this->db->affected_rows() > 0;
    } else {
        return false;
    }
    }
    public function update_raza($rowId, $data) {
        $this->db->where('id', $rowId);
        $this->db->update('raza_type', $data);
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
    public function get_razatype()
    {
        $sql = 'SELECT * from `raza_type`';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function get_umoorrazatype()
    {
        $sql = "SELECT * from `raza_type` WHERE `umoor` IN ('UmoorDeeniyah', 'UmoorTalimiyah', 'UmoorMarafiqBurhaniyah', 'UmoorMaaliyah', 'UmoorMawaridBashariyah', 'UmoorDakheliyah', 'UmoorKharejiyah', 'UmoorIqtesadiyah', 'UmoorFMB', 'UmoorAl-Qaza', 'UmoorAl-Amlaak', 'UmoorAl-Sehhat')";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function get_eventrazatype()
    {
        $sql = "SELECT * from `raza_type` WHERE  `umoor` IN ('Public-Event', 'Private-Event')";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
     public function get_umoortype()
{
    $this->db->distinct();
    $this->db->select('umoor');
    $this->db->from('raza_type');
    $this->db->where_not_in('umoor', array('Public-Event', 'Private-Event'));

    $query = $this->db->get();
    return $query->result_array();
}


    public function get_eventtype()
    {
        $this->db->distinct();
        $this->db->select('umoor');
        $this->db->from('raza_type');
        $this->db->where_not_in('umoor', array('UmoorDeeniyah', 'UmoorTalimiyah', 'UmoorMarafiqBurhaniyah', 'UmoorMaaliyah', 'UmoorMawaridBashariyah', 'UmoorDakheliyah', 'UmoorKharejiyah', 'UmoorIqtesadiyah', 'UmoorFMB', 'UmoorAl-Qaza', 'UmoorAl-Amlaak', 'UmoorAl-Sehhat'));
    
        $query = $this->db->get();
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
     public function insert_miqaat($data)
    {
        $this->db->insert('rsvp', $data);
        return $this->db->affected_rows() > 0;
    }
    public function get_rsvp_byid($id)
    {
        $sql = "SELECT * FROM rsvp WHERE id = ? and active = 1";
        $query = $this->db->query($sql, array($id));
        return $query->result_array();
    }
    public function modify_miqaat($data, $id)
    {
        if (!empty($data)) {
            $this->db->where('id', $id);
            $this->db->update('rsvp', $data);
            return $this->db->affected_rows() > 0;
        } else {
            return false;
        }
    }
    public function delete_miqaat($id)
    {
        $this->db->where('rsvp_id', $id);
        $this->db->delete('rsvp_attendance');
        $this->db->where('id', $id);
        $this->db->delete('rsvp');

        return true;
    }

     public function get_all_rsvp()
    {
        $sql = "SELECT *
        FROM rsvp
        ORDER BY rsvp.timestamp DESC";

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function get_rsvp_attendance($rsvp)
    {
        $sql = "SELECT * FROM  rsvp_attendance where rsvp_id = ?";
        $query = $this->db->query($sql, array($rsvp));
        return $query->num_rows();
    }
    public function get_rsvp_attendance_guest($rsvp)
    {
        $sql = "SELECT COALESCE(SUM(male), 0) as male_count, COALESCE(SUM(female), 0) as female_count FROM guest_rsvp WHERE rsvp_id = ?";
        $query = $this->db->query($sql, array($rsvp));
        $result = $query->row_array();
        return $result;
    }
    public function get_rsvp_attendance_present($rsvp)
    {
        $sql = "SELECT * FROM  rsvp_attendance where rsvp_id=$rsvp and attend=1";
        $query = $this->db->query($sql);
        return $query->num_rows();
    }
    public function get_rsvp_attendance_present_gender($rsvp)
    {
        $sql = "SELECT SUM(CASE WHEN u.Gender = 'Male' THEN 1 ELSE 0 END) as male_count, SUM(CASE WHEN u.Gender = 'Female' THEN 1 ELSE 0 END) as female_count FROM rsvp_attendance a JOIN user u ON a.user_id = u.id WHERE a.rsvp_id = ? and attend =1";
        $query = $this->db->query($sql, array($rsvp));
        $result = $query->row_array();

        // Check if the result is not empty before returning
        if (!empty($result)) {
            return $result;
        } else {
            return array('male_count' => 0, 'female_count' => 0);
        }
    }
    public function get_user_count()
    {
        $sql = "SELECT COUNT(*) as user_count FROM `user`";
        $query = $this->db->query($sql);
        $result = $query->row_array();

        // Check if the result is not empty before returning
        if (!empty($result['user_count'])) {
            return $result['user_count'];
        } else {
            return 0; // Return 0 if there are no users
        }
    }
    public function addMumineen($data, $logindata)
    {
        $this->db->trans_start();

        $this->db->insert('user', $data);
        $this->db->insert('login', $logindata);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // Transaction failed
            return false;
        } else {
            // Transaction succeeded
            return true;
        }
    }

}