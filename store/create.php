<?php
include '../config.php';
$title = "Create Store - LiveLap";
include '../partials/Header.php';
include '../partials/notify.php';
$userId = $_SESSION['user_id'];
if($_SESSION['account_type'] === 'vendor') {
    header('Location: /livelap/store/');
    exit;
}
?>
<div class="container w-2/4 mx-auto mt-20 px-4">
    <h1 class="text-4xl font-bold merriweather text-center mb-4">Create Store</h1>
    <p class="text-lg nunito text-center mb-8">Create your own store and start selling your products on LiveLap. Fill in the details below to get started.</p>

    <form action="" method="POST" class="lato font-semibold">
        <div class="mb-6">
            <label for="store_name" class="block text-sm font-medium text-gray-700 mb-2">Store Name</label>
            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" id="store_name" name="store_name" required>
        </div>
        <div class="mb-6">
            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" id="address" name="address" rows="3" required></textarea>
        </div>
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" id="description" name="description" rows="5" required></textarea>
        </div>
        <div class="mb-6">
            <label for="business_phone" class="block text-sm font-medium text-gray-700 mb-2">Business Phone</label>
            <input type="tel" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" id="business_phone" name="business_phone" required>
        </div>
        <div class="mb-6">
            <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Website/Facebook Page</label>
            <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" id="website" name="website">
        </div>
        <button type="submit" class="bg-blue-800 hover:bg-black text-white raleway py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create Store</button>
    </form>
</div>

<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $store_name = $_POST['store_name'];
    $address = $_POST['address'];
    $description = $_POST['description'];
    $business_phone = $_POST['business_phone'];
    $website = $_POST['website'];
    $userId = $_SESSION['user_id'];
    try {
        // Insert store details
        $stmt = $pdo->prepare("INSERT INTO stores (store_name, address, description, business_phone, website, vendor_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$store_name, $address, $description, $business_phone, $website, $userId]);
        

        // Update user's account type
        $stmt = $pdo->prepare("UPDATE users SET account_type = 'vendor' WHERE id = ?");
        $stmt->execute([$userId]);


        displayToast("Store Created Successfully!", "success");
        header("Location: /livelap/store");
        exit();
    } catch (\PDOException $e) {
        echo "Error creating store: " . $e->getMessage();
        displayToast("error", "Error creating store: " . $e->getMessage());
    }
}

include '../partials/Footer.php';
?>
