<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Catering extends CI_Model {

	function Detail($d){
		$this->datatables->select("id,DATE_FORMAT(tanggal,'%d-%m-%Y - %H:%i-%s WIB') as tanggal,DATE_FORMAT(waktu,'%d-%m-%Y') as waktu,nominal");
		$this->datatables->where('id_siswa',$d);
		$this->datatables->from('catering');
		return $this->datatables->generate();
	}
	

}