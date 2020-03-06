<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

                <div class="modal fade" id="ubahPasswordModal">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Perbaharui Password</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            </div>
                            <form action="<?= base_url('index.php/user/doUpdatePassword') ?>" method="post">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="cur_password">Password Saat Ini</label>
                                        <input type="password" class="form-control" name="cur_password" id="cur_password" autocomplete="off" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="new_password">Password Baru</label>
                                        <input type="password" class="form-control" name="new_password" id="new_password" autocomplete="off" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="con_password">Konfirmasi Password Baru</label>
                                        <input type="password" class="form-control" name="con_password" id="con_password" autocomplete="off" required>
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

                <div class="modal fade" id="keluarModal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Anda Yakin Ingin Keluar Dari Program Ini?</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                                <button type="button" class="btn btn-success" onclick="return window.location='<?= base_url('index.php/user/doLogout') ?>';">Ya</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer">
                <div class="copyright">
                    <p>Copyright © e-Aset Desa</p>
                </div>
            </div>
        </div>
        <script src="assets/vendor/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <script src="assets/vendor/quixnav/quixnav.min.js"></script>
        <script src="assets/js/quixnav-init.js"></script>
        <script src="assets/js/custom.min.js"></script>
        <script src="assets/vendor/datatables/js/jquery.dataTables.min.js"></script>
        <script src="assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
        <script src="assets/vendor/select2/dist/js/select2.full.min.js"></script>
        <script src="assets/vendor/sweetalert2/dist/sweetalert2.min.js"></script>
        <script>
            $(function() {
                $('#datatable').DataTable({
                    responsive: true
                });
                
                $('.js-example-basic-single').select2();
            });
        </script>
    </body>
</html>