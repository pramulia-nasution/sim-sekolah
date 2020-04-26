<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guru extends CI_Controller {

	private $parents = 'Guru';
	private $icon	 = 'fa fa-graduation-cap';
	var $table 		 = 'guru';

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
		$this->breadcrumb->append_crumb($this->parents,$this->parents);

		$data['title']	= $this->parents.' | SIM Sekolah ';
		$data['judul']	= $this->parents;
		$data['icon']	= $this->icon;

	$this->template->views('Backend/'.$this->parents.'/v_'.$this->parents,$data);
	}

	function getData (){
		header('Content-Type:application/json');
		echo $this->mod->getAllData();
	}


	public function edit($id){
		$data = $this->M_General->getByID($this->table,'id',$id,'id')->row();
		echo json_encode($data);
	}

	function Simpan(){
        $insert = array(
                    'name'  	=> filter_string(ucwords($this->input->post('nama'),TRUE)),
                    'sex'		=> $this->input->post('gender',TRUE),
                    'nip' 		=> $this->input->post('nip',TRUE),
                    'bidang'	=> filter_string($this->input->post('bidang',TRUE)),
                    'alamat'	=> filter_string($this->input->post('alamat',TRUE)),
                    'status'	=> filter_string($this->input->post('status',TRUE)),
                    'number'	=> filter_string($this->input->post('telepon',TRUE))
                );

        $insert = $this->M_General->insert($this->table,$insert);
        $data['status'] = TRUE;
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	function Ubah(){
        $insert = array(
                    'name'  	=> filter_string(ucwords($this->input->post('nama'),TRUE)),
                    'sex'		=> $this->input->post('gender',TRUE),
                    'nip' 		=> $this->input->post('nip',TRUE),
                    'bidang'	=> filter_string($this->input->post('bidang',TRUE)),
                    'alamat'	=> filter_string($this->input->post('alamat',TRUE)),
                    'status'	=> filter_string($this->input->post('status',TRUE)),
                    'number'	=> filter_string($this->input->post('telepon',TRUE))
                );
        $insert = $this->M_General->update($this->table,$insert,'id',$this->input->post('id'));
        $data['status'] = TRUE;
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
	}
}