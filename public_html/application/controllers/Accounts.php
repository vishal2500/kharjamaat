<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Accounts extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('AccountM');
        $this->load->library('email', $this->config->item('email'));
        $this->load->helper('file');
    }
    public function index()
    {
        if (!empty($_SESSION['user'])) {
            redirect('/accounts/home');
        }
        if (!empty($_SESSION['login_status'])) {
            $data['status'] = $_SESSION['login_status'];
            $this->load->view('Home/Header');
            $this->load->view('Accounts/Login', $data);
        } else {
            $this->load->view('Home/Header');
            $this->load->view('Accounts/Login');
        }
    }
    public function login()
    {
        if (!empty($_SESSION['user'])) {
            redirect('/accounts');
        }
        $username = $_POST['username'];
        $password = $_POST['password'];
        $check = $this->AccountM->check_user($username, md5($password));

        $_SESSION['login_status'] = null;

        if (!empty($check)) {
            $user = $this->AccountM->get_user($username);
            $_SESSION['user'] = $check[0];
            if (!empty($user)) {
                $_SESSION['user_data'] = $user[0];
            }
            // echo print_r($check);
            // die();
            if ($check[0]['role'] == 1) {
                redirect('/admin');
            } elseif ($check[0]['role'] == 2) {
                redirect('/amilsaheb');
            } elseif ($check[0]['role'] == 3) {
                redirect('/anjuman');
            } elseif ($check[0]['role'] == 16) {
                redirect('/MasoolMusaid');
            } elseif ($check[0]['role'] >= 4 && $check[0]['role'] <= 15) {
                redirect('/Umoor');
            } else {
                redirect('/accounts/home');
            }

        } else {
            $_SESSION['login_status'] = true;
            redirect(base_url('/accounts/logout'));
        }
    }
    public function register()
    {
        $this->load->view('Home/Header');
        $this->load->view('Accounts/Register');
    }

    public function logout()
    {
        $_SESSION['user'] = null;
        redirect('/accounts');
    }
    public function home()
    {
        if (empty($_SESSION['user'])) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        //only for wajebaat appointment
        $user_id = $_SESSION['user']['username'];
        $data['user_data'] = $this->AccountM->getUserData($user_id);
        $data['hof_data'] = $data['user_data']['HOF_ID'];
        $this->load->view('Accounts/Header', $data);
        $this->load->view('Accounts/Home');
    }
    public function MyRazaRequest()
    {
        if (empty($_SESSION['user'])) {
            redirect('/accounts');
        }

        $user_id = $_SESSION['user_data']['ITS_ID'];
        $data['raza'] = $this->AccountM->get_raza($user_id);

        foreach ($data['raza'] as $key => $value) {
            $username = $this->AccountM->get_user($value['user_id']);
            $razatype = $this->AccountM->get_razatype_byid($value['razaType'])[0];
            $data['raza'][$key]['razaType'] = $razatype['name'];
            $data['raza'][$key]['user_name'] = $username[0]['Full_Name'];

            // Fetch chat count
            $chatCount = $this->AccountM->get_chat_count($value['id']); // Assuming id is the raza_id
            $data['raza'][$key]['chat_count'] = $chatCount;
        }

        $data['user_name'] = $_SESSION['user']['username'];
        $this->load->view('Accounts/Header', $data);
        $this->load->view('Accounts/MyRaza/Home', $data);
    }
    public function Umoor()
    {
        if (empty($_SESSION['user'])) {
            redirect('/accounts');
        }

        // Load the model
        $this->load->model('AccountM');

        // Get the data from model
        $data['raza_types'] = $this->AccountM->RazaTypesDetails();
        $data['user_name'] = $_SESSION['user']['username'];

        $this->load->view('Accounts/Header', $data);
        $this->load->view('Accounts/12_Umoor/Home', $data);
    }
    public function NewRaza()
    {
        if (empty($_SESSION['user'])) {
            redirect('/accounts');
        }
        $data['razatype'] = $this->AccountM->get_razatype();
        $data['user_name'] = $_SESSION['user']['username'];
        $this->load->view('Accounts/Header', $data);
        $this->load->view('Accounts/MyRaza/NewRaza3', $data);
    }
    public function updateraza($id)
    {
        $this->email->from('admin@kharjamaat.in', 'Raza Update');
        $this->email->to($_SESSION['user_data']['Email']);
        $this->email->subject('Raza Status');
        $this->email->message('Your Raza has been updated');
        $this->email->send();

        unset($_POST['raza-type']);
        $data = json_encode($_POST);
        $flag = $this->AccountM->update_raza($id, $data);
        if ($flag) {
            redirect('/accounts/success/MyRazaRequest');
        } else {
            redirect('/accounts/error/MyRazaRequest');
        }
    }
    public function updateVasanReq($id)
    {
        $data['vasan'] = $this->AccountM->get_vasanreq_byid($id)[0];
        $data['vasan_type'] = $this->AccountM->get_vasantype();
        $otherInfo = json_decode($data['vasan']['utensils'], true);

        unset($data['vasan']['utensils']);

        foreach ($otherInfo as $key => $value) {
            $data['vasan']['utensils'][$key] = $value;
        }
        // echo '<pre>';
        // echo print_r($data);
        // die();

        $data['user_name'] = $_SESSION['user']['username'];
        $this->load->view('Accounts/Header', $data);
        $this->load->view('Accounts/Miqaat/updateVasan', $data);
    }
    public function editVasanReq($id)
    {
        $reason = $_POST['reason'];
        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];
        $utensil_name = $_POST['form-utensil'];
        $utensil_quantity = $_POST['form-quantity'];

        $utensils = [];
        foreach ($utensil_name as $key => $value) {
            $utensils[$key] = [
                'name' => $value,
                'quantity' => $utensil_quantity[$key],
            ];
        }
        $utensil = json_encode($utensils);
        // echo $id;
        // die();
        $flag = $this->AccountM->update_vasan_req($id, $reason, $from_date, $to_date, $utensil);
        if ($flag) {
            redirect('/accounts/success/miqaat');
        } else {
            redirect('/accounts/error/miqaat');
        }
    }
    public function deleteVasanReq($id)
    {
        $flag = $this->AccountM->delete_vasan_req($id);
        if ($flag) {
            redirect('/accounts/success/miqaat');
        } else {
            redirect('/accounts/error/miqaat');
        }
    }
    public function DeleteRaza($id)
    {
        $flag = $this->AccountM->delete_raza($id);
        if ($flag) {
            redirect('/accounts/success/MyRazaRequest');
        } else {
            redirect('/accounts/error/MyRazaRequest');
        }
    }
    public function miqaat()
    {
        if (empty($_SESSION['user'])) {
            redirect('/accounts');
        }
        $userId = $_SESSION['user_data']['ITS_ID'];
        $data['rsvp_list'] = $this->AccountM->get_rsvp();
        $data['vasanreq_list'] = $this->AccountM->get_vasan_request($userId);
        $userHof = $_SESSION['user_data']['HOF_ID'];
        foreach ($data['rsvp_list'] as $key => $r) {
            $family = $this->AccountM->get_all_family_member($userHof);
            $attend = true;
            foreach ($family as $f) {
                $isattended = $this->AccountM->get_rsvp_attendance($r['id'], $f['id']);
                if (!$isattended) {
                    $attend = false;
                    break;
                }
            }
            $data['rsvp_list'][$key]['attend'] = $attend;
        }

        // echo '<pre>';
        // echo print_r($data['rsvp_list']);
        // die();

        foreach ($data['vasanreq_list'] as $key => $vr) {
            $str = "";
            $value = json_decode($vr['utensils'], true);
            foreach ($value as $v) {
                $nameofvasan = $this->AccountM->get_vasan_byid($v['name'])[0];
                $str .= $nameofvasan['name'] . '-' . $v['quantity'] . '<br/>';
            }
            $data['vasanreq_list'][$key]['utensils'] = $str;
        }

        $data['user_name'] = $_SESSION['user']['username'];
        $this->load->view('Accounts/Header', $data);
        $this->load->view('Accounts/Miqaat/Home', $data);
    }
    public function rsvp($id)
    {
        if (empty($_SESSION['user'])) {
            $_SESSION['redirect_to_url'] = 'accounts/rsvp/' . $id;
            redirect('/accounts/loggin');
        }
        $data['rsvp'] = $this->AccountM->get_rsvp_byid($id)[0];
        $userId = $_SESSION['user_data']['HOF_ID'];
        $family = $this->AccountM->get_all_family_member($userId);
        foreach ($family as $key => $f) {
            $family[$key]['attend'] = $this->AccountM->get_rsvp_attendance_present($id, $f['id']);
        }
        $data['family'] = $family;
        $data['guest'] = $this->AccountM->get_guset_rsvp($userId, $id);

        // echo '<pre>';
        // echo print_r($family);
        // die();
        $data['user_name'] = $_SESSION['user']['username'];
        $this->load->view('Accounts/Header', $data);
        $this->load->view('Accounts/Miqaat/Rsvp', $data);
    }
    public function submit_rsvp($id)
    {
        try {
            $hofid = $_SESSION['user_data']['HOF_ID'];
            $family = $this->AccountM->get_all_family_member($hofid);
            $guset_male = $_POST['guest_male'];
            $guset_female = $_POST['guest_female'];
            $this->AccountM->insert_rsvp_attendance_guest($hofid, $guset_male, $guset_female, $id);

            foreach ($family as $f) {
                if (!empty($_POST[$f['id']])) {
                    //mark present
                    $alreadypresent = $this->AccountM->get_rsvp_attendance($id, $f['id']);
                    if (!$alreadypresent) {
                        $this->AccountM->insert_rsvp_attendance($id, $f['id'], 1);
                    } else {
                        $this->AccountM->update_rsvp_attendance($id, $f['id'], 1);
                    }
                } else {
                    //mark absent
                    $alreadypresent = $this->AccountM->get_rsvp_attendance($id, $f['id']);
                    if ($alreadypresent) {
                        $this->AccountM->update_rsvp_attendance($id, $f['id'], 0);
                    } else {
                        $this->AccountM->insert_rsvp_attendance($id, $f['id'], 0);
                    }
                }
            }
            redirect('/accounts/success/miqaat');
        } catch (Exception $e) {
            log_message('error', 'Exception in submit_rsvp: ' . $e->getMessage());
            redirect('/accounts/error/miqaat');
        }
    }
    public function newVasanReq()
    {
        if (empty($_SESSION['user'])) {
            redirect('/accounts');
        }
        $data['vasan_type'] = $this->AccountM->get_vasantype();
        $data['user_name'] = $_SESSION['user']['username'];
        $this->load->view('Accounts/Header', $data);
        $this->load->view('Accounts/Miqaat/NewVasanRequest.php', $data);
    }
    public function submit_vasanrequest()
    {
        if ($_POST) {
            // echo '<pre>';
            // echo print_r($_POST);

            $userId = $_SESSION['user_data']['ITS_ID'];
            $reason = $_POST['reason'];
            $from_date = $_POST['from_date'];
            $to_date = $_POST['to_date'];
            $utensil_name = $_POST['form-utensil'];
            $utensil_quantity = $_POST['form-quantity'];

            $utensils = [];
            foreach ($utensil_name as $key => $value) {
                $utensils[$key] = [
                    'name' => $value,
                    'quantity' => $utensil_quantity[$key],
                ];
            }
            $utensil = json_encode($utensils);
            $flag = $this->AccountM->insert_vasan_req($userId, $reason, $from_date, $to_date, $utensil);
            if ($flag) {
                redirect('/accounts/success/miqaat');
            } else {
                redirect('/accounts/error/miqaat');
            }
        }
    }
    public function success($redirectto)
    {
        $data['user_name'] = $_SESSION['user']['username'];
        $data['redirect'] = $redirectto;
        $this->load->view('Accounts/Header', $data);
        $this->load->view('Accounts/Success.php', $data);
    }
    public function error($redirectto)
    {
        $data['user_name'] = $_SESSION['user']['username'];
        $data['redirect'] = $redirectto;
        $this->load->view('Accounts/Header', $data);
        $this->load->view('Accounts/Error.php', $data);
    }
    public function changepassword()
    {
        if (empty($_SESSION['user'])) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $this->load->view('Accounts/Header', $data);
        $this->load->view('Accounts/ResetPassword');
    }
    public function submitchangepassword()
    {
        if (empty($_SESSION['user'])) {
            redirect('/accounts');
        }
        $id = $_SESSION['user_data']['ITS_ID'];
        $role = $_SESSION['user']['role'];
        $password = trim($_POST['confirm_password']);
        $flag = $this->AccountM->change_password($id, md5($password));
        if ($flag) {
            switch ($role) {
                case 0:
                    redirect('/accounts/success/home');
                    break;
                case 1:
                    redirect('/accounts/success/admin');
                    break;
                case 2:
                    redirect('/accounts/success/amilsaheb');
                    break;

                default:
                    redirect('/accounts/logout');
                    break;
            }
        } else {
            redirect('/accounts/error/logout');
        }
    }
    public function submit_raza()
    {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = str_replace(["\r", "\n", "\r\n"], ' ', $value);
        }
        $razatypeid = $_POST['raza-type'];
        $sabil = isset($_POST['sabil']) ? $_POST['sabil'] : null;
        $fmb = isset($_POST['fmb']) ? $_POST['fmb'] : null;


        unset($_POST['sabil']);
        unset($_POST['fmb']);
        $razatype = $this->AccountM->get_razatype_byid($razatypeid)[0];
        $razafields = json_decode($razatype['fields'], true);



        $table = '';
        foreach ($razafields['fields'] as $value) {
            $result = preg_replace(
                ['/[\s]/', '/[()]/', '/[\/?]/'],
                ['-', '_', '-'],
                strtolower($value['name'])
            );
            $v = "";
            if ($value['type'] == "select" && isset($_POST[$result])) {
                $v = htmlspecialchars($value['options'][$_POST[$result]]['name']);
            } else {
                $v = isset($_POST[$result]) ? htmlspecialchars($_POST[$result]) : '';
            }
            $table .= '<tr>
            <td align="center" style="border: 1px solid black;width: 50%;">
              <p style="color: #000000; margin: 0px; padding: 10px; font-size: 15px; font-weight: bold; font-family: Roboto, arial, sans-serif;">' . htmlspecialchars($value['name']) . '</p>
            </td>
            <td align="center" style="border: 1px solid black;width: 50%;">
              <p style="color: #000000; margin: 0px; padding: 10px; font-size: 15px; font-weight: normal; font-family: Roboto, arial, sans-serif;">' . $v . '</p>
            </td>
          </tr>';
        }

        $user_data = $_SESSION['user_data'];

        $weekDateTime = date('l, j M Y, h:i:s A');

        $file_path = 'assets/email.php';
        $email_template = file_get_contents($file_path);

        $dynamic_data = array(
            'todayDate' => $weekDateTime,
            'name' => $user_data['Full_Name'],
            'its' => $user_data['ITS_ID'],
            'table' => $table,
            'razaname' => $razatype['name']
        );

        foreach ($dynamic_data as $key => $value) {
            $placeholder = '{%' . $key . '%}';
            $email_template = str_replace($placeholder, $value, $email_template);
        }



        $this->email->from('admin@kharjamaat.in', 'New Raza');
        $this->email->to($_SESSION['user_data']['Email']);
        $this->email->subject('New Raza');
        $this->email->message($email_template);
        $this->email->send();

        $this->email->from('admin@kharjamaat.in', 'New Raza');
        $this->email->to('anjuman@kharjamaat.in');
        $this->email->subject('New Raza');
        $this->email->message($email_template);
        $this->email->send();

        $this->email->from('admin@kharjamaat.in', 'New Raza');
        $this->email->to('amilsaheb@kharjamaat.in');
        $this->email->subject('New Raza');
        $this->email->message($email_template);
        $this->email->send();

        $this->email->from('admin@kharjamaat.in', 'New Raza');
        $this->email->to('3042@carmelnmh.in');
        $this->email->subject('New Raza');
        $this->email->message($email_template);
        $this->email->send();

        $this->email->from('admin@kharjamaat.in', 'New Raza');
        $this->email->to('kharjamaat@gmail.com');
        $this->email->subject('New Raza');
        $this->email->message($email_template);
        $this->email->send();

        $userId = $_SESSION['user_data']['ITS_ID'];
        unset($_POST['raza-type']);
        $data = json_encode($_POST);
        echo"<pre>";print_r($_SESSION);exit;
        $check = $this->AccountM->insert_raza($userId, $razatypeid, $data, $sabil, $fmb);
        if ($check) {
            redirect('/accounts/success/myrazarequest');
        } else {
            redirect('/accounts/error/myrazarequest');
        }
    }
    public function edit_raza($id)
    {
        if (empty($_SESSION['user'])) {
            redirect('/accounts');
        }
        $data['razatype'] = $this->AccountM->get_razatype();
        $data['raza'] = $this->AccountM->get_raza_byid($id)[0];

        $data['user_name'] = $_SESSION['user']['username'];

        $this->load->view('Accounts/Header', $data);
        $this->load->view('Accounts/MyRaza/UpdateRaza', $data);
    }
    public function forgetpassword()
    {
        $this->load->view('Home/Header');
        $this->load->view('Accounts/ForgotPassword');
    }
    public function submitForgotPassword()
    {
        if (!empty($_POST['username'])) {
            $check_user = $this->AccountM->check_user_exist($_POST['username'])[0];
            $microtime = microtime(true);
            $microseconds = (int) ($microtime * 1000000);
            $uniqueNumber = $microseconds % 1000;
            $uniqueNumber = sprintf("%03d", $uniqueNumber);
            if (!empty($check_user)) {
                $new_password = $this->AccountM->change_password_to_default($check_user['ITS_ID'], $check_user['ITS_ID'] . $uniqueNumber);
                if ($new_password) {
                    $msg = 'Your Password Has been Reset.<br/> New Password is ' . $check_user['ITS_ID'] . $uniqueNumber . '.<br/> To Maintain Your Privacy Please Change Your Password After Login';
                    $this->email->from('admin@kharjamaat.in', 'Admin');
                    $this->email->to($check_user['Email']);
                    $this->email->subject('Password Reset Request');
                    $this->email->message($msg);
                    $email = $this->email->send();
                    $data['user_email'] = $check_user['Email'];
                    if (!empty($email)) {
                        $this->load->view('Accounts/PasswordResetConfirm', $data);
                    }
                } else {
                    echo 'Error Please Try Again Later';
                }
            } else {
                echo 'User Not Found';
            }
        }
    }
    public function loggin()
    {
        $this->load->view('Home/Header');
        $this->load->view('Accounts/Redirect');
    }
    public function redirect()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $check = $this->AccountM->check_user($username, md5($password));

        $_SESSION['login_status'] = null;

        if (!empty($check)) {
            $user = $this->AccountM->get_user($username);
            $_SESSION['user'] = $check[0];
            if (!empty($user)) {
                $_SESSION['user_data'] = $user[0];
            }
            redirect($_SESSION['redirect_to_url']);

        } else {
            $_SESSION['login_status'] = true;
            redirect(base_url('/accounts/logout'));
        }
    }
    public function profile()
    {
        // Get user ID from session
        $user_id = $_SESSION['user_data']['ITS_ID'];

        // Fetch user data from the database
        $data['user_data'] = $this->AccountM->getUserData($user_id);

        // Fetch Father's data based on Father_ITS_ID
        $father_its_id = $data['user_data']['Father_ITS_ID'];
        $data['father_data'] = $this->AccountM->getFatherData($father_its_id);

        // Fetch Mother's data based on Mother_ITS_ID
        $mother_its_id = $data['user_data']['Mother_ITS_ID'];
        $data['mother_data'] = $this->AccountM->getMotherData($mother_its_id);

        // Fetch HOF_ID
        $hof_id = $data['user_data']['HOF_ID'];
        $data['hof_data'] = $this->AccountM->getHOFData($hof_id);

        // Fetch Family Members based on HOF_ID
        $hof_id = $data['user_data']['HOF_ID'];
        $data['family_members'] = $this->AccountM->getFamilyMembers($hof_id);

        $data['incharge_data'] = $this->AccountM->getInchargeDetails($user_id);

        $data['user_name'] = $_SESSION['user']['username'];
        $this->load->view('Accounts/Header', $data);
        // Load the view with inline CSS
        $this->load->view('Accounts/profile', $data);
    }
    public function appointment()
    {
        $data['user_name'] = $_SESSION['user']['username'];
        // Get the dates
        $data['dates'] = $this->AccountM->get_dates();
        $data['user_appointments'] = $this->AccountM->get_user_appointments($data['user_name']);


        $this->load->view('Accounts/Header', $data);
        $this->load->view('Accounts/Appointment/Home', $data);
    }


    public function time_slots()
    {
        $date = $this->input->get('date');
        $data['user_name'] = $_SESSION['user']['username'];

        $data['time_slots'] = $this->AccountM->get_available_time_slots($date);

        $data['date'] = $date;

        $this->load->view('Accounts/Header', $data);
        $this->load->view('Accounts/Appointment/TimeSlot', $data);
    }

    public function book_slot()
    {
        // Retrieve data from the URL parameters
        $slot_id = $this->input->get('slot_id');
        $time = $this->input->get('time');

        // Retrieve user_id from the session
        $user_id = $_SESSION['user']['username'];

        // Load the model

        // Retrieve ITS_ID and Full_Name from the user table based on user_id
        $user_info = $this->AccountM->get_user_info($user_id);

        if ($user_info) {
            // Store the appointment in the appointments table
            $this->AccountM->book_slot($slot_id, $user_info->ITS_ID, $user_info->Full_Name);

            // Add any additional logic or redirects as needed
            redirect('accounts/appointment');
        } else {
            // Handle the case where user info is not found
            echo 'User information not found.';
        }
    }
    public function delete_appointment($appointment_id)
    {
        // Load the model

        // Delete the appointment
        $this->AccountM->delete_appointment($appointment_id);

        // Redirect back to the appointments page
        redirect('accounts/appointment');
    }

    public function chat($id)
    {
        $data['user_name'] = $_SESSION['user']['username'];
        $data['id'] = $id;

        // Fetch chat data from the model
        $data['chat'] = $this->AccountM->get_chat_by_raza_id($id);

        // Fetch status data from the model
        $data['status'] = $this->AccountM->get_status_by_raza_id($id);

        // Load views
        $this->load->view('Accounts/Header', $data);
        $this->load->view('Accounts/Chat', $data);
    }

    public function send_message()
    {
        // Load the model
        $this->load->model('AccountM');

        // Get data from the POST request
        $raza_id = $this->input->post('raza_id');
        $user = $this->input->post('user');
        $message = $this->input->post('message');

        // Prepare data to be inserted into the database
        $data = array(
            'raza_id' => $raza_id,
            'user' => $user,
            'message' => $message
        );

        // Call the model function to insert the message into the database
        $insert_id = $this->AccountM->insert_message($data);

        // Check if the insertion was successful
        if ($insert_id) {
            // If successful, redirect back to the chat page
            echo '<script>window.history.go(-1);</script>';

            exit();
        } else {
            // If unsuccessful, return an error response
            $response = array('status' => 'error', 'message' => 'Failed to send message.');
            echo json_encode($response);
        }
    }

    public function deleteMessage($message_id)
    {

        $result = $this->AccountM->deleteMessage($message_id);

        // Redirect back to the chat page with a success or error message
        if ($result) {
            // Deleted successfully
            echo '<script>window.history.go(-1);</script>';
        } else {
            // Error occurred
            echo "Failed to delete message!";
        }
    }

}
?>