<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?= base_url() ?>">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title><?= $title ?></title>
        <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
        <link rel="stylesheet" href="assets/vendor/datatables/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
        <link rel="stylesheet" href="assets/vendor/select2/dist/css/select2.min.css">
        <link rel="stylesheet" href="assets/vendor/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <script src="assets/vendor/jquery/jquery.min.js"></script>
        <script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <div id="preloader">
            <div class="spinner">
                <div class="spinner-a"></div>
                <div class="spinner-b"></div>
            </div>
        </div>
        <div id="main-wrapper">
            <div class="nav-header">
                <a href="<?= base_url('index.php/user') ?>" class="brand-logo">
                    <span class="logo-abbr">E</span>
                    <span class="logo-compact">e-Aset Desa<br><?= $profile->nama ?></span>
                    <span class="brand-title">e-Aset Desa<br><?= $profile->nama ?></span>
                </a>
                <div class="nav-control">
                    <div class="hamburger">
                        <span class="toggle-icon"><i class="icon-menu"></i></span>
                    </div>
                </div>
            </div>
            <div class="header">
                <div class="header-content">
                    <nav class="navbar navbar-expand">
                        <div class="collapse navbar-collapse justify-content-end">
                            <ul class="navbar-nav header-right">
                                <li class="nav-item dropdown header-profile">
                                    <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <img src="assets/images/users/2.jpg">
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <div class="dropdown-profile-header">
                                            <img src="assets/images/users/2.jpg">
                                            <span class="avatar-name ml-2"><?= $profile->nama ?></span>
                                        </div>
                                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#ubahPasswordModal">
                                            <i class="mdi mdi-account"></i>
                                            <span>Ubah Password</span>
                                        </a>
                                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#keluarModal">
                                            <i class="mdi mdi-power"></i>
                                            <span>Keluar</span>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>  
            <div class="quixnav">
                <div class="quixnav-scroll">
                    <ul class="metismenu" id="menu">
                        <li class="nav-label">Navigation</li>
                        <li><a href="<?= base_url('index.php/user') ?>" aria-expanded="false"><i class="mdi mdi-home"></i><span class="nav-text">Input Aset Desa</span></a></li>
                        <li><a href="<?= base_url('index.php/user/assets/delete') ?>" aria-expanded="false"><i class="mdi mdi-close-circle"></i><span class="nav-text">Hapus Aset Desa</span></a></li>
                        <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="mdi mdi-printer"></i><span class="nav-text">Tools dan Cetak</span></a>
                            <ul aria-expanded="false">
                                <li><a href="<?= base_url('index.php/user/assets/deleted') ?>">Aset Desa Yg Dihapus</a></li>
                                <li><a href="<?= base_url('index.php/user/assets/print') ?>">Inventaris Aset Desa</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="content-body">