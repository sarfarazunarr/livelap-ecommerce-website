<?php
session_start();
require_once 'config.php';
include './partials/Header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items
$stmt = $pdo->prepare("SELECT c.id, c.quantity, p.name, p.price, p.image FROM cart c 
                       JOIN products p ON c.product_id = p.id 
                       WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle update and delete actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        $cart_id = $_POST['cart_id'];
        $quantity = $_POST['quantity'];
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$quantity, $cart_id, $user_id]);
    } elseif (isset($_POST['delete'])) {
        $cart_id = $_POST['cart_id'];
        $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
        $stmt->execute([$cart_id, $user_id]);
    }
    header('Location: cart_data.php');
    exit();
}

$total = 0;
?>


    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 merriweather">Your Shopping Cart</h1>
        <?php if (empty($cart_items)): ?>
            <p class="text-xl text-center nunito py-10">Your cart is empty.</p>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <?php foreach ($cart_items as $item): ?>
                    <?php $total += $item['price'] * $item['quantity']; ?>
                    <div class="flex items-center justify-between border-b py-4">
                        <div class="flex items-center">
                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-20 h-20 object-cover rounded mr-4">
                            <div>
                                <h2 class="text-lg font-bold"><?= htmlspecialchars(substr($item['name'], 0, 50)) . '...' ?></h2>                                
                                <p class="text-gray-600">$<?= number_format($item['price'], 2) ?></p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <form method="POST" class="mr-4">
                                <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                                <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="w-16 text-center border rounded p-2">
                                <button type="submit" name="update" class="bg-blue-500 text-white px-2 py-1 rounded ml-2">Update</button>
                            </form>
                            <form method="POST">
                                <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                                <button type="submit" name="delete" class="text-red-500">Remove</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="flex justify-between items-center mt-8">
                    <h3 class="text-xl font-bold">Total: $<?= number_format($total, 2) ?></h3>
                    <a href="place_order.php" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Place Order</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
