<?php
require_once 'config/database.php';

// Get featured products
$stmt = $pdo->query("SELECT * FROM products ORDER BY RAND() LIMIT 3");
$featured_products = $stmt->fetchAll();
?>

<?php include 'includes/header.php'; ?>

<div class="jumbotron bg-primary text-white rounded-3 mb-5">
    <div class="container py-5">
        <h1 class="display-4">Welcome to Our Product Management System</h1>
        <p class="lead">Manage your products and orders efficiently with our powerful tools.</p>
        <hr class="my-4">
        <p>Get started by browsing our products or logging in to manage your inventory.</p>
        <a class="btn btn-light btn-lg" href="product_view.php" role="button">View Products</a>
        <?php if(!isset($_SESSION['user_id'])): ?>
            <a class="btn btn-outline-light btn-lg" href="login.php" role="button">Login</a>
        <?php endif; ?>
    </div>
</div>

<h2 class="mb-4">Featured Products</h2>

<div class="row">
    <?php foreach($featured_products as $product): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <?php if($product['image_path']): ?>
                <img src="<?php echo $product['image_path']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
            <?php else: ?>
                <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                    No Image
                </div>
            <?php endif; ?>
            <div class="card-body">
                <h5 class="card-title"><?php echo $product['name']; ?></h5>
                <p class="card-text"><?php echo substr($product['description'], 0, 100); ?>...</p>
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
                <a href="product_view.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary">View Details</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>