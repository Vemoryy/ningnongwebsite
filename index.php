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

// Database connection for dynamic products
$conn = new mysqli("localhost", "root", "", "ningnong_db");
$featuredProducts = [];
if (!$conn->connect_error) {
    $result = $conn->query("SELECT * FROM products WHERE is_active = 1 AND is_featured = 1 ORDER BY created_at DESC LIMIT 6");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $featuredProducts[] = $row;
        }
    }
}

// Check if user is admin
$isAdmin = false;
$cartCount = 0;
if (isset($_SESSION['user_id'])) {
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
    <title>Ning Nong Indonesia - Premium Kembang Goyang</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .about-preview {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            padding: 3rem;
            margin-top: -80px;
            position: relative;
            z-index: 10;
        }
        .about-preview img {
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
        }
        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--accent-color), var(--accent-hover));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }
        .product-section {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            padding: 5rem 0;
        }
        .carousel-custom .carousel-item img {
            height: 450px;
            object-fit: cover;
            border-radius: var(--border-radius);
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
                        <a class="nav-link active" href="index.php?lang=<?= $lang ?>"><?= $translations['home'] ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php?lang=<?= $lang ?>"><?= $translations['about'] ?></a>
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
    <section class="hero">
        <video autoplay muted loop playsinline class="hero-video">
            <source src="../videos/Indo.mp4" type="video/mp4">
        </video>
        <div class="hero-content">
            <h1>One Bite For Every Moment!</h1>
            <p>Kue Kembang Goyang — 100% Gluten Free — Premium Quality</p>
            <a class="btn btn-primary btn-lg mt-3" href="products.php?lang=<?= $lang ?>">
                <i class="fas fa-shopping-bag me-2"></i><?= $translations['order'] ?>
            </a>
        </div>
    </section>

    <!-- About Preview Section -->
    <div class="container">
        <div class="about-preview">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2><?= $translations['title'] ?></h2>
                    <p class="text-muted"><?= $translations['vision'] ?></p>
                    <ul class="list-unstyled mt-4">
                        <li class="mb-3"><i class="fas fa-check-circle text-accent me-2"></i><?= $translations['mission1'] ?></li>
                        <li class="mb-3"><i class="fas fa-check-circle text-accent me-2"></i><?= $translations['mission2'] ?></li>
                        <li class="mb-3"><i class="fas fa-check-circle text-accent me-2"></i><?= $translations['mission3'] ?></li>
                    </ul>
                    <a href="about.php?lang=<?= $lang ?>" class="btn btn-outline mt-3">
                        <?= $translations['about'] ?> <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
                <div class="col-lg-6">
                    <img src="../Image/kembang.jpg" alt="Kembang Goyang" class="img-fluid rounded-img img-shadow">
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <section class="section section-light">
        <div class="container">
            <div class="section-title">
                <h2><?= $lang === 'en' ? 'Why Choose Us' : 'Mengapa Memilih Kami' ?></h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h5><?= $lang === 'en' ? '100% Gluten Free' : '100% Bebas Gluten' ?></h5>
                        <p class="text-muted"><?= $lang === 'en' ? 'Made with premium rice flour, perfect for gluten-sensitive individuals.' : 'Dibuat dengan tepung beras premium, cocok untuk yang sensitif gluten.' ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <h5><?= $lang === 'en' ? 'Traditional Recipe' : 'Resep Tradisional' ?></h5>
                        <p class="text-muted"><?= $lang === 'en' ? 'Authentic Indonesian recipes passed down through generations.' : 'Resep asli Indonesia yang diwariskan turun temurun.' ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <h5><?= $lang === 'en' ? 'Premium Quality' : 'Kualitas Premium' ?></h5>
                        <p class="text-muted"><?= $lang === 'en' ? 'Only the finest ingredients for the best taste experience.' : 'Hanya bahan-bahan terbaik untuk pengalaman rasa terbaik.' ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="product-section">
        <div class="container">
            <div class="section-title">
                <h2 class="text-white"><?= $translations['showcase'] ?></h2>
            </div>
            
            <?php if (!empty($featuredProducts)): ?>
            <!-- Product Carousel -->
            <div id="productCarousel" class="carousel slide carousel-custom" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($featuredProducts as $index => $product): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <img src="../Image/<?= htmlspecialchars($product['image'] ?: 'product1.jpg') ?>" 
                             class="d-block w-100" 
                             alt="<?= htmlspecialchars($product['name']) ?>">
                        <div class="carousel-caption">
                            <h5><?= $lang === 'en' ? htmlspecialchars($product['name']) : htmlspecialchars($product['name_id'] ?: $product['name']) ?></h5>
                            <p><?= $lang === 'en' ? htmlspecialchars($product['description']) : htmlspecialchars($product['description_id'] ?: $product['description']) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
            <?php else: ?>
            <!-- Fallback static products -->
            <div id="productCarousel" class="carousel slide carousel-custom" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="../Image/product2.jpg" class="d-block w-100" alt="Original Flavor">
                        <div class="carousel-caption">
                            <h5><?= $translations['original'] ?></h5>
                            <p><?= $translations['desc1'] ?></p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="../Image/product1.jpg" class="d-block w-100" alt="Coffee Flavor">
                        <div class="carousel-caption">
                            <h5><?= $translations['coffee'] ?></h5>
                            <p><?= $translations['desc2'] ?></p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="../Image/product3.jpg" class="d-block w-100" alt="Chocolate Flavor">
                        <div class="carousel-caption">
                            <h5><?= $translations['chocolate'] ?></h5>
                            <p><?= $translations['desc3'] ?></p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
            <?php endif; ?>
            
            <div class="text-center mt-4">
                <a href="products.php?lang=<?= $lang ?>" class="btn btn-primary btn-lg">
                    <?= $lang === 'en' ? 'View All Products' : 'Lihat Semua Produk' ?> <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
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
