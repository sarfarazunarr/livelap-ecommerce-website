<?php
session_start();
require_once 'config.php';
include './partials/Header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the latest order for the user
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC LIMIT 1");
$stmt->execute([$user_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header('Location: index.php');
    exit();
}

// Fetch order items
$stmt = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmt->execute([$order['id']]);
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="w-3/4 mx-auto p-5">
    <h1 class="text-3xl font-bold mb-4">Order Confirmation</h1>
    <h2 class="text-2xl font-semibold mb-6">Thank you for your order!</h2>
    
    <h3 class="text-xl font-semibold mb-2">Order Details</h3>
    <p class="mb-1">Order ID: <?php echo $order['id']; ?></p>
    <p class="mb-1">Date: <?php echo $order['created_at']; ?></p>
    <p class="mb-4">Total Amount: $<?php echo number_format($order['total_amount'], 2); ?></p>
    
    <h3 class="text-xl font-semibold mb-2">Shipping Address</h3>
    <p class="mb-4"><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
    
    <h3 class="text-xl font-semibold mb-2">Billing Address</h3>
    <p class="mb-4"><?php echo nl2br(htmlspecialchars($order['billing_address'])); ?></p>
    
    <h3 class="text-xl font-semibold mb-2">Order Items</h3>
    <table class="w-full mb-6">
        <thead>
            <tr class="bg-gray-100">
                <th class="py-2 px-4 text-left">Product</th>
                <th class="py-2 px-4 text-left">Quantity</th>
                <th class="py-2 px-4 text-left">Price</th>
                <th class="py-2 px-4 text-left">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($order_items as $item): ?>
                <tr class="border-b">
                    <td class="py-2 px-4"><?php echo htmlspecialchars($item['name']); ?></td>
                    <td class="py-2 px-4"><?php echo $item['quantity']; ?></td>
                    <td class="py-2 px-4">$<?php echo number_format($item['price'], 2); ?></td>
                    <td class="py-2 px-4">$<?php echo number_format($item['quantity'] * $item['price'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="flex justify-center items-start flex-col">
    <p class="mb-4">Thank you for shopping with us!</p>
    <p><a href="/livelap/" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Continue Shopping</a></p>
    </div>
</div>