<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SPP extends CI_Controller {

	private $parents = 'SPP';
	private $icon	 = 'fa fa-money';
	var $table 		 = 'spp';

	function __construct(){
		parent::__construct();

		is_login();
		get_breadcrumb();
		//$this->load->model('M_'.$this->parents,'mod');
		$this->load->library('form_validation');
		$this->load->library('Datatables'); 
	}

	public function index(){

		$this->breadcrumb->append_crumb('SIM Sekolah ','Beranda');
		$this->breadcrumb->append_crumb('Uang '.$this->parents,$this->parents);

		$data['title']	= 'Pembayaran Uang '.$this->parents.' | SIM Sekolah ';
		$data['judul']	= 'Pembayaran Uang '.$this->parents;
		$data['icon']	= $this->icon;

	$this->template->views('Backend/'.$this->parents.'/v_'.$this->parents,$data);
	}

	function getData (){
		header('Content-Type:application/json');
		$kls = $this->input->post('is_kelas');
		echo $this->M_General->getSiswa($kls);
	}

	function getSPP(){
		header('Content-Type:application/json');
		$n = $this->db->query("SELECT nominal FROM pembayaran WHERE id = 1")->row_array();
		echo json_encode($n['nominal']);
	}

	function Detail($id){
		$this->breadcrumb->append_crumb('SIM Sekolah ',base_url());
		$this->breadcrumb->append_crumb($this->parents,base_url('SPP'));
		$this->breadcrumb->append_crumb('Detail Pembayaran SPP',$this->parents);

		$data['title']	= 'Pembayaran Uang '.$this->parents.' | SIM Sekolah ';
		$data['judul']	= 'Pembayaran Uang '.$this->parents;
		$data['icon']	= $this->icon;
		$data['isi']	= $this->M_General->getByID('spp','id_siswa',$id,'DESC')->result();

	$this->template->views('Backend/'.$this->parents.'/v_Detail',$data);

	}

	function Simpan(){

		$id = $this->input->post('id',TRUE);
		$bln = filter_string($this->input->post('bulan',TRUE));
		$cek = $this->db->query("SELECT id FROM spp WHERE id_siswa = '$id' AND bulan = '$bln' ")->num_rows();

		if ($cek > 0){
			$data['status'] = FALSE;
    	}
    	else{

    		$total = filter_string($this->input->post('harga',TRUE));
    		$insert = array(
	                    'id_siswa'	=> $id,
	                    'time'	   => waktu(),
	                    'bulan'		=> $bln,
	                    'nominal'	=> $total
	                );

	        $insert = $this->M_General->insert($this->table,$insert);
	        $this->M_General->update_kas('kas_masuk',$total);
	        $data['status'] = TRUE;
    		
    	}
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

}