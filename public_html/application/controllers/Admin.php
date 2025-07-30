<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('AdminM');
        $this->load->model('AccountM');
        $this->load->library('email', $this->config->item('email'));
    }
    public function index()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $this->load->view('Admin/Header', $data);
        $this->load->view('Admin/Home');
    }
    public function RazaRequest()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
            redirect('/accounts');
        }
        $data['raza'] = $this->AdminM->get_raza();
        $data['razatype'] = $this->AdminM->get_razatype();
        foreach ($data['raza'] as $key => $value) {
            $username = $this->AccountM->get_user($value['user_id']);
            $razatype = $this->AccountM->get_razatype_byid($value['razaType'])[0];
            $data['raza'][$key]['razaType'] = $razatype['name'];
            $data['raza'][$key]['razafields'] = $razatype['fields'];
            $data['raza'][$key]['user_name'] = $username[0]['Full_Name'];
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $this->load->view('Admin/Header', $data);
        $this->load->view('Admin/RazaRequest', $data);
    }
    public function miqaat()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $this->load->view('Admin/Header', $data);
        $this->load->view('Admin/Miqaat', $data);
    }
    public function approveRaza()
    {
        $remark = trim($_POST['remark']);
        $raza_id = $_POST['raza_id'];
        $user = $this->AdminM->get_user_by_raza_id($raza_id);
        $flag = $this->AdminM->approve_raza($raza_id, $remark);

        $this->email->from('admin@kharjamaat.in', 'Admin');
        $this->email->to($user['Email']);
        $this->email->subject('Raza Status');
        $this->email->message('Mubarak! Your Raza request has received a recommendation from Anjuman e Saifee Jamaat.<br/>Kindly reach out to Janab Amil Saheb via phone or WhatsApp at +91-8452840052 to obtain his final Raza and Dua.<br/><br/>Wassalaam. ');
        $this->email->send();

        $this->email->from('admin@kharjamaat.in', 'Admin');
        $this->email->to('amilsaheb@kharjamaat.in');
        $this->email->subject('Raza Recommended');
        $this->email->message('Mubarak!<br/><br/><br/> Your Raza request has received a recommendation from Anjuman e Saifee Jamaat.<br/>Kindly reach out to Janab Amil Saheb via phone or WhatsApp at +91-8452840052 to obtain his final Raza and Dua.<br/><br/>Wassalaam. ');
        $this->email->send();

        $msg = $user['Full_Name'] . ' (' . $user['ITS_ID'] . ') Raza has been recommended by jamaat coordinator';

        $this->email->from('admin@kharjamaat.in', 'Admin');
        $this->email->to('kharjamaat@gmail.com');
        $this->email->subject('Raza Recommended');
        $this->email->message($msg);
        $this->email->send();

        $this->email->from('admin@kharjamaat.in', 'Admin');
        $this->email->to('3042@carmelnmh.in');
        $this->email->subject('Raza Recommended');
        $this->email->message($msg);
        $this->email->send();

        $this->email->from('admin@kharjamaat.in', 'Admin');
        $this->email->to('anjuman@kharjamaat.in');
        $this->email->subject('Raza Recommended');
        $this->email->message($msg);
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
        $remark = trim($_POST['remark']);
        $raza_id = $_POST['raza_id'];
        $flag = $this->AdminM->reject_raza($raza_id, $remark);

        $user = $this->AdminM->get_user_by_raza_id($raza_id);

        $this->email->from('admin@kharjamaat.in', 'Admin');
        $this->email->to($user['Email']);
        $this->email->subject('Raza Status');
        $this->email->message('Sorry. Your Raza has not recommended by jamaat coordinator. wait for janab response');
        $this->email->send();

        $msg = $user['Full_Name'] . ' (' . $user['ITS_ID'] . ') Raza not recommended by jamaat coordinator';
        $this->email->from('admin@kharjamaat.in', 'Admin');
        $this->email->to('amilsaheb@kharjamaat.in');
        $this->email->subject('Raza Not Recommended');
        $this->email->message($msg);
        $this->email->send();

        $this->email->from('admin@kharjamaat.in', 'Admin');
        $this->email->to('kharjamaat@gmail.com');
        $this->email->subject('Raza Not Recommended');
        $this->email->message($msg);
        $this->email->send();

        $this->email->from('admin@kharjamaat.in', 'Admin');
        $this->email->to('3042@carmelnmh.in');
        $this->email->subject('Raza Not Recommended');
        $this->email->message($msg);
        $this->email->send();

        $this->email->from('admin@kharjamaat.in', 'Admin');
        $this->email->to('anjuman@kharjamaat.in');
        $this->email->subject('Raza Not Recommended');
        $this->email->message($msg);
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
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $data['raza_type'] = $this->AdminM->get_razatype();
        $this->load->view('Admin/Header', $data);
        $this->load->view('Admin/ManageRaza', $data);
    }
    public function manage_edit_raza($id)
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $data['raza'] = $this->AdminM->get_razatype_byid($id)[0];
        // echo '<pre>';
        // echo print_r($data['raza']);
        // die();
        $data['raza']['fields'] = json_decode($data['raza']['fields'], true);
        $this->load->view('Admin/Header', $data);
        $this->load->view('Admin/EditRaza', $data);
    }
    public function modifyrazaoption()
    {
        $postData = $this->input->post();
    
        // Fetch the raza object by ID
        $raza = $this->AdminM->get_razatype_byid($postData['raza-id'])[0];
        $fieldIndexToUpdate = $postData['option-id'];
    
        // Decode JSON
        $raza['fields'] = json_decode($raza['fields'], true);
    
        if (!isset($raza['fields']['fields'][$fieldIndexToUpdate])) {
            http_response_code(400);
            echo json_encode(['status' => false, 'error' => 'Invalid field index']);
            return;
        }
    
        // Build new options
        $options = [];
        if (!empty($_POST['option_values']) && is_array($_POST['option_values'])) {
            foreach ($_POST['option_values'] as $i => $value) {
                $options[] = ['id' => $i, 'name' => $value];
            }
        }
    
        $raza['fields']['fields'][$fieldIndexToUpdate]['options'] = $options;
    
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
        $raza_name = $_POST['raza-name'];
        $umoor_name = $_POST['umoor'];
        // echo $raza_name;
        // die();
        $flag = $this->AdminM->add_new_razatype($raza_name, $umoor_name);

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
            redirect('/admin/success/razalist');
        } else {
            redirect('/admin/error/razalist');
        }
    }
    public function update_raza_details()
    {
        $rowId = $this->input->post('rowId');
        $razaName = $this->input->post('razaName');
        $umoor = $this->input->post('umoor');


        $data = array(
            'name' => $razaName,
            'umoor' => $umoor
        );
        $this->load->model('AdminM');

        $this->AdminM->update_raza($rowId, $data);

        echo json_encode(array('success' => true));
    }
    public function success($redirectto)
    {
        $data['user_name'] = $_SESSION['user']['username'];
        $data['redirect'] = $redirectto;
        $this->load->view('Admin/Header', $data);
        $this->load->view('Admin/Success.php', $data);
    }
    public function error($redirectto)
    {
        $data['user_name'] = $_SESSION['user']['username'];
        $data['redirect'] = $redirectto;
        $this->load->view('Admin/Header', $data);
        $this->load->view('Admin/Error.php', $data);
    }
    public function managemiqaat()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $data['rsvp_list'] = $this->AccountM->get_all_rsvp();
        $this->load->view('Admin/Header', $data);
        $this->load->view('Admin/Miqaat', $data);
    }
    public function addmiqaat()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $this->load->view('Admin/Header', $data);
        $this->load->view('Admin/AddMiqaat');
    }
    public function submitmiqaat()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
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
            redirect('/admin/success/miqaat');
        } else {
            redirect('/admin/error/miqaat');
        }

    }
    public function modifymiqaat($id)
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $data['rsvp'] = $this->AdminM->get_rsvp_byid($id)[0];
        $this->load->view('Admin/Header', $data);
        $this->load->view('Admin/ModifyMiqaat', $data);
    }
    public function submitmodifymiqaat($id)
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
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
            redirect('/admin/success/managemiqaat');
        } else {
            redirect('/admin/error/managemiqaat');
        }

    }
    function deletemiqaat($id)
    {
        $check = $this->AdminM->delete_miqaat($id);
        if ($check) {
            redirect('/admin/success/managemiqaat');
        } else {
            redirect('/admin/error/managemiqaat');
        }
    }
    public function miqaatattendance()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
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
        $this->load->view('Admin/Header', $data);
        $this->load->view('Admin/MiqaatAttendance', $data);
    }
    public function addmumineen()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $this->load->view('Admin/Header', $data);
        $this->load->view('Admin/AddMumineen', $data);
    }
    public function submitaddmumineen()
    {

        $data = array(
            'Full_Name' => $this->input->post('fullName'),
            'ITS_ID' => $this->input->post('itsId'),
            'Mobile' => $this->input->post('contact'),
            'Email' => $this->input->post('email'),
            'HOF_FM_TYPE' => $this->input->post('isHOF') ? 'HOF' : 'FM',
            'HOF_ID' => $this->input->post('isHOF') ? $this->input->post('itsId') : $this->input->post('hofItsId')
        );
        $logindata = array(
            'username' => $this->input->post('itsId'),
            'password' => md5($this->input->post('itsId')),
            'hof' => $this->input->post('isHOF') ? $this->input->post('itsId') : $this->input->post('hofItsId')
        );

        $check = $this->AdminM->addMumineen($data, $logindata);

        if ($check) {
            $response = array(
                'status' => 'success',
                'message' => 'User added successfully!'
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Error adding user.'
            );
        }


        echo json_encode($response);
    }
}
?>