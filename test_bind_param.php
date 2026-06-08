<?php
/**
 * Test script for product edit with image upload
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Product Handler bind_param Test</h1>";

// Test the bind_param type strings match parameter counts
$tests = [
    [
        'name' => 'Edit without image',
        'types' => 'ssssdsiis',
        'params' => ['name', 'name_id', 'description', 'description_id', 'price', 'category', 'is_featured', 'is_active', 'id'],
        'expected_count' => 9
    ],
    [
        'name' => 'Edit with image', 
        'types' => 'ssssdsiiss',
        'params' => ['name', 'name_id', 'description', 'description_id', 'price', 'category', 'is_featured', 'is_active', 'newFileName', 'id'],
        'expected_count' => 10
    ],
    [
        'name' => 'Add product',
        'types' => 'ssssdssii',
        'params' => ['name', 'name_id', 'description', 'description_id', 'price', 'image', 'category', 'is_featured', 'is_active'],
        'expected_count' => 9
    ]
];

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Test</th><th>Type String</th><th>Type Count</th><th>Param Count</th><th>Result</th></tr>";

foreach ($tests as $test) {
    $typeCount = strlen($test['types']);
    $paramCount = count($test['params']);
    $pass = ($typeCount === $paramCount);
    $color = $pass ? 'green' : 'red';
    $result = $pass ? '✅ PASS' : '❌ FAIL';
    
    echo "<tr>";
    echo "<td>{$test['name']}</td>";
    echo "<td><code>{$test['types']}</code></td>";
    echo "<td>{$typeCount}</td>";
    echo "<td>{$paramCount}</td>";
    echo "<td style='color:{$color}'>{$result}</td>";
    echo "</tr>";
}
echo "</table>";

// Now test actual database operations
echo "<h2>Database Connection Test</h2>";
require_once 'process/db.php';

if ($conn->connect_error) {
    echo "<p style='color:red'>❌ Connection failed: " . $conn->connect_error . "</p>";
} else {
    echo "<p style='color:green'>✅ Database connected</p>";
    
    // Test prepare statements
    echo "<h3>Prepare Statement Tests</h3>";
    
    // Test 1: Edit without image
    $stmt = $conn->prepare("UPDATE products SET name = ?, name_id = ?, description = ?, description_id = ?, price = ?, category = ?, is_featured = ?, is_active = ? WHERE id = ?");
    if ($stmt) {
        echo "<p style='color:green'>✅ Edit (no image) prepare OK</p>";
        $stmt->close();
    } else {
        echo "<p style='color:red'>❌ Edit (no image) prepare FAILED: " . $conn->error . "</p>";
    }
    
    // Test 2: Edit with image
    $stmt = $conn->prepare("UPDATE products SET name = ?, name_id = ?, description = ?, description_id = ?, price = ?, category = ?, is_featured = ?, is_active = ?, image = ? WHERE id = ?");
    if ($stmt) {
        echo "<p style='color:green'>✅ Edit (with image) prepare OK</p>";
        $stmt->close();
    } else {
        echo "<p style='color:red'>❌ Edit (with image) prepare FAILED: " . $conn->error . "</p>";
    }
    
    // Test actual bind_param
    echo "<h3>bind_param Execution Test</h3>";
    
    $stmt = $conn->prepare("UPDATE products SET name = ?, name_id = ?, description = ?, description_id = ?, price = ?, category = ?, is_featured = ?, is_active = ?, image = ? WHERE id = ?");
    
    $name = "Test";
    $name_id = "Test ID";
    $description = "Test Desc";
    $description_id = "Test Desc ID";
    $price = 10000.0;
    $category = "snack";
    $is_featured = 0;
    $is_active = 1;
    $newFileName = "test.jpg";
    $id = 9999; // Non-existent ID for safe test
    
    try {
        $result = $stmt->bind_param("ssssdsiiss", $name, $name_id, $description, $description_id, $price, $category, $is_featured, $is_active, $newFileName, $id);
        if ($result) {
            echo "<p style='color:green'>✅ bind_param (with image) OK - 10 params with 'ssssdsiiss' (10 chars)</p>";
        } else {
            echo "<p style='color:red'>❌ bind_param (with image) FAILED</p>";
        }
        $stmt->close();
    } catch (Exception $e) {
        echo "<p style='color:red'>❌ bind_param Exception: " . $e->getMessage() . "</p>";
    }
    
    // Test without image
    $stmt = $conn->prepare("UPDATE products SET name = ?, name_id = ?, description = ?, description_id = ?, price = ?, category = ?, is_featured = ?, is_active = ? WHERE id = ?");
    
    try {
        $result = $stmt->bind_param("ssssdsiis", $name, $name_id, $description, $description_id, $price, $category, $is_featured, $is_active, $id);
        if ($result) {
            echo "<p style='color:green'>✅ bind_param (no image) OK - 9 params with 'ssssdsiis' (9 chars)</p>";
        } else {
            echo "<p style='color:red'>❌ bind_param (no image) FAILED</p>";
        }
        $stmt->close();
    } catch (Exception $e) {
        echo "<p style='color:red'>❌ bind_param Exception: " . $e->getMessage() . "</p>";
    }
}

// Check Image directory
echo "<h2>Image Directory Check</h2>";
$imageDir = __DIR__ . '/Image';
if (is_dir($imageDir)) {
    echo "<p style='color:green'>✅ Image directory exists</p>";
    if (is_writable($imageDir)) {
        echo "<p style='color:green'>✅ Image directory is writable</p>";
    } else {
        echo "<p style='color:red'>❌ Image directory is NOT writable - uploads will fail!</p>";
    }
} else {
    echo "<p style='color:red'>❌ Image directory does not exist</p>";
}

echo "<hr><p><a href='pages/admin_products.php'>Go to Admin Panel</a></p>";
?>
