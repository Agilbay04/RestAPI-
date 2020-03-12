<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Kontak extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }

    /*
    |-----------------------------------------------------------------------------------------
    | Menampilkan data Kontak
    |-----------------------------------------------------------------------------------------
    | GET Function 
    | Metode GET menyediakan akses baca pada sumber daya yang disediakan oleh REST API. 
    | Sebagai contohnya digunakan untuk membaca data dari tabel telepon pada database kontak.
    */
    
    function index_get() {
        $id = $this->get('id');
        if ($id == '') {
            $kontak = $this->db->get('telepon')->result();
        } else {
            $this->db->where('id', $id);
            $kontak = $this->db->get('telepon')->result();
        }
        $this->response($kontak, 200);
    }

    /*
    |-----------------------------------------------------------------------------------------
    | Menambah data kontak baru
    |-----------------------------------------------------------------------------------------
    | POST Function 
    | Metode POST digunakan untuk mengirimkan data baru dari client ke server REST API. 
    | Sebagai contohnya digunakan untuk menambahkan kontak baru yang terdiri dari id, nama, 
    | dan nomor.
    */ 
    function index_post() {
        $data = array(
                    'id'           => $this->post('id'),
                    'nama'          => $this->post('nama'),
                    'nomor'    => $this->post('nomor'));
        $insert = $this->db->insert('telepon', $data);
        if ($insert) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    /*
    |-----------------------------------------------------------------------------------------
    | Mengedit data kontak
    |-----------------------------------------------------------------------------------------
    | PUT Function 
    | Metode PUT digunakan untuk memperbarui data yang telah ada di server REST API. 
    | Sebagai contohnya digunakan untuk memperbarui data dengan id 88 pada tabel telepon 
    | database kontak.
    */
       function index_put() {
        $id = $this->put('id');
        $data = array(
                    'id'       => $this->put('id'),
                    'nama'          => $this->put('nama'),
                    'nomor'    => $this->put('nomor'));
        $this->db->where('id', $id);
        $update = $this->db->update('telepon', $data);
        if ($update) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
    
    /*
    |-----------------------------------------------------------------------------------------
    | Menghapus data di table kontak
    |-----------------------------------------------------------------------------------------
    | DELETE Function
    | Metode DELETE digunakan untuk menghapus data yang telah ada di server REST API. 
    | Sebagai contohnya digunakan untuk menghapus data dengan id 1 pada tabel telepon 
    | database kontak.
    */
    function index_delete() {
        $id = $this->delete('id');
        $this->db->where('id', $id);
        $delete = $this->db->delete('telepon');
        if ($delete) {
            $this->response(array('status' => 'success'), 201);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
}
?>