<?php

include 'application/Services.php';

use Google\Client;
use Google\Service\Oauth2;

class Google extends Services
{
    public function __construct()
    {
        parent::__construct();
        $client = new Client();
        $client->setAuthConfig('secret.json');
        $client->addScope('https://www.googleapis.com/auth/plus.login');

        if (!isset($_SESSION['access_token'])) {
            if (isset($_GET['code'])) {
                $client->fetchAccessTokenWithAuthCode($_GET['code']);
                $accessToken = $client->getAccessToken();
                $_SESSION['access_token'] = $accessToken;

                // Buat objek Google_Service_Oauth2 untuk mengakses layanan OAuth2.
                $service = new Oauth2($client);

                // Panggil API untuk mendapatkan informasi alamat email pengguna.
                $userInfo = $service->userinfo->get();

                $email = $userInfo->getEmail();
                $sql = "SELECT * FROM users WHERE email=?";
                $this->stmt = $this->mysqli->prepare($sql);
                $this->stmt->bind_param('s', $email);

                if ($this->stmt->execute()) {
                    $result = $this->stmt->get_result();
                    if ($result->num_rows > 0) {
                        $_SESSION['email'] = $userInfo->getEmail();
                    } else {
                        $active = 1;
                        $sql = "INSERT INTO users (is_active, email) VALUES (?, ?)";
                        $this->stmt = $this->mysqli->prepare($sql);
                        $this->stmt->bind_param('is', $active, $email);
                        if ($this->stmt->execute()) {
                            $_SESSION['username'] = $userInfo->getName();
                            $_SESSION['email'] = $userInfo->getEmail();
                        }
                    }
                }

                header('location: index.php');
            } else {
                $authUrl = $client->createAuthUrl(['scope' => 'email profile']);
                header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
            }
        } else {
            header('location: index.php');
        }
    }
}

new Google();
