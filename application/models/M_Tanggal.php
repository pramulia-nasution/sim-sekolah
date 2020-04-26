<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Tanggal extends CI_Model {

	function getAllData(){
		$this->datatables->select("id,DATE_FORMAT(tgl,'%d-%m-%Y') as tgl,keterangan");
		$this->datatables->from('tanggal');
		$this->datatables->add_column('view','<center><a href="javascript:void(0)" onclick="Hapus($1)" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Hapus</a></center> ','id');
		return $this->datatables->generate();
	}
	

}

/* End of file m_Menu_1.php */
/* Location: ./application/models/m_Menu_1.php */