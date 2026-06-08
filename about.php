<?php
$lang = $_GET['lang'] ?? 'id';
$langFile = __DIR__ . "/lang/$lang.php";

if (file_exists($langFile)) {
    include $langFile;
} else {
    include __DIR__ . "/lang/id.php";
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is admin
$isAdmin = false;
$cartCount = 0;
$conn = new mysqli("localhost", "root", "", "ningnong_db");
if (!$conn->connect_error && isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $userResult = $stmt->get_result();
        $userData = $userResult->fetch_assoc();
        $isAdmin = $userData && in_array($userData['role'], ['admin', 'owner']);
    }
    
    // Get cart count
    $cartStmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
    if ($cartStmt) {
        $cartStmt->bind_param("i", $_SESSION['user_id']);
        $cartStmt->execute();
        $cartResult = $cartStmt->get_result();
        $cartData = $cartResult->fetch_assoc();
        $cartCount = intval($cartData['total'] ?? 0);
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $translations['about'] ?> - Ning Nong Indonesia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .about-hero {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            padding: 5rem 0;
            color: white;
            text-align: center;
        }
        .about-hero h1 {
            color: white;
            font-size: 3rem;
        }
        .about-content-box {
            background: white;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            padding: 3rem;
            margin-top: -3rem;
            position: relative;
            z-index: 10;
        }
        .mission-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: var(--border-radius);
            padding: 2rem;
            height: 100%;
        }
        .mission-card i {
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 1rem;
        }
        .value-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }
        .value-item i {
            color: var(--accent-color);
            font-size: 1.5rem;
            margin-right: 1rem;
            margin-top: 0.25rem;
        }
        .timeline {
            position: relative;
            padding-left: 2rem;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--accent-color);
            border-radius: 3px;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 2rem;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -2rem;
            top: 0.5rem;
            width: 12px;
            height: 12px;
            background: var(--accent-color);
            border-radius: 50%;
            margin-left: -4.5px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php?lang=<?= $lang ?>">
                <i class="fas fa-cookie-bite me-2"></i>Ning Nong Indonesia
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?lang=<?= $lang ?>"><?= $translations['home'] ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="about.php?lang=<?= $lang ?>"><?= $translations['about'] ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php?lang=<?= $lang ?>"><?= $translations['products'] ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://linktr.ee/KueNingNong" target="_blank"><?= $translations['contact'] ?></a>
                    </li>
                    
                    <!-- Cart Icon -->
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="basket.php?lang=<?= $lang ?>">
                            <i class="fas fa-shopping-basket"></i>
                            <span class="cart-count position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark" style="font-size: 0.65rem;">
                                <?= $cartCount ?>
                            </span>
                        </a>
                    </li>
                    
                    <!-- Language Switch -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <?= $translations['language'] ?> 🌐
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="?lang=en">🇬🇧 English</a></li>
                            <li><a class="dropdown-item" href="?lang=id">🇮🇩 Indonesia</a></li>
                        </ul>
                    </li>

                    <!-- User Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            <?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : $translations['guest']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if (isset($_SESSION['email'])): ?>
                                <li><a class="dropdown-item" href="dashboard.php?lang=<?= $lang ?>"><i class="fas fa-tachometer-alt me-2"></i><?= $translations['dashboard'] ?></a></li>
                                <?php if ($isAdmin): ?>
                                    <li><a class="dropdown-item" href="admin_products.php?lang=<?= $lang ?>"><i class="fas fa-cog me-2"></i>Admin Panel</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php?lang=<?= $lang ?>"><i class="fas fa-sign-out-alt me-2"></i><?= $translations['logout'] ?></a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="login.php?lang=<?= $lang ?>"><i class="fas fa-sign-in-alt me-2"></i><?= $translations['login'] ?></a></li>
                                <li><a class="dropdown-item" href="register.php?lang=<?= $lang ?>"><i class="fas fa-user-plus me-2"></i><?= $translations['register'] ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="about-hero">
        <div class="container">
            <h1>Ning Nong Indonesia</h1>
            <p class="lead"><?= $translations['slogan'] ?></p>
        </div>
    </section>

    <!-- About Content -->
    <div class="container pb-5">
        <div class="about-content-box">
            <!-- Philosophy Section -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="../Image/kembang.jpg" alt="Kembang Goyang" class="img-fluid rounded-img img-shadow">
                </div>
                <div class="col-lg-6">
                    <h2><?= $translations['philosophy'] ?></h2>
                    <p class="text-muted"><?= $translations['philosophy2'] ?></p>
                    <ul class="list-unstyled mt-4">
                        <li class="value-item">
                            <i class="fas fa-check-circle"></i>
                            <div><?= $translations['philosophy3'] ?></div>
                        </li>
                        <li class="value-item">
                            <i class="fas fa-check-circle"></i>
                            <div><?= $translations['philosophy4'] ?></div>
                        </li>
                    </ul>
                    <p class="text-muted"><?= $translations['philosophy5'] ?></p>
                </div>
            </div>

            <hr class="my-5">

            <!-- Company Profile Section -->
            <div class="mb-5">
                <div class="section-title">
                    <h2><?= $lang === 'en' ? 'Company Profile' : 'Profil Perusahaan' ?></h2>
                </div>
                <p class="text-muted text-center mb-4"><?= $translations['desc4'] ?></p>
                <p class="text-muted text-center"><?= $translations['desc5'] ?></p>
            </div>

            <!-- Values Section -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="mission-card text-center">
                        <i class="fas fa-gem"></i>
                        <h5><?= $lang === 'en' ? 'Premium Quality' : 'Kualitas Premium' ?></h5>
                        <p class="text-muted mb-0"><?= $translations['premium'] ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mission-card text-center">
                        <i class="fas fa-shield-alt"></i>
                        <h5><?= $lang === 'en' ? 'Safe & Healthy' : 'Aman & Sehat' ?></h5>
                        <p class="text-muted mb-0"><?= $translations['safe'] ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mission-card text-center">
                        <i class="fas fa-heart"></i>
                        <h5><?= $lang === 'en' ? 'Joy in Every Bite' : 'Kebahagiaan di Setiap Gigitan' ?></h5>
                        <p class="text-muted mb-0"><?= $translations['joy'] ?></p>
                    </div>
                </div>
            </div>

            <!-- Inspire Section -->
            <div class="text-center bg-light p-4 rounded">
                <i class="fas fa-quote-left fa-2x text-accent mb-3"></i>
                <p class="lead mb-0"><?= $translations['inspire'] ?></p>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <section class="section section-dark">
        <div class="container text-center">
            <h3><?= $lang === 'en' ? 'Ready to Try Our Products?' : 'Siap Mencoba Produk Kami?' ?></h3>
            <p class="mb-4"><?= $lang === 'en' ? 'Discover the authentic taste of Indonesian traditional snacks.' : 'Temukan rasa autentik camilan tradisional Indonesia.' ?></p>
            <a href="products.php?lang=<?= $lang ?>" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag me-2"></i><?= $translations['products'] ?>
            </a>
        </div>
    </section>

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
<?php if ($conn) $conn->close(); ?>
