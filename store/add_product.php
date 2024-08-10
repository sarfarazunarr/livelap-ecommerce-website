<?php
session_start();
require_once '../config.php';
require_once '../partials/Header.php';
require_once '../partials/notify.php';

// Check if the user is logged in and is a vendor
if (!isset($_SESSION['user_id']) || $_SESSION['account_type'] !== 'vendor') {
    header("Location: /livelap/auth/login.php");
    exit();
}

$vendor_id = $_SESSION['user_id'];

// Get store_id from URL
$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;

// Verify if the store belongs to the logged-in vendor
$stmt = $pdo->prepare("SELECT id FROM stores WHERE id = ? AND vendor_id = ?");
$stmt->execute([$store_id, $vendor_id]);
if ($stmt->rowCount() == 0) {
    // Store doesn't exist or doesn't belong to this vendor
    header("Location: ../dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process form submission
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $processor = $_POST['processor'];
    $ram = $_POST['ram'];
    $storage = $_POST['storage'];
    $display_size = $_POST['display_size'];
    $graphics_card = $_POST['graphics_card'];
    $operating_system = $_POST['operating_system'];
    $weight = $_POST['weight'];
    $dimensions = $_POST['dimensions'];
    $color = $_POST['color'];
    $battery_life = $_POST['battery_life'];
    $connectivity = $_POST['connectivity'];
    $camera = $_POST['camera'];
    $additional_features = $_POST['additional_features'];

    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["image"]["name"];
        $filetype = $_FILES["image"]["type"];
        $filesize = $_FILES["image"]["size"];

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");

        $maxsize = 5 * 1024 * 1024;
        if ($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");

        if (in_array($filetype, $allowed)) {
            $image = "../uploads/" . uniqid() . "." . $ext;
            move_uploaded_file($_FILES["image"]["tmp_name"], $image);
        } else {
            displayToast("Error: There was a problem uploading your file. Please try again.", 'error');
        }
    }

    // Insert product into database
    $stmt = $pdo->prepare("INSERT INTO products (store_id, vendor_id, name, image, description, price, stock_quantity, brand, model, processor, ram, storage, display_size, graphics_card, operating_system, weight, dimensions, color, battery_life, connectivity, camera, additional_features) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$store_id, $vendor_id, $name, $image, $description, $price, $stock_quantity, $brand, $model, $processor, $ram, $storage, $display_size, $graphics_card, $operating_system, $weight, $dimensions, $color, $battery_life, $connectivity, $camera, $additional_features]);

    if ($stmt->rowCount() > 0) {
        $success_message = "Product added successfully!";
        displayToast($success_message, 'success');
    } else {
        $error_message = "Error adding product. Please try again.";
        displayToast($error_message, 'error');
    }
}
?>

    <div class="container mx-auto w-2/4 mt-20">
        <h1 class="text-center text-3xl font-bold mb-8 merriweather">Add New Product</h1>

        <form action="add_product.php?store_id=<?php echo $store_id; ?>" method="post" enctype="multipart/form-data" class="space-y-8">
            <div class="space-y-6">
                <h2 class="text-2xl font-semibold">Basic Information</h2>
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="name" name="name" required>
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="description" name="description" rows="3" required></textarea>
                </div>
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                    <input type="number" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="price" name="price" step="0.01" required>
                </div>
                <div>
                    <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                    <input type="number" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="stock_quantity" name="stock_quantity" required>
                </div>
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Product Image</label>
                    <input type="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" id="image" name="image">
                </div>
            </div>

            <div class="space-y-6">
                <h2 class="text-2xl font-semibold">Technical Specifications</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="brand" class="block text-sm font-medium text-gray-700">Brand</label>
                        <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="brand" name="brand">
                    </div>
                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                        <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="model" name="model">
                    </div>
                    <div>
                        <label for="processor" class="block text-sm font-medium text-gray-700">Processor</label>
                        <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="processor" name="processor">
                    </div>
                    <div>
                        <label for="ram" class="block text-sm font-medium text-gray-700">RAM</label>
                        <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="ram" name="ram">
                    </div>
                    <div>
                        <label for="storage" class="block text-sm font-medium text-gray-700">Storage</label>
                        <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="storage" name="storage">
                    </div>
                    <div>
                        <label for="display_size" class="block text-sm font-medium text-gray-700">Display Size</label>
                        <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="display_size" name="display_size">
                    </div>
                    <div>
                        <label for="graphics_card" class="block text-sm font-medium text-gray-700">Graphics Card</label>
                        <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="graphics_card" name="graphics_card">
                    </div>
                    <div>
                        <label for="operating_system" class="block text-sm font-medium text-gray-700">Operating System</label>
                        <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="operating_system" name="operating_system">
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <h2 class="text-2xl font-semibold">Physical Characteristics</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="weight" class="block text-sm font-medium text-gray-700">Weight (kg)</label>
                        <input type="number" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="weight" name="weight" step="0.01">
                    </div>
                    <div>
                        <label for="dimensions" class="block text-sm font-medium text-gray-700">Dimensions</label>
                        <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="dimensions" name="dimensions">
                    </div>
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                        <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="color" name="color">
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <h2 class="text-2xl font-semibold">Additional Features</h2>
                <div>
                    <label for="battery_life" class="block text-sm font-medium text-gray-700">Battery Life</label>
                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="battery_life" name="battery_life">
                </div>
                <div>
                    <label for="connectivity" class="block text-sm font-medium text-gray-700">Connectivity</label>
                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="connectivity" name="connectivity">
                </div>
                <div>
                    <label for="camera" class="block text-sm font-medium text-gray-700">Camera</label>
                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="camera" name="camera">
                </div>
                <div>
                    <label for="additional_features" class="block text-sm font-medium text-gray-700">Additional Features</label>
                    <textarea class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="additional_features" name="additional_features" rows="3"></textarea>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Add Product</button>
            </div>
        </form>
    </div>

