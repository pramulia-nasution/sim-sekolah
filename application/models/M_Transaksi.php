<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Transaksi extends CI_Model {

	function getAllData(){
		$this->datatables->select('id,kode,nama,nominal');
		$this->datatables->from('pembayaran');
		$this->datatables->add_column('view','<center><a href="javascript:void(0)" onclick="Ubah($1)" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> Ubah</a></center> ','id');
		//$this->datatables->edit_column('nominal','Rp. $1','nominal');
		return $this->datatables->generate();
	}
	

}

/* End of file m_Menu_1.php */
/* Location: ./application/models/m_Menu_1.php */