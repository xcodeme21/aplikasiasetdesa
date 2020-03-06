<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
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
                    <h5 class="card-title"><a href="<?= base_url('index.php/user') ?>"><i class="mdi mdi-arrow-left"></i> Daftar Aset Desa Tahun <?= $tahun ?></a></h5>
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addInventarisModal"><i class="mdi mdi-plus-box"></i> Aset Desa</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-xs table-responsive-sm" id="datatable">
                            <thead>
                                <tr>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Merk</th>
                                    <th>Asal Perolehan</th>
                                    <th>Tgl Pembelian</th>
                                    <th>UE (Bulan)</th>
                                    <th>Harga Beli</th>
                                    <th>Total Penyusutan</th>
                                    <th>Nilai Buku</th>
                                    <th>Banyaknya</th>
                                    <th>Penggunaan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                foreach($inventaris as $data):
                                    if($data->kategori_inventaris === 'tnh-desa') {
                                        $nilai_buku = number_format($data->harga_beli, 0, '', '.');
                                        $penyusutan = 0;
                                    } else {
                                        $D = (double) $data->harga_beli / (int) $data->umur;
                            
                                        $first  = date('d-m-Y', strtotime($data->tgl_beli));
                                        $second = date('d-m-Y');
                                        $diff   = date_diff(date_create($first), date_create($second));
                                        $years  = (int) $diff->format('%y') * 12;
                                        $F      = ((int) $diff->format('%m') + $years) * $D;
                            
                                        if((int) date('d', strtotime($data->tgl_beli)) <= 15 && (int) date('d') <= 15) {
                                            $nilai_buku = ((double) $data->harga_beli - $F);
                                        } elseif((int) date('d', strtotime($data->tgl_beli)) <= 15 && (int) date('d') >= 15) {
                                            $nilai_buku = ((double) $data->harga_beli - $F);
                                        } elseif((int) date('d', strtotime($data->tgl_beli)) >= 15 && (int) date('d') <= 15) {
                                            $nilai_buku = ((double) $data->harga_beli - $F );
                                        } elseif((int) date('d', strtotime($data->tgl_beli)) >= 15 && (int) date('d') >= 15) {
                                            $nilai_buku = ((double) $data->harga_beli - $F + $D);
                                        }

                                        $penyusutan = (double) $data->harga_beli - (double) $nilai_buku;
                                    }
                                ?>
                                <tr>
                                    <td><?= $data->kode_barang ?></td>
                                    <td><?= $data->nama_barang ?></td>
                                    <td><?= $data->merk ?></td>
                                    <td><?= $data->asal_perolehan ?></td>
                                    <td><?= date('d-m-Y', strtotime($data->tgl_beli)) ?></td>
                                    <td><?= (int) $data->umur === 0 ? '-' : (int) $data->umur ?></td>
                                    <td><?= number_format($data->harga_beli, 0, '', '.') ?></td>
                                    <td><?= number_format(round($penyusutan), 0, '', '.') ?></td>
                                    <td><?= number_format(round($nilai_buku), 0, '', '.') ?></td>
                                    <td><?= $data->banyak ?></td>
                                    <td><?= $data->penggunaan ?></td>
                                    <td><button type="button" class="btn btn-secondary btn-sm" onclick="return doEdit('<?= $data->id ?>');"><i class="mdi mdi-pencil"></i></button></td>
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

<div class="modal fade" id="addInventarisModal">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Inventaris</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="<?= base_url('index.php/user/doAddAsset') ?>" method="post">
                <div class="modal-body" style="max-height: calc(100vh - 300px); overflow-y: auto;">
                    <div class="form-group row">
                        <label for="tipe_inventaris" class="col-sm-4 col-form-label text-right">Tipe Inventaris</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="tipe_inventaris" required>
                                <option value="">Pilih Inventaris</option>
                                <option value="tanah">TANAH</option>
                                <option value="peralatan">PERALATAN DAN MESIN</option>
                                <option value="gedung">GEDUNG DAN BANGUNAN</option>
                                <option value="jalan">JALAN, IRIGASI DAN JARINGAN</option>
                                <option value="ATL">ASET TETAP LAINNYA</option>
                                <option value="KDP">KONSRUKSI DALAM PENGERJAAN</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row d-none" id="form_kategori_inventaris">
                        <label for="kategori_inventaris" class="col-sm-4 col-form-label text-right">Kategori Inventaris</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="kategori_inventaris"></select>
                        </div>
                    </div>
                    <div class="form-group row d-none" id="form_nama_inventaris">
                        <label for="nama_inventaris" class="col-sm-4 col-form-label text-right">Nama Inventaris</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="nama_inventaris"></select>
                        </div>
                        <input type="hidden" name="id_barang" id="id_barang">
                    </div>
                    <div class="form-group row">
                        <label for="merk" class="col-sm-4 col-form-label text-right">Merk</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="merk" id="merk" autocomplete="off" spellcheck="false" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="umur" class="col-sm-4 col-form-label text-right">Umur</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="umur" id="umur" autocomplete="off" spellcheck="false" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tahun" class="col-sm-4 col-form-label text-right">Aset Data Tahun</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="tahun" id="tahun" autocomplete="off" spellcheck="false" placeholder="2010" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="harga_beli" class="col-sm-4 col-form-label text-right">Harga Beli</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="harga_beli" id="harga_beli" autocomplete="off" spellcheck="false" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="id_asal" class="col-sm-4 col-form-label text-right">Asal Perolehan</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="id_asal" id="id_asal" required>
                                <option value=""></option>
                                <?php foreach($perolehan as $data): ?>
                                <option value="<?= $data->id ?>"><?= $data->keterangan ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tgl_beli" class="col-sm-4 col-form-label text-right">Tanggal Pembelian</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="tgl_beli" id="tgl_beli" data-provide="datepicker" autocomplete="off" spellcheck="false" placeholder="dd-mm-yyyy" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="banyak" class="col-sm-4 col-form-label text-right">Banyaknya</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="banyak" id="banyak" autocomplete="off" spellcheck="false" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="satuan" class="col-sm-4 col-form-label text-right">Satuan</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="satuan" id="satuan" required>
                                <option value="KG">KG</option>
                                <option value="TON">TON</option>
                                <option value="L (Liter)">L (Liter)</option>
                                <option value="G (Gallon)">G (Gallon)</option>
                                <option value="M3 (Meter Kubik)">M3 (Meter Kubik)</option>
                                <option value="Ha (Hektar)">Ha (Hektar)</option>
                                <option value="M2 (Meter Persegi)">M2 (Meter Persegi)</option>
                                <option value="Buah">Buah</option>
                                <option value="Batang">Batang</option>
                                <option value="Botol">Botol</option>
                                <option value="Doos">Doos</option>
                                <option value="Zak">Zak</option>
                                <option value="Ekor">Ekor</option>
                                <option value="Stel">Stel</option>
                                <option value="Rim">Rim</option>
                                <option value="Unit">Unit</option>
                                <option value="Pucuk">Pucuk</option>
                                <option value="Set">Set</option>
                                <option value="Lembar">Lembar</option>
                                <option value="Box">Box</option>
                                <option value="Pasang">Pasang</option>
                                <option value="Roll">Roll</option>
                                <option value="Lusin/Gross">Lusin/Gross</option>
                                <option value="Eksemplar">Eksemplar</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="penggunaan" class="col-sm-4 col-form-label text-right">Penggunaannya</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="penggunaan" id="penggunaan" cols="30" rows="5" required></textarea>
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

<div class="modal fade" id="editInventarisModal">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Perbaharui Data</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="<?= base_url('index.php/user/doEditAsset') ?>" method="post">
                <input type="hidden" name="id" id="id">
                <div class="modal-body" style="max-height: calc(100vh - 300px); overflow-y: auto;">
                    <div class="form-group row">
                        <label for="tipe_inventaris" class="col-sm-4 col-form-label text-right">Tipe Inventaris</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="tipe_inventaris" required>
                                <option value="">Pilih Inventaris</option>
                                <option value="tanah">TANAH</option>
                                <option value="peralatan">PERALATAN DAN MESIN</option>
                                <option value="gedung">GEDUNG DAN BANGUNAN</option>
                                <option value="jalan">JALAN, IRIGASI DAN JARINGAN</option>
                                <option value="ATL">ASET TETAP LAINNYA</option>
                                <option value="KDP">KONSRUKSI DALAM PENGERJAAN</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" id="form_kategori_inventaris">
                        <label for="kategori_inventaris" class="col-sm-4 col-form-label text-right">Kategori Inventaris</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="kategori_inventaris"></select>
                        </div>
                    </div>
                    <div class="form-group row" id="form_nama_inventaris">
                        <label for="nama_inventaris" class="col-sm-4 col-form-label text-right">Nama Inventaris</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="nama_inventaris"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="merk" class="col-sm-4 col-form-label text-right">Merk</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="merk" id="merk" autocomplete="off" spellcheck="false" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="umur" class="col-sm-4 col-form-label text-right">Umur</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="umur" id="umur" autocomplete="off" spellcheck="false" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tahun" class="col-sm-4 col-form-label text-right">Aset Data Tahun</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="tahun" id="tahun" autocomplete="off" spellcheck="false" placeholder="2010" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="harga_beli" class="col-sm-4 col-form-label text-right">Harga Beli</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="harga_beli" id="harga_beli" autocomplete="off" spellcheck="false" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="id_asal" class="col-sm-4 col-form-label text-right">Asal Perolehan</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="id_asal" id="id_asal" required>
                                <option value=""></option>
                                <?php foreach($perolehan as $data): ?>
                                <option value="<?= $data->id ?>"><?= $data->keterangan ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tgl_beli" class="col-sm-4 col-form-label text-right">Tanggal Pembelian</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="tgl_beli" id="tgl_beli" data-provide="datepicker" autocomplete="off" spellcheck="false" placeholder="dd-mm-yyyy" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="banyak" class="col-sm-4 col-form-label text-right">Banyaknya</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="banyak" id="banyak" autocomplete="off" spellcheck="false" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="satuan" class="col-sm-4 col-form-label text-right">Satuan</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="satuan" id="satuan" required>
                                <option value="KG">KG</option>
                                <option value="TON">TON</option>
                                <option value="L (Liter)">L (Liter)</option>
                                <option value="G (Gallon)">G (Gallon)</option>
                                <option value="M3 (Meter Kubik)">M3 (Meter Kubik)</option>
                                <option value="Ha (Hektar)">Ha (Hektar)</option>
                                <option value="M2 (Meter Persegi)">M2 (Meter Persegi)</option>
                                <option value="Buah">Buah</option>
                                <option value="Batang">Batang</option>
                                <option value="Botol">Botol</option>
                                <option value="Doos">Doos</option>
                                <option value="Zak">Zak</option>
                                <option value="Ekor">Ekor</option>
                                <option value="Stel">Stel</option>
                                <option value="Rim">Rim</option>
                                <option value="Unit">Unit</option>
                                <option value="Pucuk">Pucuk</option>
                                <option value="Set">Set</option>
                                <option value="Lembar">Lembar</option>
                                <option value="Box">Box</option>
                                <option value="Pasang">Pasang</option>
                                <option value="Roll">Roll</option>
                                <option value="Lusin/Gross">Lusin/Gross</option>
                                <option value="Eksemplar">Eksemplar</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="penggunaan" class="col-sm-4 col-form-label text-right">Penggunaannya</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="penggunaan" id="penggunaan" cols="30" rows="5" required></textarea>
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

<script>
    function doEdit(id) {
        $.post('<?= base_url('index.php/user/doGetInventaris') ?>', {id: id}, (data) => {
            $('#editInventarisModal').modal('show');

            $('#editInventarisModal form #id').val(data.data.id);

            let kategori_inventaris = data.data.kategori_inventaris.split('-')[0];
            if(kategori_inventaris === 'tnh') {
                $('#editInventarisModal form #tipe_inventaris option[value="tanah"]').prop('selected', true);
                $('#editInventarisModal form #kategori_inventaris').append('<option value="tnh-desa">Tanah Desa</option>');
            } else if(kategori_inventaris === 'alkrt') {
                $('#editInventarisModal form #tipe_inventaris option[value="peralatan"]').prop('selected', true);
                $('#editInventarisModal form #kategori_inventaris').append('<option value="albsr-desa">ALAT BESAR</option><option value="alakt-desa">ALAT ANGKUTAN</option><option value="abau-desa">ALAT BENGKEL DAN ALAT UKUR</option><option value="alp-desa">ALAT PERTANIAN</option><option value="alkrt-desa">ALAT KANTOR &amp; RUMAH TANGGA</option><option value="alskp-desa">ALAT STUDIO, KOMUNIKASI DAN PEMANCAR</option><option value="alkom-desa">KOMPUTER</option><option value="alpgb-desa">ALAT PENGEBORAN</option><option value="alppp-desa">ALAT PRODUKSI, PENGOLAHAN DAN PEMURNIAN</option><option value="alpor-desa">PERALATAN OLAH RAGA</option>');
            }

            $.post('<?= base_url('index.php/user/doGetBarang') ?>', {kategori_inventaris: data.data.kategori_inventaris}, (kdata) => {
                if(!kdata.error) {
                    const result = [];
                    const map    = new Map();

                    for(const item of kdata.data) {
                        if(!map.has(item.nama_barang)) {
                            map.set(item.nama_barang, true);

                            result.push({
                                id: item.id,
                                kode_barang: item.kode_barang,
                                nama_barang: item.nama_barang
                            });
                        }
                    }

                    result.map((v, k) => {
                        $('#editInventarisModal form #nama_inventaris').append('<option value="' + v.id + '" data-kode-barang="' + v.kode_barang + '">' + v.nama_barang + '</option>');
                    });
                }
            });

            $('#editInventarisModal form #kategori_inventaris option[value="' + data.data.kategori_inventaris + '"]').prop('selected', true);

            setTimeout(() => {
                $('#editInventarisModal form #nama_inventaris option[value="' + data.data.kode_barang + '"]').prop('selected', true);
                $('#editInventarisModal form #nama_barang').val(data.data.nama_barang);
            }, 100);

            $('#editInventarisModal form #merk').val(data.data.merk);

            //let umur = parseInt(data.data.umur) / 12;
            $('#editInventarisModal form #umur').val(data.data.umur);

            $('#editInventarisModal form #tahun').val(data.data.tahun);
            $('#editInventarisModal form #harga_beli').val(data.data.harga_beli);
            $('#editInventarisModal form #id_asal option[value="' + data.data.id_asal + '"]').prop('selected', true);

            let tgl_beli = data.data.tgl_beli.split('-');
            let tgl     = tgl_beli[2];
            let bulan   = tgl_beli[1];
            let tahun   = tgl_beli[0];
                tgl_beli = tgl + '-' + bulan + '-' + tahun;
            $('#editInventarisModal form #tgl_beli').val(tgl_beli);

            let banyak = data.data.banyak.split(' ')[0];
            let satuan = data.data.banyak.split(' ')[1];
            $('#editInventarisModal form #banyak').val(banyak);
            $('#editInventarisModal form #satuan option[value="' + satuan + '"]').prop('selected', true);

            $('#editInventarisModal form #penggunaan').val(data.data.penggunaan);
        });
    }

    $(function() {
        $('[data-provide="datepicker"]').datepicker({
            format: 'dd-mm-yyyy',
        });

        $(document).on('change', '#tipe_inventaris', function() {
            let tipe_inventaris = $(this).val();

            $('#form_kategori_inventaris').removeClass('d-none');
            $('#kategori_inventaris').attr('required', 'required');
            $('#kategori_inventaris').html('<option value="">Pilih Kategori</option>');
            $('#nama_inventaris').removeAttr('required');
            $('#form_nama_inventaris').addClass('d-none');
            
            if(tipe_inventaris === 'tanah') {
                $('#kategori_inventaris').append('<option value="tnh-desa">Tanah Desa</option>');
            } else if(tipe_inventaris === 'peralatan') {
                $('#kategori_inventaris').append('<option value="albsr-desa">ALAT BESAR</option><option value="alakt-desa">ALAT ANGKUTAN</option><option value="abau-desa">ALAT BENGKEL DAN ALAT UKUR</option><option value="alp-desa">ALAT PERTANIAN</option><option value="alkrt-desa">ALAT KANTOR &amp; RUMAH TANGGA</option><option value="alskp-desa">ALAT STUDIO, KOMUNIKASI DAN PEMANCAR</option><option value="alkom-desa">KOMPUTER</option><option value="alpgb-desa">ALAT PENGEBORAN</option><option value="alppp-desa">ALAT PRODUKSI, PENGOLAHAN DAN PEMURNIAN</option><option value="alpor-desa">PERALATAN OLAH RAGA</option>')
            } else if(tipe_inventaris === 'gedung') {
                $('#kategori_inventaris').append('<option value="bangg-desa">BANGUNAN GEDUNG</option><option value="bangm-desa">MONUMEN</option>');
            } else if(tipe_inventaris === 'jalan') {
                $('#kategori_inventaris').append('<option value="jijjj-desa">JALAN DAN JEMBATAN</option><option value="jijba-desa">BANGUNAN AIR</option><option value="jiji-desa">INSTALASI</option><option value="jijjr-desa">JARINGAN</option>');
            } else if(tipe_inventaris === 'ATL') {
                $('#kategori_inventaris').append('<option value="bhn-desa">BAHAN PERPUSTAKAAN</option><option value="bbkk-desa">BARANG BERCORAK KESENIAN/KEBUDAYAAN/OLAHRAGA</option><option value="hwn-desa">HEWAN</option><option value="ikn-desa">IKAN</option><option value="tnm-desa">TANAMAN</option><option value="atdr-desa">ASET TETAP DALAM RENOVASI</option>');
            } else if(tipe_inventaris === 'KDP') {
                $('#kategori_inventaris').append('<option value="kdp-desa">KONSTRUKSI DALAM PENGERJAAN</option>');
            }
        });

        $(document).on('change', '#kategori_inventaris', function() {
            let kategori_inventaris = $(this).val();
            
            $('#form_nama_inventaris').removeClass('d-none');
            $('#nama_inventaris').attr('required', 'required');
            $('#nama_inventaris').html('<option value="">Nama Inventaris</option>');

            $.post('<?= base_url('index.php/user/doGetBarang') ?>', {kategori_inventaris: kategori_inventaris}, (data) => {
                if(!data.error) {
                    const result = [];
                    const map    = new Map();

                    for(const item of data.data) {
                        if(!map.has(item.nama_barang)) {
                            map.set(item.nama_barang, true);

                            result.push({
                                id: item.id,
                                kode_barang: item.kode_barang,
                                nama_barang: item.nama_barang
                            });
                        }
                    }

                    result.map((v, k) => {
                        $('#nama_inventaris').append('<option value="' + v.id + '" data-kode-barang="' + v.kode_barang + '">' + v.nama_barang + '</option>');
                    });
                }
            });

            $(document).on('change', '#nama_inventaris', function() {
                let id_barang   = $(this).val();
                let kode_barang = $('#nama_inventaris option:selected').attr('data-kode-barang');
                let umur;

                if(kode_barang === '30101' || kode_barang === '30301' || kode_barang === '30203' || kode_barang === '30603' || kode_barang === '30801' || kode_barang === '30802' || kode_barang === '30901' || kode_barang === '30902') {
		            umur = 10;
		        } else if(kode_barang === '30102') {
		            umur = 8;
		        } else if(kode_barang === '30103' || kode_barang === '30201') {
		            umur = 7;
		        } else if(kode_barang === '30202') {
		            umur = 2;
		        } else if(kode_barang === '30204' || kode_barang === '31001') { 
		            umur = 3;
		        } else if(kode_barang === '30302' || kode_barang === '30501' || kode_barang === '30502' || kode_barang === '30601' || kode_barang === '30602' || kode_barang === '30303') {
		            umur = 5;
		        } else if(kode_barang === '30401' || kode_barang === '30701' || kode_barang === '30702') {
		            umur = 4;
		        } else if( kode_barang === '30604' || kode_barang === '30903') {
		            umur = 15;
		        } else if(kode_barang === '50201' || kode_barang === '50202' || kode_barang === '40101' || kode_barang === '40201') {
		            umur = 50;
		        } else if(kode_barang === '50203') {
		            umur = 25;
		        } else if(kode_barang === '50204' || kode_barang === '50201') {
		            umur = 10;
		        } else if(kode_barang === '50205') {
		            umur = 30;
		        } else if(kode_barang === '50206' || kode_barang === '50207') {
		            umur = 40;
		        } else if(kode_barang === '50301' || kode_barang === '50302') {
		            umur = 30;
		        } else if(kode_barang === '50303' || kode_barang === '50304') {
		            umur = 10;
		        } else if(kode_barang === '50307') {
		            umur = 5;
		        } else if(kode_barang === '50305' || kode_barang === '50306') {
		            umur = 40;
		        } else if( kode_barang === '50404' || kode_barang === '50401') {
		            umur = 30;
		        } else if(kode_barang === '50402') {
		            umur = 40;
		        } else if(kode_barang === '50403') {
		            umur = 20;
		        } else {
		            umur = 0;
		        }

                $('#id_barang').val(id_barang);
                $('#umur').val(umur);
            });
        });
    });
</script>