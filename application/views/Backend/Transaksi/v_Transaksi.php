<div class="col-xs-12">
    <div class="box box-primary">
        <div class="box-header">
           <!--  <div class="pull-right">
                <a href="#" onclick="Tambah()" class="btn btn-primary">Tambah Data </a>
            </div> -->
        </div>
        <div class="box-body">
            <div class="table-responsive">      
                <table id="list-data" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                      <th style="width: 10px;">No</th>
                      <th>Kode Transaksi</th>
                      <th>Nama Transaksi</th>
                      <th>Nominal</th>
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
                    <label class="control-label">Tipe Transaki</label>
                    <select required="" name="tipe"  id="status"  data-placeholder="--Pilih--" class="form-control">
                        <option value="">--Pilih--</option>
                        <option value="KM">Kas Masuk</option>
                        <option value="KK">Kas Keluar</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label"> Kode Transaksi</label>
                    <div><input type="text" readonly="" value="" placeholder="Kode Transaksi" autocomplete="off" name="kode" class="form-control"></div>
                </div>
                <div class="form-group">
                    <label class="control-label"> Nama Transaksi</label>
                    <div><input type="text" id="nama" value="" required="" placeholder="Nama Transaksi" autocomplete="off" name="nama" class="form-control"></div>
                </div>
                <div class="form-group">
                    <label class="control-label"> Nominal</label>
                    <div><input type="text" value="" required="" onkeypress="return Angka(this)" placeholder="Nominal" autocomplete="off" name="nominal" class="form-control"></div>
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
                {"data": "kode"},
                {"data": "nama"},
                {"data": "nominal",render: $.fn.dataTable.render.number('.',',','')},
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


        $('#status').on('change',function(){

            var tipe = $(this).val();

           if(label == 'simpan'){
            $.ajax({

                url:"<?=base_url($this->uri->segment(1).'/buat_kode/')?>"+tipe,
                type:"GET",
                dataType:"JSON",
                success:function(data){
                    $('[name="kode"]').val(data);
                },
                error: function (jqXHR, textStatus, errorThrown){
                sweet('Oops...','Data tidak dapat diambil','error');
            }
            });
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
    };

    function Tambah(){
        label = 'simpan';
        $('#form')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty(); 
        $('#modal-form').modal('show');
        $('#status').attr('disabled',false);
        $('#nama').attr('disabled',false);
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
                $('#status').attr('disabled',true);
                $('#nama').attr('disabled',true);
                $('[name="id"]').val(data.id);
                $('[name="nama"]').val(data.nama);
                $('[name="kode"]').val(data.kode);
                $('[name="nominal"]').val(data.nominal);
                $('[name="tipe"]').val(data.tipe).trigger('change');
                $('#modal-form').modal('show');
                $('.modal-title').text('Ubah Data'); 
            },
            error: function (jqXHR, textStatus, errorThrown){
                sweet('Oops...','Data tidak dapat diambil','error');
            }
        });
    }
</script>