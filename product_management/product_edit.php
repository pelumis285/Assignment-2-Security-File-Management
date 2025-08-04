<?php
// Enable error reporting at the top
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Corrected paths using __DIR__
require_once __DIR__ . '/includes/auth_check.php';  // Changed from ../includes
require_once __DIR__ . '/config/database.php';     // Changed from ../config

$product_id = $_GET['id'] ?? null;
if (!$product_id) {
    header("Location: admin/products.php");  // Updated redirect
    exit();
}

// Fetch product data
try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    
    if (!$product) {
        header("Location: admin/products.php");
        exit();
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $stock = $_POST['stock'] ?? 0;
    $offer_price = $_POST['offer_price'] ?? null;
    
    try {
        // Handle file upload
        $image_path = $product['image_path'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // Delete old image if exists
            if ($image_path && file_exists($image_path)) {
                unlink($image_path);
            }
            
            $upload_dir = 'uploads/';  // Removed leading ../
            $file_name = uniqid() . '_' . basename($_FILES['image']['name']);
            $target_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $image_path = $target_path;
            }
        }
        
        $stmt = $pdo->prepare("UPDATE products SET name=?, description=?, price=?, stock=?, image_path=?, offer_price=? WHERE id=?");
        $stmt->execute([$name, $description, $price, $stock, $image_path, $offer_price, $product_id]);
        
        // Corrected redirect path
        header("Location: admin/products.php?updated=1");
        exit();
    } catch (PDOException $e) {
        die("Update failed: " . $e->getMessage());
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Edit Product</h4>
            </div>
            <div class="card-body">
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo $product['price']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="offer_price" class="form-label">Offer Price (optional)</label>
                            <input type="number" step="0.01" class="form-control" id="offer_price" name="offer_price" value="<?php echo $product['offer_price']; ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock Quantity</label>
                        <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $product['stock']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Product Image</label>
                        <?php if($product['image_path']): ?>
                            <div class="mb-2">
                                <img src="<?php echo $product['image_path']; ?>" class="img-thumbnail" style="max-height: 200px;">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image">
                                    <label class="form-check-label" for="remove_image">Remove current image</label>
                                </div>
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="admin/products.php" class="btn btn-secondary me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>