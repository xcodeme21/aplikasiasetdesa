<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class M_user Extends CI_Model {
    public function getProfile() {
        $this->db->select('id, id_peran, nama, password, status');
        $this->db->from('tb_pengguna');
        $this->db->where('username', $this->session->userdata('username'));
        return $this->db->get()->row();
    }

    public function getAsalPerolehan() {
        $this->db->from('tb_asal');
        return $this->db->get()->result();
    }

    public function getBarang() {
        $this->db->from('tb_barang');
        return $this->db->get()->result();
    }

    public function getInventaris($id_pengguna, $tahun = null) {
        $this->db->select('tb_barang.kode_barang, tb_barang.kategori_inventaris, tb_barang.nama_barang, tb_asal.keterangan AS asal_perolehan, tb_inventaris.id, tb_inventaris.id_asal, tb_inventaris.merk, tb_inventaris.tgl_beli, tb_inventaris.tahun, tb_inventaris.banyak, tb_inventaris.penggunaan, tb_inventaris.harga_beli, tb_inventaris.umur');
        $this->db->from('tb_inventaris');
        $this->db->join('tb_barang', 'tb_barang.id = tb_inventaris.id_barang');
        $this->db->join('tb_asal', 'tb_asal.id = tb_inventaris.id_asal');
        $this->db->where('tb_inventaris.id_pengguna', $id_pengguna);
        
        if(!is_null($tahun)) {
            $this->db->where('tb_inventaris.tahun', $tahun);
        }
        
        return $this->db->get()->result();
    }

    public function getInventarisById($id, $id_pengguna) {
        $this->db->select('tb_barang.kode_barang, tb_barang.kategori_inventaris, tb_barang.nama_barang, tb_asal.keterangan AS asal_perolehan, tb_inventaris.id, tb_inventaris.id_asal, tb_inventaris.merk, tb_inventaris.tgl_beli, tb_inventaris.tahun, tb_inventaris.banyak, tb_inventaris.penggunaan, tb_inventaris.harga_beli, tb_inventaris.umur');
        $this->db->from('tb_inventaris');
        $this->db->join('tb_barang', 'tb_barang.id = tb_inventaris.id_barang');
        $this->db->join('tb_asal', 'tb_asal.id = tb_inventaris.id_asal');
        $this->db->where('tb_inventaris.id', $id);
        $this->db->where('tb_inventaris.id_pengguna', $id_pengguna);
        return $this->db->get()->row();
    }

    public function getKeterangan() {
        $this->db->from('tb_keterangan');
        return $this->db->get()->result();
    }

    public function searchInventaris($id_pengguna, $nama_inventaris, $tahun) {
        $this->db->select('tb_barang.kode_barang, tb_barang.nama_barang, tb_asal.keterangan AS asal_perolehan, tb_inventaris.id, tb_inventaris.merk, tb_inventaris.tgl_beli, tb_inventaris.banyak');
        $this->db->from('tb_inventaris');
        $this->db->join('tb_barang', 'tb_barang.id = tb_inventaris.id_barang');
        $this->db->join('tb_asal', 'tb_asal.id = tb_inventaris.id_asal'); 
        $this->db->where('tb_inventaris.id_pengguna', $id_pengguna);
        $this->db->where('(tb_barang.nama_barang LIKE "%' . $nama_inventaris . '%" OR tb_inventaris.merk LIKE "%' . $nama_inventaris . '%")', NULL, FALSE);
        $this->db->like('tb_inventaris.tahun', $tahun);
        return $this->db->get()->result();
    }

    public function getAsetDihapus($id_pengguna, $tahun = null) {
        $this->db->select('tb_inventaris.id_asal, tb_inventaris.merk, tb_inventaris.tahun, tb_inventaris.tgl_beli, tb_barang.kode_barang, tb_barang.nama_barang, tb_asal.keterangan AS asal_perolehan, tb_keterangan.keterangan AS keterangan_hapus, tb_hapus.id, tb_hapus.jumlah');
        $this->db->from('tb_hapus');
        $this->db->join('tb_inventaris', 'tb_inventaris.id = tb_hapus.id_inventaris');
        $this->db->join('tb_barang', 'tb_barang.id = tb_inventaris.id_barang');
        $this->db->join('tb_asal', 'tb_asal.id = tb_inventaris.id_asal');
        $this->db->join('tb_keterangan', 'tb_keterangan.id = tb_hapus.id_keterangan');
        $this->db->where('tb_hapus.id_pengguna', $id_pengguna);

        if(!is_null($tahun)) {
            $this->db->where('tb_inventaris.tahun', $tahun);
        }

        return $this->db->get()->result();
    }
}