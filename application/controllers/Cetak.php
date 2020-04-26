<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cetak extends CI_Controller{
    function __construct() {
        parent::__construct();
         $this->load->library('pdf');
          is_login();
    }

    function cetak_laporan($id){

      $data = $this->M_General->get_Laporan($id);
      $tgl = $data['tanggal'];
      $awal = $this->db->query("SELECT saldo_awal FROM laporan WHERE id = '$id'")->row_array();
      $cat = 0;
      $snak = 0;
      $sp =0;
      $uji = 0;
      $pend = 0;
      $gaj = 0;
      foreach ($data['catering'] as $k) {
        $cat +=$k->total;
      }
      foreach ($data['snack'] as $k) {
        $snak +=$k->total;
      }
      foreach ($data['spp'] as $k) {
        $sp +=$k->nominal;
      }
      foreach ($data['ujian'] as $k) {
        $uji +=$k->nominal;
      }
      foreach ($data['pendaftaran'] as $k) {
        $pend +=$k->nominal;
      }
      foreach ($data['gaji'] as $k) {
        $gaj +=$k->gaji;
      }

      $pdf = new FPDF('p','mm','A4');
        $pdf->AddPage();
       // head
       
       $pdf->Cell(3,5,'',0,1);
       $pdf->Image(base_url().'/assets/dist/img/j.png', 177, 10,29);
       $pdf->Image(base_url().'/assets/dist/img/dik.png', 2, 10,33);
       $pdf->Cell(3,-5,'',0,1);
       $pdf->SetFont('TIMES','B',14);
       $pdf->Cell(189, 5, 'MAJELIS PENDIDIKAN DASAR DAN MENENGAH', 0, 1, 'C');
       $pdf->Cell(189, 7, 'PIMPINAN CABANG MUHAMMADIYAH KAMPAR', 0, 1, 'C');
       $pdf->SetFont('TIMES','B',16);
       $pdf->Cell(192, 7, 'SD MUHAMMADIYAH KAMPA I FULL DAY SCHOOL', 0, 1, 'C');
       $pdf->SetFont('TIMES','',12);
       $pdf->Cell(189, 5, 'Jalan Melur Pekanbaru 40513, Riau', 0, 1, 'C');
       $pdf->Cell(189, 5, 'Telp.(022) 6645951,Fax(022)6645951', 0, 1, 'C');
       $pdf->Cell(189, 5, 'E-mail : sd.muhammadiyah.kampa1@gmail.com', 0, 1, 'C');
       $pdf->SetLineWidth(1);
       $pdf->Line(9, 46, 203, 46);
       $pdf->SetLineWidth(0);
       $pdf->Line(9, 47, 203, 47);
       
       $pdf->Cell(3,8,'',0,1);
       $pdf->SetFont('TIMES','',12);
       $pdf->Cell(10, 5, 'Laporan Kas Masuk dan Keluar Pertanggal : '.tanggal($tgl,'bulan'), 0, 1);
       $pdf->SetFont('TIMES','B',12);
       $pdf->Cell(10,9, '- Saldo Awal ', 0, 1);
       $pdf->Cell(191,4,rupiah($awal['saldo_awal']), 0, 1,'R');
       $pdf->SetFont('TIMES','B',12);
       $pdf->Cell(10,9, '- Kas Masuk ', 0, 1);
       // kasi jarak
       $pdf->Cell(5,0,'',0,1);
       $pdf->SetFont('TIMES','B',11);
       $pdf->Cell(7, 5, 'No', 1, 0,'C');
       $pdf->Cell(132, 5, 'Keterangan', 1, 0,'C');
       $pdf->Cell(53, 5, 'Nominal', 1, 1,'C');
       $pdf->SetFont('TIMES','',10);
       $pdf->Cell(7, 5, '1', 1, 0,'C');
       $pdf->Cell(132, 5, 'Uang Catering', 1, 0);
       $pdf->Cell(53, 5,rupiah($cat), 1, 1,'R');
       $pdf->Cell(7, 5, '2', 1, 0,'C');
       $pdf->Cell(132, 5, 'Uang Snack', 1, 0);
       $pdf->Cell(53, 5,rupiah($snak), 1, 1,'R');
       $pdf->Cell(7, 5, '3', 1, 0,'C');
       $pdf->Cell(132, 5, 'Uang SPP', 1, 0);
       $pdf->Cell(53, 5,rupiah($sp), 1, 1,'R');
       $pdf->Cell(7, 5, '4', 1, 0,'C');
       $pdf->Cell(132, 5, 'Uang ujian', 1, 0);
       $pdf->Cell(53, 5,rupiah($uji), 1, 1,'R');
       $pdf->Cell(7, 5, '5', 1, 0,'C');
       $pdf->Cell(132, 5, 'Uang Pendaftaran', 1, 0);
       $pdf->Cell(53, 5,rupiah($pend), 1, 1,'R');

$t=6;
$pem = 0;
foreach($data['pemasukan'] as $r){
       $pem+=$r->nominal;
       $pdf->Cell(7, 5,$t++, 1, 0,'C');
       $pdf->Cell(132, 5, $r->keterangan, 1, 0);
       $pdf->Cell(53, 5,rupiah($r->nominal), 1, 1,'R');
}
  $sum = $pem+$cat+$snak+$sp+$uji+$pend;
       $pdf->SetFont('TIMES','B',11);
       $pdf->Cell(139, 5, 'Total Pemasukan', 1, 0,'C');
       $pdf->Cell(53, 5,rupiah($sum), 1, 1,'R');

       $pdf->SetFont('TIMES','B',12);
       $pdf->Cell(0,4,'',0,1);
       $pdf->Cell(7,9, '- Kas Keluar ', 0, 1);
       // kasi jarak
       $pdf->Cell(5,0,'',0,1);
       $pdf->SetFont('TIMES','B',11);
       $pdf->Cell(7, 5, 'No', 1, 0,'C');
       $pdf->Cell(132, 5, 'Keterangan', 1, 0,'C');
       $pdf->Cell(53, 5, 'Nominal', 1, 1,'C');
       $pdf->SetFont('TIMES','',10);
       $pdf->Cell(7, 5, '1', 1, 0,'C');
       $pdf->Cell(132, 5, 'Pembayaran Gaji', 1, 0);
       $pdf->Cell(53, 5,rupiah($gaj), 1, 1,'R');
$t=2;
$pen = 0;
foreach($data['pengeluaran'] as $r){
       $pen+=$r->nominal;
       $pdf->Cell(7, 5,$t++, 1, 0,'C');
       $pdf->Cell(132, 5, $r->keterangan, 1, 0);
       $pdf->Cell(53, 5,rupiah($r->nominal), 1, 1,'R');
}
       $sum1 = $gaj+$pen;
       $pdf->SetFont('TIMES','B',11);
       $pdf->Cell(139, 5, 'Total Pengeluaran', 1, 0,'C');
       $pdf->Cell(53, 5,rupiah($sum1), 1, 1,'R');
       $akhir = $awal['saldo_awal']+$sum-$sum1;
       $pdf->SetFont('TIMES','B',12);
       $pdf->Cell(5,2,'',0,1);
       $pdf->Cell(10,9, '- Saldo Akhir ', 0, 1);
       $pdf->Cell(191,4,rupiah($akhir), 0, 1,'R');
 



       $pdf->SetFont('TIMES','',12);
       $pdf->Cell(125, 35, '', 0, 1);
       $pdf->Cell(125, 35, '', 0, 0);
       $pdf->Cell(55, 5, 'Kampa, '.  tanggal(waktu(),'bulan'), 0, 1);
       $pdf->Cell(125, 5, '', 0, 0);
       $pdf->Cell(35, 5, 'Kepala Sekolah,', 0, 1);
       $pdf->Cell(125, 10, '', 0, 0);
       $pdf->Cell(35, 14, '', 0, 1);
       $pdf->Cell(125, 8, '', 0, 0);
       $pdf->Cell(35, 9, 'Rahmad, S.Ag, M.Sy', 0, 0);
       $pdf->Output();  
    }

    function Cetak_periode(){

        $awal = $this->input->post('awal');
        $akhir = $this->input->post('akhir');

        $this->db->where('tanggal >=',$awal);
        $this->db->where('tanggal <=',$akhir);
        $a = $this->db->get('laporan')->result();

        $pdf = new FPDF('p','mm','A4');
        $pdf->AddPage();
       // head
       
       $pdf->Cell(3,5,'',0,1);
       $pdf->Image(base_url().'/assets/dist/img/j.png', 177, 10,29);
       $pdf->Image(base_url().'/assets/dist/img/dik.png', 2, 10,33);
       $pdf->Cell(3,-5,'',0,1);
       $pdf->SetFont('TIMES','B',14);
       $pdf->Cell(189, 5, 'MAJELIS PENDIDIKAN DASAR DAN MENENGAH', 0, 1, 'C');
       $pdf->Cell(189, 7, 'PIMPINAN CABANG MUHAMMADIYAH KAMPAR', 0, 1, 'C');
       $pdf->SetFont('TIMES','B',16);
       $pdf->Cell(192, 7, 'SD MUHAMMADIYAH KAMPA I FULL DAY SCHOOL', 0, 1, 'C');
       $pdf->SetFont('TIMES','',12);
       $pdf->Cell(189, 5, 'Jalan Melur Pekanbaru 40513, Riau', 0, 1, 'C');
       $pdf->Cell(189, 5, 'Telp.(022) 6645951,Fax(022)6645951', 0, 1, 'C');
       $pdf->Cell(189, 5, 'E-mail : sd.muhammadiyah.kampa1@gmail.com', 0, 1, 'C');
       $pdf->SetLineWidth(1);
       $pdf->Line(9, 46, 203, 46);
       $pdf->SetLineWidth(0);
       $pdf->Line(9, 47, 203, 47);

       $pdf->Cell(3,8,'',0,1);
       $pdf->SetFont('TIMES','',12);
       $pdf->Cell(10, 5, 'Laporan Kas Masuk dan Keluar Pertanggal : '.tanggal($awal,'bulan').' - '.tanggal($akhir,'bulan'), 0, 1);

       $pdf->Cell(5,5,'',0,1);
       $pdf->SetFont('TIMES','B',11);
       $pdf->Cell(7, 5, 'No', 1, 0,'C');
       $pdf->Cell(40, 5, 'Tanggal', 1, 0,'C');
       $pdf->Cell(35, 5, 'Saldo Awal', 1, 0,'C');
       $pdf->Cell(35, 5, 'Kas Masuk', 1, 0,'C');
       $pdf->Cell(35, 5, 'Kas Keluar', 1, 0,'C');
       $pdf->Cell(35, 5, 'Saldo Akhir', 1, 1,'C');
       
       $pdf->SetFont('TIMES','',10);
       $no = 1;
       foreach ($a as $i){
        $saldo = $i->saldo_awal + $i->kas_masuk - $i->kas_keluar;
          $pdf->Cell(7, 5,$no++, 1,0,'C');
          $pdf->Cell(40, 5,tanggal($i->tanggal,'bln'), 1, 0,'C');
          $pdf->Cell(35, 5,rupiah($i->saldo_awal), 1, 0,'C');
          $pdf->Cell(35, 5,rupiah($i->kas_masuk), 1, 0,'C');
          $pdf->Cell(35, 5,rupiah($i->kas_keluar), 1, 0,'C');
          $pdf->Cell(35, 5,rupiah($saldo), 1, 1,'C');
       }

       $pdf->SetFont('TIMES','',12);
       $pdf->Cell(125, 35, '', 0, 1);
       $pdf->Cell(125, 35, '', 0, 0);
       $pdf->Cell(55, 5, 'Kampa, '.  tanggal(waktu(),'bulan'), 0, 1);
       $pdf->Cell(125, 5, '', 0, 0);
       $pdf->Cell(35, 5, 'Kepala Sekolah,', 0, 1);
       $pdf->Cell(125, 10, '', 0, 0);
       $pdf->Cell(35, 14, '', 0, 1);
       $pdf->Cell(125, 8, '', 0, 0);
       $pdf->Cell(35, 9, 'Rahmad, S.Ag, M.Sy', 0, 0);
       $pdf->Output();  

        $data['status'] = TRUE;

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}