<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title; ?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="description" content=" " /> 
        <meta name="keywords" content=" " />
         <link rel="shorcut icon" type="text/css" href="<?php echo base_url('assets/dist/img/j.png')?>">

        <!-- File Css -->
        <?php echo @$_css; ?>
        <script src="<?php echo base_url('assets/')?>bower_components/jquery/dist/jquery.min.js"></script>
    </head>
    <SCRIPT language=Javascript>
        function Angka(evt){
            var charCode = (evt.which) ? evt.which : event.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }
    </SCRIPT>

<?php $masuk = $this->db->get_where('users',['id'=> $this->session->userdata('id')])->row_array();?>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                 <!-- Header -->
                <?php echo @$_header; ?>
            </header>
            <aside class="main-sidebar">
                <section class="sidebar">
                    <div class="user-panel">
                        <div class="pull-left image">
                          <img src="<?php echo base_url('assets/dist/img/').$masuk['gambar']?>" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                          <p><?= $masuk['name']?></p>
                        </div>
                    </div>
                    <!-- Sidebar -->
                    <?php echo @$_sidebar; ?>
                </section>
            </aside>
            <div class="content-wrapper">
                <section class="content-header">
                    <h1> <i class="<?php echo $icon;?>"></i> Data <?php echo $judul; ?></h1>
                    <?php echo $this->breadcrumb->output(); ?>
                </section>
                <section class="content">
                    <div class="row">
                        <!-- Content -->
                        <?php echo @$_content; ?>        
                    </div>      
                </section>
            </div>
            <!-- Footer -->
            <?php echo @$_footer;?>
          <div class="control-sidebar-bg"></div>
      </div>
      <!-- File Js -->
      <?php echo @$_js; ?>

    <script>
        $(document).ready(function () {

            $('.select2').css('width','100%').select2({allowClear:false})
                .on('change', function(){
                    $(this).closest('form').validate().element($(this));
            });

            $('.datepicker').datepicker({
                autoclose:true,format: "yyyy-mm-dd",
                todayHighlight: true,})
                .on('changeDate', function(ev) {
                    $(this).closest('form').validate().element($(this));
            });

        $('.tabel').DataTable({
            "oLanguage": {
                "sSearch"       :"<i class='fa fa-search fa-fw'></i> Cari: ",
                "sLengthMenu"   :"Tampilkan _MENU_ data",
                "sInfo"         :"Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "sInfoFiltered" :"(disaring dari _MAX_ total data)", 
                "sZeroRecords"  :"Data Pencarian kosong", 
                "sEmptyTable"   :"Data kosong", 
                "sInfoEmpty"    :"Menampilkan 0 sampai 0 data",
                "sProcessing"   :"Sedang memproses...", 
                "oPaginate": {
                    "sPrevious" :"Sebelumnya",
                    "sNext"     :"Selanjutnya",
                    "sFirst"    :"Pertama",
                    "sLast"     :"Terakhir"
                }
            },
            "processing": true
        });

            $('.sidebar-menu').tree();

            $('input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass   : 'iradio_minimal-blue'
            });

                            $(document).one('ajaxloadstart.page', function(e) {
                    //in ajax mode, remove remaining elements before leaving page
                    $('[class*=select2]').remove();
                });

        });
        </script>
    </body>
</html>
