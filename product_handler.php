<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';

// Check if user is logged in and is admin/owner
function isAdmin() {
    if (!isset($_SESSION['user_id'])) {
        return false;
    }
    
    global $conn;
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    return $user && in_array($user['role'], ['admin', 'owner']);
}

// Handle different actions
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        addProduct();
        break;
    case 'edit':
        editProduct();
        break;
    case 'delete':
        deleteProduct();
        break;
    case 'toggle_active':
        toggleActive();
        break;
    case 'toggle_featured':
        toggleFeatured();
        break;
    default:
        header("Location: ../pages/admin_products.php");
        exit();
}

function addProduct() {
    global $conn;
    
    if (!isAdmin()) {
        $_SESSION['error'] = "Unauthorized access.";
        header("Location: ../pages/login.php");
        exit();
    }
    
    $name = trim($_POST['name']);
    $name_id = trim($_POST['name_id']);
    $description = trim($_POST['description']);
    $description_id = trim($_POST['description_id']);
    $price = floatval($_POST['price']);
    $category = trim($_POST['category']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../Image/';
        $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = 'product_' . time() . '.' . $fileExtension;
            $uploadPath = $uploadDir . $newFileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $image = $newFileName;
            } else {
                $_SESSION['error'] = "Failed to upload image.";
                header("Location: ../pages/admin_products.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid image format. Allowed: jpg, jpeg, png, gif, webp";
            header("Location: ../pages/admin_products.php");
            exit();
        }
    }
    
    $stmt = $conn->prepare("INSERT INTO products (name, name_id, description, description_id, price, image, category, is_featured, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssdssii", $name, $name_id, $description, $description_id, $price, $image, $category, $is_featured, $is_active);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Product added successfully!";
    } else {
        $_SESSION['error'] = "Failed to add product: " . $conn->error;
    }
    
    header("Location: ../pages/admin_products.php");
    exit();
}

function editProduct() {
    global $conn;
    
    if (!isAdmin()) {
        $_SESSION['error'] = "Unauthorized access.";
        header("Location: ../pages/login.php");
        exit();
    }
    
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $name_id = trim($_POST['name_id']);
    $description = trim($_POST['description']);
    $description_id = trim($_POST['description_id']);
    $price = floatval($_POST['price']);
    $category = trim($_POST['category']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Handle image upload
    $newFileName = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../Image/';
        $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = 'product_' . time() . '.' . $fileExtension;
            $uploadPath = $uploadDir . $newFileName;
            
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $newFileName = ''; // Reset if upload failed
            }
        }
    }
    
    if (empty($newFileName)) {
        // Update without image (9 params: name, name_id, desc, desc_id, price, cat, featured, active, id)
        $stmt = $conn->prepare("UPDATE products SET name = ?, name_id = ?, description = ?, description_id = ?, price = ?, category = ?, is_featured = ?, is_active = ? WHERE id = ?");
        $stmt->bind_param("ssssdsiis", $name, $name_id, $description, $description_id, $price, $category, $is_featured, $is_active, $id);
    } else {
        // Update with new image (10 params: name, name_id, desc, desc_id, price, cat, featured, active, image, id)
        $stmt = $conn->prepare("UPDATE products SET name = ?, name_id = ?, description = ?, description_id = ?, price = ?, category = ?, is_featured = ?, is_active = ?, image = ? WHERE id = ?");
        $stmt->bind_param("ssssdsiiss", $name, $name_id, $description, $description_id, $price, $category, $is_featured, $is_active, $newFileName, $id);
    }
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Product updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update product: " . $conn->error;
    }
    
    header("Location: ../pages/admin_products.php");
    exit();
}

function deleteProduct() {
    global $conn;
    
    if (!isAdmin()) {
        $_SESSION['error'] = "Unauthorized access.";
        header("Location: ../pages/login.php");
        exit();
    }
    
    $id = intval($_GET['id']);
    
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Product deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete product: " . $conn->error;
    }
    
    header("Location: ../pages/admin_products.php");
    exit();
}

function toggleActive() {
    global $conn;
    
    if (!isAdmin()) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit();
    }
    
    $id = intval($_POST['id']);
    
    $stmt = $conn->prepare("UPDATE products SET is_active = NOT is_active WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Product status updated!";
    } else {
        $_SESSION['error'] = "Failed to update status.";
    }
    
    header("Location: ../pages/admin_products.php");
    exit();
}

function toggleFeatured() {
    global $conn;
    
    if (!isAdmin()) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit();
    }
    
    $id = intval($_POST['id']);
    
    $stmt = $conn->prepare("UPDATE products SET is_featured = NOT is_featured WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Featured status updated!";
    } else {
        $_SESSION['error'] = "Failed to update featured status.";
    }
    
    header("Location: ../pages/admin_products.php");
    exit();
}
?>
