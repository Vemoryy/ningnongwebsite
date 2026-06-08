<?php
session_start();

// Language switching
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}
$langCode = $_SESSION['lang'] ?? 'id';

// Translations
$translations = [
    "en" => [
        "title" => "Create Account",
        "subtitle" => "Join us and discover authentic Indonesian snacks",
        "username" => "Username",
        "email" => "Email",
        "password" => "Password",
        "address" => "Address",
        "register" => "Create Account",
        "already" => "Already have an account?",
        "login_here" => "Login here",
        "back_home" => "Back to Home",
        "error_exists" => "Email already registered!",
        "error_general" => "Something went wrong. Please try again.",
        "success" => "Account successfully registered! You can now login.",
    ],
    "id" => [
        "title" => "Buat Akun",
        "subtitle" => "Bergabunglah dan temukan camilan Indonesia yang autentik",
        "username" => "Nama Pengguna",
        "email" => "Email",
        "password" => "Kata Sandi",
        "address" => "Alamat",
        "register" => "Buat Akun",
        "already" => "Sudah punya akun?",
        "login_here" => "Masuk di sini",
        "back_home" => "Kembali ke Beranda",
        "error_exists" => "Email sudah terdaftar!",
        "error_general" => "Terjadi kesalahan. Silakan coba lagi.",
        "success" => "Akun berhasil dibuat! Anda sekarang bisa masuk.",
    ]
];

$t = $translations[$langCode];

// Database connection
$conn = new mysqli("localhost", "root", "", "ningnong_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $address = trim($_POST['address']);

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $error = $t["error_exists"];
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $insertStmt = $conn->prepare("INSERT INTO users (username, email, password, address) VALUES (?, ?, ?, ?)");
        $insertStmt->bind_param("ssss", $username, $email, $hashedPassword, $address);

        if ($insertStmt->execute()) {
            $success = $t["success"];
        } else {
            $error = $t["error_general"];
        }

        $insertStmt->close();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="<?= $langCode ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t["title"] ?> - Ning Nong Indonesia</title>
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
                <h3><?= $t['title'] ?></h3>
                <p class="text-muted"><?= $t['subtitle'] ?></p>
            </div>

            <!-- Alerts -->
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i><?= $success ?>
                </div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i><?= $error ?>
                </div>
            <?php endif; ?>

            <!-- Register Form -->
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label"><?= $t["username"] ?></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><?= $t["email"] ?></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><?= $t["password"] ?></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label"><?= $t["address"] ?></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        <textarea name="address" class="form-control" rows="2" required></textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="fas fa-user-plus me-2"></i><?= $t["register"] ?>
                </button>
            </form>

            <!-- Links -->
            <div class="auth-links">
                <p class="mb-2">
                    <?= $t["already"] ?> 
                    <a href="login.php?lang=<?= $langCode ?>"><?= $t["login_here"] ?></a>
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
<?php $conn->close(); ?>
