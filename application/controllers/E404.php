<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class E404 extends CI_Controller {

      private $parents = 'File Not Found';
      private $icon  = 'fa fa-warning';

  public function index(){

    $data['title']  = $this->parents.' | SIM ';
    $data['judul']  = $this->parents;
    $data['icon'] = $this->icon;
      
      $this->template->views('v_Error',$data);
  }
}
