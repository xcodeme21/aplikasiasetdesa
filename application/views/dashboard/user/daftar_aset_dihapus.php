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
                <form action="<?= base_url('index.php/user/doDeleteAsset') ?>" method="post">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="tahun" id="tahun">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="kode_barang" class="col-sm-2 col-form-label text-left">Kode Barang<br>Nama Barang</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="kode_barang" readonly>
                            </div>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="nama_barang" readonly>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-lg btn-primary" data-toggle="modal" data-target="#searchInventarisModal"><i class="mdi mdi-magnify"></i> Cari</button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="merk" class="col-sm-2 col-form-label text-left">Merk Barang<br>Asal Perolehan</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="merk" readonly>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="asal_perolehan" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tgl_beli" class="col-sm-2 col-form-label text-left">Tgl Pembelian<br>Byknya Pembelian</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="tgl_beli" readonly>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="banyak" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hapus" class="col-sm-2 col-form-label text-left">Jumlah Yg Akan Dihapus<br>Keterangan</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="jumlah" id="jumlah" autocomplete="off" required>
                            </div>
                            <div class="col-sm-4">
                                <select class="form-control js-example-basic-single" name="id_keterangan" id="id_keterangan" required>
                                    <option value="">-- Pilih Keterangan --</option>
                                    <?php foreach($keterangan as $data): ?>
                                    <option value="<?= $data->id ?>"><?= $data->keterangan ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-success"><i class="mdi mdi-content-save"></i> Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title"><a href="<?= base_url('index.php/user/assets/delete') ?>"><i class="mdi mdi-arrow-left"></i> Daftar Aset Desa Yang Dihapus Tahun <?= $tahun ?></a></h5>
                    <button type="button" class="btn btn-secondary btn-sm"><i class="mdi mdi-printer"></i> Cetak</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-sm" id="datatable">
                            <thead>
                                <tr>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Merk</th>
                                    <th>Asal Perolehan</th>
                                    <th>Tgl Pembelian</th>
                                    <th>Hapus</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($inventaris as $data): ?>
                                <tr>
                                    <td><?= $data->kode_barang ?></td>
                                    <td><?= $data->nama_barang ?></td>
                                    <td><?= $data->merk ?></td>
                                    <td><?= $data->asal_perolehan ?></td>
                                    <td><?= date('d-m-Y', strtotime($data->tgl_beli)) ?></td>
                                    <td><?= $data->jumlah ?></td>
                                    <td><?= $data->keterangan_hapus ?></td>
                                    <td><button type="button" class="btn btn-danger btn-sm" onclick="return doDelete('<?= $data->id ?>');"><i class="mdi mdi-delete"></i></button></td>
                                </tr>
                                <?php endforeach; ?>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="searchInventarisModal">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ketik Nama Aset Desa</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group m-0">
                    <input type="text" class="form-control" id="search">
                </div>
                <div class="d-none mt-2" id="result">
                    <div class="table-responsive">
                        <table class="table table-bordered table-responsive-sm">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Merk</th>
                                    <th>Asal</th>
                                    <th>Tgl Beli</th>
                                    <th>Banyak</th>
                                    <th>Pilih</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<script>
    function doSelectItem(id) {
        $.post('<?= base_url('index.php/user/doGetInventaris') ?>', {id: id}, (data) => {
            if(!data.error) {
                $('#searchInventarisModal').modal('hide');

                let getUnselectedItemId = $('button[data-id][disabled="disabled"]').data('id');

                $('button[data-id][disabled="disabled"]').removeClass('btn-danger').addClass('btn-secondary');
                $('button[data-id][disabled="disabled"] > i').removeClass('mdi mdi-close').addClass('mdi mdi-check');
                $('button[data-id][disabled="disabled"]').removeAttr('disabled').attr('onclick', 'return doSelectItem(\'' + getUnselectedItemId + '\')');

                $('#id').val(data.data.id);
                $('#tahun').val(data.data.tahun);
                $('#kode_barang').val(data.data.kode_barang);
                $('#nama_barang').val(data.data.nama_barang);
                $('#merk').val(data.data.merk);
                $('#asal_perolehan').val(data.data.asal_perolehan);

                let tgl = data.data.tgl_beli.split('-')[2]; 
                let bln = data.data.tgl_beli.split('-')[1]; 
                let thn = data.data.tgl_beli.split('-')[0]; 
                $('#tgl_beli').val(tgl + '-' + bln + '-' + thn);

                $('#banyak').val(data.data.banyak);

                $('[data-id="' + id + '"]').removeClass('btn-primary').addClass('btn-danger').removeAttr('onclick').attr('disabled', 'disabled');
                $('[data-id="' + id + '"] > i').removeClass('mdi mdi-check').addClass('mdi mdi-close');
            }
        });
    }

    function doDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if(result.value) {
                $.post('<?= base_url('index.php/user/doRemoveDeletedAsset') ?>', {id: id}, (data) => {
                    if(!data.error) {
                        Swal.fire('Removed!', 'Deleted items successfully removed.', 'success');

                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    }
                });
            }
        });
    }

    $(function() {
        $(document).on('keyup', '#search', function() {
            let nama_inventaris = $(this).val();

            if(nama_inventaris === '') {
                $('#result > div > table > tbody').html('');
                $('#result').addClass('d-none');
            }

            $.post('<?= base_url('index.php/user/doSearchInventaris') ?>', {nama_inventaris: nama_inventaris, tahun: <?= is_null($tahun) ? date('Y') : $tahun ?>}, (data) => {
                if(!data.error) {
                    $('#result').removeClass('d-none');

                    if(data.data.length !== 0) {
                        let items = [];
                        data.data.map((v, k) => {
                            let tgl      = v.tgl_beli.split('-')[2];
                            let bln      = v.tgl_beli.split('-')[1];
                            let thn      = v.tgl_beli.split('-')[0];
                            let tgl_beli = tgl + '-' + bln + '-' + thn;

                            if($('#kode_barang').val() === v.kode_barang || parseInt(v.banyak) === 0) {
                                items.push('<tr><td>' + v.kode_barang + '</td><td>' + v.nama_barang + '</td><td>' + v.merk + '</td><td>' + v.asal_perolehan + '</td><td>' + tgl_beli + '</td><td>' + v.banyak + '</td><td><button type="button" class="btn btn-xs btn-danger" disabled><i class="mdi mdi-close"></i> Pilih</button></td>');
                            } else {
                                items.push('<tr><td>' + v.kode_barang + '</td><td>' + v.nama_barang + '</td><td>' + v.merk + '</td><td>' + v.asal_perolehan + '</td><td>' + tgl_beli + '</td><td>' + v.banyak + '</td><td><button type="button" class="btn btn-xs btn-secondary" onclick="return doSelectItem(\''+ v.id +'\');" data-id="' + v.id + '"><i class="mdi mdi-check"></i> Pilih</button></td>');
                            }
                        });

                        $('#result > div > table > tbody').html($.unique(items));
                    } else {
                        $('#result > div > table > tbody').html('');
                    }
                }
            });
        });
    });
</script>