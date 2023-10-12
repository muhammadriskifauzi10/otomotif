<?php

class Servicedata
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
}

new Servicedata();
