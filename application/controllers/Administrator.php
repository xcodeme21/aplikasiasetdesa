<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administrator extends CI_Controller {
	private $profile;

	public function __construct() {
        parent::__construct();

        if(!$this->session->userdata('__administrator__')) {
            redirect(base_url('index.php/login'));
			exit();
        }

        $this->load->model('m_admin');

        $this->profile           = $this->m_admin->getProfile();
        $this->profile->id       = (int) $this->profile->id;
		$this->profile->id_peran = (int) $this->profile->id_peran;
        
        if($this->profile->status === ACCOUNT_NOTACTIVE) {
            $this->session->unset_userdata('username');
			$this->session->unset_userdata('__administrator__');
			$this->session->sess_destroy();
			
            redirect(base_url('index.php/login'));
			exit();
        }
    }

    public function index() {
		$this->manageUsers();
	}

	public function deletedAssets() {
		$data['head']['title']     = 'e-Aset Desa';
        $data['head']['profile']   = $this->profile;

        $this->load->view('dashboard/administrator/header', $data['head']);
        $this->load->view('dashboard/administrator/aset_dihapus');
        $this->load->view('dashboard/administrator/footer');
	}

	public function assets() {
		$data['head']['title']     = 'e-Aset Desa';
        $data['head']['profile']   = $this->profile;

        $this->load->view('dashboard/administrator/header', $data['head']);
        $this->load->view('dashboard/administrator/inventaris_aset');
        $this->load->view('dashboard/administrator/footer');
	}

	public function assetsStatus() {
		$data['head']['title']     = 'e-Aset Desa';
        $data['head']['profile']   = $this->profile;

        $this->load->view('dashboard/administrator/header', $data['head']);
        $this->load->view('dashboard/administrator/status_aset');
        $this->load->view('dashboard/administrator/footer');
	}

	public function manageAdmins() {
		$data['head']['title']     = 'e-Aset Desa';
        $data['head']['profile']   = $this->profile;
		$data['index']['pengguna'] = $this->m_admin->getUsers(ROLE_ADMIN);

        $this->load->view('dashboard/administrator/header', $data['head']);
        $this->load->view('dashboard/administrator/manage_admins', $data['index']);
        $this->load->view('dashboard/administrator/footer');
	}
	
	public function manageUsers() {
		$data['head']['title']      = 'e-Aset Desa';
		$data['head']['profile']    = $this->profile;
		$data['index']['kecamatan'] = $this->m_admin->getKecamatan();
		$data['index']['pengguna']  = $this->m_admin->getUsers(ROLE_USER);

        $this->load->view('dashboard/administrator/header', $data['head']);
        $this->load->view('dashboard/administrator/manage_users', $data['index']);
        $this->load->view('dashboard/administrator/footer');
	}

	public function doCetak($jenis) {
        $tahun      = $this->input->post('tahun');

        if($jenis === 'inventaris_aset') {
            $query = $tahun ? $this->m_admin->getInventaris($tahun) : $this->m_admin->getInventaris();
            $thn   = $tahun ? $tahun : $tahun . ' - ' . date('Y');

            $n       = 1;
            $content = '';
            foreach($query as $data) {
                if((int) $data->id_asal === 1) {
                    $asli = '&radic;';
                    $apb  = '';
                    $sah  = '';
                } elseif((int) $data->id_asal === 2) {
                    $asli = '';
                    $apb  = '&radic;';
                    $sah  = '';
                } elseif((int) $data->id_asal === 3) {
                    $asli = '';
                    $apb  = '';
                    $sah  = '&radic;';
                }

                $content .= '<tr style="font-size: 14px;"><td align="center" valign="top">' . $n++ . '</td><td valign="top">' . $data->nama_barang . '</td><td align="center" valign="top">' . $data->kode_barang . '</td><td valign="top">' . $data->merk . '</td><td align="center" valign="top">' . $apb . '</td><td>' . $sah . '</td><td>' . $asli . '</td><td align="center" valign="top">' . date('d-m-Y', strtotime($data->tgl_beli)) . '</td><td align="center" valign="top">' . $data->banyak . '</td></tr>';
            }

            $date = preg_replace(['/Jan/', '/Feb/', '/Mar/', '/Apr/', '/Mey/', '/Jun/', '/Jul/', '/Aug/', '/Sep/', '/Oct/', '/Nov/', '/Dec/'], ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'], date('d M Y'));
            echo preg_replace(['/__NAMA_DESA__/', '/__TAHUN__/', '/__DATA__/', '/__NAMA_DESA_KECIL__/', '/__TANGGAL_CETAK__/', '/__NAMA_SEKRETARIS__/', '/__NAMA_PENGURUS__/'], [strtoupper($this->profile->nama), $thn, $content, $this->profile->nama, $date, '', ''], file_get_contents(ASET_TEMPLATES));

            $data = [
                'id_pengguna' => $this->profile->id, 
                'tahun'       => $tahun, 
                'tgl_cetak'   => date('Y-m-d'), 
                'sekretaris'  => null,
                'pengurus'    => null
            ];  
            $this->db->insert('tb_cetak', $data);

            exit();
        } elseif($jenis === 'aset_dihapus') {
            $query = $tahun ? $this->m_admin->getAsetDihapus($tahun) : $this->m_admin->getAsetDihapus();
            $thn   = $tahun ? $tahun : $tahun . ' - ' . date('Y');

            $n       = 1;
            $content = '';
            foreach($query as $data) {
                if((int) $data->id_asal === 1) {
                    $asli = '&radic;';
                    $apb  = '';
                    $sah  = '';
                } elseif((int) $data->id_asal === 2) {
                    $asli = '';
                    $apb  = '&radic;';
                    $sah  = '';
                } elseif((int) $data->id_asal === 3) {
                    $asli = '';
                    $apb  = '';
                    $sah  = '&radic;';
                }

                $content .= '<tr style="font-size: 14px;"><td align="center" valign="top">' . $n++ . '</td><td valign="top">' . $data->nama_barang . '</td><td align="center" valign="top">' . $data->jumlah . '</td><td align="center" valign="top">' . $asli . '</td><td>' . $apb . '</td><td>' . $sah . '</td><td align="center" valign="top">' . date('d-m-Y', strtotime($data->tgl_beli)) . '</td><td>' . $data->keterangan_hapus . '</td></tr>';
            }

            $date = preg_replace(['/Jan/', '/Feb/', '/Mar/', '/Apr/', '/Mey/', '/Jun/', '/Jul/', '/Aug/', '/Sep/', '/Oct/', '/Nov/', '/Dec/'], ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'], date('d M Y'));
            echo preg_replace(['/__NAMA_DESA__/', '/__TAHUN__/', '/__DATA__/', '/__NAMA_DESA_KECIL__/', '/__TANGGAL_CETAK__/', '/__NAMA_PENGURUS__/'], [strtoupper($this->profile->nama), $thn, $content, $this->profile->nama, $date, ''], file_get_contents(HAPUS_TEMPLATES));
            
            $data = [
                'id_pengguna' => $this->profile->id, 
                'tahun'       => $tahun, 
                'tgl_cetak'   => date('Y-m-d'), 
                'sekretaris'  => null,
                'pengurus'    => null
            ];  
            $this->db->insert('tb_cetak', $data);

            exit();
		} elseif($jenis === 'status_penggunaan') {
			var_dump('wkwkwkw');
			die();
		}
	}

	public function doGetDesa() {
		$id_kecamatan = $this->input->post('id_kecamatan');

		$config = [
			[
				'field' => 'id_kecamatan',
				'label' => 'ID Kecamatan',
				'rules' => 'trim|required|numeric'
			]
		];

		$this->form_validation->set_rules($config);

		if($this->form_validation->run() === FALSE) {
			$this->output->set_status_header(400);
            $this->output->set_content_type('application/json', 'utf-8');
            $this->output->set_output(json_encode([
                'error'     => true, 
                'errorCode' => 400, 
                'errorMsg'  => 'Bad Request',
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->output->_display();
            exit();
		} else {
			$id_kecamatan = (int) $id_kecamatan;
			$query 		  = $this->m_admin->getDesa($id_kecamatan);

			if(count($query) === 0) {
				$this->output->set_status_header(200);
				$this->output->set_content_type('application/json', 'utf-8');
				$this->output->set_output(json_encode([
					'error'     => false, 
					'errorCode' => 0, 
					'errorMsg'  => null,
					'data'		=> [
						'message' => 'No result.'
					]
				], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
				$this->output->_display();
				exit();
			}

			$this->output->set_status_header(200);
			$this->output->set_content_type('application/json', 'utf-8');
			$this->output->set_output(json_encode([
				'error'     => false, 
				'errorCode' => 0, 
				'errorMsg'  => null,
				'data'		=> $query
			], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			$this->output->_display();
			exit();
		}
	}

	public function doGetUser() {
		$id = $this->input->post('id');

		$config = [
			[
				'field' => 'id',
				'label' => 'ID User',
				'rules' => 'trim|required|numeric'
			]
		];

		$this->form_validation->set_rules($config);

		if($this->form_validation->run() === FALSE) {
			$this->output->set_status_header(400);
            $this->output->set_content_type('application/json', 'utf-8');
            $this->output->set_output(json_encode([
                'error'     => true, 
                'errorCode' => 400, 
                'errorMsg'  => 'Bad Request',
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->output->_display();
            exit();
		} else {
			$id	   = (int) $id;
			$query = $this->m_admin->getUserById($id);

			if(is_null($query)) {
				$this->output->set_status_header(200);
				$this->output->set_content_type('application/json', 'utf-8');
				$this->output->set_output(json_encode([
					'error'     => false, 
					'errorCode' => 0, 
					'errorMsg'  => null,
					'data'		=> [
						'message' => 'No result.'
					]
				], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
				$this->output->_display();
				exit();
			}

			$this->output->set_status_header(200);
			$this->output->set_content_type('application/json', 'utf-8');
			$this->output->set_output(json_encode([
				'error'     => false, 
				'errorCode' => 0, 
				'errorMsg'  => null,
				'data'		=> $query
			], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			$this->output->_display();
			exit();
		}
	}

	public function doAddAdmin() {
		$fullname = $this->input->post('fullname');
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		$config = [
			[
				'field' => 'fullname',
				'label' => 'Nama',
				'rules' => 'trim|required'
			],
			[
				'field' => 'username',
				'label' => 'Username',
				'rules' => 'trim|required|alpha_numeric'
			],
			[
				'field' => 'password',
				'label' => 'password',
				'rules' => 'required'
			]
		];

		$this->form_validation->set_rules($config);
		
		if($this->form_validation->run() === FALSE) {
			$this->index();
		} else {
			$password = sha1(bin2hex($password));
			$query    = $this->db->get_where('tb_pengguna', ['username' => $username])->row();

			if(!is_null($query)) {
				redirect(base_url('/index.php/administrator/manage/admins'));
				exit();
			}

			$data = [
				'id_peran'  => ROLE_ADMIN,
				'nama'  	=> $fullname,
				'username'  => $username,
				'password'  => $password
			];
			$this->db->insert('tb_pengguna', $data);

			redirect(base_url('/index.php/administrator/manage/admins'));
			exit();
		}
	}

	public function doAddUser() {
		$desa      = $this->input->post('desa');
		$fullname = $this->input->post('fullname');
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		$config = [
			[
				'field' => 'desa',
				'label' => 'Desa',
				'rules' => 'trim|required|numeric'
			],
			[
				'field' => 'fullname',
				'label' => 'Nama',
				'rules' => 'trim|required'
			],
			[
				'field' => 'username',
				'label' => 'Username',
				'rules' => 'trim|required|alpha_numeric'
			],
			[
				'field' => 'password',
				'label' => 'password',
				'rules' => 'required'
			]
		];

		$this->form_validation->set_rules($config);
		
		if($this->form_validation->run() === FALSE) {
			$this->index();
		} else {
			$desa	  = (int) $desa;
			$username = strtolower($username);
			$password = sha1(bin2hex($password));
			$query    = $this->db->get_where('tb_pengguna', ['username' => $username])->row();

			if(!is_null($query)) {
				redirect(base_url('index.php/administrator'));
				exit();
			}

			$data = [
				'id_peran'  => ROLE_USER,
				'id_desa'	=> $desa,
				'nama'  	=> $fullname,
				'username'  => $username,
				'password'  => $password
			];
			$this->db->insert('tb_pengguna', $data);

			redirect(base_url('index.php/administrator'));
			exit();
		}
	}

	public function doEditUser() {
		$id       = $this->input->post('id');
		$desa 	  = $this->input->post('desa');
		$fullname = $this->input->post('fullname');
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		$config = [
			[
				'field' => 'id',
				'label' => 'ID User',
				'rules' => 'trim|required|numeric'
			],
			[
				'field' => 'id',
				'label' => 'ID Desa',
				'rules' => 'trim|required|numeric'
			],
			[
				'field' => 'fullname',
				'label' => 'Nama',
				'rules' => 'trim|required'
			],
			[
				'field' => 'username',
				'label' => 'Username',
				'rules' => 'trim|required|alpha_numeric'
			]
		];

		$this->form_validation->set_rules($config);

		if($this->form_validation->run() === FALSE) {
			$this->index();
		} else {
			$id 	  = (int) $id;
			$desa 	  = (int) $desa;
			$username = strtolower($username);
			$password = sha1(bin2hex($password));

			$query = $this->db->get_where('tb_pengguna', ['id' => $id])->row();

			$data = [
				'id_desa'	=> $desa,
				'nama'  	=> $fullname,
				'username'  => $username,
				'password'  => isset($password) ? $password : $query->password
			];
			$this->db->update('tb_pengguna', $data, ['id' => $id]);

			redirect(base_url('index.php/administrator'));
			exit();
		}
	}

	public function doDeleteUser() {
		$id = $this->input->post('id');

		$config = [
			[
				'field' => 'id',
				'label' => 'ID User',
				'rules' => 'trim|required|numeric'
			]
		];

		$this->form_validation->set_rules($config);

		if($this->form_validation->run() === FALSE) {
			$this->index();
		} else {
			$id	   = (int) $id;
			$query = $this->db->get_where('tb_pengguna', ['id' => $id])->row();

			if(is_null($query)) {
				redirect(base_url('index.php/administrator'));
				exit();
			}

			$this->db->delete('tb_pengguna', ['id' => $id]);

			redirect(base_url('index.php/administrator'));
		}
	}

    public function doUpdatePassword() {
		$cur_password = $this->input->post('cur_password');
		$new_password = $this->input->post('new_password');
		$con_password = $this->input->post('con_password');

		$config = [
			[
				'field' => 'cur_password',
				'label'	=> 'Current Password',
				'rules' => 'required'
			],
			[
				'field' => 'new_password',
				'label'	=> 'New Password',
				'rules' => 'required|min_length[6]'
			],
			[
				'field' => 'con_password',
				'label'	=> 'Confirm New Password',
				'rules' => 'required|min_length[6]|matches[new_password]'
			],
		];

		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button><strong>Sorry!</strong> ', '</div>');

		if($this->form_validation->run() === FALSE) {
			$this->index();
		} else {
			$cur_password = sha1(bin2hex($cur_password));
            $password 	  = sha1(bin2hex($con_password));

			if($cur_password !== $this->profile->password) {
				$this->session->set_flashdata('status', '<div class="alert alert-warning alert-dismissible fade show"><button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button><strong>Sorry!</strong> Your current password is incorrect.</div>');
				redirect(base_url('index.php/administrator'));
				exit();
            }

            if($password === $this->profile->password) {
                $this->session->set_flashdata('status', '<div class="alert alert-warning alert-dismissible fade show"><button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button><strong>Sorry!</strong> Your new password cannot be the same as your current password.</div>');
				redirect(base_url('index.php/administrator'));
				exit();
            }
            
            $this->db->update('tb_pengguna', ['password' => $password], ['username' => $this->session->userdata('username')]);

			$this->session->set_flashdata('status', '<div class="alert alert-success alert-dismissible fade show"><button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button><strong>Success!</strong> Password successfully updated.</div>');
			redirect(base_url('index.php/administrator'));
		}
    }

    public function doLogout() {
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('__administrator__');
        $this->session->sess_destroy();
        redirect(base_url());
	}
}