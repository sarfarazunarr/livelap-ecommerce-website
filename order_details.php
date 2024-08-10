<?php
require_once 'config.php';
require_once './partials/Header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /livelap/auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /livelap/dashboard.php");
    exit();
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch order details
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// if (!$order || $order['user_id'] != $user_id) {
//     header("Location: /livelap/dashboard");
//     exit();
// }

// Fetch order items and check if user is the vendor
$stmt = $pdo->prepare("SELECT oi.*, p.name, p.image, p.vendor_id FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$is_vendor = false;
foreach ($order_items as $item) {
    if ($item['vendor_id'] == $user_id) {
        $is_vendor = true;
        break;
    }
}

if (!$is_vendor && $order['user_id'] != $user_id) {
    header("Location: /livelap/dashboard");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Order Details</h1>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4">Order #<?php echo $order['id']; ?></h2>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-gray-600">Status: <span class="font-medium text-gray-800"><?php echo ucfirst($order['status']); ?></span></p>
                        <p class="text-gray-600">Date: <span class="font-medium text-gray-800"><?php echo date('F j, Y', strtotime($order['created_at'])); ?></span></p>
                    </div>
                    <div>
                        <p class="text-gray-600">Total Amount: <span class="font-medium text-gray-800">$<?php echo number_format($order['total_amount'], 2); ?></span></p>
                        <p class="text-gray-600">Payment Method: <span class="font-medium text-gray-800"><?php echo $order['payment_method']; ?></span></p>
                    </div>
                </div>
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Shipping Address</h3>
                    <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                </div>
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Billing Address</h3>
                    <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($order['billing_address'])); ?></p>
                </div>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($item['name']); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?php echo $item['quantity']; ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">$<?php echo number_format($item['price'], 2); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">$<?php echo number_format($item['quantity'] * $item['price'], 2); ?></div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-4 whitespace-nowrap text-right font-medium">Total:</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">$<?php echo number_format($order['total_amount'], 2); ?></div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="mt-8">
            <a href="javascript:history.back()" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Back to Orders</a>        </div>
    </div>
</body>
</html>
