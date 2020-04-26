<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas extends CI_Controller {

	private $parents = 'Kelas';
	private $icon	 = 'fa fa-institution ';
	var $table 		 = 'kelas';

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
		$data['guru']	=$this->db->query("SELECT name,nip FROM guru")->result();

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

	function Pindah(){

		$siswa = $this->input->post('id');
		$kelas = $this->input->post('kelas');

		$ar = array();

		if(!empty($siswa)){
			foreach ($siswa as $i => $key){

				array_push($ar,array(
					'kelas' => $kelas,
					'id'	=> $key
				));
			}

		$this->db->update_batch('siswa',$ar,'id');
		}

		redirect($this->uri->segment(1),'refresh');
	}

	function Detail($id){

		$this->load->helper('data');

		$this->breadcrumb->append_crumb('SIM Sekolah ',base_url());
		$this->breadcrumb->append_crumb($this->parents,base_url('Kelas'));
		$this->breadcrumb->append_crumb('Detail Kelas',$this->parents);

		$data['title']	= 'Data '.$this->parents.' '.get_kelas($id).' | SIM Sekolah ';
		$data['judul']	=  $this->parents.' '.get_kelas($id);
		$data['icon']	= $this->icon;
		$data['siswa']	=$this->db->query("SELECT id,name,nis,sex FROM siswa WHERE kelas = $id")->result();

		$this->template->views('Backend/'.$this->parents.'/v_Detail',$data);


	}

	function Simpan(){
        $insert = array(
                    'nama'  	=> filter_string(ucwords($this->input->post('nama'),TRUE)),
                    'wali'		=> $this->input->post('wali',TRUE),
                    'keterangan'	=> filter_string($this->input->post('keterangan',TRUE))
                );

        $insert = $this->M_General->insert($this->table,$insert);
        $data['status'] = TRUE;
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	function Ubah(){
        $insert = array(
                    'nama'  	=> filter_string(ucwords($this->input->post('nama'),TRUE)),
                    'wali'		=> $this->input->post('wali',TRUE),
                    'keterangan'	=> filter_string($this->input->post('keterangan',TRUE))
                );
        $insert = $this->M_General->update($this->table,$insert,'id',$this->input->post('id'));
        $data['status'] = TRUE;
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
	}
}