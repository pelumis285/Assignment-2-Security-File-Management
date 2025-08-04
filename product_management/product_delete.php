<?php
require_once 'includes/auth_check.php';
require_once 'config/database.php';

if(!isset($_GET['id'])) {
    header("Location: admin/products.php");
    exit();
}

$product_id = $_GET['id'];

// Fetch product to get image path
$stmt = $pdo->prepare("SELECT image_path FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

try {
    // Delete product
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    
    // Delete associated image if exists
    if($product && $product['image_path'] && file_exists($product['image_path'])) {
        unlink($product['image_path']);
    }
    
    header("Location: admin/products.php?deleted=1");
    exit();
} catch (PDOException $e) {
    header("Location: admin/products.php?error=1");
    exit();
}
?>