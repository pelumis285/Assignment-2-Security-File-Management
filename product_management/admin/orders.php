<?php
require_once '../includes/auth_check.php';
require_once '../../config/database.php';

$stmt = $pdo->query("SELECT o.*, p.name as product_name FROM orders o LEFT JOIN products p ON o.product_id = p.id ORDER BY o.order_date DESC");
$orders = $stmt->fetchAll();
?>

<?php include '../includes/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Orders</h2>
    <span><?php echo count($orders); ?> orders found</span>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Address</th>
                        <th>Date</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orders as $order): ?>
                    <tr>
                        <td>#<?php echo $order['id']; ?></td>
                        <td><?php echo $order['customer_name']; ?></td>
                        <td><?php echo substr($order['customer_address'], 0, 20); ?>...</td>
                        <td><?php echo date('j M Y', strtotime($order['order_date'])); ?></td>
                        <td>$<?php echo number_format($order['price'], 2); ?></td>
                        <td>
                            <span class="badge bg-<?php 
                                switch($order['status']) {
                                    case 'Processing': echo 'warning'; break;
                                    case 'Shipped': echo 'info'; break;
                                    case 'Delivered': echo 'success'; break;
                                    case 'Cancelled': echo 'danger'; break;
                                    default: echo 'secondary';
                                }
                            ?>">
                                <?php echo $order['status']; ?>
                            </span>
                        </td>
                        <td>
                            <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-primary">View</a>
                            <a href="order_edit.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>