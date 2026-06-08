<?php
session_start();

// Translations
$translations = [
    'id' => [
        'login' => 'Masuk',
        'email' => 'Email',
        'password' => 'Kata Sandi',
        'no_account' => 'Belum punya akun?',
        'register_here' => 'Daftar di sini',
        'back_home' => 'Kembali ke Beranda',
        'login_error' => 'Email atau kata sandi salah.',
        'welcome' => 'Selamat Datang Kembali',
        'subtitle' => 'Masuk ke akun Anda untuk melanjutkan',
    ],
    'en' => [
        'login' => 'Login',
        'email' => 'Email',
        'password' => 'Password',
        'no_account' => "Don't have an account?",
        'register_here' => 'Register here',
        'back_home' => 'Back to Home',
        'login_error' => 'Invalid email or password.',
        'welcome' => 'Welcome Back',
        'subtitle' => 'Sign in to your account to continue',
    ]
];

// Language switching
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}
$langCode = $_SESSION['lang'] ?? 'id';
$t = $translations[$langCode];

$error = "";

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $conn = new mysqli("localhost", "root", "", "ningnong_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId, $hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $userId;
            $_SESSION['email'] = $email;
            header("Location: index.php?lang=$langCode");
            exit();
        } else {
            $error = $t["login_error"];
        }
    } else {
        $error = $t["login_error"];
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="<?= $langCode ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t["login"] ?> - Ning Nong Indonesia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <!-- Language Switcher -->
            <div class="lang-switcher">
                <span>🌐</span>
                <a href="?lang=en" class="<?= $langCode === 'en' ? 'fw-bold' : '' ?>">EN</a> |
                <a href="?lang=id" class="<?= $langCode === 'id' ? 'fw-bold' : '' ?>">ID</a>
            </div>

            <!-- Logo/Brand -->
            <div class="text-center mb-4">
                <i class="fas fa-cookie-bite fa-3x text-accent mb-2"></i>
                <h3><?= $t['welcome'] ?></h3>
                <p class="text-muted"><?= $t['subtitle'] ?></p>
            </div>

            <!-- Error Alert -->
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i><?= $error ?>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label"><?= $t["email"] ?></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label"><?= $t["password"] ?></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="fas fa-sign-in-alt me-2"></i><?= $t["login"] ?>
                </button>
            </form>

            <!-- Links -->
            <div class="auth-links">
                <p class="mb-2">
                    <?= $t["no_account"] ?> 
                    <a href="register.php?lang=<?= $langCode ?>"><?= $t["register_here"] ?></a>
                </p>
                <a href="index.php?lang=<?= $langCode ?>" class="btn btn-outline btn-sm">
                    <i class="fas fa-home me-1"></i><?= $t["back_home"] ?>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
