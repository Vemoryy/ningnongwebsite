<?php
/**
 * Cart Handler - Manages shopping cart operations
 * Supports: add, remove, update quantity, clear cart
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php';

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add':
            addToCart();
            break;
        case 'remove':
            removeFromCart();
            break;
        case 'update':
            updateQuantity();
            break;
        case 'clear':
            clearCart();
            break;
        case 'get_count':
            getCartCount();
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
    exit();
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Add product to cart
 */
function addToCart() {
    global $conn;
    
    header('Content-Type: application/json');
    
    if (!isLoggedIn()) {
        echo json_encode(['success' => false, 'message' => 'Please login to add items to cart', 'redirect' => 'login.php']);
        return;
    }
    
    $user_id = intval($_SESSION['user_id']);
    $product_id = intval($_POST['product_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 1);
    
    if ($product_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product']);
        return;
    }
    
    if ($quantity <= 0) {
        $quantity = 1;
    }
    
    // Check if product exists and is active
    $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id = ? AND is_active = 1");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not available']);
        return;
    }
    
    // Check if product already in cart
    $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cartItem = $result->fetch_assoc();
    
    if ($cartItem) {
        // Update quantity
        $newQuantity = $cartItem['quantity'] + $quantity;
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->bind_param("ii", $newQuantity, $cartItem['id']);
    } else {
        // Insert new cart item
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    }
    
    if ($stmt->execute()) {
        $cartCount = getCartCountValue($user_id);
        echo json_encode([
            'success' => true, 
            'message' => $product['name'] . ' added to cart!',
            'cart_count' => $cartCount
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add to cart']);
    }
}

/**
 * Remove product from cart
 */
function removeFromCart() {
    global $conn;
    
    header('Content-Type: application/json');
    
    if (!isLoggedIn()) {
        echo json_encode(['success' => false, 'message' => 'Please login first']);
        return;
    }
    
    $user_id = intval($_SESSION['user_id']);
    $cart_id = intval($_POST['cart_id'] ?? 0);
    
    if ($cart_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid cart item']);
        return;
    }
    
    // Delete cart item (ensure it belongs to this user)
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);
    
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $cartCount = getCartCountValue($user_id);
        echo json_encode([
            'success' => true, 
            'message' => 'Item removed from cart',
            'cart_count' => $cartCount
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove item']);
    }
}

/**
 * Update cart item quantity
 */
function updateQuantity() {
    global $conn;
    
    header('Content-Type: application/json');
    
    if (!isLoggedIn()) {
        echo json_encode(['success' => false, 'message' => 'Please login first']);
        return;
    }
    
    $user_id = intval($_SESSION['user_id']);
    $cart_id = intval($_POST['cart_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 1);
    
    if ($cart_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid cart item']);
        return;
    }
    
    if ($quantity <= 0) {
        // If quantity is 0 or negative, remove the item
        $_POST['cart_id'] = $cart_id;
        removeFromCart();
        return;
    }
    
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("iii", $quantity, $cart_id, $user_id);
    
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Quantity updated']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update quantity']);
    }
}

/**
 * Clear entire cart
 */
function clearCart() {
    global $conn;
    
    header('Content-Type: application/json');
    
    if (!isLoggedIn()) {
        echo json_encode(['success' => false, 'message' => 'Please login first']);
        return;
    }
    
    $user_id = intval($_SESSION['user_id']);
    
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Cart cleared', 'cart_count' => 0]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to clear cart']);
    }
}

/**
 * Get cart count for AJAX
 */
function getCartCount() {
    header('Content-Type: application/json');
    
    if (!isLoggedIn()) {
        echo json_encode(['success' => true, 'cart_count' => 0]);
        return;
    }
    
    $user_id = intval($_SESSION['user_id']);
    $count = getCartCountValue($user_id);
    echo json_encode(['success' => true, 'cart_count' => $count]);
}

/**
 * Helper: Get cart item count for a user
 */
function getCartCountValue($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return intval($row['total'] ?? 0);
}

/**
 * Helper: Get all cart items for a user (for basket page)
 */
function getCartItems($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT c.id as cart_id, c.quantity, c.created_at,
               p.id as product_id, p.name, p.name_id, p.price, p.image, p.category
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ? AND p.is_active = 1
        ORDER BY c.created_at DESC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    
    return $items;
}

/**
 * Helper: Calculate cart total
 */
function getCartTotal($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT SUM(c.quantity * p.price) as total
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ? AND p.is_active = 1
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return floatval($row['total'] ?? 0);
}
?>
