<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Umoor extends CI_Controller
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
        if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] < 4 || $_SESSION['user']['role'] > 15)) {
            redirect('/accounts');
        }

        $data['user_name'] = $_SESSION['user']['username'];
        $this->load->view('Umoor/Header', $data);
        $this->load->view('Umoor/Home');
    }
    public function RazaRequest()
    {
    try {
        if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] < 4 || $_SESSION['user']['role'] > 15)) {
            redirect('/accounts');
        }

        
        // Load necessary models
        $this->load->model('UmoorM');
        $this->load->model('AccountM');
        
        // Fetch user_id from the session
        $user_id = $_SESSION['user']['username'];
        
        // Fetch data from UmoorM model
        $data['raza'] = $this->UmoorM->get_raza($user_id);
        $data['razatype'] = $this->UmoorM->get_razatype($user_id);
        

        // Process data
        foreach ($data['raza'] as $key => $value) {
            $username = $this->AccountM->get_user($value['user_id']);
            $razatype = $this->UmoorM->get_razatype_byid($value['razaType']);


            if (!empty($razatype)) {
                $razatype = $razatype[0];
                $data['raza'][$key]['razaType'] = $razatype['name'];
                $data['raza'][$key]['razafields'] = $razatype['fields'];
                $data['raza'][$key]['user_name'] = $username[0]['Full_Name'];
            } else {
                // Handle the case where razatype is not found
                // You may want to log this or handle it according to your application's requirements
            }
        }

        foreach ($data['raza'] as $key => $value) {
            $chatCount = $this->AccountM->get_chat_count($value['id']); // Assuming id is the raza_id
            $data['raza'][$key]['chat_count'] = $chatCount;
        }
        $data['user_name'] = $_SESSION['user']['username'];

        // Load views
        $this->load->view('Umoor/Header', $data);
        $this->load->view('Umoor/Raza/RazaRequest', $data);
    } catch (Exception $e) {
        // Handle exceptions
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
    }

}
?>