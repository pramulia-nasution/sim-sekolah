<?php $masuk = $this->db->get_where('users',['id'=> $this->session->userdata('id')])->row_array();?>
<a href="<?=base_url('Beranda')?>" class="logo">
    <span class="logo-mini"><b>SD</b>M</span>
    <span class="logo-lg"><b>SDM</b>Kampa</span>
</a>
<nav class="navbar navbar-static-top">
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    </a>
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="<?php echo base_url('assets/dist/img/').$masuk['gambar']?>" class="user-image" alt="User Image">
                    <span class="hidden-xs"> <?php echo $masuk['name']?></span>
                </a>
                <ul class="dropdown-menu">
                    <li class="user-header">
                        <img src="<?php echo base_url('assets/dist/img/').$masuk['gambar']?>" class="img-circle" alt="User Image">
                        <p> <?php echo $masuk['name']?></p>
                    </li>
                    <li class="user-footer">
                        <div class="pull-left">
                          <!--   <a href="#" onclick="Password()" class="btn btn-default btn-flat">Password</a> -->
                        </div>
                        <div class="pull-right">
                            <a href="<?php echo base_url('Auth/logout')?>" class="btn btn-default btn-flat">Sign out</a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

 <!--   
<div class="modal fade"  id="modal-password">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"></h4>
            </div>
<?= form_open('','role = "form" id = "form"')?>
            <div class="modal-body">
                <input type="hidden" name="id" value="">
                <div class="form-group">
                    <label class="control-label"> Password Lama</label>
                    <div><input type="password" required="" placeholder="Password Lama" autocomplete="off" name="lama" class="form-control"></div>
                </div>
                <div class="form-group">
                    <label class="control-label"> Password Baru</label>
                    <div><input type="password" id="password" required="" placeholder="Password Baru" autocomplete="off" name="baru" class="form-control"></div>
                </div>
                <div class="form-group">
                    <label class="control-label"> Konfirmasi Password</label>
                    <div><input type="password" required="" placeholder="Konfirmasi Password" autocomplete="off" name="ulangi" class="form-control"></div>
                </div>
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="submit" id="simpan"  class="btn btn-primary">Ganti</button>
            </div>
<?= form_close()?>
        </div>
    </div>
</div>
 -->


<script type="text/javascript">

    $(document).ready(function(){

        $('#for').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            ignore: "",
            rules:{
                baru:{
                    minlength:6
                },
                ulangi:{
                    minlength:6,
                    equalTo:"#password"
                }
            },
            messages:{
                baru:{
                    required:"Password baru tidak boleh kosong",
                    minlength:"Minimal 6 Karakter"
                },
                ulangi:{
                    required: "Password Konfirmasi tidak boleh kosong",
                    minlength: "Minimal 6 Karakter",
                    equalTo: "Konfirmasi Password tidak cocok"
                },
                lama: "Password lama tidak boleh kosong"
            },
            highlight: function (e) {
                $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },
            success: function (e) {
                $(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
                $(e).remove();
            },
            errorPlacement: function (error, element) {
                if(element.is('input[type=radio]')) {
                    var controls = element.closest('div[class*="ra"]');
                    if(controls.find(':radio').length > 0) controls.append(error);
                    else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                }
                else if(element.is('.select2')) {
                    error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                }
                else error.insertAfter(element.parent());
            },
            submitHandler: function (form) {
                $('#simpan').text('Menyimpan...');
                $('#simpan').attr('disabled',true);
                var url,method;
                if (label == 'simpan'){
                    url = '<?=base_url('Auth/Simpan')?>';
                    method = 'Ubah';
                }
                var isi = $('#form').serialize();
                $.ajax({
                    url: url,
                    type:"POST",
                    data: isi,
                    dataType:"JSON",
                    success:function(data){
                        $('#modal-password').modal('hide');
                        $('#simpan').text('Simpan');
                        $('#simpan').attr('disabled',false);
                        if(data.status)
                            sweet('Sukses','Berhasil '+method+' Password','success');
                        else
                            sweet('Gagal ','Gagal '+method+' Password','error');
                    }
                });
            },
            invalidHandler: function (form) {}
        });


    });

        function sweet(judul,text,tipe){
        Swal({
            title: judul,
            text: text,
            type: tipe
        });
    }
    
    function Password(){

        label = 'simpan';
        $('#form')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty(); 
        $('#modal-password').appendTo("body").modal('show');
        $('.modal-title').text('Ganti Password');
    }

</script>
