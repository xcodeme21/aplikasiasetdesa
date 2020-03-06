<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class M_login Extends CI_Model {
    public function doLogin($username, $password) {
		$this->db->select('tb_peran.rute AS peran_rute, tb_pengguna.id, tb_pengguna.id_peran, tb_pengguna.status');
		$this->db->from('tb_pengguna');
		$this->db->join('tb_peran', 'tb_peran.id = tb_pengguna.id_peran');
		$this->db->where('tb_pengguna.username', $username);
		$this->db->where('tb_pengguna.password', sha1(bin2hex($password)));
		return $this->db->get()->row();
	}
}