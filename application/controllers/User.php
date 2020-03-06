<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    private $profile;

	public function __construct() {
        parent::__construct();

        if(!$this->session->userdata('__user__')) {
            redirect(base_url('index.php/login'));
			exit();
        }

        $this->load->model('m_user');

        $this->profile           = $this->m_user->getProfile();
        $this->profile->id       = (int) $this->profile->id;
        $this->profile->id_peran = (int) $this->profile->id_peran;

        if($this->profile->status === ACCOUNT_NOTACTIVE) {
            $this->session->unset_userdata('username');
            $this->session->unset_userdata('__user__');
            $this->session->sess_destroy();
            
            redirect(base_url('index.php/login'));
			exit();
        }
    }

    public function index() {
        $data['head']['title']       = 'e-Aset Desa';
        $data['head']['profile']     = $this->profile;
        $data['index']['inventaris'] = $this->m_user->getInventaris($this->profile->id, date('Y'));
        $data['index']['perolehan']  = $this->m_user->getAsalPerolehan();

        $this->load->view('dashboard/user/header', $data['head']);
        $this->load->view('dashboard/user/index', $data['index']);
        $this->load->view('dashboard/user/footer');
    }

    public function doGetInventaris() {
        $id = $this->input->post('id');

        $config = [
            [
                'field' => 'id',
                'label' => 'ID Inventaris',
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
            $id    = (int) $id;
            $query = $this->m_user->getInventarisById($id, $this->profile->id);

            if(is_null($query)) {
                $this->output->set_status_header(200);
                $this->output->set_content_type('application/json', 'utf-8');
                $this->output->set_output(json_encode([
                    'error'     => false, 
                    'errorCode' => 0, 
                    'errorMsg'  => null,
                    'data'      => [
                        'message' => 'Item not found.'
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
                'data'      => $query
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->output->_display();
            exit();
        }
    } 

    public function doGetBarang() {
        $kategori_inventaris = $this->input->post('kategori_inventaris');

        $config = [
            [
                'field' => 'kategori_inventaris',
                'label' => 'Kategori Inventaris',
                'rules' => 'trim|required'
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
            $query = $this->db->get_where('tb_barang', ['kategori_inventaris' => $kategori_inventaris])->result();

            if(count($query) === 0) {
                $this->output->set_status_header(200);
                $this->output->set_content_type('application/json', 'utf-8');
                $this->output->set_output(json_encode([
                    'error'     => false, 
                    'errorCode' => 0, 
                    'errorMsg'  => null,
                    'data'      => [
                        'message' => 'Item not found.'
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
                'data'      => $query
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->output->_display();
            exit();
        }
    }

    public function doShowAssets($tahun = null) {
        $data['head']['title']       = 'e-Aset Desa';
        $data['head']['profile']     = $this->profile;
        $data['index']['perolehan']  = $this->m_user->getAsalPerolehan();
        $data['index']['tahun']      = $tahun; 
        $data['index']['inventaris'] = $this->m_user->getInventaris($this->profile->id, is_null($tahun) ? date('Y') : $tahun);

        $this->load->view('dashboard/user/header', $data['head']);
        $this->load->view('dashboard/user/daftar_aset', $data['index']);
        $this->load->view('dashboard/user/footer');
    }

    public function deleteAssets() {
        $data['head']['title']   = 'e-Aset Desa';
        $data['head']['profile'] = $this->profile;

        $this->load->view('dashboard/user/header', $data['head']);
        $this->load->view('dashboard/user/hapus_aset');
        $this->load->view('dashboard/user/footer');
    }

    public function doShowDeletedAssets($tahun = null) {
        $data['head']['title']       = 'e-Aset Desa';
        $data['head']['profile']     = $this->profile;
        $data['index']['tahun']      = $tahun;
        $data['index']['keterangan'] = $this->m_user->getKeterangan();
        $data['index']['inventaris'] = $this->m_user->getAsetDihapus($this->profile->id, is_null($tahun) ? date('Y') : $tahun);

        $this->load->view('dashboard/user/header', $data['head']);
        $this->load->view('dashboard/user/daftar_aset_dihapus', $data['index']);
        $this->load->view('dashboard/user/footer');
    }

    public function doSearchInventaris() {
        $nama_inventaris = $this->input->post('nama_inventaris');
        $tahun           = $this->input->post('tahun');

        $config = [
            [
                'field' => 'nama_inventaris',
                'label' => 'Nama Inventaris',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'tahun',
                'label' => 'Tahun',
                'rules' => 'trim|required'
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
            $query = $this->m_user->searchInventaris($this->profile->id, $nama_inventaris, $tahun);

            $this->output->set_status_header(200);
            $this->output->set_content_type('application/json', 'utf-8');
            $this->output->set_output(json_encode([
                'error'     => false, 
                'errorCode' => 0, 
                'errorMsg'  => null,
                'data'      => $query
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->output->_display();
            exit();
        }
    }

    public function doDeleteAsset() {
        $id            = $this->input->post('id');
        $id_keterangan = $this->input->post('id_keterangan');
        $tahun         = $this->input->post('tahun');
        $jumlah        = $this->input->post('jumlah');

        $config = [
            [
                'field' => 'id',
                'label' => 'ID Inventaris',
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => 'id_keterangan',
                'label' => 'Keterangan',
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => 'tahun',
                'label' => 'Tahun',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'jumlah',
                'label' => 'Jumlah',
                'rules' => 'trim|required|numeric'
            ]
        ];

        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button><strong>Sorry!</strong> ', '</div>');
        
        if($this->form_validation->run() === FALSE) {
            $this->doShowDeletedAssets($tahun);
        } else {
            $id            = (int) $id;
            $id_keterangan = (int) $id_keterangan;
            $tahun         = date('Y', strtotime($tahun));
            $jumlah        = (int) $jumlah;

            $query  = $this->db->get_where('tb_inventaris', ['id' => $id])->row();
            $banyak = (int) $query->banyak;
            $satuan = explode(' ', $query->banyak)[1];

            if($jumlah > $banyak) {
                $this->session->set_flashdata('status', '<div class="alert alert-warning alert-dismissible fade show"><button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button><strong>Sorry!</strong> the input number cannot exceed the number of items.</div>');
                redirect(base_url("index.php/user/assets/delete/view/{$query->tahun}"));
                exit();
            }

            $data = [
                'id_pengguna'   => $this->profile->id,
                'id_inventaris' => $id,
                'id_keterangan' => $id_keterangan,
                'jumlah'        => "$jumlah $satuan"
            ];
            $this->db->insert('tb_hapus', $data);

            $selisih = $banyak - $jumlah;
            $this->db->update('tb_inventaris', ['banyak' => "$selisih $satuan"], ['id' => $id]);

            $this->session->set_flashdata('status', '<div class="alert alert-success alert-dismissible fade show"><button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button><strong>Success!</strong> Item successfully deleted.</div>');
            redirect(base_url("index.php/user/assets/delete/view/{$query->tahun}"));
        }
    }

    public function doRemoveDeletedAsset() {
        $id = $this->input->post('id');

        $config = [
            [
                'field' => 'id',
                'label' => 'ID Inventaris',
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
            $id     = (int) $id;
            $query  = $this->db->get_where('tb_hapus', ['id' => $id, 'id_pengguna' => $this->profile->id])->row();

            $id_inventaris = (int) $query->id_inventaris;
            $jumlah        = (int) $query->jumlah;
            $satuan        = explode(' ', $query->jumlah)[1];

            if(is_null($query)) {
                $this->output->set_status_header(200);
                $this->output->set_content_type('application/json', 'utf-8');
                $this->output->set_output(json_encode([
                    'error'     => false, 
                    'errorCode' => 0, 
                    'errorMsg'  => null,
                    'data'      => [
                        'message' => 'Item not found'
                    ]
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                $this->output->_display();
                exit();
            }

            $query  = $this->db->get_where('tb_inventaris', ['id' => $id_inventaris, 'id_pengguna' => $this->profile->id])->row();
            $total  = (int) $query->banyak + $jumlah;
            
            $this->db->delete('tb_hapus', ['id' => $id, 'id_pengguna' => $this->profile->id]);
            $this->db->update('tb_inventaris', ['banyak' => "$total $satuan"], ['id' => $id_inventaris, 'id_pengguna' => $this->profile->id]);

            $this->output->set_status_header(200);
            $this->output->set_content_type('application/json', 'utf-8');
            $this->output->set_output(json_encode([
                'error'     => false, 
                'errorCode' => 0, 
                'errorMsg'  => null,
                'data'      => [
                    'message' => 'Item successfully removed from deleted assets.'
                ]
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->output->_display();
            exit();
        }
    }

    public function aset_dihapus() {
        $data['head']['title']   = 'e-Aset Desa';
        $data['head']['profile'] = $this->profile;

        $this->load->view('dashboard/user/header', $data['head']);
        $this->load->view('dashboard/user/aset_dihapus');
        $this->load->view('dashboard/user/footer');
    }

    public function inventaris_aset() {
        $data['head']['title']   = 'e-Aset Desa';
        $data['head']['profile'] = $this->profile;

        $this->load->view('dashboard/user/header', $data['head']);
        $this->load->view('dashboard/user/inventaris_aset');
        $this->load->view('dashboard/user/footer');
    }

    public function doAddAsset() {
        $id_barang  = $this->input->post('id_barang');
        $id_asal    = $this->input->post('id_asal');
        $merk       = $this->input->post('merk');
        $umur       = $this->input->post('umur');
        $tahun      = $this->input->post('tahun');
        $harga_beli = $this->input->post('harga_beli');
        $tgl_beli   = $this->input->post('tgl_beli');
        $banyak     = $this->input->post('banyak');
        $satuan     = $this->input->post('satuan');
        $penggunaan = $this->input->post('penggunaan');

        $config = [
            [
                'field' => 'id_barang',
                'label' => 'Nama Inventaris',
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => 'id_asal',
                'label' => 'Asal Perolehan',
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => 'merk',
                'label' => 'Merk',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'umur',
                'label' => 'Umur',
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => 'tahun',
                'label' => 'Aset Data Tahun',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'harga_beli',
                'label' => 'Harga Beli',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'tgl_beli',
                'label' => 'Tanggal Pembelian',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'banyak',
                'label' => 'Banyak',
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => 'satuan',
                'label' => 'Satuan',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'penggunaan',
                'label' => 'Penggunaan',
                'rules' => 'trim'
            ],
        ];

        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button><strong>Sorry!</strong> ', '</div>');
        
        if($this->form_validation->run() === FALSE) {
            $this->doShowAssets();
        } else {
            $id_barang  = (int) $id_barang;
            $id_asal    = (int) $id_asal;
            $umur       = (int) $umur * 12;
            $tahun      = date('Y', strtotime($tahun));
            $harga_beli = (double) $harga_beli;
            $tgl_beli   = date('Y-m-d', strtotime($tgl_beli));
            $banyak     = (int) $banyak . ' ' . $satuan;

            $data = [
                'id_pengguna' => $this->profile->id,
                'id_barang'   => $id_barang,
                'id_asal'     => $id_asal,
                'merk'        => $merk,
                'umur'        => $umur,
                'tahun'       => $tahun,
                'harga_beli'  => $harga_beli,
                'tgl_beli'    => $tgl_beli,
                'banyak'      => $banyak,
                'penggunaan'  => $penggunaan,
            ];
            $this->db->insert('tb_inventaris', $data);

            $this->session->set_flashdata('status', '<div class="alert alert-success alert-dismissible fade show"><button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button><strong>Success!</strong> Item successfully added.</div>');
            redirect(base_url("index.php/user/assets/view/$tahun"));
        }
    }

    public function doEditAsset() {
        $id         = $this->input->post('id');
        $id_asal    = $this->input->post('id_asal');
        $merk       = $this->input->post('merk');
        $umur       = $this->input->post('umur');
        $tahun      = $this->input->post('tahun');
        $harga_beli = $this->input->post('harga_beli');
        $tgl_beli   = $this->input->post('tgl_beli');
        $banyak     = $this->input->post('banyak');
        $satuan     = $this->input->post('satuan');
        $penggunaan = $this->input->post('penggunaan');

        $config = [
            [
                'field' => 'id',
                'label' => 'ID Inventaris',
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => 'id_asal',
                'label' => 'Asal Perolehan',
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => 'merk',
                'label' => 'Merk',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'umur',
                'label' => 'Umur',
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => 'tahun',
                'label' => 'Aset Data Tahun',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'harga_beli',
                'label' => 'Harga Beli',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'tgl_beli',
                'label' => 'Tanggal Pembelian',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'banyak',
                'label' => 'Banyak',
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => 'satuan',
                'label' => 'Satuan',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'penggunaan',
                'label' => 'Penggunaan',
                'rules' => 'trim'
            ],
        ];

        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button><strong>Sorry!</strong> ', '</div>');
        
        if($this->form_validation->run() === FALSE) {
            $this->index();
        } else {
            $id         = (int) $id;
            $id_asal    = (int) $id_asal;
            $umur       = (int) $umur;
            $tahun      = date('Y', strtotime($tahun));
            $harga_beli = (double) $harga_beli;
            $tgl_beli   = date('Y-m-d', strtotime($tgl_beli));
            $banyak     = (int) $banyak . ' ' . $satuan;

            $data = [
                'id_asal'    => $id_asal,  
                'merk'       => $merk,
                'umur'       => $umur,
                'tahun'      => $tahun,
                'harga_beli' => $harga_beli,
                'tgl_beli'   => $tgl_beli,
                'banyak'     => $banyak,
                'penggunaan' => $penggunaan,
            ];
            $this->db->update('tb_inventaris', $data, ['id' => $id]);

            $this->session->set_flashdata('status', '<div class="alert alert-success alert-dismissible fade show"><button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button><strong>Success!</strong> Item successfully updated.</div>');
            
            if(preg_match('/assets\/view/', $_SERVER['HTTP_REFERER'])) {
                redirect(base_url("index.php/user/assets/view/$tahun"));
            } else {
                redirect(base_url('index.php/user'));
            }
        }
    }

    public function doCetak($jenis) {
        $tahun      = $this->input->post('tahun');
        $tgl_cetak  = $this->input->post('tgl_cetak');
        $sekretaris = $this->input->post('sekretaris');
        $petugas    = $this->input->post('petugas');

        if($jenis === 'inventaris_aset') {
            $query = $tahun ? $this->m_user->getInventaris($this->profile->id, $tahun) : $this->m_user->getInventaris($this->profile->id);
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

            $date = preg_replace(['/Jan/', '/Feb/', '/Mar/', '/Apr/', '/Mey/', '/Jun/', '/Jul/', '/Aug/', '/Sep/', '/Oct/', '/Nov/', '/Dec/'], ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'], date('d M Y', strtotime($tgl_cetak)));
            echo preg_replace(['/__NAMA_DESA__/', '/__TAHUN__/', '/__DATA__/', '/__NAMA_DESA_KECIL__/', '/__TANGGAL_CETAK__/', '/__NAMA_SEKRETARIS__/', '/__NAMA_PENGURUS__/'], [strtoupper($this->profile->nama), $thn, $content, $this->profile->nama, $date, $sekretaris, $petugas], file_get_contents(ASET_TEMPLATES));

            $data = [
                'id_pengguna' => $this->profile->id, 
                'tahun'       => $tahun, 
                'tgl_cetak'   => date('Y-m-d', strtotime($tgl_cetak)), 
                'sekretaris'  => null,
                'pengurus'    => $petugas
            ];  
            $this->db->insert('tb_cetak', $data);

            exit();
        } elseif($jenis === 'aset_dihapus') {
            $query = $tahun ? $this->m_user->getAsetDihapus($this->profile->id, $tahun) : $this->m_user->getAsetDihapus($this->profile->id);
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

            $date = preg_replace(['/Jan/', '/Feb/', '/Mar/', '/Apr/', '/Mey/', '/Jun/', '/Jul/', '/Aug/', '/Sep/', '/Oct/', '/Nov/', '/Dec/'], ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'], date('d M Y', strtotime($tgl_cetak)));
            echo preg_replace(['/__NAMA_DESA__/', '/__TAHUN__/', '/__DATA__/', '/__NAMA_DESA_KECIL__/', '/__TANGGAL_CETAK__/', '/__NAMA_PENGURUS__/'], [strtoupper($this->profile->nama), $thn, $content, $this->profile->nama, $date, $petugas], file_get_contents(HAPUS_TEMPLATES));
            
            $data = [
                'id_pengguna' => $this->profile->id, 
                'tahun'       => $tahun, 
                'tgl_cetak'   => date('Y-m-d', strtotime($tgl_cetak)), 
                'sekretaris'  => null,
                'pengurus'    => $petugas
            ];  
            $this->db->insert('tb_cetak', $data);

            exit();
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
            $password = sha1(bin2hex($con_password));

			if(sha1(bin2hex($cur_password)) !== $this->profile->password) {
				$this->session->set_flashdata('status', '<div class="alert alert-warning alert-dismissible fade show"><button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button><strong>Sorry!</strong> Your current password is incorrect.</div>');
				redirect(base_url('index.php/user'));
				exit();
            }

            if($password === $this->profile->password) {
                $this->session->set_flashdata('status', '<div class="alert alert-warning alert-dismissible fade show"><button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button><strong>Sorry!</strong> Your new password cannot be the same as your current password.</div>');
				redirect(base_url('index.php/user'));
				exit();
            }
            
            $this->db->update('tb_pengguna', ['password' => $password], ['username' => $this->session->userdata('username')]);

			$this->session->set_flashdata('status', '<div class="alert alert-success alert-dismissible fade show"><button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button><strong>Success!</strong> Password successfully updated.</div>');
			redirect(base_url('index.php/user'));
		}
    }

    public function doLogout() {
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('__user__');
        $this->session->sess_destroy();
        redirect(base_url());
	}
}