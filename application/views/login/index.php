<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <base href="<?= base_url() ?>">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>e-Aset Desa</title>
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="h-100">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-10">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-6">
                                <div class="welcome-content">
                                    <div class="brand-logo">
                                        <a href="<?= base_url() ?>">Sistem Pendataan Aset Desa</a>
                                    </div>
                                    <h3 class="welcome-title">Kabupaten Pidie</h3>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="auth-form">
                                    <?php
                                    print ($this->session->flashdata('status') ? $this->session->flashdata('status') : '');
                                    print validation_errors();
                                    ?>
                                    <form action="<?= base_url('index.php/login/doLogin') ?>" method="post">
                                        <div class="form-group">
                                            <label><strong>Username</strong></label>
                                            <input type="text" class="form-control" name="username" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" value="<?= $this->session->flashdata('form_value') ? $this->session->flashdata('form_value') : '' ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Password</strong></label>
                                            <input type="password" class="form-control" name="password" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" required>
                                        </div>
                                        <div class="form-row d-flex justify-content-end mt-4 mb-2">
                                            <div class="form-group">
                                                <a href="#">Lupa Password?</a>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-block">Masuk</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>