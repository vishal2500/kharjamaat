<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class AccountM extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    function check_user($username, $password)
    {
        $sql = "SELECT * FROM `login` WHERE `username` = ? AND `password` = ? AND `active` = 1";

        $query = $this->db->query($sql, array($username, $password));

        return $query->result_array();
    }
    function check_user_exist($username)
    {
        $sql = "SELECT * FROM `user` WHERE `ITS_ID` = ? ";

        $query = $this->db->query($sql, array($username));

        return $query->result_array();
    }
    public function change_password_to_default($its_id, $password)
    {
        $data = array(
            'password' => md5($password)
        );

        $this->db->where('username', $its_id);
        $this->db->update('login', $data);
        return $this->db->affected_rows() > 0;
    }
    public function get_all_family_member($user_id)
    {
        $sql = "SELECT * FROM user WHERE HOF_ID = $user_id";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function get_rsvp_attendance($rsvp, $userId)
    {
        $sql = "SELECT * FROM  rsvp_attendance where user_id=$userId AND rsvp_id=$rsvp";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function get_rsvp_attendance_present($rsvp, $userId)
    {
        $sql = "SELECT * FROM  rsvp_attendance where user_id=$userId AND rsvp_id=$rsvp and attend=1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function insert_raza($userId, $razaType, $data, $sabil, $fmb)
    {
        $data = array(
            'user_id' => $userId,
            'razaType' => $razaType,
            'razadata' => $data,
            'sabil' => $sabil,
            'fmb' => $fmb,
        );

        if (!empty($data)) {
            $this->db->insert('raza', $data);
            return $this->db->affected_rows() > 0;
        } else {
            return false;
        }
    }
    public function update_raza($id, $razadata)
    {
        $data = array(
            'razadata' => $razadata
        );

        $this->db->where('id', $id);
        $this->db->update('raza', $data);

        return $this->db->affected_rows() > 0;
    }
    public function delete_raza($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('raza');
        return $this->db->affected_rows() > 0;
    }
    public function delete_vasan_req($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('vasan_request');
        return $this->db->affected_rows() > 0;
    }
    public function update_vasan_req($id, $reason, $from_date, $to_date, $utensil)
    {
        $data = array(
            'reason' => $reason,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'utensils' => $utensil,
        );


        if (!empty($data)) {
            $this->db->where('id', $id);
            $this->db->update('vasan_request', $data);
            return $this->db->affected_rows() > 0;
        } else {
            return false;
        }
    }
    public function insert_vasan_req($userId, $reason, $from_date, $to_date, $utensil)
    {
        $data = array(
            'user_id' => $userId,
            'reason' => $reason,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'utensils' => $utensil,
        );


        if (!empty($data)) {
            $this->db->insert('vasan_request', $data);
            return $this->db->affected_rows() > 0;
        } else {
            return false;
        }
    }
    public function get_razatype()
    {
        $sql = 'SELECT * from `raza_type` where  `active`=1';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function get_raza_byid($id)
    {
        $sql = 'SELECT * from `raza` where  id = ?';
        $query = $this->db->query($sql, array($id));
        return $query->result_array();
    }
    public function get_vasantype()
    {
        $sql = 'SELECT * from `vasan_type` where  `active`=1';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function get_raza($user_id)
    {
        $sql = "SELECT * FROM raza WHERE user_id = ? AND active = 1 ORDER BY raza.`time-stamp` DESC";
        $query = $this->db->query($sql, array($user_id));
        return $query->result_array();
    }
    public function get_vasan_request($user_id)
    {
        $sql = "SELECT * FROM vasan_request WHERE user_id = ? AND active = 1";
        $query = $this->db->query($sql, array($user_id));
        return $query->result_array();
    }
    public function get_rsvp()
    {
        $sql = "SELECT *
        FROM rsvp
        WHERE expired BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 15 DAY)
        ORDER BY rsvp.timestamp DESC";

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    public function get_rsvp_byid($id)
    {
        $sql = "SELECT * FROM rsvp WHERE id = ? and active = 1";
        $query = $this->db->query($sql, array($id));
        return $query->result_array();
    }
    public function get_vasan_byid($id)
    {
        $sql = "SELECT * FROM vasan_type WHERE id = ? and active = 1";
        $query = $this->db->query($sql, array($id));
        return $query->result_array();
    }
    public function get_vasanreq_byid($id)
    {
        $sql = "SELECT * FROM vasan_request WHERE id = ? and active = 1";
        $query = $this->db->query($sql, array($id));
        return $query->result_array();
    }
    public function get_razatype_byid($id)
    {
        $sql = "SELECT * FROM raza_type WHERE id = ? and active = 1";
        $query = $this->db->query($sql, array($id));
        return $query->result_array();
    }
    public function insert_rsvp_attendance($id, $user_id, $attend)
    {
        $data = array(
            'user_id' => $user_id,
            'rsvp_id' => $id,
            'attend' => $attend,
        );

        if (!empty($data)) {
            $this->db->insert('rsvp_attendance', $data);
            return $this->db->affected_rows() > 0;
        } else {
            return false;
        }
    }
    public function update_rsvp_attendance($id, $user_id, $attend)
    {
        $data = array(
            'attend' => $attend,
        );

        if (!empty($data)) {
            $this->db->where('rsvp_id', $id);
            $this->db->where('user_id', $user_id);
            $this->db->update('rsvp_attendance', $data);
            return $this->db->affected_rows() > 0;
        } else {
            return false;
        }
    }

    public function change_password($id, $password)
    {
        $data = array(
            'password' => $password,
        );


        if (!empty($data)) {
            $this->db->where('username', $id);
            $this->db->update('login', $data);
            return $this->db->affected_rows() > 0;
        } else {
            return false;
        }
    }
    public function get_user($id)
    {
        $sql = "SELECT * from `user` where  `ITS_ID`= '$id'";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function get_all_rsvp()
    {
        $sql = "SELECT *
        FROM rsvp
        ORDER BY rsvp.timestamp DESC";

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function get_guset_rsvp($familyid, $rsvp)
    {
        $sql = "SELECT * FROM guest_rsvp WHERE rsvp_id = ?  and familyid = ?";
        $query = $this->db->query($sql, array($rsvp, $familyid));
        return $query->result_array();
    }
    public function insert_rsvp_attendance_guest($hofid, $guest_male, $guest_female, $id)
    {
        $data = array(
            'familyid' => $hofid,
            'rsvp_id' => $id,
            'male' => $guest_male,
            'female' => $guest_female,
        );

        // Check if already present
        $existing_record = $this->db->get_where('guest_rsvp', array('rsvp_id' => $id, 'familyid' => $hofid))->row_array();

        if (empty($existing_record)) {
            // Insert new record if not exist
            $this->db->insert('guest_rsvp', $data);
            return $this->db->affected_rows() > 0;
        } else {
            // Update existing record
            $this->db->where('id', $existing_record['id']);
            $this->db->update('guest_rsvp', $data);
            return $this->db->affected_rows() > 0;
        }
    }
    public function getUserData($user_id)
    {
        $this->db->where('ITS_ID', $user_id);
        $query = $this->db->get('user');
        return $query->row_array();
    }

    public function getFatherData($father_its_id)
    {
        if ($father_its_id !== null && $father_its_id !== 0) {
            $this->db->where('ITS_ID', $father_its_id);
            $query = $this->db->get('user');
            $result = $query->row_array();

            // Check if data is found and ITS_ID is not 0 or null
            if ($result && $result['ITS_ID'] !== null && $result['ITS_ID'] !== 0) {
                return $result;
            } else {
                // Return default values or handle as needed
                return array(
                    'ITS_ID' => '---',
                    'Full_Name' => '---',
                );
            }
        } else {
            return array(
                'ITS_ID' => '---',
                'Full_Name' => '---',
            );
        }
    }

    public function getMotherData($mother_its_id)
    {
        if ($mother_its_id !== null && $mother_its_id !== 0) {
            $this->db->where('ITS_ID', $mother_its_id);
            $query = $this->db->get('user');
            $result = $query->row_array();

            // Check if data is found and ITS_ID is not 0 or null
            if ($result && $result['ITS_ID'] !== null && $result['ITS_ID'] !== 0) {
                return $result;
            } else {
                return array(
                    'ITS_ID' => '---',
                    'Full_Name' => '---',
                );
            }
        } else {
            return array(
                'ITS_ID' => '---',
                'Full_Name' => '---',
            );
        }
    }

    public function getHOFData($hof_id)
    {
        if ($hof_id !== null && $hof_id !== 0) {
            $this->db->where('ITS_ID', $hof_id);
            $query = $this->db->get('user');
            $result = $query->row_array();

            // Check if data is found and ITS_ID is not 0 or null
            if ($result && $result['ITS_ID'] !== null && $result['ITS_ID'] !== 0) {
                return $result;
            } else {
                return array(
                    'ITS_ID' => '--',
                    'Full_Name' => '--',
                );
            }
        } else {
            return array(
                'ITS_ID' => '--',
                'Full_Name' => '--',
            );
        }
    }

    public function getFamilyMembers($hof_id)
    {
        $this->db->where('HOF_ID', $hof_id);
        $query = $this->db->get('user');
        return $query->result_array();
    }

    public function getInchargeDetails($user_id)
    {
        $this->db->where('ITS_ID', $user_id);
        $query = $this->db->get('user');
        return $query->row_array();
    }

    public function get_dates()
    {
        $start_date = new DateTime('2024-03-10');
        $end_date = new DateTime('2024-04-08');
        $interval = new DateInterval('P1D');
        $date_range = new DatePeriod($start_date, $interval, $end_date);

        $dates = array();
        foreach ($date_range as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }

    public function get_available_time_slots($date)
    {
        $this->db->select('time');
        $this->db->select('slot_id');
        $this->db->select('date');
        $this->db->from('slots');
        $this->db->where('date', $date);
        $this->db->where('booked', 0);
        $this->db->group_by('date, time');
        $this->db->having('COUNT(*) = 1 OR MAX(active) = 1');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = [];

            foreach ($query->result() as $row) {
                $time_slot = new stdClass();
                $time_slot->time = $row->time;
                $time_slot->slot_id = $row->slot_id;
                $time_slot->date = $row->date;

                $this->db->select('COUNT(*) as count');
                $this->db->from('slots');
                $this->db->where('date', $date);
                $this->db->where('time', $row->time);
                $this->db->where('booked', 0);
                $count_query = $this->db->get();
                $time_slot->count = $count_query->row()->count;

                $result['time_slots'][] = $time_slot;
            }

            $this->db->select('COUNT(DISTINCT date, time) as count', false);
            $this->db->from('slots');
            $this->db->where('date', $date);
            $this->db->where('booked', 0);
            $count_query = $this->db->get();
            $result['total_count'] = $count_query->row()->count;

            return $result;
        } else {
            return "No time slots are available for the specified date.";
        }
    }



    public function get_user_info($user_id)
    {
        // Retrieve ITS_ID and Full_Name from the user table based on user_id
        $this->db->select('ITS_ID, Full_Name, Email');
        $this->db->where('ITS_ID', $user_id); // Assuming 'user_id' is the column in the 'user' table
        $query = $this->db->get('user');

        return $query->row(); // Fetch a single row as an object
    }

    public function book_slot($slot_id, $its_id, $full_name)
    {
        // Store the appointment in the appointments table
        $appointment_data = array(
            'slot_id' => $slot_id,
            'its' => $its_id,
            'name' => $full_name,
        );

        $this->db->insert('appointments', $appointment_data);

        // Update the slots table to mark the slot as booked
        $slot_update_data = array(
            'booked' => 1,
        );

        $this->db->where('slot_id', $slot_id);
        $this->db->update('slots', $slot_update_data);
    }

    public function get_user_appointments($user_name)
    {
        // Assuming you have a 'slots' table and an 'appointments' table
        $this->db->select('appointments.id,appointments.slot_id, appointments.status');
        $this->db->from('appointments');
        $this->db->where('appointments.its', $user_name);
        $query = $this->db->get();

        $user_appointments = $query->result();

        // Fetch date and time for each appointment
        foreach ($user_appointments as &$appointment) {
            $slot_info = $this->get_slot_info($appointment->slot_id);
            $appointment->date = $slot_info->date;
            $appointment->time = $slot_info->time;
        }

        return $user_appointments;
    }

    public function get_slot_info($slot_id)
    {
        // Assuming you have a 'slots' table
        $this->db->select('date, time');
        $this->db->from('slots');
        $this->db->where('slot_id', $slot_id);
        $query = $this->db->get();

        return $query->row();
    }



    public function delete_appointment($appointment_id)
    {
        // Assuming you have an 'appointments' table
        $this->db->select('slot_id');
        $this->db->where('id', $appointment_id);
        $query = $this->db->get('appointments');

        if ($query->num_rows() > 0) {
            $appointment = $query->row();
            $slot_id = $appointment->slot_id;

            // Update the 'slots' table to set booked = 0
            $this->db->set('booked', 0);
            $this->db->where('slot_id', $slot_id);
            $this->db->update('slots');

            // Delete the appointment
            $this->db->where('id', $appointment_id);
            $this->db->delete('appointments');
        }
    }
    
    public function get_chat_by_raza_id($id) {
        // Fetch chat data from the database based on $id
        $this->db->where('raza_id', $id);
        $this->db->order_by('created_at', 'asc');
        $query = $this->db->get('chat');

        return $query->result(); // Return chat data
    }

    public function get_status_by_raza_id($id) {
        $this->db->select('coordinator-status, Janab-status');
        $this->db->where('id', $id);
        $query = $this->db->get('raza');
        
        return $query->row(); // Return status data
    }
    
    public function insert_message($data) {
        // Insert message data into the database
        $this->db->insert('chat', $data);
        return $this->db->insert_id(); // Return the ID of the inserted record
    }

    public function get_chat_count($raza_id) {
        $this->db->where('raza_id', $raza_id);
        $query = $this->db->get('chat'); // Assuming 'chat' is the name of your table
        return $query->num_rows();
    }
    
    public function deleteMessage($message_id)
    {
        $this->db->where('id', $message_id);
        $result = $this->db->delete('chat');

        return $result;
    }
    
    public function RazaTypesDetails()
    {
        $sql = 'SELECT id, name, umoor from `raza_type` where `active`=1';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


}

