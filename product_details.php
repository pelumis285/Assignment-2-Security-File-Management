<?php
require_once '../config/database.php';

// Get product ID from URL
$product_id = $_GET['id'] ?? null;

if (!$product_id) {
    header("Location: product_view.php");
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    
    if (!$product) {
        header("Location: product_view.php");
        exit();
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <?php if($product['image_path']): ?>
                <img src="<?= htmlspecialchars($product['image_path']) ?>" class="img-fluid" alt="<?= htmlspecialchars($product['name']) ?>">
            <?php else: ?>
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 300px;">
                    No Image Available
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <h2><?= htmlspecialchars($product['name']) ?></h2>
            <p class="text-muted">Product ID: <?= $product['id'] ?></p>
            
            <div class="mb-3">
                <?php if($product['offer_price'] && $product['offer_price'] < $product['price']): ?>
                    <h4 class="text-danger"><del>$<?= number_format($product['price'], 2) ?></del></h4>
                    <h3 class="text-success">$<?= number_format($product['offer_price'], 2) ?></h3>
                <?php else: ?>
                    <h3>$<?= number_format($product['price'], 2) ?></h3>
                <?php endif; ?>
                
                <span class="badge bg-<?= $product['stock'] > 0 ? 'success' : 'danger' ?>">
                    <?= $product['stock'] > 0 ? 'In Stock' : 'Out of Stock' ?>
                </span>
            </div>
            
            <h4>Description</h4>
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            
            <a href="product_view.php" class="btn btn-primary">Back to Products</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="product_edit.php?id=<?= $product['id'] ?>" class="btn btn-secondary">Edit Product</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>