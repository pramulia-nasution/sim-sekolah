<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Gaji extends CI_Model {

	function getAllData(){
		$this->datatables->select('id,name,sex,nip,number');
		$this->datatables->from('guru');
		$this->datatables->add_column('view','<center><a href="javascript:void(0)" onclick="Detail($1)" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Detail</a></center> ','id');
		return $this->datatables->generate();
	}
	

}