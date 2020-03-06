<?php
defined('BASEPATH') OR exit('No direct script access allowed');

for($i = 2017; $i <= 2036; $i++) {
    $tahun[] = $i;
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><i class="mdi mdi-calendar"></i> Pilih Tahun Untuk Hapus Aset Desa</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <select class="form-control col-lg-3 js-example-basic-single" name="tahun" id="tahun" required>
                            <?php for($i = 0; $i < count($tahun); $i++): ?>
                            <option value="<?= $tahun[$i] ?>"><?= $tahun[$i] ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-end">
                    <button type="button" class="btn btn-success" onclick="return window.location = '<?= base_url("index.php/user/assets/delete/view/{$tahun[0]}") ?>';"><i class="mdi mdi-checkbox-marked-outline"></i> OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $(document).on('change', '#tahun', function() {
            let tahun = parseInt($(this).val());

            $('button').attr('onclick', 'return window.location = "<?= base_url('index.php/user/assets/delete/view') ?>/' + tahun + '"');
        }); 
    });
</script>