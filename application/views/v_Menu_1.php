<div class="col-xs-12">
	<div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Data <?php echo $judul;?></h3>
            <div class="pull-right">
            	<a href="#" onclick="Tambah()" class="btn btn-primary btn-sm">Tambah Data </a>
            </div>
        </div>
	    <div class="box-body">
	    	<div class="table-responsive">    	
		        <table id="list-satu" class="table table-bordered table-hover">
		            <thead>
			            <tr>
			                <th>No</th>
			                <th>Nama</th>
			                <th>Jenis Kelamin</th>
			                <th>Agama</th>
			                <th>Tanggal</th>
			                <th width="100">Aksi</th>
			            </tr>
		            </thead>
		            <tbody>
		              
		            </tbody>
		            <tfoot>
			            <tr>
			                <th>No</th>
			                <th>Nama</th>
			                <th>Jenis Kelamin</th>
			                <th>Agama</th>
			                <th>Tanggal</th>
			                <th width="100">Aksi</th>
			            </tr>
		            </tfoot>
		        </table>
	       	</div>
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
            		<label class="control-label">Jenis Kelamin</label><br>
            		<div class="ra">
            		<input type="radio" name="gender" value="Pria" class="minimal"><span class="lbl"> Pria</span>
            		<input type="radio" name="gender" value="Wanita" class="minimal"><span class="lbl"> Wanita</span>
            		</div>
            	</div>
            	<div class="form-group">
            		<label class="control-label">Agama</label>
            		<select name="agama" required="" data-placeholder="--Pilih--" class="form-control select2">
            			<option value=""></option>
            			<option value="Islam">Islam</option>
            			<option value="Kristen">Kristen</option>
            			<option value="Hindu">Hindu</option>
            		</select>
            	</div>
            	<div class="form-group">
            		<label class="control-label">Tanggal Lahir</label>
            		<div><input type="text" autocomplete="off" placeholder="Tambahkan Tanggal" class="form-control datepicker" name="tanggal"></div>
            	</div>
            	<div class="form-group">
            		<label class="control-label"> Gambar</label>
            		<input type="file" name="gambar">
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

       table =  $("#list-satu").DataTable({
            initComplete: function() {
                var api = this.api();
                $('#baran input')
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
                "type": "POST"
            },
            columns: [
                {
                    "data": "id",
                    "orderable": false,
                    "searchable": false
                },
                {"data": "nama"},
                {"data": "gender"},
                {"data": "agama"},
                {"data": "tanggal"},
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

		$('#form').validate({
			errorElement: 'div',
			errorClass: 'help-block',
			focusInvalid: false,
			ignore: "",
			rules:{
				gender:{
					required:true
				},
				tanggal:{
					required:true,
					date:'required'
				}
			},
			messages: {
				nama: {
					required:"Nama tidak boleh Kosong."
				},
				agama:"Pilih Agama",
				gender:" ",
				tanggal:"Tambahkan Tanggal"

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
						console.log(data);
						console.log(data.success);
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

	});

    function reload(){
        table.ajax.reload(null,false);
    }
	function sweet(judul,text,tipe){
        Swal({
            title: judul,
            text: text,
            type: tipe
        });
    };

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
                $('[name="nama"]').val(data.nama);
                $('[name="tanggal"]').datepicker('update',data.tanggal);
                $('[name="gender"]').iCheck('update',data.gender);
                $('[name="agama"]').val(data.agama).trigger('change');
                $('#modal-form').modal('show');
                $('.modal-title').text('Ubah Data'); 
			},
            error: function (jqXHR, textStatus, errorThrown){
                sweet('Oops...','Data tidak dapat diambil','error');
                console.log(jqXHR, textStatus, errorThrown);
            }
		});
	}

	function Hapus(id){
		Swal({
            title: 'Ingin menghapus data?',
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya'
        }).then((result) => {
            if(result.value) {
                $.ajax({
                    url : "<?=base_url($this->uri->segment(1).'/Hapus')?>/"+id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data){
                        reload();
                        sweet('Dihapus !','Berhasil Hapus Data','success');
                    },
                    error: function (jqXHR, textStatus, errorThrown){
                        sweet('Oops...','Gagal Hapus Data','error');
                        console.log(jqXHR, textStatus, errorThrown);
                    }
                });
            }
        });

	}

	 $('#modal-form').on('show.bs.modal', function (e) {


	});
</script>