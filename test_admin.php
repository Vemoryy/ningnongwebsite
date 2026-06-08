<?php
/**
 * Admin Panel Test Script
 * Run this to verify all functionality works correctly
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<html><head><title>Admin Panel Tests</title>";
echo "<style>
body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
.test { margin: 10px 0; padding: 15px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.pass { border-left: 4px solid #28a745; }
.fail { border-left: 4px solid #dc3545; }
.warn { border-left: 4px solid #ffc107; }
h1 { color: #001f3f; }
h2 { color: #333; margin-top: 30px; }
.status { font-weight: bold; }
.pass .status { color: #28a745; }
.fail .status { color: #dc3545; }
.warn .status { color: #ffc107; }
</style></head><body>";

echo "<h1>🧪 NingNong Admin Panel Test Results</h1>";

// Test 1: Database Connection
echo "<h2>1. Database Connection</h2>";
try {
    require_once 'process/db.php';
    if ($conn && $conn->ping()) {
        echo '<div class="test pass"><span class="status">✅ PASS</span> - Database connection successful</div>';
    } else {
        echo '<div class="test fail"><span class="status">❌ FAIL</span> - Database connection failed</div>';
    }
} catch (Exception $e) {
    echo '<div class="test fail"><span class="status">❌ FAIL</span> - ' . htmlspecialchars($e->getMessage()) . '</div>';
}

// Test 2: Users Table
echo "<h2>2. Users Table</h2>";
try {
    $result = $conn->query("SELECT COUNT(*) as count FROM users");
    $row = $result->fetch_assoc();
    echo '<div class="test pass"><span class="status">✅ PASS</span> - Users table exists with ' . $row['count'] . ' users</div>';
    
    // Check admin users
    $result = $conn->query("SELECT email, role FROM users WHERE role = 'admin'");
    $admins = [];
    while ($admin = $result->fetch_assoc()) {
        $admins[] = $admin['email'];
    }
    if (count($admins) > 0) {
        echo '<div class="test pass"><span class="status">✅ PASS</span> - Admin users found: ' . implode(', ', $admins) . '</div>';
    } else {
        echo '<div class="test warn"><span class="status">⚠️ WARN</span> - No admin users found</div>';
    }
} catch (Exception $e) {
    echo '<div class="test fail"><span class="status">❌ FAIL</span> - ' . htmlspecialchars($e->getMessage()) . '</div>';
}

// Test 3: Products Table
echo "<h2>3. Products Table</h2>";
try {
    $result = $conn->query("DESCRIBE products");
    $columns = [];
    while ($col = $result->fetch_assoc()) {
        $columns[] = $col['Field'];
    }
    
    $required = ['id', 'name', 'name_id', 'description', 'description_id', 'price', 'image', 'category', 'is_featured', 'is_active'];
    $missing = array_diff($required, $columns);
    
    if (empty($missing)) {
        echo '<div class="test pass"><span class="status">✅ PASS</span> - Products table has all required columns</div>';
    } else {
        echo '<div class="test fail"><span class="status">❌ FAIL</span> - Missing columns: ' . implode(', ', $missing) . '</div>';
    }
    
    // Count products
    $result = $conn->query("SELECT COUNT(*) as count FROM products");
    $row = $result->fetch_assoc();
    echo '<div class="test pass"><span class="status">✅ PASS</span> - Products table has ' . $row['count'] . ' products</div>';
    
} catch (Exception $e) {
    echo '<div class="test fail"><span class="status">❌ FAIL</span> - ' . htmlspecialchars($e->getMessage()) . '</div>';
}

// Test 4: Image Directory
echo "<h2>4. Image Upload Directory</h2>";
$imageDir = __DIR__ . '/Image';
if (is_dir($imageDir)) {
    echo '<div class="test pass"><span class="status">✅ PASS</span> - Image directory exists</div>';
    if (is_writable($imageDir)) {
        echo '<div class="test pass"><span class="status">✅ PASS</span> - Image directory is writable</div>';
    } else {
        echo '<div class="test fail"><span class="status">❌ FAIL</span> - Image directory is NOT writable</div>';
    }
} else {
    echo '<div class="test fail"><span class="status">❌ FAIL</span> - Image directory does not exist</div>';
}

// Test 5: Session Handling
echo "<h2>5. Session Handling</h2>";
if (session_status() === PHP_SESSION_ACTIVE) {
    echo '<div class="test pass"><span class="status">✅ PASS</span> - Session is active (started in db.php)</div>';
} else {
    echo '<div class="test warn"><span class="status">⚠️ WARN</span> - Session is not active</div>';
}

// Test 6: Product Handler Functions
echo "<h2>6. Product Handler</h2>";
$handlerFile = __DIR__ . '/process/product_handler.php';
if (file_exists($handlerFile)) {
    echo '<div class="test pass"><span class="status">✅ PASS</span> - product_handler.php exists</div>';
    
    // Check for required functions
    $content = file_get_contents($handlerFile);
    $functions = ['addProduct', 'editProduct', 'deleteProduct', 'toggleActive', 'toggleFeatured', 'isAdmin'];
    foreach ($functions as $func) {
        if (strpos($content, "function $func") !== false) {
            echo '<div class="test pass"><span class="status">✅ PASS</span> - Function ' . $func . '() defined</div>';
        } else {
            echo '<div class="test fail"><span class="status">❌ FAIL</span> - Function ' . $func . '() NOT found</div>';
        }
    }
} else {
    echo '<div class="test fail"><span class="status">❌ FAIL</span> - product_handler.php not found</div>';
}

// Test 7: Admin Products Page
echo "<h2>7. Admin Products Page</h2>";
$adminPage = __DIR__ . '/pages/admin_products.php';
if (file_exists($adminPage)) {
    echo '<div class="test pass"><span class="status">✅ PASS</span> - admin_products.php exists</div>';
} else {
    echo '<div class="test fail"><span class="status">❌ FAIL</span> - admin_products.php not found</div>';
}

// Test 8: Test Add Product (simulation)
echo "<h2>8. Database Operations Test</h2>";
try {
    // Test INSERT preparation
    $stmt = $conn->prepare("INSERT INTO products (name, name_id, description, description_id, price, image, category, is_featured, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        echo '<div class="test pass"><span class="status">✅ PASS</span> - INSERT statement prepares correctly</div>';
        $stmt->close();
    } else {
        echo '<div class="test fail"><span class="status">❌ FAIL</span> - INSERT prepare failed: ' . $conn->error . '</div>';
    }
    
    // Test UPDATE preparation (without image)
    $stmt = $conn->prepare("UPDATE products SET name = ?, name_id = ?, description = ?, description_id = ?, price = ?, category = ?, is_featured = ?, is_active = ? WHERE id = ?");
    if ($stmt) {
        echo '<div class="test pass"><span class="status">✅ PASS</span> - UPDATE (no image) statement prepares correctly</div>';
        $stmt->close();
    } else {
        echo '<div class="test fail"><span class="status">❌ FAIL</span> - UPDATE (no image) prepare failed: ' . $conn->error . '</div>';
    }
    
    // Test UPDATE preparation (with image)
    $stmt = $conn->prepare("UPDATE products SET name = ?, name_id = ?, description = ?, description_id = ?, price = ?, category = ?, is_featured = ?, is_active = ?, image = ? WHERE id = ?");
    if ($stmt) {
        echo '<div class="test pass"><span class="status">✅ PASS</span> - UPDATE (with image) statement prepares correctly</div>';
        $stmt->close();
    } else {
        echo '<div class="test fail"><span class="status">❌ FAIL</span> - UPDATE (with image) prepare failed: ' . $conn->error . '</div>';
    }
    
    // Test DELETE preparation
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    if ($stmt) {
        echo '<div class="test pass"><span class="status">✅ PASS</span> - DELETE statement prepares correctly</div>';
        $stmt->close();
    } else {
        echo '<div class="test fail"><span class="status">❌ FAIL</span> - DELETE prepare failed: ' . $conn->error . '</div>';
    }
    
} catch (Exception $e) {
    echo '<div class="test fail"><span class="status">❌ FAIL</span> - ' . htmlspecialchars($e->getMessage()) . '</div>';
}

// Summary
echo "<h2>📊 Summary</h2>";
echo "<div class='test'>";
echo "<p><strong>Testing Complete!</strong></p>";
echo "<p>If all tests pass, you can access the admin panel at:</p>";
echo "<p><a href='pages/admin_products.php'>→ Admin Products Panel</a></p>";
echo "<p>Login first at: <a href='pages/login.php'>→ Login Page</a></p>";
echo "</div>";

echo "</body></html>";
?>
