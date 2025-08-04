<?php
// Enable error reporting at the VERY TOP
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Use correct absolute paths
require_once __DIR__ . '/../config/database.php';  // Changed from ../../config
require_once __DIR__ . '/../includes/auth_check.php'; // Changed from ../includes

try {
    // Get counts for dashboard
    $products_count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $orders_count = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    $users_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

    // Get recent orders
    $stmt = $pdo->query("SELECT o.*, p.name as product_name FROM orders o LEFT JOIN products p ON o.product_id = p.id ORDER BY o.order_date DESC LIMIT 5");
    $recent_orders = $stmt->fetchAll();

    // Include header AFTER all processing
    include __DIR__ . '/../includes/header.php'; // Changed from ../includes
?>

<!-- Your existing HTML content EXACTLY as you had it -->
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Products</h5>
                <h2><?php echo $products_count; ?></h2>
                <a href="products.php" class="text-white">View Products</a>
            </div>
        </div>
    </div>
    <!-- Rest of your dashboard HTML remains exactly the same -->
</div>

<?php
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Include footer
include __DIR__ . '/../includes/footer.php';
?>