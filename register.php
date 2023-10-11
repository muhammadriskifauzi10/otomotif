<?php
include 'application/Services.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Register extends Services
{
    public function __construct()
    {
        parent::__construct();
        if (isset($_SESSION['email'])) {
            header('location: index.php');
        }
    }
    public function post($data = [])
    {
        if (!empty($data)) {
            $sql = "INSERT INTO users (username, slug, email, password) VALUES (?, ?, ?, ?)";

            $this->stmt = $this->mysqli->prepare($sql);

            $this->stmt->bind_param('ssss', $data['username'], $this->createSlug($data['username']), $data['email'], $data['password']);

            if ($this->stmt->execute()) {
                $user_id = $this->stmt->insert_id;
                $token = bin2hex(random_bytes(32));

                $this->_sendemail($user_id, $data['username'], $data['email'], $token, 'verify');

                $_SESSION['flash-message'] = '
                <div class="alert alert-primary m-2" role="alert">
                    Terimakasih sudah membuat akun di 
                    <a href="http://localhost/otomotif" class="text-decoration-none"><strong class="text-dark">otomotif.com</strong></a>
                    <br><br>
                    Selanjutnya, silahkan aktivasi akun Anda melalui email yang kami kirimkan ke email Anda!
                </div>';

                header('location: login.php');
            }
        }
    }
    private function _sendemail($user_id, $username, $email, $token, $trigger)
    {
        $mail = new PHPMailer(true);

        try {
            // Pengaturan server SMTP
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Aktifkan debugging jika diperlukan
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Ganti dengan alamat SMTP server Anda
            $mail->SMTPAuth = true;
            $mail->Username = 'developertestingenv10@gmail.com'; // Ganti dengan nama pengguna SMTP Anda
            $mail->Password = 'yogy jflt rzpq dvfe'; // Ganti dengan kata sandi SMTP Anda
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587; // Port SMTP yang digunakan, bisa berbeda tergantung server Anda

            // Pengaturan email
            $mail->setFrom('developertestingenv10@gmail.com', 'Otomotif');
            $mail->addAddress($email); // Tambahkan alamat penerima

            if ($trigger == "verify") {

                $sql = "INSERT INTO user_token (user_id, email, token) VALUES (?,?,?)";
                $this->stmt = $this->mysqli->prepare($sql);
                $this->stmt->bind_param('sss', $user_id, $email, $token);
                $this->stmt->execute();

                $mail->Subject = 'Aktivasi akun';
                $mail->isHTML(true); // Aktifkan mode HTML
                $mail->Body = '<html>
                                <body>
                                    <h1>Hai, ' . $username . '👋</h1>
                                    <p>
                                        Terimakasih sudah membuat akun di 
                                        <a href="http://localhost/otomotif" class="text-decoration-none">otomotif.com</a>
                                        <br><br>
                                        Selanjutnya, silahkan aktivasi akun Anda 
                                        <a href="http://localhost/otomotif/verify.php?email=' . urlencode($email) . '&token=' . urlencode($token) . '">disini</a>
                                    </p>
                                </body>
                            </html>';
                // Kirim email
                $mail->send();
            }
        } catch (Exception $e) {
            echo "Email gagal terkirim. Pesan error: {$mail->ErrorInfo}";
        }
    }
}

$register = new Register();

if (!isset($_POST['csrf_token'])) {
    $csrf_token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $csrf_token;
}

$username_err;
$email_err;
$password_err;
$password_confirmation_err;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $csrf_token = $_POST['csrf_token'];
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $password_confirmation = $_POST['password_confirmation'];

    if (isset($csrf_token) && $csrf_token === $_SESSION['csrf_token']) {
        $error = 0;

        if ($username == "") {
            $error++;
            $username_err = 'Nama lengkap wajib diisi';
        } else {
            $username_err = '';
        }

        if ($email == "") {
            $error++;
            $email_err = 'Alamat email wajib diisi';
        } else {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error++;
                $email_err = 'Alamat email tidak valid';
            } else {
                $email_err = '';
            }
        }


        if ($password == "") {
            $error++;
            $password_err = 'Kata sandi wajib diisi';
        } else {
            $password_err = '';
        }

        if ($password_confirmation != $password) {
            $error++;
            $password_confirmation_err = 'Pengulangan kata sandi salah';
        } else {
            $password_confirmation_err = '';
        }

        // If validation success
        if ($error === 0) {
            $register->post([
                'username' => $username,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ]);
        }
    }
}

include 'application/templates/head.php';
?>

<div style="height: 100vh; position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center;">
    <div class="bg-info" style="width: 100%; margin: auto; padding: 2px; position: absolute; top: 0; left: 0;">
        <a href="index.php" style="display: block; color: white; text-align: center; text-decoration: none; font-weight: 600;">&laquo Beranda</a>
    </div>
    <div class="card" style="width: 24rem;">
        <h3 class="my-3 text-center">Daftar Akun</h3>
        <div class="card-body">
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" autocomplete="off">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                <div class="mb-2">
                    <label for="username" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control <?= !empty($username_err) ? 'is-invalid' : ''; ?>" name="username" id="username" value="<?= isset($_POST['username']) ? $_POST['username'] : ''; ?>">
                    <?php
                    if (!empty($username_err)) {
                        echo '
                                <div class="invalid-feedback">
                                    ' . $username_err . '
                                </div>
                            ';
                    }
                    ?>
                </div>
                <div class="mb-2">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control <?= !empty($email_err) ? 'is-invalid' : ''; ?>" name="email" id="email" value="<?= isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                    <?php
                    if (!empty($email_err)) {
                        echo '
                                <div class="invalid-feedback">
                                    ' . $email_err . '
                                </div>
                            ';
                    }
                    ?>
                </div>
                <div class="mb-2">
                    <label for="password" class="form-label">Kata sandi</label>
                    <input type="password" class="form-control <?= !empty($password_err) ? 'is-invalid' : ''; ?>" name="password" id="password">
                    <?php
                    if (!empty($password_err)) {
                        echo '
                                <div class="invalid-feedback">
                                    ' . $password_err . '
                                </div>
                            ';
                    }
                    ?>
                </div>
                <div class="mb-2">
                    <label for="password" class="form-label">Konfirmasi Kata sandi</label>
                    <input type="password" class="form-control <?= !empty($password_confirmation_err) ? 'is-invalid' : ''; ?>" name="password_confirmation" id="password_confirmation">
                    <?php
                    if (!empty($password_confirmation_err)) {
                        echo '
                                <div class="invalid-feedback">
                                    ' . $password_confirmation_err . '
                                </div>
                            ';
                    }
                    ?>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary w-100">Daftar</button>
                </div>
            </form>

            <p class="card-text text-center mt-3 mb-3">
                Sudah punya akun?
                <a href="login.php" class="card-link">Masuk</a>
            </p>
        </div>
    </div>
</div>

<?php include 'application/templates/footer.php'; ?>