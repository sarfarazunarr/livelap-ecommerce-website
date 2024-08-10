<?php
require_once 'config.php';
require_once './packages/stripe-php/init.php';

// Only include Header.php when displaying the form
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    include './partials/Header.php';
}

// Set your Stripe secret key
\Stripe\Stripe::setApiKey('YOUR_KEY');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect order details
    session_start();
    $user_id = $_SESSION['user_id']; // Assuming user is logged in
    $shipping_address = $_POST['shipping_address'];
    $billing_address = $_POST['billing_address'];
    $payment_method = 'stripe';

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Insert order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, shipping_address, billing_address, payment_method) VALUES (?, 0, ?, ?, ?)");
        $stmt->execute([$user_id, $shipping_address, $billing_address, $payment_method]);
        $order_id = $pdo->lastInsertId();

        // Get cart items
        $stmt = $pdo->prepare("SELECT c.product_id, c.quantity, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
        $stmt->execute([$user_id]);
        $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total_amount = 0;

        // Insert order items and calculate total
        foreach ($cart_items as $item) {
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
            $total_amount += $item['quantity'] * $item['price'];
        }

        // Update order total
        $stmt = $pdo->prepare("UPDATE orders SET total_amount = ? WHERE id = ?");
        $stmt->execute([$total_amount, $order_id]);

        // Create Stripe Payment Intent
        $payment_intent = \Stripe\PaymentIntent::create([
            'amount' => $total_amount * 100, // Amount in cents
            'currency' => 'usd',
            'metadata' => ['order_id' => $order_id],
        ]);

        // Clear cart
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);

        $pdo->commit();

        // Return client secret for Stripe
        echo json_encode(['client_secret' => $payment_intent->client_secret]);
        exit; // Ensure no additional output is sent
    } catch (Exception $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
        exit; // Ensure no additional output is sent
    }
} else {
    $user_id = $_SESSION['user_id']; // Assuming user is logged in

    // Fetch cart items
    $stmt = $pdo->prepare("SELECT p.name, p.price, c.quantity FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total = array_sum(array_map(function ($item) {
        return $item['price'] * $item['quantity'];
    }, $cart_items));
    ?>

    <script src="https://js.stripe.com/v3/"></script>

    <body class="bg-gray-100 font-sans">
        <div class="container w-2/4 mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">Checkout</h1>
            <form id="payment-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h2 class="text-2xl font-semibold mb-4 text-gray-700">Order Summary</h2>
                <ul class="mb-4">
                    <?php foreach ($cart_items as $item): ?>
                        <li class="mb-2 text-gray-600"><?php echo htmlspecialchars($item['name']); ?> -
                            $<?php echo number_format($item['price'], 2); ?> x <?php echo htmlspecialchars($item['quantity']); ?></li>
                    <?php endforeach; ?>
                </ul>
                <p class="text-xl font-semibold mb-6 text-gray-800">Total: $<?php echo number_format($total, 2); ?></p>

                <h2 class="text-2xl font-semibold mb-4 text-gray-700">Shipping Information</h2>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="shipping_address">Shipping
                        Address:</label>
                    <textarea
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="shipping_address" name="shipping_address" required></textarea>
                </div>

                <h2 class="text-2xl font-semibold mb-4 text-gray-700">Billing Information</h2>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="billing_address">Billing Address:</label>
                    <textarea
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="billing_address" name="billing_address" required></textarea>
                </div>

                <div id="card-element" class="mb-4"></div>
                <div id="card-errors" role="alert" class="text-red-500 text-sm mb-4"></div>
                <p class="text-center py-5 font-semibold text-gray-900">We don't save your card details!</p>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">Pay
                    Now</button>
            </form>
        </div>

        <script>
            var stripe = Stripe('YOUR_KEY');
            var elements = stripe.elements();
            var card = elements.create('card', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#32325d',
                        '::placeholder': {
                            color: '#aab7c4'
                        }
                    },
                    invalid: {
                        color: '#fa755a',
                        iconColor: '#fa755a'
                    }
                }
            });
            card.mount('#card-element');

            var form = document.getElementById('payment-form');
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                fetch('place_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(new FormData(form))
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.client_secret) {
                            return stripe.confirmCardPayment(data.client_secret, {
                                payment_method: {
                                    card: card,
                                }
                            });
                        } else {
                            throw new Error(data.error || 'Unknown error');
                        }
                    })
                    .then(result => {
                        if (result.error) {
                            throw new Error(result.error.message);
                        }
                        // Redirect to the order confirmation page
                        window.location.href = 'order_confirmation.php';
                    })
                    .catch(error => {
                        console.error('Payment processing error:', error.message);
                        var errorElement = document.getElementById('card-errors');
                        errorElement.textContent = error.message;
                    });
            });
        </script>
    </body>
    <?php
}
?>
