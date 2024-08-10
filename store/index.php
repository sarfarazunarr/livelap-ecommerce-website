<?php
require '../config.php';
$title = 'Dashboard';
require '../partials/Header.php';
require '../partials/notify.php';
?>
<div class="wrapper">
    <?php

    if (!isset($_SESSION['user_id'])) {
        header("Location: /livelap/auth/login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    if (($_SESSION['account_type'] !== 'vendor')) {
        header("Location: /livelap/dashboard");
        exit();
    }
    $stmt = $pdo->prepare("SELECT * FROM stores WHERE vendor_id = ?");
    $stmt->execute([$user_id]);
    $store = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_store'])) {
        $store_name = $_POST['store_name'];
        $address = $_POST['address'];
        $description = $_POST['description'];
        $business_phone = $_POST['business_phone'];
        $website = $_POST['website'];

        $update_stmt = $pdo->prepare("UPDATE stores SET store_name = ?, address = ?, description = ?, business_phone = ?, website = ? WHERE id = ?");
        $result = $update_stmt->execute([$store_name, $address, $description, $business_phone, $website, $store['id']]);

        if ($result) {
            // Refresh user data after update
            $stmt->execute([$user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $success = "Store updated successfully.";
            displayToast($success, 'success');
        } else {
            $error = "Failed to update store. Please try again.";
            displayToast($error, 'error');
        }
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order'])) {
        $order_id = $_POST['order_id'];
        $new_status = $_POST['new_status'];

        // Verify that the order belongs to this store
        $check_stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
        $check_stmt->execute([$order_id]);
        $order = $check_stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            $update_stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $result = $update_stmt->execute([$new_status, $order_id]);

            if ($result) {
                $success = "Order status updated successfully.";
                displayToast($success, 'success');
            } else {
                $error = "Failed to update order status. Please try again.";
                displayToast($error, 'error');
            }
        } else {
            $error = "Invalid order or you don't have permission to update this order.";
            displayToast($error, 'error');
        }
    }





    ?>
    <div class="bg-white w-full p-20">
        <div class="profile-info mb-8 lato">
            <h2 class="text-4xl font-bold mb-4 merriweather text-gray-800">
                <?php echo htmlspecialchars($store['store_name']); ?>
            </h2>
            <p class=" text-gray-600"><span class="font-semibold">Phone Number:</span>
                <?php echo htmlspecialchars($store['business_phone']); ?></p>
            <p class=" text-gray-600"><span class="font-semibold">Address:</span>
                <?php echo htmlspecialchars($store['address']); ?></p>
            <p class=" text-gray-600 w-3/4"><?php echo htmlspecialchars($store['description']); ?></p>
        </div>
        <div class="flex flex-wrap gap-4 raleway">
            <button
                class="inline-flex justify-center items-center gap-x-3 text-center bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 border border-transparent text-white text-sm font-medium rounded-md py-3 px-4" onclick="document.getElementById('updatePopup').style.display='block'">
                Update Store
            </button>
            <a class="inline-flex justify-center items-center gap-x-3 text-center bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 border border-transparent text-white text-sm font-medium rounded-md py-3 px-4"
                href="/livelap/dashboard/">
                Move to User Dashboard
            </a>
        </div>

        <div id="updatePopup" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-2/4 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Update Store</h3>
                    <div class="mt-2 px-7 py-3">
                        <form action="" method="POST" class="lato font-semibold">
                            <div class="mb-6">
                                <label for="store_name"
                                    class="block text-left text-sm font-medium text-gray-700 mb-2">Store
                                    Name</label>
                                <input type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    id="store_name" value="<?php echo htmlspecialchars($store['store_name']); ?>"
                                    name="store_name" required>
                            </div>
                            <div class="mb-6">
                                <label for="address"
                                    class="block text-left text-sm font-medium text-gray-700 mb-2">Address</label>
                                <textarea
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    id="address" name="address" rows="3"
                                    value="<?php echo htmlspecialchars($store['address']); ?>"
                                    required><?php echo htmlspecialchars($store['address']); ?></textarea>
                            </div>
                            <div class="mb-6">
                                <label for="description"
                                    class="block text-left text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    id="description" name="description" rows="5"
                                    value="<?php echo htmlspecialchars($store['description']); ?>"
                                    required><?php echo htmlspecialchars($store['description']); ?></textarea>
                            </div>
                            <div class="mb-6">
                                <label for="business_phone"
                                    class="block text-left text-sm font-medium text-gray-700 mb-2">Business
                                    Phone</label>
                                <input type="tel"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    id="business_phone" name="business_phone"
                                    value="<?php echo htmlspecialchars($store['business_phone']); ?>" required>
                            </div>
                            <div class="mb-6">
                                <label for="website"
                                    class="block text-left text-sm font-medium text-gray-700 mb-2">Website/Facebook
                                    Page</label>
                                <input type="url"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    id="website" name="website"
                                    value="<?php echo htmlspecialchars($store['website']); ?>">
                            </div>
                            <button type="submit" name="update_store"
                                class="bg-blue-800 hover:bg-black text-white raleway py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update
                                Store</button>
                        </form>
                    </div>
                    <div class="absolute top-0 right-0 mt-4 mr-4">
                        <button onclick="document.getElementById('updatePopup').style.display='none'"
                            class="text-gray-400 hover:text-gray-600 transition duration-150">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-center gap-3 mt-8">
        <button id="productTab"
            class="bg-blue-500 p-5 hover:bg-black text-white hover:text-white raleway py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            onclick="switchTabs('products')">
            Products
        </button>
        <button id="orderTab"
            class="bg-gray-400, text-gray-800 p-5 hover:bg-black  raleway py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-4"
            onclick="switchTabs('orders')">
            Orders
        </button>
    </div>

    <section id="products" class=" py-16 bg-gray-50">
        <div class="max-w-screen-xl mx-auto px-4 py-4 md:px-8">
            <h2 class="text-4xl font-bold text-center text-gray-800 py-5 mb-8 merriweather">Products</h2>
            <div class="relative">
                <div class="overflow-hidden">
                    <?php
                    $store_id = $store['id'];
                    $stmtlaptop = $pdo->prepare("SELECT * FROM products WHERE store_id = :store_id");
                    $stmtlaptop->execute(['store_id' => $store_id]);
                    $laptops = $stmtlaptop->fetchAll(PDO::FETCH_ASSOC);
                    if (count($laptops) == 0) {
                        echo '<div class="text-center flex justify-between items-center py-8">
                                <p class="text-xl font-semibold mb-4 merriweather">No products available</p>
                                <a href="/livelap/store/add_product.php?store_id=' . $store_id . '" class="buttons bg-blue-500 text-white px-6 py-3 rounded-md hover:bg-blue-600 transition duration-300">Add Product</a>
                            </div>';
                    } else {
                        ?>
                        <div class="flex transition-transform duration-500 ease-in-out transform translate-x-0"
                            id="laptopCarousel">
                            <?php
                            foreach ($laptops as $laptop) {
                                echo '<div class="flex-none w-1/4 px-4 mb-8">
                                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                    <img src="' . $laptop['image'] . '" alt="' . $laptop['name'] . '" class="w-full h-48 object-cover">
                                    <div class="p-4">
                                        <h3 class="text-xl font-semibold mb-2">' . implode(' ', array_slice(explode(' ', $laptop['name']), 0, 10)) . '...</h3>
                                        <p class="text-gray-600 mb-4">' . implode(' ', array_slice(explode(' ', $laptop['description']), 0, 20)) . '...</p>                                        
                                        <div class="flex justify-between items-center">
                                        <span class="text-gray-800 font-semibold">Rs.' . $laptop['price'] . '</span>
                                            <a href="/livelap/product.php?id=' . $laptop['id'] . '" class="buttons bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">View</a>
                                            <a href="/livelap/store/update_product.php?product_id=' . $laptop['id'] . '" class="buttons bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">Update</a>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>';
                            }
                    }
                    ?>
                    </div>
                </div>
                <button class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white p-2 rounded-full shadow-md"
                    onclick="moveLaptopCarousel(-1)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white p-2 rounded-full shadow-md"
                    onclick="moveLaptopCarousel(1)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </section>

    <section id="orders" class="py-12 hidden bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-semibold mb-8 merriweather">Orders</h2>
            <table class="w-full bg-white rounded-lg shadow-md">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-3 text-left">Order #</th>
                        <th class="p-3 text-left">Total</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Payment Method</th>
                        <th class="p-3 text-left">Number of Products</th>
                        <th class="p-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch orders from the database where product's vendor_id matches the user_id
                    $stmt = $pdo->prepare("
                    SELECT o.*, COUNT(DISTINCT oi.product_id) as product_count 
                    FROM orders o 
                    JOIN order_items oi ON o.id = oi.order_id 
                    JOIN products p ON oi.product_id = p.id 
                    WHERE p.vendor_id = ? 
                    GROUP BY o.id
                ");
                    $stmt->execute([$_SESSION['user_id']]);
                    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($orders as $order) {
                        echo '<tr class="border-b">
                        <td class="p-3">Order #' . $order['id'] . '</td>
                        <td class="p-3">Rs.' . $order['total_amount'] . '</td>
                        <td class="p-3">' . ucfirst($order['status']) . '</td>
                        <td class="p-3">' . ucfirst($order['payment_method']) . '</td>
                        <td class="p-3">' . $order['product_count'] . '</td>
                        <td class="p-3">
                            <a href="/livelap/order_details.php?id=' . $order['id'] . '" class="buttons bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300 mr-2">View</a>
                            <button onclick="openUpdatePopup(' . $order['id'] . ', \'' . $order['status'] . '\')" class="buttons bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition duration-300">Update</button>
                        </td>
                    </tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Update Status Popup -->
    <div id="updateOrderPopup" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-semibold mb-4">Update Order Status</h3>
            <form id="updateStatusForm" method="post">
                <input type="hidden" id="order_id" name="order_id">
                <select id="new_status" name="new_status" class="w-full p-2 mb-4 border rounded">
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <button type="submit" name="update_order"
                    class="buttons bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">Update
                    Status</button>
            </form>
        </div>
    </div>

    <script>

        function switchTabs(tabName) {
            const productTab = document.getElementById('productTab');
            const orderTab = document.getElementById('orderTab');
            const productContent = document.getElementById('products');
            const orderContent = document.getElementById('orders');

            if (tabName === 'products') {
                productTab.classList.add('bg-blue-500', 'text-white');
                productTab.classList.remove('bg-gray-400', 'text-gray-800')
                orderTab.classList.add('bg-gray-400', 'text-gray-800');
                orderTab.classList.remove('bg-blue-500', 'text-white');
                productContent.classList.remove('hidden');
                orderContent.classList.add('hidden');
            } else if (tabName === 'orders') {
                productTab.classList.add('bg-gray-400', 'text-gray-800');
                productTab.classList.remove('bg-blue-500', 'text-white')
                orderTab.classList.add('bg-blue-500', 'text-white');
                orderTab.classList.remove('bg-gray-400', 'text-gray-800');
                orderContent.classList.remove('hidden');
                productContent.classList.add('hidden');
            }
        }
        function openUpdatePopup(orderId, currentStatus) {
            document.getElementById('order_id').value = orderId;
            document.getElementById('new_status').value = currentStatus;
            document.getElementById('updateOrderPopup').style.display = 'block';
        }

        var popup = document.getElementById('updatePopup');
        let popup2 = document.getElementById('updateOrderPopup');
        window.onclick = function (event) {
            if (event.target == popup) {
                popup.style.display = "none";
            }
            if (event.target == popup2) {
                popup2.style.display = "none";
            }
        }
    </script>
    </body>

    </html>
    </head>