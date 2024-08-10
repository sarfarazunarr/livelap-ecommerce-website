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

  $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
  $stmt->execute([$user_id]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_account'])) {
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $new_password = $_POST['new_password'];

    // Validate input (you may want to add more validation)
    if (empty($username) || empty($phone_number) || empty($address)) {
      $error = "All fields are required.";
    } else {
      // Hash the new password
      $ps = empty($password) ? $user['password'] : $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

      // Update user data in the database
      $update_stmt = $pdo->prepare("UPDATE users SET username = ?, phone_number = ?, address = ?, password = ? WHERE id = ?");
      $result = $update_stmt->execute([$username, $phone_number, $address, $ps, $user_id]);

      if ($result) {
        // Refresh user data after update
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $success = "Account updated successfully.";
        displayToast($success, 'success');
      } else {
        $error = "Failed to update account. Please try again.";
        displayToast($error, 'error');
      }
    }
  }




  ?>
  <div class="bg-white w-full p-20">
    <div class="profile-info mb-8 lato">
      <h2 class="text-4xl font-bold mb-4 merriweather text-gray-800"><?php echo htmlspecialchars($user['username']); ?></h2>
      <p class="text-lg text-gray-600"><span class="font-semibold">Email:</span> <?php echo htmlspecialchars($user['email']); ?></p>
      <p class="text-lg text-gray-600"><span class="font-semibold">Phone Number:</span> <?php echo htmlspecialchars($user['phone_number']); ?></p>
      <p class="text-lg text-gray-600"><span class="font-semibold">Address:</span> <?php echo htmlspecialchars($user['address']); ?></p>
    </div>
    <div class="flex flex-wrap gap-4 mb-8 raleway">
      <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-300" onclick="document.getElementById('updatePopup').style.display='block'">
        Update Account
      </button>
      <button class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-300" onclick="window.location.href='/livelap/auth/delete_account.php'">
        Delete Account
      </button>
      <a class="inline-flex justify-center items-center gap-x-3 text-center bg-gradient-to-tl from-blue-600 to-violet-600 hover:from-violet-600 hover:to-blue-600 focus:outline-none focus:from-violet-600 focus:to-blue-600 border border-transparent text-white text-sm font-medium rounded-full py-3 px-4" href="/livelap/store/create.php">
        Create Store Now
      </a>
    </div>

    <div id="updatePopup" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
          <h3 class="text-lg leading-6 font-medium text-gray-900">Update Account</h3>
          <div class="mt-2 px-7 py-3">
            <form method="POST" action="">
              <div class="mb-4">
                <input type="text" name="username" placeholder="Enter username" value="<?php echo htmlspecialchars($user['username']); ?>" required class="w-full px-3 py-2 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-100 focus:border-indigo-300">
              </div>
              <div class="mb-4">
                <input type="tel" name="phone_number" placeholder="Enter phone number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required class="w-full px-3 py-2 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-100 focus:border-indigo-300">
              </div>
              <div class="mb-4">
                <input type="text" name="address" placeholder="Enter address" value="<?php echo htmlspecialchars($user['address']); ?>" required class="w-full px-3 py-2 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-100 focus:border-indigo-300">
              </div>
              <div class="mb-4">
                <input type="password" name="new_password" placeholder="Update Password" class="w-full px-3 py-2 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-100 focus:border-indigo-300">
              </div>
              <div class="items-center px-4 py-3">
                <button type="submit" name="update_account" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                  Update Details
                </button>
              </div>
            </form>
          </div>
        </div>
        <div class="absolute top-0 right-0 mt-4 mr-4">
          <button onclick="document.getElementById('updatePopup').style.display='none'" class="text-gray-400 hover:text-gray-600 transition duration-150">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Section For Orders -->
  <div class="mt-8 p-10">
    <h2 class="text-2xl font-bold mb-4 merriweather">Orders</h2>
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white nunito">
        <thead class="bg-gray-100">
          <tr>
            <th class="py-2 px-4 border-b">Order ID</th>
            <th class="py-2 px-4 border-b">Number of Products</th>
            <th class="py-2 px-4 border-b">Amount</th>
            <th class="py-2 px-4 border-b">Payment Method</th>
            <th class="py-2 px-4 border-b">Status</th>
            <th class="py-2 px-4 border-b">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $stmt = $pdo->prepare("SELECT o.*, COUNT(oi.id) as product_count 
                                 FROM orders o 
                                 LEFT JOIN order_items oi ON o.id = oi.order_id 
                                 WHERE o.user_id = :user_id 
                                 GROUP BY o.id");
          $stmt->execute(['user_id' => $user['id']]);
          $orders = $stmt->fetchAll();

          foreach ($orders as $order):
          ?>
            <tr>
              <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($order['id']); ?></td>
              <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($order['product_count']); ?></td>
              <td class="py-2 px-4 border-b">$<?php echo htmlspecialchars($order['total_amount']); ?></td>
              <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($order['payment_method']); ?></td>
              <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($order['status']); ?></td>
              <td class="py-2 px-4 border-b">
                <a href="/livelap/order_details.php?id='<?php $order['id']?>'" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-2 rounded transition duration-300">
                  View
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div id="orderDetailsPopup" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-3/4 shadow-lg rounded-md bg-white">
      <div class="mt-3">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Order Details</h3>
        <div id="orderDetailsContent"></div>
      </div>
      <div class="absolute top-0 right-0 mt-4 mr-4">
        <button onclick="document.getElementById('orderDetailsPopup').style.display='none'" class="text-gray-400 hover:text-gray-600 transition duration-150">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
    </div>
  </div>

  <script>
    function showOrderDetails(orderId) {
      fetch(`get_order_details.php?order_id=${orderId}`)
        .then(response => response.text())
        .then(data => {
          document.getElementById('orderDetailsContent').innerHTML = data;
          document.getElementById('orderDetailsPopup').style.display = 'block';
        });
    }
  </script>

<script>
  var popup = document.getElementById('updatePopup');;
  window.onclick = function (event) {
    if (event.target == popup) {
      popup.style.display = "none";
    }
  }
</script>
</body>

</html>
</head>