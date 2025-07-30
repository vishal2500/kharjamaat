<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MasoolMusaidM extends CI_Model
{
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();
    }

    // Example: Fetch all users for MasoolMusaid
    public function get_all_users()
    {
        $query = $this->db->get('user');
        return $query->result_array();
    }

    // Example: Fetch single user by ITS_ID
    public function get_user_by_id($its_id)
    {
        $this->db->where('ITS_ID', $its_id);
        return $this->db->get('user')->row_array();
    }

    // Example: Update user details
    public function update_user($its_id, $data)
    {
        $this->db->where('ITS_ID', $its_id);
        $this->db->update('user', $data);
        return $this->db->affected_rows() > 0;
    }
    public function get_users_by_sector($sector, $subsector = '')
    {
        $this->db->where('Sector', $sector);
        if (!empty($subsector)) {
            $this->db->where('Sub_Sector', $subsector);
        }
        return $this->db->get('user')->result_array();
    }

    public function search_users_by_sector($keyword, $sector, $subsector = '')
    {
        $this->db->group_start()
            ->like('Full_Name', $keyword)
            ->or_like('ITS_ID', $keyword)
            ->or_like('Mobile', $keyword)
            ->or_like('Address', $keyword)
            ->group_end();

        $this->db->where('Sector', $sector);
        if (!empty($subsector)) {
            $this->db->where('Sub_Sector', $subsector);
        }

        return $this->db->get('user')->result_array();
    }


    public function get_ashara_by_sector($sector, $subsector)
    {
        $this->db->select(
            'ao.ITS, ao.LeaveStatus, ao.Comment, 
         u.Full_Name, u.HOF_ID, u.HOF_FM_TYPE, 
         u.Age, u.Gender, u.Mobile, u.Sector, u.Sub_Sector'
        );
        $this->db->from('ashara_ohbat ao');
        $this->db->join('user u', 'u.ITS_ID = ao.ITS', 'left');
        $this->db->where('u.Sector', $sector);
        if (!empty($subsector)) {
            $this->db->where('u.Sub_Sector', $subsector);
        }
        return $this->db->get()->result_array();
    }

    public function search_ashara_by_sector($keyword, $sector, $subsector)
    {
        $this->db->select(
            'ao.ITS, ao.LeaveStatus, ao.Comment, 
         u.Full_Name, u.HOF_ID, u.HOF_FM_TYPE, 
         u.Age, u.Gender, u.Mobile, u.Sector, u.Sub_Sector'
        );
        $this->db->from('ashara_ohbat ao');
        $this->db->join('user u', 'u.ITS_ID = ao.ITS', 'left');

        $this->db->group_start();
        $this->db->like('ao.ITS', $keyword);
        $this->db->or_like('u.Full_Name', $keyword);
        $this->db->or_like('u.Mobile', $keyword);
        $this->db->or_like('ao.LeaveStatus', $keyword);
        $this->db->or_like('ao.Comment', $keyword);
        $this->db->group_end();

        $this->db->where('u.Sector', $sector);
        if (!empty($subsector)) {
            $this->db->where('u.Sub_Sector', $subsector);
        }
        return $this->db->get()->result_array();
    }


    public function get_sectors_stats($sector = null, $subsector = null)
    {
        $this->db->select([
            'u.Sector',
            'COUNT(*) as total',
            'SUM(CASE WHEN u.HOF_FM_TYPE = "HOF" THEN 1 ELSE 0 END) as hof_count',
            'SUM(CASE WHEN u.HOF_FM_TYPE = "FM" THEN 1 ELSE 0 END) as fm_count',
            'SUM(CASE WHEN LOWER(u.Gender) = "male" THEN 1 ELSE 0 END) as male_count',
            'SUM(CASE WHEN LOWER(u.Gender) = "female" THEN 1 ELSE 0 END) as female_count',
            'SUM(CASE WHEN u.Age BETWEEN 0 AND 4 THEN 1 ELSE 0 END) as age_0_4',
            'SUM(CASE WHEN u.Age BETWEEN 5 AND 15 THEN 1 ELSE 0 END) as age_5_15',
            'SUM(CASE WHEN u.Age > 65 THEN 1 ELSE 0 END) as seniors_count',
            'SUM(CASE WHEN ao.LeaveStatus IS NULL OR ao.LeaveStatus = "" THEN 1 ELSE 0 END) as no_status_count'
        ]);

        $this->db->from('user u');
        $this->db->join('ashara_ohbat ao', 'u.ITS_ID = ao.ITS', 'left');

        if ($sector) {
            $this->db->where('u.Sector', $sector);
        }

        $this->db->group_by('u.Sector');
        $this->db->order_by('u.Sector');

        return $this->db->get()->result_array();
    }

    public function get_sub_sectors_stats($sector = null, $subsector = null)
    {
        $this->db->select([
            'u.Sector',
            'u.Sub_Sector as SubSector',
            'COUNT(*) as total',
            'SUM(CASE WHEN u.HOF_FM_TYPE = "HOF" THEN 1 ELSE 0 END) as hof_count',
            'SUM(CASE WHEN u.HOF_FM_TYPE = "FM" THEN 1 ELSE 0 END) as fm_count',
            'SUM(CASE WHEN LOWER(u.Gender) = "male" THEN 1 ELSE 0 END) as male_count',
            'SUM(CASE WHEN LOWER(u.Gender) = "female" THEN 1 ELSE 0 END) as female_count',
            'SUM(CASE WHEN u.Age BETWEEN 0 AND 4 THEN 1 ELSE 0 END) as age_0_4',
            'SUM(CASE WHEN u.Age BETWEEN 5 AND 15 THEN 1 ELSE 0 END) as age_5_15',
            'SUM(CASE WHEN u.Age > 65 THEN 1 ELSE 0 END) as seniors_count',
            'SUM(CASE WHEN ao.LeaveStatus IS NULL OR ao.LeaveStatus = "" THEN 1 ELSE 0 END) as no_status_count'
        ]);

        $this->db->from('user u');
        $this->db->join('ashara_ohbat ao', 'u.ITS_ID = ao.ITS', 'left');

        if ($sector) {
            $this->db->where('u.Sector', $sector);
        }

        if ($subsector) {
            $this->db->where('u.Sub_Sector', $subsector);
        }

        $this->db->group_by('u.Sector, u.Sub_Sector');
        $this->db->order_by('u.Sector, u.Sub_Sector');

        return $this->db->get()->result_array();
    }




    public function upsert_ashara_row($ITS, $data)
    {
        $this->db->where('ITS', $ITS);
        $query = $this->db->get('ashara_ohbat');

        if ($query->num_rows() > 0) {
            $this->db->where('ITS', $ITS);
            return $this->db->update('ashara_ohbat', $data);
        } else {
            $data['ITS'] = $ITS; // Ensure ITS is included for insert
            return $this->db->insert('ashara_ohbat', $data);
        }
    }
    
    public function update_attendance_leave_status($ITS, $leaveStatus)
{
    $data = [
        'Day2' => $leaveStatus,
        'Day3' => $leaveStatus,
        'Day4' => $leaveStatus,
        'Day5' => $leaveStatus,
        'Day6' => $leaveStatus,
        'Day7' => $leaveStatus,
        'Day8' => $leaveStatus,
        'Day9' => $leaveStatus,
        'Ashura' => $leaveStatus
    ];

    $this->db->where('ITS', $ITS);
    return $this->db->update('ashara_attendance', $data);
}

    
    
    public function get_attendance_by_sub_sector($sector, $sub_sector)
    {
        if (empty($sector))
            return $this->get_all_attendance();
        return $this->get_ashara_attendance($sector, $sub_sector);
    }

    public function get_attendance_by_sector($sector, $sub_sector = '')
    {
        if (empty($sector))
            return $this->get_all_attendance();
        return $this->get_ashara_attendance($sector, $sub_sector);
    }

    public function get_sub_sector_stats($sector, $sub_sector)
    {
        if (empty($sector))
            return $this->get_all_attendance_stats();
        return $this->get_attendance_stats($sector, $sub_sector);
    }

    public function get_sector_stats($sector, $sub_sector = '')
    {
        if (empty($sector))
            return $this->get_all_attendance_stats();
        return $this->get_attendance_stats($sector, $sub_sector);
    }

    private function get_ashara_attendance($sector, $sub_sector = '')
    {
        $this->db->select('u.ITS_ID, u.HOF_ID, u.Full_Name, u.Mobile, u.Sector, u.Sub_Sector, 
        a.Day2, a.Comment2, a.Day3, a.Comment3, a.Day4, a.Comment4, 
        a.Day5, a.Comment5, a.Day6, a.Comment6, a.Day7, a.Comment7, 
        a.Day8, a.Comment8, a.Day9, a.Comment9, a.Ashura, a.CommentAshura');
        $this->db->from('user u');
        $this->db->join('ashara_attendance a', 'u.ITS_ID = a.ITS', 'left');
        $this->db->where('u.Sector', $sector);
        if (!empty($sub_sector)) {
            $this->db->where('u.Sub_Sector', $sub_sector);
        }
        $this->db->order_by('u.Sub_Sector, u.Full_Name');
        return $this->db->get()->result_array();
    }

    public function get_all_attendance()
    {
        $this->db->select('u.ITS_ID, u.HOF_ID, u.Full_Name, u.Mobile, u.Sector, u.Sub_Sector, 
        a.Day2, a.Comment2, a.Day3, a.Comment3, a.Day4, a.Comment4, 
        a.Day5, a.Comment5, a.Day6, a.Comment6, a.Day7, a.Comment7, 
        a.Day8, a.Comment8, a.Day9, a.Comment9, a.Ashura, a.CommentAshura');
        $this->db->from('user u');
        $this->db->join('ashara_attendance a', 'u.ITS_ID = a.ITS', 'left');
        $this->db->order_by('u.Sector, u.Sub_Sector, u.Full_Name');
        return $this->db->get()->result_array();
    }

    public function get_all_attendance_stats()
    {
        $days = ['Day2', 'Day3', 'Day4', 'Day5', 'Day6', 'Day7', 'Day8', 'Day9', 'Ashura'];
        $stats = [];

        foreach ($days as $day) {
            $this->db->select("
            COUNT(CASE WHEN a.$day = 'Attended with Maula' THEN 1 END) as with_maula,
            COUNT(CASE WHEN a.$day = 'Attended in Khar on Time' THEN 1 END) as khar_on_time,
            COUNT(CASE WHEN a.$day = 'Attended in Khar Late' THEN 1 END) as khar_late,
            COUNT(CASE WHEN a.$day = 'Attended in Other Jamaat' THEN 1 END) as other_jamaat,
            COUNT(CASE WHEN a.$day = 'Not attended anywhere' THEN 1 END) as not_attended,
            COUNT(CASE WHEN a.$day = 'Not in Town' THEN 1 END) as not_in_town,
            COUNT(CASE WHEN a.$day = 'Married Outcaste' THEN 1 END) as outcaste,
            COUNT(u.ITS_ID) as total_members
        ");
            $this->db->from('user u');
            $this->db->join('ashara_attendance a', 'u.ITS_ID = a.ITS', 'left');
            $stats[$day] = $this->db->get()->row_array();
        }

        return $stats;
    }

    public function search_attendance_by_sector($keyword, $sector = '', $sub_sector = '')
    {
        $this->db->select('u.ITS_ID, u.HOF_ID, u.Full_Name, u.Mobile, u.Sector, u.Sub_Sector, 
        a.Day2, a.Comment2, a.Day3, a.Comment3, a.Day4, a.Comment4, 
        a.Day5, a.Comment5, a.Day6, a.Comment6, a.Day7, a.Comment7, 
        a.Day8, a.Comment8, a.Day9, a.Comment9, a.Ashura, a.CommentAshura');
        $this->db->from('user u');
        $this->db->join('ashara_attendance a', 'u.ITS_ID = a.ITS', 'left');

        if (!empty($sector)) {
            $this->db->where('u.Sector', $sector);
        }
        if (!empty($sub_sector)) {
            $this->db->where('u.Sub_Sector', $sub_sector);
        }

        $this->db->group_start();
        $this->db->like('u.ITS_ID', $keyword);
        $this->db->or_like('u.Full_Name', $keyword);
        $this->db->or_like('u.HOF_ID', $keyword);
        $this->db->group_end();

        $this->db->order_by('u.Sub_Sector, u.Full_Name');
        return $this->db->get()->result_array();
    }

    public function get_all_sectors()
    {
        $this->db->select('DISTINCT(Sector) as sector');
        $this->db->from('user');
        $this->db->order_by('sector');
        return $this->db->get()->result_array();
    }

    public function get_all_sub_sectors($sector)
    {
        $this->db->select('DISTINCT(Sub_Sector) as sub_sector');
        $this->db->from('user');
        $this->db->where('Sector', $sector);
        $this->db->order_by('sub_sector');
        return $this->db->get()->result_array();
    }



    private function get_attendance_stats($sector, $sub_sector = '')
    {
        $days = ['Day2', 'Day3', 'Day4', 'Day5', 'Day6', 'Day7', 'Day8', 'Day9', 'Ashura'];
        $stats = [];

        foreach ($days as $day) {
            $this->db->select("
            COUNT(CASE WHEN a.$day = 'Attended with Maula' THEN 1 END) as with_maula,
            COUNT(CASE WHEN a.$day = 'Attended in Khar on Time' THEN 1 END) as khar_on_time,
            COUNT(CASE WHEN a.$day = 'Attended in Khar Late' THEN 1 END) as khar_late,
            COUNT(CASE WHEN a.$day = 'Attended in Other Jamaat' THEN 1 END) as other_jamaat,
            COUNT(CASE WHEN a.$day = 'Not attended anywhere' THEN 1 END) as not_attended,
            COUNT(CASE WHEN a.$day = 'Not in Town' THEN 1 END) as not_in_town,
            COUNT(CASE WHEN a.$day = 'Married Outcaste' THEN 1 END) as outcaste,
            COUNT(u.ITS_ID) as total_members
        ");
            $this->db->from('user u');
            $this->db->join('ashara_attendance a', 'u.ITS_ID = a.ITS', 'left');
            $this->db->where('u.Sector', $sector);

            if (!empty($sub_sector)) {
                $this->db->where('u.Sub_Sector', $sub_sector);
            }

            $stats[$day] = $this->db->get()->row_array();
        }

        return $stats;
    }





}
