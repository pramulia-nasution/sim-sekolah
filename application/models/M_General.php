<?php
/**
 * @author P.S Nasution
 */
class M_General extends CI_Model{
   
    public function getAll($tables,$sort,$type){
        $this->db->order_by($sort,$type);
        return $this->db->get($tables);
    }
    
    public function countAll($tables){
        return $this->db->get($tables)->num_rows();
    }

    public function getByID($tables,$pk,$id,$type){
        $this->db->order_by($pk,$type);
        $this->db->where($pk,$id);
        return $this->db->get($tables);
    }

    public function countByID($tables,$pk,$id){
        $this->db->where($pk,$id);
        return $this->db->get($tables)->num_rows();
    }


    public function insert($tables,$data){
        $this->db->insert($tables,$data);
    }
    
    public function update($tables,$data,$pk,$id){
        $this->db->where($pk,$id);
        $this->db->update($tables,$data);
    }
    
    public function delete($tables,$pk,$id){
        $this->db->where($pk,$id);
        $this->db->delete($tables);
    }

    public function truncate ($tables){
        $this->db->truncate($tables);
    }
    
    function login($tables,$username,$password){
       return $this->db->get_where($tables,array('email'=>$username,'password'=>$password));        
    }

    public function save_log ($param){
        $sql = $this->db->insert_string('log',$param);
        $ex  = $this->db->query($sql);
        return $this->db->affected_rows($sql);
    }


    function upload_file($filename){ 
        $this->load->library('upload'); // Load librari upload
        
        $config['upload_path'] = './excel/';
        $config['allowed_types'] = 'xlsx';
        $config['max_size'] = '2048';
        $config['overwrite'] = true;
        $config['file_name'] = $filename;
    
        $this->upload->initialize($config); // Load konfigurasi uploadnya
        if($this->upload->do_upload('file')){ // Lakukan upload dan Cek jika proses upload berhasil
            // Jika berhasil :
            $return = array('status' => true, 'file' => $this->upload->data(), 'error' => '');
            return $return;
        }else{
            // Jika gagal :
            $return = array('status' => false, 'file' => '', 'error' => $this->upload->display_errors());
            return $return;
        }
    }

    public function insert_multiple($data){
        $this->db->insert_batch('siswa', $data);
    }

    function getSiswa($kls = ''){
        $this->datatables->select('id,name,nis,sex');
        $this->datatables->from('siswa');
        $this->datatables->add_column('view','<center><a href="javascript:void(0)" onclick="Detail($1)" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Detail</a> <a href="javascript:void(0)"  onclick="Bayar($1)" class="btn btn-success btn-xs"><i class="fa fa-money"></i> Bayar</a></center> ','id');
        $this->datatables->where('kelas',$kls);
        return $this->datatables->generate();
    }

    function cek_laporan(){
        date_default_timezone_set('Asia/Jakarta');
        $this->db->where('tanggal',date('Y-m-d'));
        $cek = $this->db->get('laporan')->num_rows();
        if ($cek > 0){
        return;
        }
        else{
            $sql = $this->db->query("SELECT kas_masuk,kas_keluar,saldo_awal FROM laporan ORDER BY tanggal DESC LIMIT 1")->row_array();
            if(!empty($sql)){
                $kas_awal = $sql['saldo_awal'] + $sql['kas_masuk'] - $sql['kas_keluar'];
                $this->db->insert('laporan',array('tanggal'=>date('Y-m-d'),'saldo_awal'=>$kas_awal));
            }
            else{
                $this->db->insert('laporan',array('tanggal'=>date('Y-m-d')));
            }
        return;
        }
    }

    function update_kas($tipe,$nominal){
        $ini = $this->db->query("UPDATE laporan SET $tipe = $tipe + '$nominal'  WHERE tanggal = DATE(NOW())");
        return;
    }

    function get_Laporan($id){

        $r = $this->db->query("SELECT tanggal FROM laporan where id = '$id'")->row_array();
        $t = $r['tanggal'];

        $spp = $this->db->query("SELECT s.name, p.bulan,p.nominal FROM spp AS p, siswa AS s WHERE time = '$t' AND s.id = p.id_siswa ")->result();
        $ujian = $this->db->query("SELECT s.name, u.periode,u.nominal FROM ujian as u, siswa as s WHERE time = '$t' AND s.id=u.id_siswa")->result();
        $snack = $this->db->query("SELECT s.name, Sum(n.nominal) AS total,Count(n.id_siswa) AS jumlah FROM snack as n, siswa as s WHERE time = '$t' AND s.id=n.id_siswa GROUP BY s.name")->result();
        $catering = $this->db->query("SELECT s.name, Sum(n.nominal) AS total,Count(n.id_siswa) AS jumlah FROM catering as n, siswa as s WHERE time = '$t' AND s.id=n.id_siswa GROUP BY s.name")->result();
        $pemasukan = $this->db->query("SELECT nominal, keterangan FROM lainnya WHERE time = '$t'")->result();
        $pendaftaran = $this->db->query("SELECT siswa, nominal FROM pendaftaran WHERE time = '$t'")->result();

        $gaji     = $this->db->query("SELECT name, periode, (jam * nominal) as gaji FROM gaji,guru WHERE time = '$t' AND guru.id=id_guru ")->result();
        $pengeluaran = $this->db->query("SELECT nominal, keterangan FROM pengeluaran WHERE time = '$t'")->result();

         return array('catering'=>$catering,'gaji'=>$gaji,'pemasukan'=>$pemasukan,'pendaftaran'=>$pendaftaran,'pengeluaran'=>$pengeluaran,'snack'=>$snack,'spp'=>$spp,'ujian'=>$ujian,'tanggal'=>$t);

    }
}


