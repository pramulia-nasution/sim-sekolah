<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Kelas extends CI_Model {

	function getAllData(){
		$this->datatables->select('id,nama,wali,keterangan');
		$this->datatables->from('kelas');
		$this->datatables->add_column('view','<center><a href="javascript:void(0)" onclick="Detail($1)" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Detail</a> <a href="javascript:void(0)" onclick="Ubah($1)" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> Ubah</a> </center> ','id');
		return $this->datatables->generate();
	}
	

}