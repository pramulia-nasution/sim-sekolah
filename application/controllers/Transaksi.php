<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {

	private $parents = 'Transaksi';
	private $icon	 = 'fa fa-money';
	var $table 		 = 'pembayaran';

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
		$kelas = $this->input->post('is_kelas');
		echo $this->mod->getAllData($kelas);
	}

	function buat_kode($tipe){

		$this->db->select('RIGHT(pembayaran.kode,4) as Kode',FALSE);
		$this->db->where('tipe',$tipe);
		$this->db->order_by('kode','DESC');
		$this->db->limit(1);

		$q = $this->db->get('pembayaran');

		if($q->num_rows() <> 0){

			$data = $q->row();

			$kode = intval($data->Kode) + 1;
		}
		else {

			$kode = 1;
		}

		$kodemax = str_pad($kode,4,"0",STR_PAD_LEFT);
		$kodejadi = $tipe."-".$kodemax;
		$cek = $this->db->last_query();

		echo json_encode ($kodejadi);
	}


	public function edit($id){
		$data = $this->M_General->getByID($this->table,'id',$id,'id')->row();
		echo json_encode($data);
	}

	function Simpan(){
        $insert = array(
                    'kode'		=> $this->input->post('kode',TRUE),
                    'nama'		=> filter_string($this->input->post('nama',TRUE)),
                    'nominal'	=> filter_string($this->input->post('nominal',TRUE)),
                    'tipe'		=> $this->input->post('tipe',TRUE)
                );

        $insert = $this->M_General->insert($this->table,$insert);
        $data['status'] = TRUE;
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	function Ubah(){
		$nom = filter_string($this->input->post('nominal',TRUE));
		$nam = filter_string($this->input->post('nama',TRUE));
        $insert = array(
                             'nominal'	=> $nom
                );
        $insert = $this->M_General->update($this->table,$insert,'id',$this->input->post('id'));
       // activity_log('Data Transaksi','Perubahan data dengan Kode: '.$this->input->post('kode').', Nama: '.$nam.', Nominal: '.$nom);

        $data['status'] = TRUE;
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
	}
}