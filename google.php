<?php
require 'vendor/autoload.php';

use Google\Client;

$client = new Client();
$client->setAuthConfig('secret.json');
$client->addScope('https://www.googleapis.com/auth/plus.login');

if (isset($_GET['code'])) {
    $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $accessToken = $client->getAccessToken();

    // Buat objek Google_Service_Oauth2 untuk mengakses layanan OAuth2.
    $service = new Google_Service_Oauth2($client);

    // Panggil API untuk mendapatkan informasi alamat email pengguna.
    $userInfo = $service->userinfo->get();

    echo '<pre>';
    print_r($userInfo);
    echo '</pre>';
    $email = $userInfo->getName();
    echo '<img src="https://lh3.googleusercontent.com/a/ACg8ocL8mB6dpgHXGXrtN8odKlaQtLB7gJPyGaXRRnaP2_w1QGM=s96-c" style="width: 60px; height: 60px; object-fit: cover;">';
    echo '<br>';
    echo 'Alamat Email Pengguna: ' . $email;
} else {
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
}
