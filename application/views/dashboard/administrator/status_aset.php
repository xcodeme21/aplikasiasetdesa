<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$tahun = $this->m_admin->getAsetDihapus();
$tahun = array_unique(array_column($tahun, 'tahun'));
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><i class="mdi mdi-calendar"></i> Pilih Tahun Untuk Mencetak Status Penggunaan Aset Desa</h5>
                </div>
                <form action="<?= base_url('index.php/administrator/assets/status/print') ?>" method="post">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="tahun" class="col-sm-3 col-form-label text-right">Tahun</label>
                            <div class="col-sm-9">
                                <select class="form-control js-example-basic-single" name="tahun" id="tahun" required>
                                    <option value="">-- Pilih Tahun --</option>
                                    <?php for($i = 0; $i < count($tahun); $i++): ?>
                                    <option value="<?= $tahun[$i] ?>"><?= $tahun[$i] ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-end">
                        <button type="submit" class="btn btn-secondary"><i class="mdi mdi-calendar"></i> Cetak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>