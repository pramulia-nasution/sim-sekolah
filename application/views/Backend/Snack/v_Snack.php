<script type="text/javascript">
    
        function TotalBiaya(){

            var biaya = $('#harga').val();
            var hari = $('#total').val() || 0;

            var sum = biaya * hari;

            $('#seluruh').val(sum);
        }

</script>

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
                      <th width="150">Aksi</th>
			            </tr>
		            </thead>
		            <tbody>
		              
		            </tbody>
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
            	<input type="hidden" name="id_siswa" value="">
                 <div class="form-group">
                    <label class="control-label"> Tanggal</label>
                    <div><input type="text" required="" placeholder="Tanggal" value="" autocomplete="off" placeholder="" name="tanggal" class="form-control datepicker"></div>
                </div>
                <div class="form-group">
                    <label class="control-label"> Jumlah hari</label>
                    <div><input type="text" onkeyup="TotalBiaya()" value="" onkeypress="return Angka(this)" id="total" placeholder="Jumlah hari" autocomplete="off" required="" name="total" class="form-control"></div>
                </div>
                <div class="form-group">
                    <label class="control-label"> Nominal</label>
                    <div><input type="text" value="" readonly="" id="harga" name="harga" class="form-control"></div>
                </div>
                <div class="form-group">
                    <label class="control-label"> Total nominal</label>
                    <div><input type="text" value="" readonly="" id="seluruh" name="seluruh" class="form-control"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="submit" id="simpan"  class="btn btn-primary">Bayar</button>
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
				$('#simpan').text('Membayar...');
				$('#simpan').attr('disabled',true);
				var url,method;
				if (label == 'simpan'){
				 	url = '<?=base_url($this->uri->segment(1).'/Simpan')?>';
				 	method = 'Tambah';
				}
				var isi = $('#form').serialize();
				$.ajax({
					url: url,
					type:"POST",
					data: isi,
					dataType:"JSON",
					success:function(data){
						$('#modal-form').modal('hide');
						$('#simpan').text('Bayar');
		 				$('#simpan').attr('disabled',false);
       
    						reload();
    						sweet('Sukses','Pembayaran Uang Snack Berhasil','success');

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


	function sweet(judul,text,tipe){
        Swal({
            title: judul,
            text: text,
            type: tipe
        });
    }

    function Detail(id){

         document.location.href= "<?= base_url($this->uri->segment(1).'/Detail/')?>"+id;
    }


    function Bayar(id){
        label = 'simpan';
        $('#form')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#modal-form').modal('show');
        $('.modal-title').text('Form Pembayaran Uang Snack'); 

        $.ajax({

            url: "<?=base_url($this->uri->segment(1).'/GetSnack')?>",
            type:"GET",
            dataType:"JSON",
            success:function(data){
                $('[name="id_siswa"]').val(id);
                $('[name="harga"]').val(data);               
            }
        });

    }

</script>