<div class="col-xs-12">
	<div class="box box-primary">
        <div class="box-header">
            <div class="col-sm-2">
<?php $kls= $this->db->query("SELECT DISTINCT sekarang, DATE_FORMAT(tanggal,'%d-%m-%Y') AS t FROM pengeluaran ORDER BY tanggal DESC")->result() ?>
                <select name="kelas" id="kelas" data-placeholder="--Pilih Tanggal--" class="form-control select2">
                    <option value=""></option>
                <?php foreach ($kls as $key) {?>    
                    <option value="<?=$key->sekarang?>"><?=$key->t?></option>
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
                      <th>Tanggal</th>
                      <th>Nominal</th>
                      <th>Keterangan</th>
			            </tr>
		            </thead>
		            <tbody>
		              
		            </tbody>
		        </table>
	       	</div>
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
                "url": "<?= base_url().$this->uri->segment(1).'/getDetail'?>",
                "type": "POST",
                "data":{tgl : is_kelas }
            },
            columns: [
                {
                    "data": "id",
                    "orderable": false,
                    "searchable": false
                },
                {"data": "Tgl"},
                {"data": "nominal",render: $.fn.dataTable.render.number('.',',','')},
                {"data": "keterangan"}
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

</script>