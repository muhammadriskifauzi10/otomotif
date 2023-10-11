<?php

include 'application/Services.php';

class Resetpassword extends Services
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['reset_password'])) {
            if (isset($_GET['email'])) {
                $sql = "SELECT * FROM users WHERE email=?";
                $this->stmt = $this->mysqli->prepare($sql);
                $this->stmt->bind_param('s', $_GET['email']);
                if ($this->stmt->execute()) {
                    $result = $this->stmt->get_result();
                    if ($result->num_rows > 0) {
                        $get_data = $result->fetch_assoc();
                        if ($get_data['is_active'] == 1 && $get_data['status'] == 1) {
                            $sql = "SELECT * FROM user_token WHERE token=?";
                            $this->stmt = $this->mysqli->prepare($sql);
                            $this->stmt->bind_param('s', $_GET['token']);
                            if ($this->stmt->execute()) {
                                $result_user_token = $this->stmt->get_result();
                                if ($result_user_token->num_rows > 0) {
                                    $get_data_user_token = $result_user_token->fetch_assoc();
                                    $_SESSION['reset_password'] = $get_data_user_token['email'];
                                    header('location: resetpassword.php');
                                } else {
                                    header("location: forgotpassword.php");
                                }
                            }
                        } else {
                            header("location: forgotpassword.php");
                        }
                    } else {
                        header("location: forgotpassword.php");
                    }
                }
            } else {
                header('location: login.php');
            }
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
                    $sql = "UPDATE users SET password=? WHERE email=?";
                    $this->stmt = $this->mysqli->prepare($sql);
                    $this->stmt->bind_param('ss', $data['new_password'], $data['email']);
                    if ($this->stmt->execute()) {
                        unset($_SESSION['reset_password']);

                        $_SESSION['flash-message'] = '
                        <div class="alert alert-primary m-2" role="alert">
                            Kata sandi Anda berhasil diperbarui, silahkan <a href="login.php">Login</a>!
                        </div>';
                    }
                } else {
                    $_SESSION['flash-message'] = '
                    <div class="alert alert-danger m-2" role="alert">
                        Opps, terjadi kesalahan!
                    </div>';
                }
            }
        }
    }
}

$reset_password = new Resetpassword();

if (!isset($_POST['csrf_token'])) {
    $csrf_token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $csrf_token;
}

$password_err;
$password_confirmation_err;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $csrf_token = $_POST['csrf_token'];
    $password = $_POST['password'];
    $password_confirmation = $_POST['password_confirmation'];

    if (isset($csrf_token) && $csrf_token === $_SESSION['csrf_token']) {
        $error = 0;

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
            $reset_password->post([
                'email' => $_SESSION['reset_password'],
                'new_password' => password_hash($password, PASSWORD_DEFAULT),
            ]);
        }
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
        <h3 class="my-3 text-center">Buat Kata Sandi Baru</h3>
        <div class="card-body">
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" autocomplete="off">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                <div class="mb-2">
                    <label for="password" class="form-label">Kata sandi baru</label>
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
                    <button type="submit" class="btn btn-primary w-100">Buat kata sandi baru</button>
                </div>
            </form>

            <p class="card-text text-center mt-3 mb-3">
                <a href="login.php" class="card-link">Login</a>
            </p>
        </div>
    </div>
</div>

<?php include 'application/templates/footer.php'; ?>