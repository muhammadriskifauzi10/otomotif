<?php
class Auth
{
    private $localhost = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbname = 'otomotif';

    protected $mysqli;
    protected $stmt;

    public $authUserName;
    public function __construct()
    {
        $this->mysqli = new mysqli($this->localhost, $this->username, $this->password, $this->dbname);

        if ($this->mysqli->connect_error) {
            die('Koneksi database gagal!');
        }
    }
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
            }
        }
    }
}

$auth = new Auth();
$auth->getDataUser();