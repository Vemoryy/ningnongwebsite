<?php
session_start();

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$lang_code = $_SESSION['lang'] ?? 'id';

$translations = [
    'en' => [
        'profile' => 'Profile',
        'username' => 'Username',
        'email' => 'Email',
        'new_password' => 'New Password',
        'password_hint' => 'Leave empty to keep current password.',
        'address' => 'Address',
        'save_changes' => 'Save Changes',
        'back_home' => 'Back to Home',
        'language' => 'Language',
        'success' => 'Profile updated successfully.',
        'error' => 'Failed to update profile. Please try again.',
        'admin_panel' => 'Admin Panel',
        'manage_products' => 'Manage Products',
        'my_account' => 'My Account',
        'welcome' => 'Welcome',
        'logout' => 'Logout',
    ],
    'id' => [
        'profile' => 'Profil',
        'username' => 'Nama Pengguna',
        'email' => 'Email',
        'new_password' => 'Kata Sandi Baru',
        'password_hint' => 'Kosongkan jika tidak ingin mengubah password.',
        'address' => 'Alamat',
        'save_changes' => 'Simpan Perubahan',
        'back_home' => 'Kembali ke Beranda',
        'language' => 'Bahasa',
        'success' => 'Profil berhasil diperbarui.',
        'error' => 'Gagal memperbarui profil. Silakan coba lagi.',
        'admin_panel' => 'Panel Admin',
        'manage_products' => 'Kelola Produk',
        'my_account' => 'Akun Saya',
        'welcome' => 'Selamat Datang',
        'logout' => 'Keluar',
    ]
];

$t = $translations[$lang_code];

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php?lang=$lang_code");
    exit();
}

$conn = new mysqli("localhost", "root", "", "ningnong_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION["user_id"];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Check if user is admin
$isAdmin = isset($user['role']) && in_array($user['role'], ['admin', 'owner']);

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $address = trim($_POST['address']);

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET username=?, email=?, password=?, address=? WHERE id=?");
        $update->bind_param("ssssi", $username, $email, $hashedPassword, $address, $user_id);
    } else {
        $update = $conn->prepare("UPDATE users SET username=?, email=?, address=? WHERE id=?");
        $update->bind_param("sssi", $username, $email, $address, $user_id);
    }

    if ($update->execute()) {
        $success = $t['success'];
        $user['username'] = $username;
        $user['email'] = $email;
        $user['address'] = $address;
        $_SESSION['email'] = $email;
    } else {
        $error = $t['error'];
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $lang_code ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['profile'] ?> - Ning Nong Indonesia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php?lang=<?= $lang_code ?>">
                <i class="fas fa-cookie-bite me-2"></i>Ning Nong Indonesia
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?lang=<?= $lang_code ?>"><i class="fas fa-home me-1"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php?lang=<?= $lang_code ?>"><i class="fas fa-shopping-bag me-1"></i> Products</a>
                    </li>
                    <?php if ($isAdmin): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_products.php?lang=<?= $lang_code ?>"><i class="fas fa-cog me-1"></i> Admin</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            🌐 <?= $lang_code === 'en' ? 'EN' : 'ID' ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?lang=en">🇬🇧 English</a></li>
                            <li><a class="dropdown-item" href="?lang=id">🇮🇩 Indonesia</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i> <?= $t['logout'] ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Welcome Card -->
                <div class="admin-header mb-4">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-user-circle fa-3x"></i>
                        </div>
                        <div>
                            <h2 class="mb-1"><?= $t['welcome'] ?>, <?= htmlspecialchars($user['username'] ?? $user['email']) ?>!</h2>
                            <p class="mb-0"><?= $t['my_account'] ?></p>
                        </div>
                    </div>
                </div>

                <?php if ($isAdmin): ?>
                <!-- Admin Quick Links -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <a href="admin_products.php?lang=<?= $lang_code ?>" class="text-decoration-none">
                            <div class="stats-card">
                                <i class="fas fa-boxes fa-2x mb-2"></i>
                                <h5 class="mb-0"><?= $t['manage_products'] ?></h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="index.php?lang=<?= $lang_code ?>" class="text-decoration-none">
                            <div class="stats-card blue">
                                <i class="fas fa-globe fa-2x mb-2"></i>
                                <h5 class="mb-0"><?= $lang_code === 'en' ? 'View Website' : 'Lihat Website' ?></h5>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Profile Form Card -->
                <div class="card">
                    <div class="card-body p-4">
                        <h4 class="mb-4"><i class="fas fa-user-edit me-2"></i><?= $t['profile'] ?></h4>

                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i><?= $success ?>
                            </div>
                        <?php elseif ($error): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i><?= $error ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= $t['username'] ?></label>
                                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= $t['email'] ?></label>
                                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><?= $t['new_password'] ?></label>
                                <input type="password" name="password" class="form-control">
                                <div class="form-text"><?= $t['password_hint'] ?></div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label"><?= $t['address'] ?></label>
                                <textarea name="address" class="form-control" rows="3"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i><?= $t['save_changes'] ?>
                                </button>
                                <a href="index.php?lang=<?= $lang_code ?>" class="btn btn-secondary">
                                    <i class="fas fa-home me-2"></i><?= $t['back_home'] ?>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-links">
                <a href="https://www.instagram.com/ningnongindonesia" target="_blank">
                    <i class="fab fa-instagram"></i> Instagram
                </a>
                <a href="https://www.tiktok.com/@kembang.goyang.ningnong" target="_blank">
                    <i class="fab fa-tiktok"></i> TikTok
                </a>
                <a href="https://api.whatsapp.com/send?phone=6282299891278" target="_blank">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Ning Nong Indonesia. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
