<?php

session_start();

include './vendor/autoload.php';

class Services
{
    private $localhost = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbname = 'otomotif';

    protected $mysqli;
    protected $stmt;
    public function __construct()
    {
        $this->mysqli = new mysqli($this->localhost, $this->username, $this->password, $this->dbname);

        if ($this->mysqli->connect_error) {
            die('Koneksi database gagal!');
        }
    }

    public function createSlug($text)
    {
        // Menghapus karakter khusus, mengonversi huruf kecil, dan mengganti spasi dengan tanda hubung
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9-]+/', '-', $text);
        $text = trim($text, '-');
        $text = preg_replace('/-+/', '-', $text);
        return $text;
    }
}

$services = new Services();
