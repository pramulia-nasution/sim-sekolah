<?php $g = $this->db->query("SELECT id,saldo_awal,kas_masuk,kas_keluar FROM laporan WHERE tanggal = DATE(NOW())")->row_array(); 

$Keluar = $g['saldo_awal'] + $g['kas_masuk'] - $g['kas_keluar'];

?>
<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="glyphicon glyphicon-inbox"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">Saldo Awal</span>
            <span class="info-box-number"><small><?=rupiah($g['saldo_awal'])?></small></span>
        </div>
    </div>
</div>
<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
        <span class="info-box-icon bg-green"><i class="glyphicon glyphicon-import"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">Kas Masuk</span>
            <span class="info-box-number"><small><?=rupiah($g['kas_masuk'])?></small></span>
        </div>
    </div>
</div>
<div class="clearfix visible-sm-block"></div>
<div class="col-md-3 col-sm-6 col-xs-12">
	<div class="info-box">
        <span class="info-box-icon bg-red"><i class="glyphicon glyphicon-export"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">Kas Keluar</span>
            <span class="info-box-number"><small><?=rupiah($g['kas_keluar'])?></small></span>
        </div>
    </div>
</div>
<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
        <span class="info-box-icon bg-blue"><i class="glyphicon glyphicon-folder-close"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">Saldo Akhir</span>
            <span class="info-box-number"><small><?=rupiah($Keluar)?></small></span>
        </div>
    </div>
</div>

<div class="col-xs-12">
    <div class="box box-primary">
        <div class="box-header"> 
        	<div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
           	</div>
        </div>
        <div class="box-body text-center">
            <div class="jumbotron jumbotron-fluid" style="background-color:white;">
                <div class="container">
                 	<img style="width: 15%" src="<?=base_url('assets/dist/img/j.png')?>">
                    <h2 class="display-4" style="color:#0066ff;">SISTEM IFORMASI MANAJEMEN KEUANGAN</h2>
                    <p class="lead" style="color:#66a3ff ">SD MUHAMMADIYAH KAMPA FULL DAY SCHOOL</p>
                </div>
            </div>
            <div class="col-lg-4 col-xs-6 text-left">
	          	<div class="small-box bg-aqua">
	            	<div class="inner">
	              		<?php $count = $this->M_General->countAll('guru') ?>
	              		<h3><?=$count?></h3>
	              		<p>Data Guru</p>
	            	</div>
	            	<div class="icon">
	              		<i class="fa fa-graduation-cap"></i>
	            	</div>
	            	<a href="<?=base_url('Guru')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
	            </div>
        	</div>
            <div class="col-lg-4 col-xs-6 text-left">
	          	<div class="small-box bg-yellow">
	            	<div class="inner">
	              		<?php $count = $this->M_General->countAll('kelas') ?>
	              		<h3><?=$count?></h3>
	              		<p>Data Kelas</p>
	            	</div>
	            	<div class="icon">
	              		<i class="fa fa-institution"></i>
	            	</div>
	            	<a href="<?=base_url('Kelas')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
	            </div>
        	</div>
            <div class="col-lg-4 col-xs-6 text-left">
	          	<div class="small-box bg-blue">
	            	<div class="inner">
	              		<?php $count = $this->M_General->countAll('siswa') ?>
	              		<h3><?=$count?></h3>
	              		<p>Data Siswa</p>
	            	</div>
	            	<div class="icon">
	              		<i class="fa fa-users"></i>
	            	</div>
	            	<a href="<?=base_url('Siswa')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
	            </div>
        	</div>
        </div>
    </div>