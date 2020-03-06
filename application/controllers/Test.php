<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {
    public function index() {
        $tanggal_1 = date_create('2018-01-01');
        $tanggal_2 = date_create('2019-01-01');
        $diff      = date_diff($tanggal_1, $tanggal_2);
        $years     = (int) $diff->format('%y') * 12;
        echo (int) $diff->format('%m') + $years . ' bulan';
    }
}