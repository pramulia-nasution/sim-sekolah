<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lainnya extends CI_Controller {

	private $parents = 'Lainnya';
	private $icon	 = 'fa fa-money';
	var $table 		 = 'lainnya';

	function __construct(){
		parent::__construct();

		is_login();
		get_breadcrumb();
		$this->load->model('M_'.$this->parents,'mod');
		$this->load->library('form_validation');
		$this->load->library('Datatables'); 
	}

	public function index(){

		$this->breadcrumb->append_crumb('SIM Sekolah ','Beranda');
		$this->breadcrumb->append_crumb('Pemasukan Uang '.$this->parents,$this->parents);

		$data['title']	= 'Pemasukan '.$this->parents.' | SIM Sekolah ';
		$data['judul']	= 'Pemasukan '.$this->parents;
		$data['icon']	= $this->icon;

	$this->template->views('Backend/'.$this->parents.'/v_'.$this->parents,$data);
	}

	function getData (){
		header('Content-Type:application/json');
		echo $this->mod->getAllData();
	}

	function getDetail(){

		header('Content-Type:application/json');
		$id = $this->input->post('tgl');
		echo $this->mod->getDetailData($id);
	}

		function Detail(){
		$this->breadcrumb->append_crumb('SIM Sekolah ',base_url());
		$this->breadcrumb->append_crumb($this->parents,base_url('Lainnya'));
		$this->breadcrumb->append_crumb('Detail Pemasukan Lainnya',$this->parents);

		$data['title']	= 'Detail Pemasukan '.$this->parents.' | SIM Sekolah ';
		$data['judul']	= 'Detail Pemasukan '.$this->parents;
		$data['icon']	= $this->icon;
	$this->template->views('Backend/'.$this->parents.'/v_Detail',$data);

	}

	function Simpan(){

		$total = filter_string($this->input->post('nominal',TRUE));

    		$insert = array(
	                    'nominal'	=> $total,
	                    'sekarang'	=> sekarang(),
	                    'time'	   => waktu(),
	                    'keterangan'	=> filter_string($this->input->post('keterangan',TRUE))
	                );

	        $insert = $this->M_General->insert($this->table,$insert);
	        $this->M_General->update_kas('kas_masuk',$total);
	        $data['status'] = TRUE;

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

}