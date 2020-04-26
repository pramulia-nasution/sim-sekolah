<?php 

$id = $this->uri->segment(3);

$na = $this->db->query("SELECT name FROM siswa WHERE id = '$id'")->row_array();

?>

<div class="col-xs-12">
    <div class="box box-primary">
        <div class="box-header">
            <!-- <input type="hidden" id="id" value="<?=$id?>"> -->
           <strong> Nama Siswa : <?=$na['name']?> </strong> 
           <div class="pull-right">
                <a href="#" onclick="Tambah()" class="btn btn-info btn-sm">Ubah </a>
        </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">      
                <table id="list-data" class="table tabel table-bordered table-hover">
                    <thead>
                        <tr>
                      <th style="width: 10px;">No</th>
                      <th>Waktu Pembayaran</th>
                      <th>Tanggal Snack</th>
                      <th>Nominal</th>
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
                <input type="hidden" name="id" value="<?=$id?>">
               <div class="form-group">
                    <label class="control-label"> Tanggal Ubah</label>
                    <div><input type="text" required="" placeholder="Tanggal" value="" autocomplete="off" placeholder="" name="tanggal" class="form-control datepicker"></div>
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
    var siswa =  "<?=$id?>";
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
                "url": "<?= base_url().$this->uri->segment(1).'/getDetail/'?>"+siswa,
                "type": "POST"
            },
            columns: [
                {
                    "data": "id",
                    "orderable": false,
                    "searchable": false
                },
                {"data": "tanggal"},
                {"data": "waktu"},
                {"data": "nominal",render: $.fn.dataTable.render.number('.',',','')}
            ],
            order: [[0, 'asc']],
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
                    url = '<?=base_url($this->uri->segment(1).'/UpdateData')?>';
                    method = 'Ubah';
                }
                var isi = $('#form').serialize();
                $.ajax({
                    url: url,
                    type:"POST",
                    data: isi,
                    dataType:"JSON",
                    success:function(data){
                        $('#modal-form').modal('hide');
                        $('#simpan').text('Simpan');
                        $('#simpan').attr('disabled',false);
                        reload();
                        if (data.status){
                        sweet('Di '+method,'Berhasil '+method+' Data','success');
                        }
                        else{
                             sweet('Error','Data tidak ditemukan','error');
                        }
                    },
                   error: function (jqXHR, textStatus, errorThrown){
                        sweet('Oops...','Data Gagal di Ubah','error');
                        console.log(jqXHR, textStatus, errorThrown);
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
    }

    function Detail(){

         document.location.href= "<?= base_url($this->uri->segment(1).'/Detail')?>";
    }

    function Tambah(){
        label = 'simpan';
        $('#form')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty(); 
        $('#modal-form').modal('show');
        $('.modal-title').text('Tambah Data');
    }

</script>