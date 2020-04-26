<?php if ( !defined('BASEPATH')) exit();
class pdf{
    function __construct()
    {
        require_once APPPATH.'/third_party/fpdf/fpdf.php';
    }
}