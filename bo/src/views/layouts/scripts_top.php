

<script src="<?php echo BASEURL . 'web/assets/js/libs/jquery-3.1.1.min.js'; ?>"></script>
<script src="<?php echo BASEURL . 'web/bootstrap/js/popper.min.js'; ?>"></script>
<script src="<?php echo BASEURL . 'web/bootstrap/js/bootstrap.min.js'; ?>"></script>

<?php
if (isset($_SESSION['INF'])) {
?>
<script src="<?=WEB_PATH?>assets/js/loader.js"></script>
<script src="<?=WEB_PATH?>plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="<?=WEB_PATH?>assets/js/app.js"></script>

<script>
        $(document).ready(function() {
            App.init();
        });
</script>

<?php }else{?>
<script src="<?php echo BASEURL . 'web/assets/js/authentication/form-1.js'; ?>"></script>
<?php } ?>

<script src="<?=WEB_PATH?>assets/js/custom.js"></script>
<script src="<?=WEB_PATH?>plugins/sweetalerts/sweetalert2.min.js"></script>
<script src="<?=WEB_PATH?>plugins/sweetalerts/custom-sweetalert.js"></script>
<script src="<?=WEB_PATH?>plugins/table/datatable/datatables.js"></script>
<script src="<?=WEB_PATH?>plugins/flatpickr/flatpickr.js"></script>
<script src="<?=WEB_PATH?>plugins/select2/select2.min.js"></script>
<!-- <script src="<?=WEB_PATH?>plugins/flatpickr/custom-flatpickr.js"></script> -->




