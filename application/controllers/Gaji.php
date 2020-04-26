<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gaji extends CI_Controller {

	private $parents = 'Gaji';
	private $icon	 = 'fa fa-calculator';
	var $table 		 = 'gaji';

	function __construct(){
		parent::__construct();

		is_login();
		get_breadcrumb();
		$this->load->model('M_'.$this->parents,'mod');
		$this->load->library('form_validation');
		$this->load->library('Datatables'); 
	}

	function index(){

		$this->breadcrumb->append_crumb('SIM Sekolah ','Beranda');
		$this->breadcrumb->append_crumb($this->parents.' Guru',$this->parents);

		$data['title']	= 'Pembayaran '.$this->parents.' | SIM Sekolah ';
		$data['judul']	= 'Pembayaran '.$this->parents;
		$data['icon']	= $this->icon;

	$this->template->views('Backend/'.$this->parents.'/v_'.$this->parents,$data);
	}

	function getData (){
		header('Content-Type:application/json');
		echo $this->mod->getAllData();
	}

		function getGaji(){
		header('Content-Type:application/json');
		$n = $this->db->query("SELECT nominal FROM pembayaran WHERE id = 6")->row_array();
		echo json_encode($n['nominal']);
	}


	function Detail($id){

		$this->load->helper('data');
		$this->breadcrumb->append_crumb('SIM Sekolah ',base_url());
		$this->breadcrumb->append_crumb($this->parents,base_url('SPP'));
		$this->breadcrumb->append_crumb('Detail Pembayaran SPP',$this->parents);

		$data['title']	= 'Detail Pembayaran '.$this->parents.' | SIM Sekolah ';
		$data['judul']	= 'Detail Pembayaran '.$this->parents;
		$data['icon']	= $this->icon;
		$data['isi']	= $this->M_General->getByID('gaji','id_guru',$id,'DESC')->result();

	$this->template->views('Backend/'.$this->parents.'/v_Detail',$data);

	}

	function Simpan(){

		$id_gur = $this->input->post('guru');
		$bln = filter_string($this->input->post('bulan',TRUE));
		$cek = $this->db->query("SELECT id FROM gaji WHERE id_guru = '$id_gur' AND periode = '$bln' ")->num_rows();
		$jam = filter_string($this->input->post('jam',TRUE));
		$nominal = filter_string($this->input->post('gaji',TRUE));
		$total = $jam * $nominal;

		if ($cek > 0){
			$data['status'] = FALSE;
    	}
    	else{

    		$insert = array(
	                    'id_guru'	=> $id_gur,
	                    'periode'	=> $bln,
	                    'time'	   => waktu(),
	                    'jam'		=> $jam,
	                    'nominal'	=> $nominal
	                );

	        $insert = $this->M_General->insert($this->table,$insert);
	        $this->M_General->update_kas('kas_keluar',$total);
	        $data['status'] = TRUE;
    		
    	}
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

}