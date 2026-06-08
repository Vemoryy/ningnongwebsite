<?php
session_start();

// Language handling
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}
$lang_code = $_SESSION['lang'] ?? 'id';

// Translations
$translations = [
    'en' => [
        'admin_panel' => 'Admin Panel',
        'product_management' => 'Product Management',
        'add_product' => 'Add New Product',
        'edit_product' => 'Edit Product',
        'delete_product' => 'Delete Product',
        'product_name' => 'Product Name (English)',
        'product_name_id' => 'Product Name (Indonesian)',
        'description' => 'Description (English)',
        'description_id' => 'Description (Indonesian)',
        'price' => 'Price (IDR)',
        'category' => 'Category',
        'image' => 'Product Image',
        'featured' => 'Featured',
        'active' => 'Active',
        'actions' => 'Actions',
        'save' => 'Save Product',
        'update' => 'Update Product',
        'cancel' => 'Cancel',
        'confirm_delete' => 'Are you sure you want to delete this product?',
        'no_products' => 'No products found.',
        'back_dashboard' => 'Back to Dashboard',
        'total_products' => 'Total Products',
        'active_products' => 'Active Products',
        'featured_products' => 'Featured Products',
        'unauthorized' => 'You do not have permission to access this page.',
        'login_required' => 'Please login to access this page.',
    ],
    'id' => [
        'admin_panel' => 'Panel Admin',
        'product_management' => 'Manajemen Produk',
        'add_product' => 'Tambah Produk Baru',
        'edit_product' => 'Edit Produk',
        'delete_product' => 'Hapus Produk',
        'product_name' => 'Nama Produk (Inggris)',
        'product_name_id' => 'Nama Produk (Indonesia)',
        'description' => 'Deskripsi (Inggris)',
        'description_id' => 'Deskripsi (Indonesia)',
        'price' => 'Harga (IDR)',
        'category' => 'Kategori',
        'image' => 'Gambar Produk',
        'featured' => 'Unggulan',
        'active' => 'Aktif',
        'actions' => 'Aksi',
        'save' => 'Simpan Produk',
        'update' => 'Perbarui Produk',
        'cancel' => 'Batal',
        'confirm_delete' => 'Apakah Anda yakin ingin menghapus produk ini?',
        'no_products' => 'Tidak ada produk ditemukan.',
        'back_dashboard' => 'Kembali ke Dashboard',
        'total_products' => 'Total Produk',
        'active_products' => 'Produk Aktif',
        'featured_products' => 'Produk Unggulan',
        'unauthorized' => 'Anda tidak memiliki izin untuk mengakses halaman ini.',
        'login_required' => 'Silakan login untuk mengakses halaman ini.',
    ]
];

$t = $translations[$lang_code];

// Check authentication
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = $t['login_required'];
    header("Location: login.php?lang=$lang_code");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "ningnong_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is admin/owner
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || !in_array($user['role'], ['admin', 'owner'])) {
    $_SESSION['error'] = $t['unauthorized'];
    header("Location: dashboard.php?lang=$lang_code");
    exit();
}

// Get products
$products = $conn->query("SELECT * FROM products ORDER BY created_at DESC");

// Get stats
$totalProducts = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$activeProducts = $conn->query("SELECT COUNT(*) as count FROM products WHERE is_active = 1")->fetch_assoc()['count'];
$featuredProducts = $conn->query("SELECT COUNT(*) as count FROM products WHERE is_featured = 1")->fetch_assoc()['count'];

// Get messages
$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="<?= $lang_code ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['admin_panel'] ?> - Ning Nong Indonesia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .product-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        .modal-lg {
            max-width: 700px;
        }
        .badge-featured {
            background: linear-gradient(135deg, #ff851b, #ff6300);
        }
        .badge-active {
            background: #28a745;
        }
        .badge-inactive {
            background: #dc3545;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php?lang=<?= $lang_code ?>">Ning Nong Indonesia</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?lang=<?= $lang_code ?>"><i class="fas fa-home me-1"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php?lang=<?= $lang_code ?>"><i class="fas fa-user me-1"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin_products.php?lang=<?= $lang_code ?>"><i class="fas fa-cog me-1"></i> Admin</a>
                    </li>
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
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <!-- Admin Header -->
        <div class="admin-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1><i class="fas fa-boxes me-2"></i><?= $t['product_management'] ?></h1>
                    <p>Manage your products, add new items, and control what's shown on your store.</p>
                </div>
                <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="fas fa-plus me-2"></i><?= $t['add_product'] ?>
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stats-card blue">
                    <h3><?= $totalProducts ?></h3>
                    <p><i class="fas fa-box me-1"></i> <?= $t['total_products'] ?></p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stats-card green">
                    <h3><?= $activeProducts ?></h3>
                    <p><i class="fas fa-check-circle me-1"></i> <?= $t['active_products'] ?></p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stats-card">
                    <h3><?= $featuredProducts ?></h3>
                    <p><i class="fas fa-star me-1"></i> <?= $t['featured_products'] ?></p>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Products Table -->
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 80px;"><?= $t['image'] ?></th>
                        <th><?= $t['product_name'] ?></th>
                        <th><?= $t['category'] ?></th>
                        <th><?= $t['price'] ?></th>
                        <th style="width: 100px;"><?= $t['featured'] ?></th>
                        <th style="width: 100px;"><?= $t['active'] ?></th>
                        <th style="width: 150px;"><?= $t['actions'] ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($products->num_rows > 0): ?>
                        <?php while ($product = $products->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?php if ($product['image']): ?>
                                        <img src="../Image/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-thumb">
                                    <?php else: ?>
                                        <div class="product-thumb bg-secondary d-flex align-items-center justify-content-center">
                                            <i class="fas fa-image text-white"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($product['name']) ?></strong>
                                    <br>
                                    <small class="text-muted"><?= htmlspecialchars($product['name_id']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($product['category'] ?? '-') ?></td>
                                <td>Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                                <td>
                                    <form action="../process/product_handler.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="toggle_featured">
                                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                        <button type="submit" class="btn btn-sm <?= $product['is_featured'] ? 'btn-warning' : 'btn-outline-secondary' ?>">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <form action="../process/product_handler.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="toggle_active">
                                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                        <button type="submit" class="btn btn-sm <?= $product['is_active'] ? 'btn-success' : 'btn-outline-danger' ?>">
                                            <i class="fas <?= $product['is_active'] ? 'fa-check' : 'fa-times' ?>"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary me-1" onclick="editProduct(<?= htmlspecialchars(json_encode($product)) ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="../process/product_handler.php?action=delete&id=<?= $product['id'] ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('<?= $t['confirm_delete'] ?>')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <p class="text-muted"><?= $t['no_products'] ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--primary-color); color: white;">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i><?= $t['add_product'] ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="../process/product_handler.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= $t['product_name'] ?> *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= $t['product_name_id'] ?></label>
                                <input type="text" name="name_id" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= $t['description'] ?></label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= $t['description_id'] ?></label>
                                <textarea name="description_id" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label"><?= $t['price'] ?> *</label>
                                <input type="number" name="price" class="form-control" required min="0" step="100">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label"><?= $t['category'] ?></label>
                                <select name="category" class="form-select">
                                    <option value="">Select Category</option>
                                    <option value="Traditional">Traditional</option>
                                    <option value="Premium">Premium</option>
                                    <option value="Special Edition">Special Edition</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label"><?= $t['image'] ?></label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="is_featured" class="form-check-input" id="addFeatured">
                                    <label class="form-check-label" for="addFeatured"><?= $t['featured'] ?></label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" class="form-check-input" id="addActive" checked>
                                    <label class="form-check-label" for="addActive"><?= $t['active'] ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= $t['cancel'] ?></button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i><?= $t['save'] ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--primary-color); color: white;">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i><?= $t['edit_product'] ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="../process/product_handler.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="editId">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= $t['product_name'] ?> *</label>
                                <input type="text" name="name" id="editName" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= $t['product_name_id'] ?></label>
                                <input type="text" name="name_id" id="editNameId" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= $t['description'] ?></label>
                                <textarea name="description" id="editDescription" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= $t['description_id'] ?></label>
                                <textarea name="description_id" id="editDescriptionId" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label"><?= $t['price'] ?> *</label>
                                <input type="number" name="price" id="editPrice" class="form-control" required min="0" step="100">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label"><?= $t['category'] ?></label>
                                <select name="category" id="editCategory" class="form-select">
                                    <option value="">Select Category</option>
                                    <option value="Traditional">Traditional</option>
                                    <option value="Premium">Premium</option>
                                    <option value="Special Edition">Special Edition</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label"><?= $t['image'] ?></label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <small class="text-muted">Leave empty to keep current image</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="is_featured" class="form-check-input" id="editFeatured">
                                    <label class="form-check-label" for="editFeatured"><?= $t['featured'] ?></label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" class="form-check-input" id="editActive">
                                    <label class="form-check-label" for="editActive"><?= $t['active'] ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= $t['cancel'] ?></button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i><?= $t['update'] ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-links">
            <a href="https://www.instagram.com/ningnongindonesia" target="_blank"><i class="fab fa-instagram"></i> Instagram</a>
            <a href="https://www.tiktok.com/@kembang.goyang.ningnong" target="_blank"><i class="fab fa-tiktok"></i> TikTok</a>
            <a href="https://api.whatsapp.com/send?phone=6282299891278" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Ning Nong Indonesia. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function editProduct(product) {
            document.getElementById('editId').value = product.id;
            document.getElementById('editName').value = product.name;
            document.getElementById('editNameId').value = product.name_id || '';
            document.getElementById('editDescription').value = product.description || '';
            document.getElementById('editDescriptionId').value = product.description_id || '';
            document.getElementById('editPrice').value = product.price;
            document.getElementById('editCategory').value = product.category || '';
            document.getElementById('editFeatured').checked = product.is_featured == 1;
            document.getElementById('editActive').checked = product.is_active == 1;
            
            new bootstrap.Modal(document.getElementById('editProductModal')).show();
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>
