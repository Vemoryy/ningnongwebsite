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

// Database connection
$conn = new mysqli("localhost", "root", "", "ningnong_db");
$products = [];
$categories = [];

if (!$conn->connect_error) {
    // Get all active products
    $result = $conn->query("SELECT * FROM products WHERE is_active = 1 ORDER BY is_featured DESC, created_at DESC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
            if ($row['category'] && !in_array($row['category'], $categories)) {
                $categories[] = $row['category'];
            }
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
    <title><?= $translations['products'] ?> - Ning Nong Indonesia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            padding: 4rem 0;
            color: white;
            text-align: center;
        }
        .page-header h1 {
            color: white;
            margin-bottom: 0.5rem;
        }
        .page-header p {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 0;
        }
        .filter-bar {
            background: white;
            padding: 1rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            margin-top: -2rem;
            position: relative;
            z-index: 10;
        }
        .product-grid {
            margin-top: 2rem;
        }
        .product-card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .product-card .product-info {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .product-card .price {
            margin-top: auto;
        }
        .badge-featured {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--accent-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .product-card-wrapper {
            position: relative;
        }
        .no-products {
            text-align: center;
            padding: 4rem 2rem;
        }
        .no-products i {
            font-size: 4rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }
        .category-filter .btn {
            margin: 0.25rem;
        }
        .category-filter .btn.active {
            background: var(--primary-color);
            color: white;
        }
        .product-qty-control {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .product-qty-control button {
            width: 32px;
            height: 32px;
            border: 2px solid var(--primary-color);
            background: white;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .product-qty-control button:hover {
            background: var(--primary-color);
            color: white;
        }
        .product-qty-control input {
            width: 50px;
            text-align: center;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            padding: 4px;
            font-weight: 600;
        }
        .product-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }
        .product-actions .btn {
            flex: 1;
        }
        .btn-buy-now {
            background: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
        }
        .btn-buy-now:hover {
            background: #e67300;
            border-color: #e67300;
            color: white;
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
                        <a class="nav-link" href="about.php?lang=<?= $lang ?>"><?= $translations['about'] ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="products.php?lang=<?= $lang ?>"><?= $translations['products'] ?></a>
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

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1><i class="fas fa-shopping-bag me-2"></i><?= $translations['showcase'] ?></h1>
            <p><?= $lang === 'en' ? 'Discover our delicious traditional Indonesian snacks' : 'Temukan camilan tradisional Indonesia yang lezat' ?></p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container content">
        <!-- Filter Bar -->
        <?php if (!empty($categories)): ?>
        <div class="filter-bar">
            <div class="category-filter text-center">
                <button class="btn btn-outline filter-btn active" data-category="all">
                    <?= $lang === 'en' ? 'All Products' : 'Semua Produk' ?>
                </button>
                <?php foreach ($categories as $category): ?>
                <button class="btn btn-outline filter-btn" data-category="<?= htmlspecialchars($category) ?>">
                    <?= htmlspecialchars($category) ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Products Grid -->
        <div class="product-grid">
            <?php if (!empty($products)): ?>
            <div class="row g-4">
                <?php foreach ($products as $product): ?>
                <div class="col-lg-4 col-md-6 product-item" data-category="<?= htmlspecialchars($product['category'] ?? '') ?>">
                    <div class="product-card-wrapper">
                        <?php if ($product['is_featured']): ?>
                        <span class="badge-featured"><i class="fas fa-star me-1"></i><?= $lang === 'en' ? 'Featured' : 'Unggulan' ?></span>
                        <?php endif; ?>
                        <div class="product-card">
                            <div style="overflow: hidden;">
                                <img src="../Image/<?= htmlspecialchars($product['image'] ?: 'product1.jpg') ?>" 
                                     alt="<?= htmlspecialchars($product['name']) ?>">
                            </div>
                            <div class="product-info">
                                <h5><?= $lang === 'en' ? htmlspecialchars($product['name']) : htmlspecialchars($product['name_id'] ?: $product['name']) ?></h5>
                                <p><?= $lang === 'en' ? htmlspecialchars($product['description']) : htmlspecialchars($product['description_id'] ?: $product['description']) ?></p>
                                <?php if ($product['category']): ?>
                                <span class="badge bg-secondary mb-2"><?= htmlspecialchars($product['category']) ?></span>
                                <?php endif; ?>
                                <div class="price">Rp <?= number_format($product['price'], 0, ',', '.') ?></div>
                                
                                <!-- Quantity Selector -->
                                <div class="product-qty-control">
                                    <button type="button" class="qty-decrease" data-product-id="<?= $product['id'] ?>">−</button>
                                    <input type="number" class="product-qty" id="qty-<?= $product['id'] ?>" value="1" min="1" max="99">
                                    <button type="button" class="qty-increase" data-product-id="<?= $product['id'] ?>">+</button>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="product-actions">
                                    <button class="btn btn-primary btn-sm add-to-cart-btn" 
                                            data-product-id="<?= $product['id'] ?>"
                                            data-product-name="<?= htmlspecialchars($product['name']) ?>">
                                        <i class="fas fa-cart-plus me-1"></i>
                                        <?= $lang === 'en' ? 'Add' : 'Tambah' ?>
                                    </button>
                                    <button class="btn btn-buy-now btn-sm buy-now-btn" 
                                            data-product-id="<?= $product['id'] ?>"
                                            data-product-name="<?= htmlspecialchars($product['name']) ?>">
                                        <i class="fas fa-bolt me-1"></i>
                                        <?= $lang === 'en' ? 'Buy Now' : 'Beli' ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <!-- Fallback static products if database is empty -->
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="product-card">
                        <div style="overflow: hidden;">
                            <img src="../Image/product2.jpg" alt="Original Flavor">
                        </div>
                        <div class="product-info">
                            <h5><?= $translations['original'] ?></h5>
                            <p><?= $translations['flavor333'] ?></p>
                            <div class="price">Rp 25.000</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="product-card">
                        <div style="overflow: hidden;">
                            <img src="../Image/product3.jpg" alt="Coffee Flavor">
                        </div>
                        <div class="product-info">
                            <h5><?= $translations['coffee'] ?></h5>
                            <p><?= $translations['flavor222'] ?></p>
                            <div class="price">Rp 28.000</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="product-card">
                        <div style="overflow: hidden;">
                            <img src="../Image/product1.jpg" alt="Chocolate Flavor">
                        </div>
                        <div class="product-info">
                            <h5><?= $translations['chocolate'] ?></h5>
                            <p><?= $translations['flavor111'] ?></p>
                            <div class="price">Rp 28.000</div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Order CTA -->
        <div class="text-center mt-5 mb-5">
            <div class="card p-4">
                <h4><?= $lang === 'en' ? 'Ready to Order?' : 'Siap Memesan?' ?></h4>
                <p class="text-muted mb-3"><?= $lang === 'en' ? 'Contact us via WhatsApp for quick ordering!' : 'Hubungi kami via WhatsApp untuk pemesanan cepat!' ?></p>
                <div>
                    <a href="https://api.whatsapp.com/send?phone=6282299891278" target="_blank" class="btn btn-success btn-lg">
                        <i class="fab fa-whatsapp me-2"></i><?= $lang === 'en' ? 'Order via WhatsApp' : 'Pesan via WhatsApp' ?>
                    </a>
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
    <script>
        // Category filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active state
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const category = this.dataset.category;
                
                document.querySelectorAll('.product-item').forEach(item => {
                    if (category === 'all' || item.dataset.category === category) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
        
        // Quantity increase/decrease
        document.querySelectorAll('.qty-increase').forEach(btn => {
            btn.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const input = document.getElementById('qty-' + productId);
                let qty = parseInt(input.value) || 1;
                if (qty < 99) input.value = qty + 1;
            });
        });
        
        document.querySelectorAll('.qty-decrease').forEach(btn => {
            btn.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const input = document.getElementById('qty-' + productId);
                let qty = parseInt(input.value) || 1;
                if (qty > 1) input.value = qty - 1;
            });
        });
        
        // Validate quantity input
        document.querySelectorAll('.product-qty').forEach(input => {
            input.addEventListener('change', function() {
                let qty = parseInt(this.value) || 1;
                if (qty < 1) qty = 1;
                if (qty > 99) qty = 99;
                this.value = qty;
            });
        });
        
        // Add to Cart functionality
        document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const quantity = parseInt(document.getElementById('qty-' + productId).value) || 1;
                const button = this;
                
                // Disable button during request
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                
                fetch('../process/cart_handler.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `action=add&product_id=${productId}&quantity=${quantity}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Update cart count badge
                        document.querySelectorAll('.cart-count').forEach(badge => {
                            badge.textContent = data.cart_count;
                        });
                        
                        // Show success feedback
                        button.innerHTML = '<i class="fas fa-check"></i>';
                        button.classList.remove('btn-primary');
                        button.classList.add('btn-success');
                        
                        setTimeout(() => {
                            button.innerHTML = '<i class="fas fa-cart-plus me-1"></i><?= $lang === 'en' ? 'Add' : 'Tambah' ?>';
                            button.classList.remove('btn-success');
                            button.classList.add('btn-primary');
                            button.disabled = false;
                        }, 1000);
                    } else {
                        if (data.redirect) {
                            alert(data.message);
                            window.location.href = data.redirect + '?lang=<?= $lang ?>';
                        } else {
                            alert(data.message);
                            button.innerHTML = '<i class="fas fa-cart-plus me-1"></i><?= $lang === 'en' ? 'Add' : 'Tambah' ?>';
                            button.disabled = false;
                        }
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('<?= $lang === 'en' ? 'Failed to add to cart' : 'Gagal menambah ke keranjang' ?>');
                    button.innerHTML = '<i class="fas fa-cart-plus me-1"></i><?= $lang === 'en' ? 'Add' : 'Tambah' ?>';
                    button.disabled = false;
                });
            });
        });
        
        // Buy Now functionality - Add to cart and redirect to basket
        document.querySelectorAll('.buy-now-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const quantity = parseInt(document.getElementById('qty-' + productId).value) || 1;
                const button = this;
                
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                
                fetch('../process/cart_handler.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `action=add&product_id=${productId}&quantity=${quantity}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Redirect to basket/checkout
                        window.location.href = 'basket.php?lang=<?= $lang ?>';
                    } else {
                        if (data.redirect) {
                            alert(data.message);
                            window.location.href = data.redirect + '?lang=<?= $lang ?>';
                        } else {
                            alert(data.message);
                            button.innerHTML = '<i class="fas fa-bolt me-1"></i><?= $lang === 'en' ? 'Buy Now' : 'Beli' ?>';
                            button.disabled = false;
                        }
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('<?= $lang === 'en' ? 'Failed to process' : 'Gagal memproses' ?>');
                    button.innerHTML = '<i class="fas fa-bolt me-1"></i><?= $lang === 'en' ? 'Buy Now' : 'Beli' ?>';
                    button.disabled = false;
                });
            });
        });
    </script>
</body>
</html>
<?php if ($conn) $conn->close(); ?>
