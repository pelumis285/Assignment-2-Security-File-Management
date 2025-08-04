<?php
// Enable error reporting at the top
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/database.php';

try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

include 'includes/header.php';
?>

<h2 class="mb-4">Our Products</h2>

<div class="row">
    <?php foreach($products as $product): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <?php if($product['image_path']): ?>
                <img src="<?php echo htmlspecialchars($product['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <?php else: ?>
                <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                    No Image
                </div>
            <?php endif; ?>
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars(substr($product['description'], 0, 100)); ?>...</p>
                <div class="d-flex justify-content-between align-items-center">
                    <?php if($product['offer_price'] && $product['offer_price'] < $product['price']): ?>
                        <div>
                            <span class="text-danger"><del>$<?php echo number_format($product['price'], 2); ?></del></span>
                            <span class="h5 text-success">$<?php echo number_format($product['offer_price'], 2); ?></span>
                        </div>
                    <?php else: ?>
                        <span class="h5">$<?php echo number_format($product['price'], 2); ?></span>
                    <?php endif; ?>
                    <span class="badge bg-<?php echo $product['stock'] > 0 ? 'success' : 'danger'; ?>">
                        <?php echo $product['stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                    </span>
                </div>
            </div>
            <div class="card-footer bg-white">
                <a href="product_details.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary">View Details</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="product_edit.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php if(isset($_SESSION['user_id'])): ?>
    <div class="mt-4">
        <a href="product_create.php" class="btn btn-success">Add New Product</a></div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>