<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct(){
		parent::__construct();

		$this->load->library('form_validation');
	}

	public function index(){

			if ($this->session->userdata('id')){
			redirect('Beranda','refresh');
		}

		$this->form_validation->set_rules('email','Email','trim|required|valid_email');
		$this->form_validation->set_rules('password','Password','trim|required');

		if ($this->form_validation->run() == false) {
				$data['title']	= 'Halaman Login | SIM ';
				$this->load->view('v_Login',$data);
		} 
		else {
			$this->_login();
		}
	}

	private function _login(){

		$email = $this->input->post('email');
		$password = $this->input->post('password');

		$user = $this->db->get_where('users', ['email'=> $email])->row_array();

		if ($user){
			if ($user['active'] == 1){
				if (password_verify($password, $user['password'])){
					$data = array(
						'id' 	=> $user['id'],
						'role'	=> $user['role']
					);
				$this->session->set_userdata( $data );
					$this->M_General->cek_laporan();
				redirect('Beranda','refresh');
				}
				else{
					$this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>  Password Salah</div>');
		redirect($this->uri->segment(1),'refresh');	
				}
			}
			else{
				$this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>  Akun belum aktif</div>');
		redirect($this->uri->segment(1),'refresh');	
			}
		}
		else{
			$this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>  Email tidak terdaftar</div>');
		redirect($this->uri->segment(1),'refresh');	
		}
	}

	function Simpan(){

		$id   = $this->session->userdata('id');
		$pass = $this->input->post('lama');
		$baru = password_hash($this->input->post('baru'), PASSWORD_DEFAULT);
		$user = $this->db->get_where('users', ['id'=> $id])->row_array();

		if (password_verify($pass, $user['password'])){
				$this->db->where('id',$id);
				$this->db->update('users',array('password'=>$baru));
				$data['status'] = true;
		}else{
			$data['status'] = false;
		}
		 $this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	public function registrasi (){

		if ($this->session->userdata('id')){
			redirect('Beranda','refresh');
		}

		$this->form_validation->set_rules('name','Nama','trim|required');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password','Password','trim|required|min_length[6]|matches[password2]');
		$this->form_validation->set_rules('password2','Kofirmasi Password','trim|required|matches[password]');

		if ($this->form_validation->run() == false) {
				$data['title']	= 'Halaman Registrasi | SIM ';
				$this->load->view('v_Registrasi',$data);
		} 
		else {
			$email = filter_string($this->input->post('email',true));
			$data = array(
					'name' 		=> filter_string($this->input->post('name',true)),
					'email' 	=> $email,
					'gambar'	=> 'user.png',
					'password'	=> password_hash($this->input->post('password'), PASSWORD_DEFAULT),
					'role'		=> 2,
					'active'	=> 0,
					'created_on'=> time()
			);

		$token = base64_encode(random_bytes(32));

		$user_token = array (
			'email'			=> 	$email,
			'token'			=> 	$token,
			'date_created'	=>	time()	
		);

		$this->M_General->insert('users',$data);
		$this->M_General->insert('user_token',$user_token);
		$this->_sendEmail($token,'verify');

		$this->session->set_flashdata('message','<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Berhasil Mendaftar, kiik link aktivasi yang telah terkirim di email tersebut</div>');
		redirect($this->uri->segment(1),'refresh');
		}
	}

	private function _sendEmail($token, $type){

		$config = [
			'protocol' 	=>	'smtp',
			'smtp_host'	=>	'ssl://smtp.gmail.com',
			'smtp_user'	=>	'Your Email',
			'smtp_pass'	=>	'Your Password',
			'smtp_port'	=>	465,
			'mailtype'	=>	'html',
			'charset'	=>	'utf-8',
			'newline'	=>	"\r\n"
		];

		$this->load->library('email', $config);
		$this->email->from('Your Email','Layanan Aktivasi Akun');
		$this->email->to($this->input->post('email'));

		if ($type == 'verify'){
			$this->email->subject('Link Aktivasi Akun');
			$this->email->message('Klik link ini untuk aktivasi akun : <a href="'.base_url().'Auth/verify?email='.$this->input->post('email').'&token='.urlencode($token).'"> Aktifasi</a>');
		}

		if($this->email->send()){
			return true;
		}
		else{
			echo $this->email->print_debugger();
			die;
		}

	}

	public function verify(){

		$email = $this->input->get('email');
		$token = $this->input->get('token');

		$user = $this->db->get_where('users',['email'=>$email])->row_array();

		if ($user){
			$user_token = $this->db->get_where('user_token',['token'=>$token])->row_array();
			if ($user_token){
				if (time() - $user_token['date_created'] < (60*60*24)){

					$this->db->set('active',1);
					$this->db->where('email',$email);
					$this->db->update('users');
					$this->db->delete('user_token',['email'=>$email]);

					$this->session->set_flashdata('message','<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> '.$email.' Telah diaktifasi! Silahkan Login </div>');
		redirect($this->uri->segment(1));

				}	
				else{
					$this->db->delete('users',['email'=>$email]);
					$this->db->delete('user_token',['email'=>$email]);

					$this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>  Token Expired</div>');
		redirect($this->uri->segment(1));
				}
			}
			else{
				$this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>  Token Salah</div>');
		redirect($this->uri->segment(1));

			}
		}
		else{
			$this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>  Email tidak valid</div>');
		redirect($this->uri->segment(1));
		}

	}

	public function logout(){
		$this->session->sess_destroy();
		redirect('Auth');
	}
}

/* End of file Beranda.php */
/* Location: ./application/controllers/Beranda.php */