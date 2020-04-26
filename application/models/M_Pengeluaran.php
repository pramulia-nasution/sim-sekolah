<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Pengeluaran  extends CI_Model {

	function getAllData(){
		$this->datatables->select("id,sekarang,DATE_FORMAT(s.tanggal,'%d-%m-%Y') AS Tgl, Sum(s.nominal) AS Total");
		$this->datatables->from('pengeluaran as s');
		$this->datatables->group_by("DATE_FORMAT(s.tanggal,'%Y-%m-%d')");
		return $this->datatables->generate();
	}

	function getDetailData($detail =''){
		$this->datatables->select("id,DATE_FORMAT(s.tanggal,'%d-%m-%Y - %H:%m:%s WIB') AS Tgl,nominal,keterangan");
		$this->datatables->from('pengeluaran as s');
		$this->datatables->where('sekarang',$detail);
		return $this->datatables->generate();
	}
	

}

/* End of file m_Menu_1.php */
/* Location: ./application/models/m_Menu_1.php */