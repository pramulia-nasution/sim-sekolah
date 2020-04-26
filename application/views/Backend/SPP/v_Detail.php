<?php 

$id = $this->uri->segment(3);

$na = $this->db->query("SELECT name FROM siswa WHERE id = '$id'")->row_array();

?>

<div class="col-xs-12">
    <div class="box box-primary">
        <div class="box-header">
           <h3> Nama Siswa : <?=$na['name']?> </h3> 
        </div>
        <div class="box-body">
            <div class="table-responsive">      
                <table  class="table tabel table-bordered table-hover">
                    <thead>
                        <tr>
                      <th style="width: 10px;">No</th>
                      <th>Waktu Pembayaran</th>
                      <th>SPP</th>
                      <th>Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
<?php 
$no=1;
foreach ($isi as $key ) { ?>
                        <tr>
                            <td><?=$no++;?></td>
                            <td><?=tanggal($key->tanggal,'bulan').' - '.jam($key->tanggal).' WIB'?></td>
                            <td><?=$key->bulan?></td>
                            <td><?=rupiah($key->nominal)?></td>
                        </tr>
<?php  } ?>
                      
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>