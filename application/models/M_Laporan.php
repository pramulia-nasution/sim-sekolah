<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Laporan extends CI_Model {

	function getAllData(){
		$this->datatables->select("id,saldo_awal,DATE_FORMAT(tanggal,'%d-%m-%Y') as tanggal,,kas_masuk,kas_keluar, (saldo_awal + kas_masuk - kas_keluar) as saldo_akhir");
		$this->datatables->from('laporan');
		$this->datatables->add_column('view','<center><a href="javascript:void(0)" onclick="Detail($1)" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Detail</a> </center> ','id');
		return $this->datatables->generate();
	}
	

}