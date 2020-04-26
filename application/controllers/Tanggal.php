<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tanggal extends CI_Controller {

	private $parents = 'Tanggal';
	private $icon	 = 'fa fa-calendar';
	var $table 		 = 'tanggal';

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
		$this->breadcrumb->append_crumb($this->parents.' Merah',$this->parents);

		$data['title']	= $this->parents.' Merah/Hari Libur | SIM Sekolah ';
		$data['judul']	= $this->parents.' Merah/Hari Libur';
		$data['icon']	= $this->icon;

	$this->template->views('Backend/'.$this->parents.'/v_'.$this->parents,$data);
	}

	function getData (){
		header('Content-Type:application/json');
		echo $this->mod->getAllData();
	}



	function Simpan(){
        $insert = array(
                    'tgl'			=> filter_string($this->input->post('tanggal',TRUE)),
                    'keterangan'	=> filter_string($this->input->post('keterangan',TRUE))
                );

        $insert = $this->M_General->insert($this->table,$insert);
        $data['status'] = TRUE;
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	function Ubah(){
        $insert = array(
                    'name'  	=> filter_string(ucwords($this->input->post('nama'),TRUE)),
                    'sex'		=> $this->input->post('gender',TRUE),
                    'nis' 		=> $this->input->post('nis',TRUE),
                    'tempat'	=> filter_string($this->input->post('tempat',TRUE)),
                    'tanggal'	=> filter_string($this->input->post('tanggal',TRUE)),
                    'alamat'	=> filter_string($this->input->post('alamat',TRUE)),
                    'status'	=> filter_string($this->input->post('status',TRUE)),
                    'wali'		=> filter_string($this->input->post('wali',TRUE))
                );
        $insert = $this->M_General->update($this->table,$insert,'id',$this->input->post('id'));
        $data['status'] = TRUE;
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	public function Hapus($id){
		$this->M_General->delete($this->table,'id',$id);
		$data['status'] = TRUE;
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}
}