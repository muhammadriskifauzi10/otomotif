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

class Auth extends Services
{
    public $authUserName;
    public $authUserRoleID;
    public function getDataUser()
    {
        $sql = "SELECT * FROM users WHERE email=?";
        $this->stmt = $this->mysqli->prepare($sql);
        $this->stmt->bind_param('s', $_SESSION['email']);

        if ($this->stmt->execute()) {
            $result = $this->stmt->get_result();
            if ($result->num_rows > 0) {
                $get_data = $result->fetch_assoc();
                $this->authUserName = $get_data['username'];
                $this->authUserRoleID = $get_data['role_id'];
            }
        }
    }
    public function logout()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            unset($_SESSION['email']);
            header('location: index.php');
        }
    }
}

$services = new Services();
$auth = new Auth();
$auth->getDataUser();
