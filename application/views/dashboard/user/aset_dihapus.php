<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$tahun = $this->m_user->getAsetDihapus($profile->id);
$tahun = array_unique(array_column($tahun, 'tahun'));
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><i class="mdi mdi-calendar"></i> Pilih Tahun Untuk Mencetak Aset Desa Yang Dihapus</h5>
                </div>
                <form action="<?= base_url('index.php/user/assets/deleted/print') ?>" method="post">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="tahun" class="col-sm-4 col-form-label text-right">Tahun</label>
                            <div class="col-sm-8">
                                <select class="form-control col-lg-3 js-example-basic-single" name="tahun" id="tahun" required>
                                    <option value="">-- Pilih Tahun --</option>
                                    <?php for($i = 0; $i < count($tahun); $i++): ?>
                                    <option value="<?= $tahun[$i] ?>"><?= $tahun[$i] ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tgl_cetak" class="col-sm-4 col-form-label text-right">Tanggal Cetak</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="tgl_cetak" id="tgl_cetak" data-provide="datepicker" autocomplete="off" spellcheck="false" placeholder="dd-mm-yyyy" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="petugas" class="col-sm-4 col-form-label text-right">Nama Petugas/Pengurus</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="petugas" id="petugas" autocomplete="off" spellcheck="false" required>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-end">
                        <button type="submit" class="btn btn-secondary"><i class="mdi mdi-calendar"></i> Cetak</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><i class="mdi mdi-calendar"></i> Cetak Semua Aset Desa Yang Dihapus</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="radio">
                            <label><input type="radio" name="optradio"> Cetak Semua Aset Desa Yang Dihapus</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><i class="mdi mdi-calendar"></i> Cetak Semua Aset Desa Yang Dihapus</h5>
                </div>
                <form action="<?= base_url('index.php/user/assets/deleted/print') ?>" method="post">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="tgl_cetak" class="col-sm-3 col-form-label text-right">Tanggal Cetak</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="tgl_cetak" id="tgl_cetak" data-provide="datepicker" autocomplete="off" spellcheck="false" placeholder="dd-mm-yyyy" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="petugas" class="col-sm-3 col-form-label text-right">Nama Petugas/Pengurus</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="petugas" id="petugas" autocomplete="off" spellcheck="false" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger">Batal</button>
                        <button type="submit" class="btn btn-secondary"><i class="mdi mdi-printer"></i> Cetak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('[data-provide="datepicker"]').datepicker({
            format: 'dd-mm-yyyy',
        });
        
        $('.container-fluid > .row:last-child').hide();

        $(document).on('click', '[name="optradio"]', () => {
            $('.container-fluid > .row:first-child').fadeOut();
            $('.container-fluid > .row:last-child').fadeIn();
        });

        $(document).on('click', 'button:first-child', () => {
            $('[name="optradio"]').prop('checked', false);
            
            $('.container-fluid > .row:first-child').fadeIn();
            $('.container-fluid > .row:last-child').fadeOut();
        });
    });
</script>