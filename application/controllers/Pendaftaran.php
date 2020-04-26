<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendaftaran extends CI_Controller {

	private $parents = 'Pendaftaran';
	private $icon	 = 'fa fa-file-text-o';
	var $table 		 = 'temp';
	private $filename = "import_pendaftaran"; 

	function __construct(){
		parent::__construct();

		is_login();
		get_breadcrumb();
		//$this->load->model('M_'.$this->parents,'mod');
		$this->load->library('form_validation');
		$this->load->library('Datatables'); 
	}

	public function index(){

		$this->load->helper('data');

		$this->breadcrumb->append_crumb('SIM Sekolah ','Beranda');
		$this->breadcrumb->append_crumb($this->parents,$this->parents);

		$data['title']	= $this->parents.' | SIM Sekolah ';
		$data['judul']	= $this->parents;
		$data['icon']	= $this->icon;
		$data['isi']	= $this->db->query("SELECT id,name,nis,alamat,sex,wali,bayar,tempat,tanggal FROM temp")->result();
		$data['bayar']	= $this->db->query("SELECT nominal FROM pembayaran WHERE id = 5 ")->row_array();

	$this->template->views('Backend/'.$this->parents.'/v_'.$this->parents,$data);
	}

	function import(){

		$upload = $this->M_General->upload_file($this->filename);
	
		if ($upload['status'] == true){  
		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$excelreader = new PHPExcel_Reader_Excel2007();
		$loadexcel = $excelreader->load('excel/'.$this->filename.'.xlsx');
		$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
		
		$data = array();
		$numrow = 1;
		foreach($sheet as $row){
			if($numrow > 1){
				// Kita push (add) array data ke variabel data
				array_push($data, array(
					'name'=>$row['A'],
					'nis'=>$row['B'],
					'tempat'=>$row['C'],
					'tanggal'=>$row['D'],
					'sex'=>$row['E'],
					'wali'=>$row['F'],
					'alamat'=>$row['G'],
				));
			}
			
			$numrow++;
		}
		$this->db->insert_batch('temp',$data);
		 $this->M_General->delete('temp','tanggal','0000-00-00');
		
       $this->session->set_flashdata('success','Berhasil import Data Baru!');
    	}
    	else{
    		$this->session->set_flashdata('error','Gagal import Data Baru!');
    	}
     redirect($this->uri->segment(1),'refresh');
	}

	function Simpan(){
        $insert = array(
                    'name'  	=> filter_string(ucwords($this->input->post('nama'),TRUE)),
                    'sex'		=> $this->input->post('gender',TRUE),
                    'nis' 		=> $this->input->post('nis',TRUE),
                    'tempat'	=> filter_string($this->input->post('tempat',TRUE)),
                    'tanggal'	=> filter_string($this->input->post('tanggal',TRUE)),
                    'alamat'	=> filter_string($this->input->post('alamat',TRUE)),
                    'wali'		=> filter_string($this->input->post('wali',TRUE))
                );

        $insert = $this->M_General->insert($this->table,$insert);
        $this->session->set_flashdata('success','Berhasil menambahkan Data Baru!');

        redirect($this->uri->segment(1),'refresh');
	}

	function Bayar(){
		$id = $this->input->post('id');
		$har = $this->input->post('harga');
		$nam = $this->input->post('nama');
		$this->db->where('id',$id);
		$this->db->update('temp',array('bayar'=>'1'));

		$this->db->insert('pendaftaran',array('siswa'=>$nam,'nominal'=>$har,'time'	    => waktu(),));
		$this->M_General->update_kas('kas_masuk',$har);

		 $this->session->set_flashdata('success','Pembayaran Uang Pendaftaran Berhasil!');

		redirect($this->uri->segment(1),'refresh');
	}

	function Kelas(){
		        $insert = array(
                    'name'  	=> filter_string(ucwords($this->input->post('nama'),TRUE)),
                    'sex'		=> $this->input->post('sex',TRUE),
                    'nis' 		=> $this->input->post('nis',TRUE),
                    'status' 	=> 'Aktif',
                    'kelas'		=> $this->input->post('kelas'),
                    'tempat'	=> filter_string($this->input->post('tempat',TRUE)),
                    'tanggal'	=> filter_string($this->input->post('tanggal',TRUE)),
                    'alamat'	=> filter_string($this->input->post('alamat',TRUE)),
                    'wali'		=> filter_string($this->input->post('wali',TRUE))
                );	
        $this->M_General->insert('siswa',$insert);
        $this->M_General->delete($this->table,'id',$this->input->post('id'));

        $this->session->set_flashdata('success','Siswa Baru Berhasil ditambahkan!');

        redirect($this->uri->segment(1),'refresh');	
	}

	function Hapus(){
		$id = $this->input->post('id');

		$this->M_General->delete('temp','id',$id);
		$this->session->set_flashdata('success','Berhasil Menghapus Data ');
		redirect($this->uri->segment(1));
	}

	function Ubah(){
        $insert = array(
                    'name'  	=> filter_string(ucwords($this->input->post('nama'),TRUE)),
                    'sex'		=> $this->input->post('gender',TRUE),
                    'nis' 		=> $this->input->post('nis',TRUE),
                    'tempat'	=> filter_string($this->input->post('tempat',TRUE)),
                    'tanggal'	=> filter_string($this->input->post('tanggal',TRUE)),
                    'alamat'	=> filter_string($this->input->post('alamat',TRUE)),
                    'wali'		=> filter_string($this->input->post('wali',TRUE))
                );
        $insert = $this->M_General->update($this->table,$insert,'id',$this->input->post('id'));
        $data['status'] = TRUE;
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
	}
}