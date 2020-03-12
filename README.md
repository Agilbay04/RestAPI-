# Praktikum 4 RestAPI-
*Sumber:* https://www.codepolitan.com/rest-api-server-sederhana-dengan-codeigniter-58901f324a29f

## REST
  Singkatan dari bahasa inggris dari *Representational Stae Transfer*, adalah suatu gaya arsitektur perangkat lunak untuk pendistribusian sistem hipermedia seperti *www.* Di perkenalkan pertama kali pada tahun 2000 pada disertasi doktoral roy fielding.
  Dalam penerapan REST pada Codeigniter diperlukan beberapa library tambahan yang tidak disediakan secara *default* pada Codeigniter.


### Persiapan
  Dalam pembuatan Rest API server diperlukan :
  1. Webserver XAMPP
  2. Framework Codeigniter dan Library REST server dapat di unduh di link ini:
     *https://github.com/ardisaurus/ci-restserver*
  3. Postman
     
  Setelah semua tools yang diperlukan sudah siap,install Postman, kemudian extract Codeigniter dan library REST server yang telah didownload dan pindah ke dalam direktori xampp/htdocs ubah nama direktori hasil ekstrakan tadi dengan nama rest_ci.
  Hidupkan xampp webserver, masukkan link *http://127.0.0.1/rest_ci/index.php/rest_server* ke addres bar browser, jika berhasil maka akan muncul halaman utama *Rest Server Tests*.
  

### Konfigurasi database
* Buat database dengan nama "kontak" :
```
CREATE DATABASE kontak;
```
* Buat tabel baru dengan nama "telpon" :
```
USE kontak;
CREATE TABLE IF NOT EXIST `telepon`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `nomor` varchar(13) NOT NULL,
  PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=8;
```
* Masukkan contoh beberapa data :
```
USE kontak;
INSERT INTO `telepon` (`id`, `nama`, `nomor`) VALUES
(1, 'Orion', '08576666762'),
(2, 'Mars', '08576666770'),
(7, 'Alpha', '08576666765');
```
* Lakukan config database pada file database.php pada rest_ci/application/config :
```
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn'   => '',
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'kontak',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
```

# GET
  Metode GET digunakan untuk membaca data dari tabel telepon pada database kontak. Untuk membaca data dari database tersebut fungsi GET dibuat terlebih dahulu. Buat file php baru di rest_ci/application/conroller dengan nama kontak.php.
```
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Kontak extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }

    //Menampilkan data kontak
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

    //Masukan function selanjutnya disini
}
?>
```
Untuk menguji kode tersebut buka Postman, pilih metode GET, masukkan *http://127.0.0.1/rest_ci/index.php/kontak* pada addressbbar lalu klik "Send", jika berhasil maka data dari tabel telepon akan muncul.

# POST
  Metode POST digunakan untuk mengirimkan data baru ke client server REST API, atau bisa dibilang membuat data baru kedalam tabel telepon. berikut fungsinya masukkan pada controller kontak.php.
```
//Mengirim atau menambah data kontak baru
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

 //Masukan function selanjutnya disini
```
Untuk mengujinya buka Postman, pilih metode POST, masukan *http://127.0.0.1/rest_ci/index.php/kontak* pada address bar, klik "Body" pada menu dibawah address bar, pilih x-www-form-urlencoded, masukan key dan value yang diperlukan (id, nama, nomor), lalu klik "Send".

# PUT
  Metode PUT digunakan untuk memperbarui atau mengedit data yang ada diserver REST API.
```
//Memperbarui data kontak yang telah ada
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

//Masukan function selanjutnya disini
```
Untuk mengujinya buka Postman, pilih metode PUT, masukan *http://127.0.0.1/rest_ci/index.php/kontak* pada address bar, klik "Body" pada menu dibawah address bar, pilih x-www-form-urlencoded, masukan key id dan value id yang akan diubah (88) diikuti key dan value selanjutnya, lalu klik "Send".

# DELETE
  Metode DELETE digunakan untuk menghapus data yang telah ada di REST API.
berikut fungsinya taruh didalam controller kontak.php.
```
Menghapus salah satu data kontak
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
```
Untuk mengujinya buka Postman, pilih metode DELETE, masukan *http://127.0.0.1/rest_ci/index.php/kontak* pada address bar, klik "Body" pada menu dibawah address bar, pilih x-www-form-urlencoded, masukan key id dan value id yang akan dihapus (88), lalu klik "Send".
  
