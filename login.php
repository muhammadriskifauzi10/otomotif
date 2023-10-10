<?php
include 'application/Services.php';

class Login extends Services
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
            $sql = "SELECT * FROM users WHERE email=?";
            $this->stmt = $this->mysqli->prepare($sql);
            $this->stmt->bind_param('s', $data['email']);
            if ($this->stmt->execute()) {
                $result = $this->stmt->get_result();
                if ($result->num_rows > 0) {
                    $get_data = $result->fetch_assoc();

                    if ($get_data['is_active'] == 1) {
                        if (password_verify($data['password'], $get_data['password'])) {
                            $_SESSION['email'] = $get_data['email'];

                            header('location: index.php');
                        } else {
                            $_SESSION['flash-message'] = '
                            <div class="alert alert-danger m-2" role="alert">
                                Opps, maaf akun Anda tidak terdaftar di data kami!
                            </div>';
                        }
                    } else {
                        $_SESSION['flash-message'] = '
                        <div class="alert alert-danger m-2" role="alert">
                            Opps, maaf akun Anda tidak terdaftar di data kami!
                        </div>';
                    }
                } else {
                    $_SESSION['flash-message'] = '
                    <div class="alert alert-danger m-2" role="alert">
                        Opps, maaf akun Anda tidak terdaftar di data kami!
                    </div>';
                }
            }
        }
    }
}

$login = new Login();

if (!isset($_POST['csrf_token'])) {
    $csrf_token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $csrf_token;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $csrf_token = $_POST['csrf_token'];
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    if (isset($csrf_token) && $csrf_token === $_SESSION['csrf_token']) {
        $login->post([
            'email' => $email,
            'password' => $password,
        ]);
    }
}

include 'application/templates/head.php';
?>

<div style="height: 100vh; position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center;">
    <div class="bg-info" style="width: 100%; margin: auto; padding: 2px; position: absolute; top: 0; left: 0;">
        <a href="index.php" style="display: block; color: white; text-align: center; text-decoration: none; font-weight: 600;">&laquo Beranda</a>
    </div>
    <div class="card" style="width: 24rem;">
        <?php
        if (isset($_SESSION['flash-message'])) {
            echo $_SESSION['flash-message'];
            unset($_SESSION['flash-message']);
        }
        ?>
        <h3 class="my-3 text-center">Login</h3>
        <div class="card-body">
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" autocomplete="off">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                <div class="mb-2">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email">
                </div>
                <div class="mb-2">
                    <label for="password" class="form-label">Kata sandi</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
                <div>
                    <button type="submit" class="btn btn-success w-100">Login</button>
                </div>
            </form>

            <p class="card-text text-center mt-3 mb-3">
                Belum punya akun?
                <a href="register.php" class="card-link">Daftar Akun</a>
            </p>
            <p class="card-text text-center mt-3 mb-3">
                <a href="forgotpassword.php" class="card-link">Lupa kata sandi</a>
            </p>
        </div>
    </div>
</div>

<?php include 'application/templates/footer.php'; ?>