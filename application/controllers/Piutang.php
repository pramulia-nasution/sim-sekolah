<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Piutang extends CI_Controller {

	private $parents = 'Piutang';
	private $icon	 = 'fa fa-file-text-o';

	function __construct(){
		parent::__construct();

		is_login();
		get_breadcrumb();
	}

	public function index(){

		$this->breadcrumb->append_crumb('SIM Sekolah','Beranda');
		$this->breadcrumb->append_crumb($this->parents,$this->parents);

		$data['title']	= $this->parents.' | SIM Sekolah';
		$data['judul']	= $this->parents;
		$data['icon']	= $this->icon;

	$this->template->views('v_'.$this->parents,$data);
	}
}

/* End of file Beranda.php */
/* Location: ./application/controllers/Beranda.php */