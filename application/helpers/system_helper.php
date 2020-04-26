<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	
    function is_login(){
        $CI = get_instance();

        if (empty($CI->session->userdata('id'))){
            redirect('Auth/logout','refresh');
        }   
    }

    function activate_menu($controller){
		$CI = get_instance();

		$class = $CI->router->fetch_class();
		return ($class == $controller) ? 'active':'';
	}

        
    function rupiah ($angka){
        $num = number_format($angka,0,',','.');
        return 'Rp '.$num;
    }

	function get_breadcrumb(){
		$ci=& get_instance();
		$config['divider'] = '<span> » </span>';
		return $ci->breadcrumb->initialize($config);
	}

    function alert(){
        $ci =& get_instance();
        $success=$ci->session->flashdata('success');
        $error=$ci->session->flashdata('error');
        if($success!=""){
            $return='<script>
                        window.onload= function(){
                            swal("Sukses !", "'.$success.'", "success");
                        };
                     </script>
                    ';
        }elseif($error!=""){
            $return='<script>
                        window.onload= function(){
                            swal("Error !", "'.$error.'", "error");
                        };
                     </script>
                    ';
        }else{
            $return="";
        }
        return $return;
    }

	function filter_string($data){
		return htmlspecialchars(htmlentities(trim($data)));
	}

	function waktu(){
        date_default_timezone_set('Asia/Jakarta');
        return date("Y-m-d");
	}

    function sekarang(){
        date_default_timezone_set('Asia/Jakarta');
        return date("ymd"); 
    }

	function tanggal($tgl,$bul){
        return substr($tgl, 8, 2).' '.$bul(substr($tgl, 5,2)).' '.substr($tgl, 0, 4);
    }

    function jam($tgl){
       	return substr($tgl,11,5);
    }

    function hari($tgl,$har){
        date_default_timezone_set('Asia/Jakarta');
    	return $har(date('N',strtotime($tgl)));
    }
        
	function bulan($bln){
        switch ($bln){    
            case 1:return "Januari";break;
            case 2:return "Februari";break;
            case 3:return "Maret";break;
            case 4:return "April";break;
            case 5:return "Mei";break;
            case 6:return "Juni";break;
            case 7:return "Juli";break;
            case 8:return "Agustus";break;
            case 9:return "September";break;
            case 10:return "Oktober";break;
            case 11:return "November";break;
            case 12:return "Desember";break;
        }   
    }

    function bln($bln){
        switch ($bln){    
            case 1:return "Jan";break;
            case 2:return "Feb";break;
            case 3:return "Mar";break;
            case 4:return "Apr";break;
            case 5:return "Mei";break;
            case 6:return "Jun";break;
            case 7:return "Jul";break;
            case 8:return "Agu";break;
            case 9:return "Sep";break;
            case 10:return "Okt";break;
            case 11:return "Nov";break;
            case 12:return "Des";break;
        }   
    }

    function gethari($hari){
        $seminggu = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
        return $seminggu[$hari];
    }

    function _gethari($hari){
        $seminggu = array("Min","Sen","Sel","Rab","Kam","Jum","Sab");
        return $seminggu[$hari];
    }

    function activity_log ($item,$aksi){

        $CI =& get_instance();

       // $param ['user']         = $this->session->userdata('level');
        $param ['item']         = $item;
        $param ['aksi']         = $aksi;

        $CI->M_General->save_log($param);
    }


