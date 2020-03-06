<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class M_admin Extends CI_Model {
    public function getProfile() {
        $this->db->select('id, id_peran, nama, password, status');
        $this->db->from('tb_pengguna');
        $this->db->where('username', $this->session->userdata('username'));
        return $this->db->get()->row();
    }

    public function getInventaris($tahun = null) {
        $this->db->select('tb_barang.kode_barang, tb_barang.kategori_inventaris, tb_barang.nama_barang, tb_asal.keterangan AS asal_perolehan, tb_inventaris.id, tb_inventaris.id_asal, tb_inventaris.merk, tb_inventaris.tgl_beli, tb_inventaris.tahun, tb_inventaris.banyak, tb_inventaris.penggunaan, tb_inventaris.harga_beli, tb_inventaris.umur');
        $this->db->from('tb_inventaris');
        $this->db->join('tb_barang', 'tb_barang.id = tb_inventaris.id_barang');
        $this->db->join('tb_asal', 'tb_asal.id = tb_inventaris.id_asal');
        
        if(!is_null($tahun)) {
            $this->db->where('tb_inventaris.tahun', $tahun);
        }
        
        return $this->db->get()->result();
    }

    public function getAsetDihapus($tahun = null) {
        $this->db->select('tb_inventaris.id_asal, tb_inventaris.merk, tb_inventaris.tahun, tb_inventaris.tgl_beli, tb_barang.kode_barang, tb_barang.nama_barang, tb_asal.keterangan AS asal_perolehan, tb_keterangan.keterangan AS keterangan_hapus, tb_hapus.id, tb_hapus.jumlah');
        $this->db->from('tb_hapus');
        $this->db->join('tb_inventaris', 'tb_inventaris.id = tb_hapus.id_inventaris');
        $this->db->join('tb_barang', 'tb_barang.id = tb_inventaris.id_barang');
        $this->db->join('tb_asal', 'tb_asal.id = tb_inventaris.id_asal');
        $this->db->join('tb_keterangan', 'tb_keterangan.id = tb_hapus.id_keterangan');

        if(!is_null($tahun)) {
            $this->db->where('tb_inventaris.tahun', $tahun);
        }

        return $this->db->get()->result();
    }

    public function getKecamatan() {
        $this->db->select('id, nama');
        $this->db->from('tb_kecamatan');
        return $this->db->get()->result(); 
    }

    public function getDesa($id_kecamatan) {
        $this->db->select('id, nama');
        $this->db->from('tb_desa');
        $this->db->where('id_kecamatan', $id_kecamatan);
        return $this->db->get()->result(); 
    }

    public function getUsers($id_peran) {
        if($id_peran === ROLE_ADMIN) {
            $this->db->select('id, nama, username');
            $this->db->from('tb_pengguna');
        } elseif($id_peran === ROLE_USER) {
            $this->db->select('tb_kecamatan.nama AS nama_kecamatan, tb_desa.nama AS nama_desa, tb_pengguna.id, tb_pengguna.nama, tb_pengguna.username');
            $this->db->from('tb_pengguna');
            $this->db->join('tb_desa', 'tb_desa.id = tb_pengguna.id_desa');
            $this->db->join('tb_kecamatan', 'tb_kecamatan.id = tb_desa.id_kecamatan');
        }

        $this->db->where('tb_pengguna.id_peran', $id_peran);
        return $this->db->get()->result();
    }

    public function getUserById($id) {
        $this->db->select('tb_kecamatan.id AS id_kecamatan, tb_kecamatan.nama AS nama_kecamatan, tb_desa.nama AS nama_desa, tb_pengguna.id, tb_pengguna.id_desa, tb_pengguna.nama, tb_pengguna.username');
        $this->db->from('tb_pengguna');
        $this->db->join('tb_desa', 'tb_desa.id = tb_pengguna.id_desa');
        $this->db->join('tb_kecamatan', 'tb_kecamatan.id = tb_desa.id_kecamatan');
        $this->db->where('tb_pengguna.id', $id);
        return $this->db->get()->row();
    }

    public function getUserDistinct() {
        $this->db->distinct();
        return $this->db->get()->result();
    }
}