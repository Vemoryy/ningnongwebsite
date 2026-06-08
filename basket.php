<?php
$lang = $_GET['lang'] ?? 'en';
$langFile = __DIR__ . "/lang/$lang.php";

if (file_exists($langFile)) {
    include $langFile;
} else {
    include __DIR__ . "/lang/en.php";
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login to view your basket";
    header("Location: login.php?lang=$lang");
    exit();
}

require_once '../process/db.php';
require_once '../process/cart_handler.php';

$user_id = $_SESSION['user_id'];
$cartItems = getCartItems($user_id);
$cartTotal = getCartTotal($user_id);
$cartCount = getCartCountValue($user_id);

// Check if user is admin
$isAdmin = false;
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $isAdmin = $userData && in_array($userData['role'], ['admin', 'owner']);
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang === 'id' ? 'Keranjang Belanja' : 'Shopping Basket' ?> - Ning Nong Indonesia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .basket-container {
            min-height: 70vh;
            padding: 40px 0;
        }
        .basket-item {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .basket-item:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        }
        .basket-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        .basket-item .product-name {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1.1rem;
        }
        .basket-item .product-price {
            color: var(--accent-color);
            font-weight: 600;
            font-size: 1.1rem;
        }
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .quantity-control button {
            width: 36px;
            height: 36px;
            border: 2px solid var(--primary-color);
            background: white;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }
        .quantity-control button:hover {
            background: var(--primary-color);
            color: white;
        }
        .quantity-control input {
            width: 60px;
            text-align: center;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 5px;
            font-weight: 600;
        }
        .remove-btn {
            color: #dc3545;
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
        }
        .remove-btn:hover {
            color: #a71d2a;
        }
        .basket-summary {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            position: sticky;
            top: 100px;
        }
        .basket-summary h4 {
            color: var(--primary-color);
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
        }
        .summary-row.total {
            border-top: 2px solid #eee;
            padding-top: 15px;
            margin-top: 15px;
            font-size: 1.2rem;
            font-weight: 700;
        }
        .summary-row.total .price {
            color: var(--accent-color);
        }
        .empty-basket {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-basket i {
            font-size: 5rem;
            color: #ddd;
            margin-bottom: 20px;
        }
        .empty-basket h3 {
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        .clear-cart-btn {
            color: #dc3545;
            border-color: #dc3545;
        }
        .clear-cart-btn:hover {
            background: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php?lang=<?= $lang ?>">
                <i class="fas fa-cookie-bite me-2"></i>Ning Nong
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?lang=<?= $lang ?>"><?= $lang === 'id' ? 'Beranda' : 'Home' ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php?lang=<?= $lang ?>"><?= $lang === 'id' ? 'Produk' : 'Products' ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php?lang=<?= $lang ?>"><?= $lang === 'id' ? 'Tentang' : 'About' ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="basket.php?lang=<?= $lang ?>">
                            <i class="fas fa-shopping-basket"></i>
                            <span class="cart-count badge bg-warning text-dark"><?= $cartCount ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php?lang=<?= $lang ?>">
                            <i class="fas fa-user"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="fas fa-globe"></i> <?= strtoupper($lang) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="?lang=en">English</a></li>
                            <li><a class="dropdown-item" href="?lang=id">Indonesia</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Basket Content -->
    <div class="basket-container" style="margin-top: 80px;">
        <div class="container">
            <h1 class="text-center mb-4" style="color: var(--primary-color);">
                <i class="fas fa-shopping-basket me-2"></i>
                <?= $lang === 'id' ? 'Keranjang Belanja' : 'Shopping Basket' ?>
            </h1>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (empty($cartItems)): ?>
                <!-- Empty Basket -->
                <div class="empty-basket">
                    <i class="fas fa-shopping-basket"></i>
                    <h3><?= $lang === 'id' ? 'Keranjang Anda Kosong' : 'Your Basket is Empty' ?></h3>
                    <p class="text-muted"><?= $lang === 'id' ? 'Ayo tambahkan produk lezat ke keranjang Anda!' : 'Start adding some delicious products to your basket!' ?></p>
                    <a href="products.php?lang=<?= $lang ?>" class="btn btn-primary btn-lg mt-3">
                        <i class="fas fa-shopping-bag me-2"></i>
                        <?= $lang === 'id' ? 'Belanja Sekarang' : 'Shop Now' ?>
                    </a>
                </div>
            <?php else: ?>
                <div class="row">
                    <!-- Cart Items -->
                    <div class="col-lg-8">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="basket-item" data-cart-id="<?= $item['cart_id'] ?>">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <img src="../Image/<?= htmlspecialchars($item['image'] ?: 'default-product.jpg') ?>" 
                                             alt="<?= htmlspecialchars($item['name']) ?>"
                                             onerror="this.src='../Image/default-product.jpg'">
                                    </div>
                                    <div class="col">
                                        <h5 class="product-name mb-1">
                                            <?= htmlspecialchars($lang === 'id' && $item['name_id'] ? $item['name_id'] : $item['name']) ?>
                                        </h5>
                                        <span class="badge bg-secondary"><?= ucfirst($item['category']) ?></span>
                                        <div class="product-price mt-2">
                                            Rp <?= number_format($item['price'], 0, ',', '.') ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="quantity-control">
                                            <button type="button" class="qty-btn" data-action="decrease" data-cart-id="<?= $item['cart_id'] ?>">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" class="qty-input" value="<?= $item['quantity'] ?>" 
                                                   min="1" data-cart-id="<?= $item['cart_id'] ?>" data-price="<?= $item['price'] ?>">
                                            <button type="button" class="qty-btn" data-action="increase" data-cart-id="<?= $item['cart_id'] ?>">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="item-subtotal fw-bold" style="min-width: 120px; text-align: right;">
                                            Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="remove-btn" data-cart-id="<?= $item['cart_id'] ?>" 
                                                title="<?= $lang === 'id' ? 'Hapus' : 'Remove' ?>">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <div class="d-flex justify-content-between mt-3">
                            <a href="products.php?lang=<?= $lang ?>" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>
                                <?= $lang === 'id' ? 'Lanjut Belanja' : 'Continue Shopping' ?>
                            </a>
                            <button type="button" class="btn btn-outline-danger clear-cart-btn" id="clearCartBtn">
                                <i class="fas fa-trash me-2"></i>
                                <?= $lang === 'id' ? 'Kosongkan Keranjang' : 'Clear Cart' ?>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Order Summary -->
                    <div class="col-lg-4">
                        <div class="basket-summary">
                            <h4><i class="fas fa-receipt me-2"></i><?= $lang === 'id' ? 'Ringkasan' : 'Summary' ?></h4>
                            
                            <div class="summary-row">
                                <span><?= $lang === 'id' ? 'Total Item' : 'Total Items' ?></span>
                                <span id="totalItems"><?= $cartCount ?></span>
                            </div>
                            <div class="summary-row">
                                <span><?= $lang === 'id' ? 'Subtotal' : 'Subtotal' ?></span>
                                <span id="subtotal">Rp <?= number_format($cartTotal, 0, ',', '.') ?></span>
                            </div>
                            <div class="summary-row total">
                                <span><?= $lang === 'id' ? 'Total' : 'Total' ?></span>
                                <span class="price" id="grandTotal">Rp <?= number_format($cartTotal, 0, ',', '.') ?></span>
                            </div>
                            
                            <button class="btn btn-primary w-100 mt-3" disabled>
                                <i class="fas fa-lock me-2"></i>
                                <?= $lang === 'id' ? 'Checkout (Segera Hadir)' : 'Checkout (Coming Soon)' ?>
                            </button>
                            
                            <p class="text-muted text-center mt-3 small">
                                <i class="fas fa-shield-alt me-1"></i>
                                <?= $lang === 'id' ? 'Pembayaran aman & terjamin' : 'Secure & safe payment' ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-cookie-bite me-2"></i>Ning Nong Indonesia</h5>
                    <p><?= $lang === 'id' ? 'Kembang Goyang Premium sejak 1990' : 'Premium Kembang Goyang since 1990' ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; 2025 Ning Nong Indonesia. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Debounce function to prevent rapid updates
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
        
        // Format number to Indonesian Rupiah
        function formatRupiah(num) {
            return 'Rp ' + Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
        
        // Update totals (without animation)
        function updateTotals() {
            let total = 0;
            let itemCount = 0;
            
            document.querySelectorAll('.basket-item').forEach(item => {
                const qtyInput = item.querySelector('.qty-input');
                if (!qtyInput) return;
                
                const qty = parseInt(qtyInput.value) || 0;
                const price = parseFloat(qtyInput.dataset.price) || 0;
                const subtotal = qty * price;
                
                const subtotalEl = item.querySelector('.item-subtotal');
                if (subtotalEl) {
                    subtotalEl.textContent = formatRupiah(subtotal);
                }
                total += subtotal;
                itemCount += qty;
            });
            
            const totalItemsEl = document.getElementById('totalItems');
            const subtotalEl = document.getElementById('subtotal');
            const grandTotalEl = document.getElementById('grandTotal');
            
            if (totalItemsEl) totalItemsEl.textContent = itemCount;
            if (subtotalEl) subtotalEl.textContent = formatRupiah(total);
            if (grandTotalEl) grandTotalEl.textContent = formatRupiah(total);
            
            // Update cart badge
            document.querySelectorAll('.cart-count').forEach(badge => {
                badge.textContent = itemCount;
            });
        }
        
        // Debounced AJAX update
        const debouncedAjaxUpdate = debounce(function(cartId, quantity) {
            fetch('../process/cart_handler.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `action=update&cart_id=${cartId}&quantity=${quantity}`
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    console.error('Update failed:', data.message);
                }
            })
            .catch(err => console.error('Error:', err));
        }, 500);
        
        // Quantity buttons
        document.querySelectorAll('.qty-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const cartId = this.dataset.cartId;
                const input = document.querySelector(`.qty-input[data-cart-id="${cartId}"]`);
                if (!input) return;
                
                let qty = parseInt(input.value) || 1;
                
                if (this.dataset.action === 'increase') {
                    qty++;
                } else if (this.dataset.action === 'decrease' && qty > 1) {
                    qty--;
                }
                
                input.value = qty;
                updateTotals();
                debouncedAjaxUpdate(cartId, qty);
            });
        });
        
        // Quantity input change
        document.querySelectorAll('.qty-input').forEach(input => {
            input.addEventListener('change', function() {
                let qty = parseInt(this.value) || 1;
                if (qty < 1) qty = 1;
                this.value = qty;
                updateTotals();
                debouncedAjaxUpdate(this.dataset.cartId, qty);
            });
        });
        
        // Remove item
        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (confirm('<?= $lang === 'id' ? 'Hapus item ini dari keranjang?' : 'Remove this item from basket?' ?>')) {
                    const cartId = this.dataset.cartId;
                    const itemEl = document.querySelector(`.basket-item[data-cart-id="${cartId}"]`);
                    
                    fetch('../process/cart_handler.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: `action=remove&cart_id=${cartId}`
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            itemEl.remove();
                            updateTotals();
                            
                            // Check if cart is empty
                            if (document.querySelectorAll('.basket-item').length === 0) {
                                location.reload();
                            }
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(err => console.error('Error:', err));
                }
            });
        });
        
        // Clear cart
        document.getElementById('clearCartBtn')?.addEventListener('click', function() {
            if (confirm('<?= $lang === 'id' ? 'Kosongkan semua item di keranjang?' : 'Clear all items from basket?' ?>')) {
                fetch('../process/cart_handler.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'action=clear'
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(err => console.error('Error:', err));
            }
        });
    </script>
</body>
</html>
