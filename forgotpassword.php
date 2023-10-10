<?php
include 'application/Services.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Forgotpassword extends Services
{
    public function __construct()
    {
        parent::__construct();
    }
    public function post($data = [])
    {
        if (!empty($data)) {
            $sql = "SELECT * FROM users WHERE email=?";

            $this->stmt = $this->mysqli->prepare($sql);

            $this->stmt->bind_param('s', $data['email']);

            if ($this->stmt->execute()) {
                $result = $this->stmt->get_result();
                if ($result->num_rows > 0) {
                    $get_data = $result->fetch_assoc();
                    $token = bin2hex(random_bytes(32));

                    $this->_sendemail($get_data['id'], $get_data['username'], $get_data['email'], $token, 'forgotpassword');

                    $_SESSION['flash-message'] = '
                    <div class="alert alert-primary m-2" role="alert">
                        Terimakasih, silahkan buat kata sandi Anda yang baru melalui email yang baru saja kami kirimkan ke alamat email Anda!
                    </div>';
                } else {
                    $_SESSION['flash-message'] = '
                    <div class="alert alert-danger m-2" role="alert">
                        Opps, maaf akun Anda tidak terdaftar di data kami!
                    </div>';
                }
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

            if ($trigger == "forgotpassword") {

                $sql = "INSERT INTO user_token (user_id, email, token) VALUES (?,?,?)";
                $this->stmt = $this->mysqli->prepare($sql);
                $this->stmt->bind_param('sss', $user_id, $email, $token);
                $this->stmt->execute();

                $mail->Subject = 'Lupa Kata Sandi';
                $mail->isHTML(true); // Aktifkan mode HTML
                $mail->Body = '<html>
                                <body>
                                    <h1>Hai, ' . $username . '👋</h1>
                                    <p>
                                        Silahkan buat kata sandi Anda yang baru, <a href="http://localhost/otomotif/resetpassword.php?email=' . urlencode($email) . '&token=' . urlencode($token) . '">disini</a>
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
    public function __destruct()
    {
        $this->mysqli->close();
        $this->stmt->close();
    }
}

$forgotpassword = new Forgotpassword();

if (!isset($_POST['csrf_token'])) {
    $csrf_token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $csrf_token;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $csrf_token = $_POST['csrf_token'];
    $email = htmlspecialchars($_POST['email']);

    if (isset($csrf_token) && $csrf_token === $_SESSION['csrf_token']) {
        $forgotpassword->post([
            'email' => $email,
        ]);
    }
}

include 'application/templates/head.php';
?>

<div style="height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="card" style="width: 24rem;">
        <?php
        if (isset($_SESSION['flash-message'])) {
            echo $_SESSION['flash-message'];
            unset($_SESSION['flash-message']);
        }
        ?>
        <h3 class="my-3 text-center">Lupa Kata Sandi</h3>
        <div class="card-body">
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" autocomplete="off">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                <div class="mb-2">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" autofocus>
                </div>
                <div>
                    <button type="submit" class="btn btn-success w-100">Reset Kata Sandi</button>
                </div>
            </form>

            <p class="card-text text-center mt-3 mb-3">
                <a href="login.php" class="card-link">Login</a>
            </p>
        </div>
    </div>
</div>

<?php include 'application/templates/footer.php'; ?>