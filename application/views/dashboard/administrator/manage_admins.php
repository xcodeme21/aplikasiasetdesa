<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <?php
            print ($this->session->flashdata('status') ? $this->session->flashdata('status') : '');
            print validation_errors();
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title"><i class="mdi mdi-account-multiple"></i> Daftar Admin</h5>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addUserModal"><i class="mdi mdi-plus-box"></i> Tambah Admin</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-xs table-responsive-sm" id="datatable">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Username</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($pengguna as $data): ?>
                                <tr>
                                    <td><?= $data->nama ?></td>
                                    <td><?= $data->username ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addUserModal">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Admin</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="<?= base_url('index.php/administrator/doAddAdmin') ?>" method="post">
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="fullname" class="col-sm-3 col-form-label text-right">Nama</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="fullname" id="fullname" autocomplete="off" spellcheck="false" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="username" class="col-sm-3 col-form-label text-right">Username</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="username" id="username" autocomplete="off" spellcheck="false" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-3 col-form-label text-right">Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="password" id="password" autocomplete="off" spellcheck="false" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>