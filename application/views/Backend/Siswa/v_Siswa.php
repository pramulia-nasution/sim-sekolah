<div class="col-xs-12">
	<div class="box box-primary">
        <div class="box-header">
            <div class="col-sm-2">
<?php $kls= $this->db->query("SELECT id,nama FROM kelas")->result() ?>
                <select name="kelas" id="kelas" data-placeholder="--Pilih kelas--" class="form-control select2">
                    <option value=""></option>
                <?php foreach ($kls as $key) {?>    
                    <option value="<?=$key->id?>"><?=$key->nama?></option>
                <?php } ?>
                </select>
            </div>
            <div class="pull-right">

                <div class="btn-group">
                    <a  class="btn btn-primary" onclick="Import()" ><i class="fa fa-download"></i> Import Data</a>
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?=base_url('excel/form.xlsx')?>">Format Data</a></li>
                    </ul>
                </div>

            	<a href="#" onclick="Tambah()" class="btn btn-primary">Tambah Data </a>
            </div>
        </div>
	    <div class="box-body">
	    	<div class="table-responsive">    	
		        <table id="list-data" class="table table-bordered table-hover">
		            <thead>
			            <tr>
                      <th style="width: 10px;">No</th>
                      <th>Nama Siswa</th>
                      <th>NIS</th>
                      <th>Jenis Kelamin</th>
                      <th>Wali</th>
                      <th>Status</th>
                      <th width="100">Aksi</th>
			            </tr>
		            </thead>
		            <tbody>
		              
		            </tbody>
		        </table>
	       	</div>
	    </div>
    </div>
</div>


<div class="modal fade" id="modal-import">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"></h4>
            </div>
<?= form_open('','role = "form" id = "form-import"')?>
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label"> File</label>
                    <input type="file" required="" accept=".xlsx" name="file">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="submit" id="import" class="btn btn-primary">Import</button>
            </div>
<?= form_close()?>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modal-form">
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
                    <label class="control-label"> Nama Lengkap</label>
                    <div><input type="text" required="" placeholder="Nama Lengkap" autocomplete="off" name="nama" class="form-control"></div>
                </div>
                <div class="form-group">
                    <label class="control-label"> NIS</label>
                    <div><input type="text" onkeypress="return Angka(this)" required="" placeholder="NIP/NIK" autocomplete="off" name="nis" class="form-control"></div>
                </div>
                <div class="form-group">
                    <div class="ra">
                        <label class="control-label">Jenis Kelamin</label><br>
                        <input type="radio" class="minimal"  name="gender" value="Pria" ><span class="lbl"> Pria</span>
                        <input type="radio"   name="gender" class="minimal" value="Wanita"><span class="lbl"> Wanita</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label"> Tempat Lahir</label>
                    <div><input type="text" required="" placeholder="Tempat Lahir" autocomplete="off" name="tempat" class="form-control"></div>
                </div>
                <div class="form-group">
                    <label class="control-label"> Tanggal Lahir</label>
                    <div><input type="text" required="" placeholder="Tanggal Lahir" autocomplete="off" name="tanggal" class="form-control datepicker"></div>
                </div>
                <div class="form-group">
                    <label class="control-label"> Nama Wali</label>
                    <div><input type="text" required="" placeholder="Nama Wali" autocomplete="off" name="wali" class="form-control"></div>
                </div>
                <div class="form-group">
                    <label class="control-label"> Alamat</label>
                    <div><input type="text" required="" placeholder="Alamat" autocomplete="off" name="alamat" class="form-control"></div>
                </div>
                <div class="form-group">
                    <label class="control-label">Kelas</label>
                    <select name="kelas" required="" data-placeholder="--Pilih--" class="form-control select2">
                        <option value="">--Pilih--</option>
                            <?php foreach ($kls as $key) {?>    
                                <option value="<?=$key->id?>"><?=$key->nama?></option>
                            <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Status</label>
                    <select name="status" required="" data-placeholder="--Pilih--" class="form-control">
                        <option value="">--Pilih--</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Cuti">Cuti</option>
                        <option value="Berhenti">Berhenti</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="submit" id="simpan"  class="btn btn-primary">Simpan</button>
            </div>
<?= form_close()?>
        </div>
    </div>
</div>


<script type="text/javascript">

	var label;
	var table;
	$(document).ready(function(){
		$.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings){
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        };

         load_data ();

    function load_data(is_kelas){

       table =  $("#list-data").DataTable({
            initComplete: function() {
                var api = this.api();
                $('#list-data input')
                    .off('.DT')
                    .on('keyup.DT', function(e) {
                        api.search(this.value).draw();
                    });
            },
            oLanguage: {
                sSearch       :"<i class='fa fa-search fa-fw'></i> Cari: ",
                sLengthMenu   :"Tampilkan _MENU_ data",
                sInfo         :"Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                sInfoFiltered :"(disaring dari _MAX_ total data)", 
                sZeroRecords  :"Oops..data kosong", 
                sEmptyTable   :"Data kosong.", 
                sInfoEmpty    :"Menampilkan 0 sampai 0 data",
                sProcessing   :"Sedang memproses...", 
                oPaginate: {
                    sPrevious :"Sebelumnya",
                    sNext     :"Selanjutnya",
                    sFirst    :"Pertama",
                    sLast     :"Terakhir"
                }
            },
            processing: true,
            serverSide: true,
            ajax: {
                "url": "<?= base_url().$this->uri->segment(1).'/getData'?>",
                "type": "POST",
                "data":{is_kelas : is_kelas }
            },
            columns: [
                {
                    "data": "id",
                    "orderable": false,
                    "searchable": false
                },
                {"data": "name"},
                {"data": "nis"},
                {"data": "sex"},
                {"data": "wali"},
                {"data": "status"},
                {
                    "data": "view",
                    "orderable": false,
                    "searchable": false
                }
            ],
            order: [[1, 'asc']],
            rowId: function(a){
                return a;
            },
            rowCallback: function(row, data, iDisplayIndex) {
                var info = this.fnPagingInfo();
                var page = info.iPage;
                var length = info.iLength;
                var index = page * length + (iDisplayIndex + 1);
                $('td:eq(0)', row).html(index);
            }
        });
    }

            $('#form-import').on('submit',function(event){

            event.preventDefault();
            $('#import').text('Mengimport..');
            $('#import').attr('disabled',true);

            $.ajax({
                url: '<?=base_url($this->uri->segment(1).'/import')?>',
                method:"POST",
                data:new FormData(this),
                cache:false,
                contentType:false,
                processData:false,
                success:function(data){
                    $('#modal-import').modal('hide');
                    reload();
                    sweet('Sukses','Berhasil Import Data','success');
                    $('#import').text('Import');
                    $('#import').attr('disabled',false);
                },
                error: function (jqXHR, textStatus, errorThrown){
                    sweet('Oops...','Data gagal di import','error');
                }
            });
        });

		$('#form').validate({
			errorElement: 'div',
			errorClass: 'help-block',
			focusInvalid: false,
			ignore: "",
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
				 	url = '<?=base_url($this->uri->segment(1).'/Simpan')?>';
				 	method = 'Tambah';
				}
				else {
				 	url = '<?=base_url($this->uri->segment(1).'/Ubah')?>';
				 	method = 'Ubah';
				}
				var isi = new FormData($('#form')[0]);
				$.ajax({
					url: url,
					type:"POST",
					data: isi,
					contentType:false,
					processData:false,
					dataType:"JSON",
					success:function(data){
						$('#modal-form').modal('hide');
						reload();
						sweet('Di '+method,'Berhasil '+method+' Data','success');
						$('#simpan').text('Simpan');
		 				$('#simpan').attr('disabled',false);
					}
				});
			},
			invalidHandler: function (form) {}
		});


        $('#kelas').on('change',function(){

            var kelas = $(this).val();
        $('#list-data').DataTable().destroy();

            if (kelas != ''){
                load_data(kelas);
            }
            else{
                load_data();
            }
       });

	});

    function reload(){
        table.ajax.reload(null,false);
    }

    function Import(){
        label = 'import';
        $('#form-import')[0].reset();
        $('#modal-import').modal('show');
        $('.modal-title').text('Import Data');
    }

	function sweet(judul,text,tipe){
        Swal({
            title: judul,
            text: text,
            type: tipe
        });
    }

	function Tambah(){
		label = 'simpan';
		$('#form')[0].reset();
		$('.form-group').removeClass('has-error');
		$('.help-block').empty(); 
		$('#modal-form').modal('show');
		$('.modal-title').text('Tambah Data');
	}

	function Ubah(id){
		label = 'ubah';
		$('#form')[0].reset();
		$('.form-group').removeClass('has-error');
		$('.help-block').empty();

		$.ajax({
			url: "<?=base_url($this->uri->segment(1).'/edit/')?>"+id,
			type:"GET",
			dataType:"JSON",
			success:function(data){
				$('[name="id"]').val(data.id);
                $('[name="nama"]').val(data.name);
                $('[name="nis"]').val(data.nis);
                $('[name="wali"]').val(data.wali);
                $('[name="alamat"]').val(data.alamat);
                $('[name="tempat"]').val(data.tempat);
                $('[name="tanggal"]').val(data.tanggal);
                $('[name="status"]').val(data.status).trigger('change');
                $('[name="gender"]').iCheck('update',data.sex);
                $('#modal-form').modal('show');
                $('.modal-title').text('Ubah Data'); 
			},
            error: function (jqXHR, textStatus, errorThrown){
                sweet('Oops...','Data tidak dapat diambil','error');
            }
		});
	}
</script>