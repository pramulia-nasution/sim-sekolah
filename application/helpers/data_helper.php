<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    function get_kelas ($id){
    $ci=& get_instance();
    $q = $ci->db->query("SELECT nama FROM kelas WHERE id='$id'")->row_array();
    return $q['nama'];
    }

    function get_nama ($id){
    $ci=& get_instance();
    $q = $ci->db->query("SELECT name FROM temp WHERE id='$id'")->row_array();
    return $q['name'];
    }

    function get_siswa($id){
    $ci=& get_instance();
    $q = $ci->db->query("SELECT name FROM siswa WHERE id='$id'")->row_array();
    return $q['name'];
    }

    function get_guru ($id){
    $ci=& get_instance();
    $q = $ci->db->query("SELECT name FROM guru WHERE id='$id'")->row_array();
    return $q['name'];
    }