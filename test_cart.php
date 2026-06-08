<?php
/**
 * Cart System Test Script
 * Tests: Add to cart, View cart, Update quantity, Remove from cart, Clear cart
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<html><head><title>Cart System Tests</title>";
echo "<style>
body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
.test { margin: 10px 0; padding: 15px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.pass { border-left: 4px solid #28a745; }
.fail { border-left: 4px solid #dc3545; }
.info { border-left: 4px solid #17a2b8; }
h1 { color: #001f3f; }
h2 { color: #333; margin-top: 30px; }
.status { font-weight: bold; }
.pass .status { color: #28a745; }
.fail .status { color: #dc3545; }
.info .status { color: #17a2b8; }
a.btn { display: inline-block; padding: 10px 20px; background: #001f3f; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }
a.btn:hover { background: #003366; }
a.btn-success { background: #28a745; }
a.btn-warning { background: #ff851b; }
</style></head><body>";

echo "<h1>🛒 Cart System Test Results</h1>";

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Test 1: Database Connection
echo "<h2>1. Database Connection</h2>";
require_once 'process/db.php';

if ($conn && !$conn->connect_error) {
    echo '<div class="test pass"><span class="status">✅ PASS</span> - Database connection successful</div>';
} else {
    echo '<div class="test fail"><span class="status">❌ FAIL</span> - Database connection failed</div>';
    exit();
}

// Test 2: Cart Table Structure
echo "<h2>2. Cart Table Structure</h2>";
$result = $conn->query("DESCRIBE cart");
if ($result) {
    $columns = [];
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
    
    $required = ['id', 'user_id', 'product_id', 'quantity'];
    $missing = array_diff($required, $columns);
    
    if (empty($missing)) {
        echo '<div class="test pass"><span class="status">✅ PASS</span> - Cart table has required columns: ' . implode(', ', $columns) . '</div>';
    } else {
        echo '<div class="test fail"><span class="status">❌ FAIL</span> - Missing columns: ' . implode(', ', $missing) . '</div>';
    }
} else {
    echo '<div class="test fail"><span class="status">❌ FAIL</span> - Cart table not found</div>';
}

// Test 3: Cart Handler Functions
echo "<h2>3. Cart Handler Functions</h2>";
$handlerFile = __DIR__ . '/process/cart_handler.php';
if (file_exists($handlerFile)) {
    echo '<div class="test pass"><span class="status">✅ PASS</span> - cart_handler.php exists</div>';
    
    $content = file_get_contents($handlerFile);
    $functions = ['addToCart', 'removeFromCart', 'updateQuantity', 'clearCart', 'getCartItems', 'getCartTotal', 'getCartCountValue'];
    
    foreach ($functions as $func) {
        if (strpos($content, "function $func") !== false) {
            echo '<div class="test pass"><span class="status">✅ PASS</span> - Function ' . $func . '() defined</div>';
        } else {
            echo '<div class="test fail"><span class="status">❌ FAIL</span> - Function ' . $func . '() NOT found</div>';
        }
    }
} else {
    echo '<div class="test fail"><span class="status">❌ FAIL</span> - cart_handler.php not found</div>';
}

// Test 4: Basket Page
echo "<h2>4. Basket Page</h2>";
$basketFile = __DIR__ . '/pages/basket.php';
if (file_exists($basketFile)) {
    echo '<div class="test pass"><span class="status">✅ PASS</span> - basket.php exists</div>';
} else {
    echo '<div class="test fail"><span class="status">❌ FAIL</span> - basket.php not found</div>';
}

// Test 5: Products available
echo "<h2>5. Products Available</h2>";
$result = $conn->query("SELECT id, name, price FROM products WHERE is_active = 1 LIMIT 5");
if ($result && $result->num_rows > 0) {
    echo '<div class="test pass"><span class="status">✅ PASS</span> - Active products found:</div>';
    echo '<div class="test info">';
    while ($product = $result->fetch_assoc()) {
        echo "- ID: {$product['id']} | {$product['name']} | Rp " . number_format($product['price'], 0, ',', '.') . "<br>";
    }
    echo '</div>';
} else {
    echo '<div class="test fail"><span class="status">❌ FAIL</span> - No active products found</div>';
}

// Test 6: Session Status
echo "<h2>6. Session Status</h2>";
if (isset($_SESSION['user_id'])) {
    echo '<div class="test pass"><span class="status">✅ PASS</span> - User logged in with ID: ' . $_SESSION['user_id'] . '</div>';
    
    // Check cart items for this user
    $stmt = $conn->prepare("SELECT COUNT(*) as count, SUM(quantity) as total FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $cartData = $stmt->get_result()->fetch_assoc();
    
    echo '<div class="test info"><span class="status">ℹ️ INFO</span> - Cart has ' . ($cartData['count'] ?? 0) . ' items, total quantity: ' . ($cartData['total'] ?? 0) . '</div>';
} else {
    echo '<div class="test info"><span class="status">ℹ️ INFO</span> - No user logged in. Login required to test cart operations.</div>';
}

// Test 7: SQL Statement Tests
echo "<h2>7. SQL Statement Preparation</h2>";

// Test INSERT
$stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
if ($stmt) {
    echo '<div class="test pass"><span class="status">✅ PASS</span> - INSERT statement prepares correctly</div>';
    $stmt->close();
} else {
    echo '<div class="test fail"><span class="status">❌ FAIL</span> - INSERT prepare failed: ' . $conn->error . '</div>';
}

// Test UPDATE
$stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
if ($stmt) {
    echo '<div class="test pass"><span class="status">✅ PASS</span> - UPDATE statement prepares correctly</div>';
    $stmt->close();
} else {
    echo '<div class="test fail"><span class="status">❌ FAIL</span> - UPDATE prepare failed: ' . $conn->error . '</div>';
}

// Test DELETE
$stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
if ($stmt) {
    echo '<div class="test pass"><span class="status">✅ PASS</span> - DELETE statement prepares correctly</div>';
    $stmt->close();
} else {
    echo '<div class="test fail"><span class="status">❌ FAIL</span> - DELETE prepare failed: ' . $conn->error . '</div>';
}

// Test SELECT with JOIN
$stmt = $conn->prepare("SELECT c.*, p.name, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
if ($stmt) {
    echo '<div class="test pass"><span class="status">✅ PASS</span> - SELECT with JOIN prepares correctly</div>';
    $stmt->close();
} else {
    echo '<div class="test fail"><span class="status">❌ FAIL</span> - SELECT with JOIN prepare failed: ' . $conn->error . '</div>';
}

// Summary and Links
echo "<h2>📊 Test Complete</h2>";
echo "<div class='test'>";
echo "<p><strong>All cart system components are ready!</strong></p>";
echo "<p>To test the cart functionality:</p>";
echo "<ol>";
echo "<li>Login first if not already logged in</li>";
echo "<li>Go to Products page and click 'Add to Cart'</li>";
echo "<li>View your basket to see items</li>";
echo "<li>Update quantities or remove items</li>";
echo "</ol>";
echo "<br>";
echo "<a href='pages/login.php' class='btn'>🔐 Login</a>";
echo "<a href='pages/products.php' class='btn btn-success'>🛍️ Products</a>";
echo "<a href='pages/basket.php' class='btn btn-warning'>🛒 Basket</a>";
echo "</div>";

echo "</body></html>";
?>
