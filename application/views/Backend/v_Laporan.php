<div class="col-xs-12">
	<div class="box box-primary">
        <div class="box-header">
            <div class="pull-right">
                <a href="#" data-toggle="modal" data-target="#modal-print" class="btn btn-info"><i class="fa fa-print"></i> Cetak</a>
            </div>
        </div>
	    <div class="box-body">
	    	<div class="table-responsive">    	
		        <table id="list-data" class="table table-bordered table-hover">
		            <thead>
			            <tr>
                      <th style="width: 10px;">No</th>
                      <th>Tanggal</th>
                      <th>Saldo Awal</th>
                      <th>Kas Masuk</th>
                      <th>Kas Keluar</th>
                      <th>Saldo Akhir</th>
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

<div class="modal fade" id="modal-print">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Cetak Laporan</h4>
            </div>
<?= form_open($this->uri->segment(1).'/Cetak','id = "form"')?>
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label"> Tanggal Awal</label>
                    <div><input type="text" autocomplete="off" placeholder="tanggal awal" class="form-control datepicker" required="" name="awal"></div>
                </div>
                <div class="form-group">
                    <label class="control-label"> Tanggal Akhir</label>
                    <div><input type="text" autocomplete="off"  placeholder="tanggal akhir" class="form-control datepicker" required="" name="akhir"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="submit" id="cetak" class="btn btn-primary">Cetak</button>
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
                "type": "POST"
            },
            columns: [
                {
                    "data": "id",
                    "orderable": false,
                    "searchable": false
                },
                {"data": "tanggal"},
                {"data": "saldo_awal",render: $.fn.dataTable.render.number('.',',','')},
                {"data": "kas_masuk",render: $.fn.dataTable.render.number('.',',','')},
                {"data": "kas_keluar",render: $.fn.dataTable.render.number('.',',','')},
                {"data": "saldo_akhir",render: $.fn.dataTable.render.number('.',',','')},
                {
                    "data": "view",
                    "orderable": false,
                    "searchable": false
                }
            ],
            order: [[0, 'DESC']],
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
                awal:{
                    required:true
                },
                akhir:{
                    required:true
                },
            },
            messages: {
                awal: {
                    required:"Tambah tanggal awal."
                },
                akhir: {
                    required:"Tambah tanggal akhir."
                },
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
                var isi = $('#form').serialize();
                $.ajax({
                    url: "<?=base_url('Cetak/Cetak_periode')?>",
                    type:"POST",
                    data: isi,
                    dataType:"JSON",
                    success:function(data){
                        $('#modal-print').modal('hide');
                        $('#form')[0].reset();
                    }
                });
            },
            invalidHandler: function (form) {}
        });

	});

    function reload(){
        table.ajax.reload(null,false);
    }

    function Detail(id){

        var i = id.toString();
            document.location.href= "<?= base_url($this->uri->segment(1).'/Detail/')?>"+i;
    }

</script>