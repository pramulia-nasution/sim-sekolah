<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Breadcrumb {
	
	private $breadcrumbs	= array();
	private $_divider 		= '';
	private $_li_open 		= '<li>';
	private $_li_close 		= '</li>';
	private $_tag_open 		= '<ul class="breadcrumb">';
	private $_tag_close 	= '</ul>';
	
	public function __construct($params = array()){
		if (count($params) > 0){
			$this->initialize($params);
		}
	log_message('debug', "Breadcrumb Class Initialized");
	}

	function initialize($params = array()){
		if (count($params) > 0){
			foreach ($params as $key => $val){
				if (isset($this->{'_' . $key})){
					$this->{'_' . $key} = $val;
				}
			}
		}
	}
	
	function append_crumb($title, $href){
		if (!$title OR !$href) return;
	$this->breadcrumbs[] = array('title' => $title, 'href' => $href);
	}

	function prepend_crumb($title, $href){
		if (!$title OR !$href) return;	
	array_unshift($this->breadcrumbs, array('title' => $title, 'href' => $href));
	}

	function output(){
		if ($this->breadcrumbs) {
			$output = $this->_tag_open;
			$output .='<i class="fa fa-dashboard"></i> ';
			foreach ($this->breadcrumbs as $key => $crumb) {
				if ($key) $output .= $this->_divider;
				$an=array_keys($this->breadcrumbs);
				if (end($an) == $key) {
					$output .= $this->_li_open.'<span>'. ($crumb['title']).'</span>' . $this->_li_close;
				} 
				else {
					$output .= $this->_li_open.'<a href="' . $crumb['href'] . '" class="active"><b>' . ($crumb['title']) . '</b></a>'.$this->_li_close;
				}
			}
		return $output . $this->_tag_close . PHP_EOL;
		}
	return '';
	}
}