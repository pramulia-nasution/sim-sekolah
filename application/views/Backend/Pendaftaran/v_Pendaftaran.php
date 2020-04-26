<div id="alert">
    <?=alert()?>
</div>
<div class="col-xs-12">
	<div class="box box-primary">
        <div class="box-header">
            <div class="pull-right">

                <div class="btn-group">
                    <a  class="btn btn-primary"  data-toggle="modal" data-target="#modal-import" ><i class="fa fa-download"></i> Import Data</a>
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?=base_url('excel/Pendaftaran.xlsx')?>">Format Data</a></li>
                    </ul>
                </div>

            	<a href="#" data-toggle="modal" data-target="#modal-tambah" class="btn btn-primary">Tambah Data </a>
            </div>
        </div>
	    <div class="box-body">
	    	<div class="table-responsive">    	
		        <table class="table tabel table-bordered table-hover">
		            <thead>
			            <tr>
                      <th style="width: 10px;">No</th>
                      <th>Nama Pendaftar</th>
                      <th>NIS</th>
                      <th>Jenis Kelamin</th>
                      <th>Wali</th>
                      <th>Alamat</th>
                      <th width="">Aksi</th>
			            </tr>
		            </thead>
		            <tbody>
                <?php 
                $no = 1;
                foreach ($isi as $r) {?>
                        <tr>
                           <td><?=$no++;?></td> 
                           <td><?=$r->name;?></td> 
                           <td><?=$r->nis?></td> 
                           <td><?=$r->sex?></td> 
                           <td><?=$r->wali?></td> 
                           <td><?=$r->alamat?></td> 
                           <td>
                               <center>
                                    <a class="btn btn-warning btn-xs" data-toggle="modal" data-target="#ubah-data<?php echo $r->id ?>" href=""><i class="fa fa-pencil"></i> Ubah</a>
                                    <a class="btn btn-danger btn-xs" data-toggle="modal" data-target="#hapus-data<?php echo $r->id ?>" href=""><i class="fa fa-trash-o"></i> Hapus</a>
                                <?php  if ($r->bayar == '0') {?>
                                    <a class="btn btn-success btn-xs" data-toggle="modal" data-target="#bayar-data<?php echo $r->id ?>" href=""><i class="fa fa-money"></i> Bayar</a>
                                <?php } else { ?>
                                    <a class="btn btn-info btn-xs" data-toggle="modal" data-target="#kelas-data<?php echo $r->id ?>" href=""><i class="fa fa-building"></i> Kelas</a>
                                <?php } ?>
                                </center>
                           </td> 
                        </tr>
                    <?php } ?>
		              
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
                <h4 class="modal-title">Import Data</h4>
            </div>
<?= form_open_multipart($this->uri->segment(1).'/Import','role = "form"')?>
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label"> File</label>
                    <input type="file" required="" accept=".xlsx" name="file">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Import</button>
            </div>
<?= form_close()?>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Form Pendaftarn</h4>
            </div>
<?= form_open($this->uri->segment(1).'/Simpan','role = "form"')?>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="submit" id="simpan"  class="btn btn-primary">Simpan</button>
            </div>
<?= form_close()?>
        </div>
    </div>
</div>

<?php foreach ($isi as  $e) { ?>
<div class="modal fade" id="hapus-data<?php echo $e->id ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-trash-o"></i> Hapus Data</h4>
            </div>
            <div class="modal-body">
             <?=form_open($this->uri->segment(1).'/Hapus') ?>
                <div class="form-group">
                  <input type="hidden" value="<?php echo $e->id ?>" name="id">
                </div>
                 <p>Apakah Anda yakin ingin menghapus data ini ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                <button type="submit" class="btn btn-danger btn-info"><i class="fa fa-trash-o"></i> Hapus</button>
            </div>
        </div>
        <?= form_close() ?>
    </div>
</div>

<div class="modal fade" id="kelas-data<?php echo $e->id ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-building"></i> Pilih Kelas</h4>
            </div>
            <div class="modal-body">
             <?= form_open($this->uri->segment(1).'/Kelas') ?>
                <div class="form-group">
                    <label class="control-label">Pilih Kelas</label>
                  <?php $kls= $this->db->query("SELECT id,nama FROM kelas")->result() ?>
                <select name="kelas" data-placeholder="--Pilih kelas--" class="form-control">
                    <option value="">--Pilih--</option>
                <?php foreach ($kls as $key) {?>    
                    <option value="<?=$key->id?>"><?=$key->nama?></option>
                <?php } ?>
                </select>
                <input type="hidden" name="id" value="<?=$e->id?>">
                  <input type="hidden" value="<?=$e->name ?>" name="nama">
                  <input type="hidden" value="<?=$e->nis ?>" name="nis">
                  <input type="hidden" value="<?= $e->sex ?>" name="sex">
                  <input type="hidden" value="<?= $e->wali?>" name="wali">
                  <input type="hidden" value="<?=$e->alamat ?>" name="alamat">
                  <input type="hidden" value="<?=$e->tempat ?>" name="tempat">
                  <input type="hidden" value="<?=$e->tanggal ?>" name="tanggal">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                <button type="submit" class="btn btn-primary"> Pilih</button>
            </div>
        </div>
        <?=form_close() ?>
    </div>
</div>


<div class="modal fade" id="bayar-data<?php echo $e->id ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-money"></i> Pembayaran Uang Pendaftaran</h4>
            </div>
            <div class="modal-body">
             <?= form_open($this->uri->segment(1).'/Bayar') ?>
                <div class="form-group">
                  <input type="hidden" value="<?php echo $e->id ?>" name="id">
                  <input type="hidden" value="<?php echo $e->name ?>" name="nama">
                  <input type="hidden" value="<?php echo $bayar['nominal']; ?>" name="harga">
                </div>
                 <p>Konfirmasi Pembayaran Uang pendaftaran Dengan Nama: <strong><?=get_nama($e->id)?></strong> senilai <?=rupiah($bayar['nominal'])?> </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                <button type="submit" class="btn btn-success"><i class="fa fa-money"></i> Bayar</button>
            </div>
        </div>
        <?=form_close() ?>
    </div>
</div>
<?php } ?>
