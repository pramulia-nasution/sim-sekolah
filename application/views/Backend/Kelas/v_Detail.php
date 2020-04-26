<?=form_open($this->uri->segment(1).'/Pindah')?>
<div class="col-xs-12">
	<div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Data <?php echo $judul;?></h3>
        </div>
	    <div class="box-body">
	    	<div class="table-responsive"> 
		        <table  class="tabel table table-bordered table-hover">
		            <thead>
			            <tr>
                      <th style="width: 10px;">#</th>
                      <th>Nama Siswa</th>
                      <!-- <th>Jumlah Siswa</th> -->
                      <th>NIS</th>
                      <th>Jenis Kelamin</th>
			            </tr>
		            </thead>
		            <tbody>
		            	<?php foreach ($siswa as $key) {?>
		            	<tr>
		            		<td><input class="form-check" type="checkbox" name="id[]" value="<?=$key->id?>"></td>
		            		<td><?=$key->name?></td>
		            		<td><?=$key->nis?></td>
		            		<td><?=$key->sex?></td>
		            	</tr>
		             <?php } ?>
		            </tbody>
		        </table>
<?php 

$id = $this->uri->segment(3);
$kls = $this->db->query("SELECT id,nama FROM kelas WHERE id != $id")->result(); ?>
		           <div class="col-sm-2">
			           	<select name="kelas" class="form-control select2" data-placeholder="--Pilih Kelas--">
			           		<option value=""></option>
			            <?php foreach ($kls as $key) { ?>
			           		<option value="<?=$key->id?>"><?=$key->nama?></option>
			            <?php } ?>
			           	</select>
			        </div>
			        <div class="col-sm">
			        	<button type="submit" class="btn btn-info btn-sm">Ok</button>
			        </div>
	       	</div>
	    </div>
    </div>
</div>
<?=form_close()?>	