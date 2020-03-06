<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public function __construct() {
        parent::__construct();

        if($this->session->userdata('__administrator__')) {
            redirect(base_url('index.php/administrator'));
			exit();
        }

        if($this->session->userdata('__user__')) {
            redirect(base_url('index.php/user'));
			exit();
        }
	}
	
	public function index() {
		$this->load->view('login/index');
	}

	public function doLogin() {
		$this->load->model('m_login');

        $username = $this->input->post('username');
		$password = $this->input->post('password');

        $config = [
            [
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'trim|required|alpha_numeric'
            ],
            [
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required'
            ],
        ];

        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button><strong>Sorry!</strong> ', '</div>');
                
        if($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('form_value', html_escape($username));
            $this->index();
        } else {
            $query = $this->m_login->doLogin($username, $password);
            
            if(is_null($query)) {
                $this->session->set_flashdata('form_value', html_escape($username));
                $this->session->set_flashdata('status', '<div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button><strong>Sorry!</strong> Username or Password is invalid.</div>');
                redirect(base_url('index.php/login'));
                exit();
            }

            if($query->status === ACCOUNT_NOTACTIVE) {
                $this->session->set_flashdata('form_value', html_escape($username));
                $this->session->set_flashdata('status', '<div class="alert alert-warning alert-dismissible fade show"><button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button><strong>Sorry!</strong> It looks like your account is not active.</div>');
                redirect(base_url('index.php/login'));
                exit();
            }

            $userdata = [
                'username'                 => $username,
                "__{$query->peran_rute}__" => sha1(bin2hex($username))  
            ];
            $this->session->set_userdata($userdata);
            
            redirect(base_url("index.php/{$query->peran_rute}"));
        }
    }
}
