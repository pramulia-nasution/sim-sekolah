<?php $id = $this->uri->segment(3); ?>
<div class="col-xs-12">
	<div class="box box-primary">
        <div class="box-header">

            <h3>Nama Guru : <?=get_guru($id)?></h3>

        </div>
	    <div class="box-body">
	    	<div class="table-responsive">    	
		        <table  class="table tabel table-bordered table-hover">
		            <thead>
			            <tr>
                            <th style="width: 10px;">No</th>
                            <th>Tanggal Penerimaan</th>
                            <th>Periode</th>
                            <th>Jumlah Jam</th>
                            <th>Total Gaji</th>
			            </tr>
		            </thead>
		            <tbody>
                    <?php 
                    $no=1;
                    foreach ($isi as $v) { ?>
                        <tr>
                            <td><?=$no++;?></td>  
                            <td><?=tanggal($v->tanggal,'bln').' - '.jam($v->tanggal).' WIB'?></td>  
                            <td><?=$v->periode?></td>  
                            <td><?=$v->jam.' Jam'?></td>
                            <td><?=rupiah($v->jam*$v->nominal)?></td>    
                        </tr>
                    <?php } ?>
		              
		            </tbody>
		        </table>
	       	</div>
	    </div>
    </div>
</div>