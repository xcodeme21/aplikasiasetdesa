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
                    <h5 class="card-title"><i class="mdi mdi-account-multiple"></i> Daftar User</h5>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addUserModal"><i class="mdi mdi-plus-box"></i> Tambah User</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-xs table-responsive-sm" id="datatable">
                            <thead>
                                <tr>
                                    <th>Kecamatan</th>
                                    <th>Desa</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($pengguna as $data): ?>
                                <tr>
                                    <td><?= $data->nama_kecamatan ?></td>
                                    <td><?= $data->nama_desa ?></td>
                                    <td><?= $data->nama ?></td>
                                    <td><?= $data->username ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary" onclick="return doEditUser('<?= $data->id ?>');"><i class="mdi mdi-pencil"></i></button>
                                            <button type="button" class="btn btn-danger ml-1" onclick="return doDeleteUser('<?= $data->id ?>');"><i class="mdi mdi-delete"></i></button>
                                        </div>
                                    </td>
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
                <h5 class="modal-title">Tambah User</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="<?= base_url('index.php/administrator/doAddAdmin') ?>" method="post">
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="kecamatan" class="col-sm-3 col-form-label text-right">Kecamatan</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="kecamatan" required>
                                <option value="">Pilih Kecamatan</option>
                                <?php foreach($kecamatan as $data): ?>
                                <option value="<?= $data->id ?>"><?= $data->nama ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="desa" class="col-sm-3 col-form-label text-right">Desa</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="desa" id="desa" required>
                                <option value="">Pilih Desa</option>
                            </select>
                        </div>
                    </div>
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

<div class="modal fade" id="editUserModal">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="<?= base_url('index.php/administrator/doEditUser') ?>" method="post">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="kecamatan" class="col-sm-3 col-form-label text-right">Kecamatan</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="kecamatan" required>
                                <?php foreach($kecamatan as $data): ?>
                                <option value="<?= $data->id ?>"><?= $data->nama ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="desa" class="col-sm-3 col-form-label text-right">Desa</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="desa" id="desa" required>
                                <option value="">Pilih Desa</option>
                            </select>
                        </div>
                    </div>
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
                            <input type="password" class="form-control" name="password" id="password" autocomplete="off" spellcheck="false">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Ya</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteUserModal">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Data</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="<?= base_url('index.php/administrator/doDeleteUser') ?>" method="post">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="mdi mdi-alert-circle"></i> Anda yakin ingin menghapus Data ini?
                    </div>

                    <div class="form-group row">
                        <label for="kecamatan" class="col-sm-3 col-form-label text-right">Kecamatan</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="kecamatan" disabled></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="desa" class="col-sm-3 col-form-label text-right">Desa</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="desa" disabled></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fullname" class="col-sm-3 col-form-label text-right">Nama</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="fullname" autocomplete="off" spellcheck="false" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="username" class="col-sm-3 col-form-label text-right">Username</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="username" autocomplete="off" spellcheck="false" disabled>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Ya</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function doEditUser(id) {
        $.post('<?= base_url('index.php/administrator/doGetUser') ?>', {id}, (data) => {
            if(!data.error) {
                $('#editUserModal').modal('show');
                $('#editUserModal #id').val(data.data.id);
                $('#editUserModal #kecamatan option[value="' + data.data.id_kecamatan + '"]').prop('selected', true);

                $.post('<?= base_url('index.php/administrator/doGetDesa') ?>', {id_kecamatan: data.data.id_kecamatan}, (data_desa) => {
                    if(!data_desa.error) {
                        let desa = data_desa.data.map((v, k) => '<option value="' + v.id + '">' + v.nama + '</option>');
                        
                        $('#editUserModal #desa').html(desa.join(''));
                        $('#editUserModal #desa option[value="' + data.data.id_desa + '"]').prop('selected', true);
                    }
                })
                .fail(() => {
                    $('#editUserModal #desa').html('<option value="">Pilih Desa</option>');
                })

                $('#editUserModal #fullname').val(data.data.nama);
                $('#editUserModal #username').val(data.data.username);
            }
        })
    }

    function doDeleteUser(id) {
        $.post('<?= base_url('index.php/administrator/doGetUser') ?>', {id}, (data) => {
            if(!data.error) {
                $('#deleteUserModal').modal('show');
                $('#deleteUserModal #id').val(data.data.id);
                $('#deleteUserModal #kecamatan').html('<option value="' + data.data.nama_kecamatan + '">' + data.data.nama_kecamatan + '</option>');
                $('#deleteUserModal #desa').html('<option value="' + data.data.nama_desa + '">' + data.data.nama_desa + '</option>');
                $('#deleteUserModal #fullname').val(data.data.nama);
                $('#deleteUserModal #username').val(data.data.username);
            }
        })
    }

    $(function() {
        $(document).on('change', '#addUserModal #kecamatan', () => {
            let id_kecamatan = $('#addUserModal #kecamatan option:selected').val();

            $.post('<?= base_url('index.php/administrator/doGetDesa') ?>', {id_kecamatan}, (data) => {
                if(!data.error) {
                    let desa = data.data.map((v, k) => '<option value="' + v.id + '">' + v.nama + '</option>');
                    
                    $('#addUserModal #desa').html('<option value="">Pilih Desa</option>' + desa.join(''));
                }
            })
            .fail(() => {
                $('#addUserModal #desa').html('<option value="">Pilih Desa</option>');
            })
        });

        $(document).on('change', '#editUserModal #kecamatan', () => {
            let id_kecamatan = $('#editUserModal #kecamatan option:selected').val();

            $.post('<?= base_url('index.php/administrator/doGetDesa') ?>', {id_kecamatan}, (data) => {
                if(!data.error) {
                    let desa = data.data.map((v, k) => '<option value="' + v.id + '">' + v.nama + '</option>');
                    
                    $('#editUserModal #desa').html('<option value="">Pilih Desa</option>' + desa.join(''));
                }
            })
            .fail(() => {
                $('#editUserModal #desa').html('<option value="">Pilih Desa</option>');
            })
        });
    });
</script>