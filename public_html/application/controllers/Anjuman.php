<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Anjuman extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('AdminM');
        $this->load->model('AccountM');
        $this->load->model('AmilsahebM');
        $this->load->model('MasoolMusaidM');
        $this->load->library('email', $this->config->item('email'));
    }
    public function index()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $this->load->view('Anjuman/Header', $data);
        $this->load->view('Anjuman/Home');
    }
    public function RazaRequest()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
            redirect('/accounts');
        }
        $data['raza'] = $this->AdminM->get_raza();
        $data['razatype'] = $this->AdminM->get_razatype();
        foreach ($data['raza'] as $key => $value) {
            $username = $this->AccountM->get_user($value['user_id']);
            $razatype = $this->AdminM->get_razatype_byid($value['razaType'])[0];
            $data['raza'][$key]['razaType'] = $razatype['name'];
            $data['raza'][$key]['razafields'] = $razatype['fields'];
            $data['raza'][$key]['user_name'] = $username[0]['Full_Name'];
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $this->load->view('Anjuman/Header', $data);
        $this->load->view('Anjuman/Raza/RazaRequest', $data);
    }
    public function EventRazaRequest()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
            redirect('/accounts');
        }
        $this->load->model('AmilsahebM');
        $this->load->model('AdminM');
        $this->load->model('AccountM');
        $data['umoor'] = "Event Raza Applications";
        $data['raza'] = $this->AmilsahebM->get_raza_event();
        $data['razatype'] = $this->AdminM->get_eventrazatype();



        // Fetch total chat count for each raza_id
        foreach ($data['raza'] as $key => $value) {
            $chatCount = $this->AccountM->get_chat_count($value['id']); // Assuming id is the raza_id
            $data['raza'][$key]['chat_count'] = $chatCount;
        }

        // Fetch user details and raza type
        foreach ($data['raza'] as $key => $value) {
            $username = $this->AccountM->get_user($value['user_id']);
            $razatype = $this->AdminM->get_razatype_byid($value['razaType'])[0];
            $data['raza'][$key]['razaType'] = $razatype['name'];
            $data['raza'][$key]['razafields'] = $razatype['fields'];
                        $data['raza'][$key]['umoor'] = $razatype['umoor'];

            $data['raza'][$key]['user_name'] = $username[0]['Full_Name'];
        }

        // Set user name
        $data['user_name'] = $_SESSION['user']['username'];

        // Load the view
        $this->load->view('Anjuman/Header', $data);
        $this->load->view('Anjuman/RazaRequest', $data);
    }

    public function UmoorRazaRequest()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
            redirect('/accounts');
        }
        $this->load->model('AmilsahebM');
        $this->load->model('AdminM');
        $this->load->model('AccountM');
        $data['umoor'] = "12 Umoor Raza Applications";
        $data['raza'] = $this->AmilsahebM->get_raza_umoor();
        $data['razatype'] = $this->AdminM->get_umoorrazatype();
        // $data['umoortype'] = $this->AdminM->get_umoortype();

        foreach ($data['raza'] as $key => $value) {
            $chatCount = $this->AccountM->get_chat_count($value['id']); // Assuming id is the raza_id
            $data['raza'][$key]['chat_count'] = $chatCount;
        }

        foreach ($data['raza'] as $key => $value) {
            $username = $this->AccountM->get_user($value['user_id']);
            $razatype = $this->AdminM->get_razatype_byid($value['razaType'])[0];
            $data['raza'][$key]['razaType'] = $razatype['name'];
            $data['raza'][$key]['razafields'] = $razatype['fields'];
                        $data['raza'][$key]['umoor'] = $razatype['umoor'];

            $data['raza'][$key]['user_name'] = $username[0]['Full_Name'];
        }
        $data['user_name'] = $_SESSION['user']['username'];
        // echo '<pre>';
        // echo print_r($data);
        // die();
        $this->load->view('Anjuman/Header', $data);
        $this->load->view('Anjuman/RazaRequest', $data);
    }
    public function miqaat()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $this->load->view('Anjuman/Header', $data);
        $this->load->view('Anjuman/Miqaat/Home', $data);
    }
    public function DeleteRaza($id)
    {
        // Retrieve the value of $umoor from the URL parameters
        $umoor = $this->input->get('umoor');

        $flag = $this->AccountM->delete_raza($id);

        if ($flag) {
            // Check the value of $umoor and redirect accordingly
            if ($umoor == 'Event Raza Applications') {
                redirect('/anjuman/success/EventRazaRequest');
            } else {
                redirect('/anjuman/success/UmoorRazaRequest');
            }
        } else {
            // Check the value of $umoor and redirect to the appropriate error URL
            if ($umoor == 'Event Raza Applications') {
                redirect('/anjuman/error/EventRazaRequest');
            } else {
                redirect('/anjuman/error/UmoorRazaRequest');
            }
        }
    }
    public function approveRaza()
    {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = str_replace(["\r", "\n", "\r\n"], ' ', $value);
        }
        $remark = $_POST['remark'];
        $raza_id = $_POST['raza_id'];
        $user = $this->AdminM->get_user_by_raza_id($raza_id);
        $flag = $this->AdminM->approve_raza($raza_id, $remark);
        $this->email->from('raza@kharjamaat.in', 'Admin');
        $this->email->to($user['Email']);
        $this->email->subject('Raza Status');
        $this->email->message('Congratulation. Your Raza has been recommended by jamaat coordinator');

        $this->email->send();
        if ($flag) {
            http_response_code(200);
            echo json_encode(['status' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => false, 'error' => 'Failed to submit']);
        }
    }
    public function rejectRaza()
    {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = str_replace(["\r", "\n", "\r\n"], ' ', $value);
        }
        $remark = $_POST['remark'];
        $raza_id = $_POST['raza_id'];
        $flag = $this->AdminM->reject_raza($raza_id, $remark);

        $user = $this->AdminM->get_user_by_raza_id($raza_id);

        $this->email->from('raza@kharjamaat.in', 'Admin');
        $this->email->to($user['Email']);
        $this->email->subject('Raza Status');
        $this->email->message('Sorry. Your Raza has not recommended by jamaat coordinator. wait for janab response');
        $this->email->send();
        if ($flag) {
            http_response_code(200);
            echo json_encode(['status' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => false, 'error' => 'Failed to submit']);
        }
    }
    public function razalist()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $data['raza_type'] = $this->AdminM->get_razatype();
        $this->load->view('Anjuman/Header', $data);
        $this->load->view('Anjuman/ManageRaza', $data);
    }
    public function manage_edit_raza($id)
    {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = str_replace(["\r", "\n", "\r\n"], ' ', $value);
        }
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $data['raza'] = $this->AdminM->get_razatype_byid($id)[0];
        // echo '<pre>';
        // echo print_r($data['raza']);
        // die();
        $data['raza']['fields'] = json_decode($data['raza']['fields'], true);
        $this->load->view('Anjuman/Header', $data);
        $this->load->view('Anjuman/Raza/EditRaza', $data);
    }
    public function modifyrazaoption()
    {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = str_replace(["\r", "\n", "\r\n"], ' ', $value);
        }
        $postData = $this->input->post();
        $raza = $this->AdminM->get_razatype_byid($postData['raza-id'])[0];
        $fieldIndexToUpdate = $postData['option-id'];
        $raza['fields'] = json_decode($raza['fields'], true);

        unset($_POST['raza-id']);
        unset($_POST['option-id']);

        if (isset($raza['fields']['fields'][$fieldIndexToUpdate])) {
            $options = [];
            $i = 0;
            foreach ($_POST as $key => $value) {
                $options[] = ['id' => $i, "name" => $value];
                $i++;
            }
            $raza['fields']['fields'][$fieldIndexToUpdate]['options'] = $options;
        }

        // echo '<pre>';
        // echo $postData['option-id'];
        // echo $fieldIndexToUpdate;
        // echo print_r($raza['fields']);
        // die();

        $flag = $this->AdminM->update_raza_type($postData['raza-id'], json_encode($raza['fields']));

        if ($flag) {
            http_response_code(200);
            echo json_encode(['status' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => false, 'error' => 'Failed to submit']);
        }
    }

    function addField($id)
    {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = str_replace(["\r", "\n", "\r\n"], ' ', $value);
        }
        $raza = $this->AdminM->get_razatype_byid($id)[0];
        $type = array("0" => "date", "1" => "text", "2" => "number", "3" => "textarea", "4" => "select");

        $raza['fields'] = json_decode($raza['fields'], true);
        if ($_POST['fieldtype'] != "4") {
            $newField = array(
                "name" => $_POST['fieldname'],
                "type" => $type[$_POST['fieldtype']],
                "required" => $_POST['fieldrequired'] == "1",
            );
            $raza['fields']['fields'][] = $newField;
        } else {
            $newField = array(
                "name" => $_POST['fieldname'],
                "type" => $type[$_POST['fieldtype']],
                "required" => $_POST['fieldrequired'] == "1",
                "options" => array(array("id" => "0", "name" => "select"))
            );

            $raza['fields']['fields'][] = $newField;
        }

        // echo '<pre>';
        // echo print_r($raza);
        // die();
        $flag = $this->AdminM->update_raza_type($id, json_encode($raza['fields']));

        if ($flag) {
            http_response_code(200);
            echo json_encode(['status' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => false, 'error' => 'Failed to submit']);
        }

    }

    function removeField($id)
    {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = str_replace(["\r", "\n", "\r\n"], ' ', $value);
        }
        $fieldname = $_POST['fieldname'];
        $raza = $this->AdminM->get_razatype_byid($id)[0];
        $raza['fields'] = json_decode($raza['fields'], true);
        foreach ($raza['fields']['fields'] as $key => $field) {
            if ($field['name'] == $fieldname) {
                unset($raza['fields']['fields'][$key]);
                break;
            }
        }
        $flag = $this->AdminM->update_raza_type($id, json_encode($raza['fields']));

        if ($flag) {
            http_response_code(200);
            echo json_encode(['status' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => false, 'error' => 'Failed to submit']);
        }
    }
    function addRaza()
    {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = str_replace(["\r", "\n", "\r\n"], ' ', $value);
        }
        $raza_name = $_POST['raza-name'];
        // echo $raza_name;
        // die();
        $flag = $this->AdminM->add_new_razatype($raza_name);

        if ($flag) {
            http_response_code(200);
            echo json_encode(['status' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => false, 'error' => 'Failed to submit']);
        }
    }
    function manage_delete_raza($id)
    {
        $check = $this->AdminM->delet_raza_type($id);
        if ($check) {
            redirect('/anjuman/success/razalist');
        } else {
            redirect('/anjuman/error/razalist');
        }
    }
    public function success($redirectto)
    {
        $data['user_name'] = $_SESSION['user']['username'];
        $data['redirect'] = $redirectto;
        $this->load->view('Anjuman/Header', $data);
        $this->load->view('Anjuman/Success.php', $data);
    }
    public function error($redirectto)
    {
        $data['user_name'] = $_SESSION['user']['username'];
        $data['redirect'] = $redirectto;
        $this->load->view('Anjuman/Header', $data);
        $this->load->view('Anjuman/Error.php', $data);
    }
    public function managemiqaat()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $data['rsvp_list'] = $this->AccountM->get_all_rsvp();
        $this->load->view('Anjuman/Header', $data);
        $this->load->view('Anjuman/Miqaat/CreateMiqaat', $data);
    }
    public function addmiqaat()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $this->load->view('Anjuman/Header', $data);
        $this->load->view('Anjuman/Miqaat/AddMiqaat');
    }
    public function submitmiqaat()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
            redirect('/accounts');
        }
        $miqaatname = $this->input->post('miqaatname');
        $miqaatdesc = $this->input->post('miqaatdesc');
        $miqaatdate = $this->input->post('miqaatdate');
        $miqaattime = $this->input->post('miqaattime');
        $miqaathijridate = $this->input->post('miqaathijridate');
        $miqaatexpired = $this->input->post('miqaatexpired');
        $data = array(
            'name' => $miqaatname,
            'description' => $miqaatdesc,
            'date' => $miqaatdate,
            'time' => $miqaattime,
            'hijri_date' => $miqaathijridate,
            'expired' => $miqaatexpired,
        );
        $check = $this->AdminM->insert_miqaat($data);
        if ($check) {
            redirect('/anjuman/success/managemiqaat');
        } else {
            redirect('/anjuman/error/managemiqaat');
        }

    }
    public function modifymiqaat($id)
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $data['rsvp'] = $this->AdminM->get_rsvp_byid($id)[0];
        $this->load->view('Anjuman/Header', $data);
        $this->load->view('Anjuman/Miqaat/ModifyMiqaat', $data);
    }
    public function submitmodifymiqaat($id)
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
            redirect('/accounts');
        }
        $miqaatname = $this->input->post('miqaatname');
        $miqaatdesc = $this->input->post('miqaatdesc');
        $miqaatdate = $this->input->post('miqaatdate');
        $miqaattime = $this->input->post('miqaattime');
        $miqaathijridate = $this->input->post('miqaathijridate');
        $miqaatexpired = $this->input->post('miqaatexpired');
        $data = array(
            'name' => $miqaatname,
            'description' => $miqaatdesc,
            'date' => $miqaatdate,
            'time' => $miqaattime,
            'hijri_date' => $miqaathijridate,
            'expired' => $miqaatexpired,
        );
        $check = $this->AdminM->modify_miqaat($data, $id);
        if ($check) {
            redirect('/anjuman/success/managemiqaat');
        } else {
            redirect('/anjuman/error/managemiqaat');
        }

    }
    function deletemiqaat($id)
    {
        $check = $this->AdminM->delete_miqaat($id);
        if ($check) {
            redirect('/anjuman/success/managemiqaat');
        } else {
            redirect('/anjuman/error/managemiqaat');
        }
    }
    public function miqaatattendance()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $data['rsvp_list'] = $this->AdminM->get_all_rsvp();
        foreach ($data['rsvp_list'] as $key => $rv) {
            $data['rsvp_list'][$key]['total_marked_attendance'] = $this->AdminM->get_rsvp_attendance($rv['id']);
            $data['rsvp_list'][$key]['total_present_attendance'] = $this->AdminM->get_rsvp_attendance_present($rv['id']);
            $data['rsvp_list'][$key]['total_absent_attendance'] = $data['rsvp_list'][$key]['total_marked_attendance'] - $this->AdminM->get_rsvp_attendance_present($rv['id']);
            $data['rsvp_list'][$key]['total_unmarked_attendance'] = $this->AdminM->get_user_count() - $data['rsvp_list'][$key]['total_marked_attendance'];
            $temp = $this->AdminM->get_rsvp_attendance_present_gender($rv['id']);
            $data['rsvp_list'][$key]['total_present_attendance_gents'] = $temp['male_count'];
            $data['rsvp_list'][$key]['total_present_attendance_ladies'] = $temp['female_count'];
            $guest = $this->AdminM->get_rsvp_attendance_guest($rv['id']);
            $data['rsvp_list'][$key]['total_present_guest_male'] = $guest['male_count'];
            $data['rsvp_list'][$key]['total_present_guest_female'] = $guest['female_count'];
        }
        $this->load->view('Anjuman/Header', $data);
        $this->load->view('Anjuman/Miqaat/MiqaatAttendance', $data);
    }
    
    public function asharaohbat()
    {
        $username = $_SESSION['user']['username'];

        // Fetch users based on search or get all
        if ($this->input->post('search')) {
            $users = $this->AmilsahebM->search_all_ashara($this->input->post('search'));
        } else {
            $users = $this->AmilsahebM->get_all_ashara();
        }

        // Fetch overall sector and sub-sector stats
        $sectorsData = $this->AmilsahebM->get_all_sector_stats();
        $subSectorsData = $this->AmilsahebM->get_all_sub_sector_stats();

        // Initialize stats
        $stats = [
            'HOF' => 0,
            'FM' => 0,
            'Mardo' => 0,
            'Bairo' => 0,
            'Age_0_4' => 0,
            'Age_5_15' => 0,
            'Buzurgo' => 0,
            'LeaveStatus' => [],
            'Sectors' => $sectorsData,
            'SubSectors' => $subSectorsData
        ];

        // Loop through users and populate stats
        foreach ($users as $u) {
            $type = $u['HOF_FM_TYPE'] ?? '';
            $gender = strtolower($u['Gender'] ?? '');
            $age = isset($u['Age']) ? intval($u['Age']) : 0;
            $status = $u['LeaveStatus'] ?? 'Unknown';

            if ($type === 'HOF')
                $stats['HOF']++;
            if ($type === 'FM')
                $stats['FM']++;
            if ($gender === 'male')
                $stats['Mardo']++;
            if ($gender === 'female')
                $stats['Bairo']++;
            if ($age >= 0 && $age <= 4)
                $stats['Age_0_4']++;
            if ($age >= 5 && $age <= 15)
                $stats['Age_5_15']++;
            if ($age > 65)
                $stats['Buzurgo']++;

            if (!isset($stats['LeaveStatus'][$status])) {
                $stats['LeaveStatus'][$status] = 0;
            }
            $stats['LeaveStatus'][$status]++;
        }

        // Pass data to view
        $data = [
            'user_name' => $username,
            'users' => $users,
            'stats' => $stats,
            'current_sector' => '',
            'current_sub_sector' => ''
        ];

        $this->load->view('Anjuman/Header', $data);
        $this->load->view('MasoolMusaid/AsharaOhbat', $data);
    } 
    
    
    public function ashara_attendance()
    {
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
            redirect('/accounts');
        }

        $username = $_SESSION['user']['username'];

        // Use GET parameters if available
        $sel_sector = $this->input->get('sector');
        $sel_sub = $this->input->get('subsector');

        // Fetch all sectors and sub-sectors for dropdowns
        $all_sectors = $this->MasoolMusaidM->get_all_sectors();
        $all_sub_sectors = $sel_sector ? $this->MasoolMusaidM->get_all_sub_sectors($sel_sector) : [];

        // Fetch attendance data
        if ($this->input->post('search')) {
            $kw = $this->input->post('search', true);
            $users = $this->MasoolMusaidM->search_attendance_by_sector($kw, $sel_sector, $sel_sub);
        } else {
            $users = $this->MasoolMusaidM->get_attendance_by_sector($sel_sector, $sel_sub);
        }

        // Stats
        $stats = $this->MasoolMusaidM->get_sector_stats($sel_sector, $sel_sub);

        // View Data
        $data = [
            'username' => $username,
            'user_sector' => '',
            'user_sub' => '',
            'sel_sector' => $sel_sector,
            'sel_sub' => $sel_sub,
            'all_sectors' => $all_sectors,
            'all_sub_sectors' => $all_sub_sectors,
            'users' => $users,
            'stats' => $stats,
            'user_name' => $username,
            'days' => range(2, 9),
            'status_options' => [
                'Attended with Maula',
                'Attended in Khar on Time',
                'Attended in Khar Late',
                'Attended in Other Jamaat',
                'Not attended anywhere',
                'Not in Town',
                'Married Outcaste'
            ],
        ];

        // Load view
        $this->load->view('Anjuman/Header', $data);
        $this->load->view('MasoolMusaid/AsharaAttendance', $data);
    }
}
?>