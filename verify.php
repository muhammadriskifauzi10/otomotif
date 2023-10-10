<?php

include 'application/Services.php';

class Verify extends Services
{
    public function __construct()
    {
        parent::__construct();

        if (isset($_GET['email'])) {
            $sql = "SELECT * FROM users WHERE email=?";
            $this->stmt = $this->mysqli->prepare($sql);
            $this->stmt->bind_param('s', $_GET['email']);
            if ($this->stmt->execute()) {
                $result = $this->stmt->get_result();
                if ($result->num_rows > 0) {
                    $get_data = $result->fetch_assoc();
                    if ($get_data['is_active'] == 0 && $get_data['status'] == 1) {
                        $sql = "SELECT * FROM user_token WHERE token=?";
                        $this->stmt = $this->mysqli->prepare($sql);
                        $this->stmt->bind_param('s', $_GET['token']);
                        if ($this->stmt->execute()) {
                            $result_user_token = $this->stmt->get_result();
                            if ($result_user_token->num_rows > 0) {
                                $sql = "UPDATE users SET is_active=1 WHERE email=?";
                                $this->stmt = $this->mysqli->prepare($sql);
                                $this->stmt->bind_param('s', $get_data['email']);
                                if ($this->stmt->execute()) {
                                    $_SESSION['flash-message'] = '
                                    <div class="alert alert-primary m-2" role="alert">
                                        Selamat akun Anda sudah teraktivasi!
                                    </div>';

                                    header('location: login.php');
                                }
                            } else {
                                header("HTTP/1.0 404 Not Found");
                            }
                        }
                    } else {
                        header("HTTP/1.0 404 Not Found");
                    }
                } else {
                    header("HTTP/1.0 404 Not Found");
                }
            }
        } else {
            header("HTTP/1.0 404 Not Found");
        }
    }
}

new Verify();
