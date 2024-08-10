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

// Get product_id from URL
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

// Verify if the product belongs to the logged-in vendor
$stmt = $pdo->prepare("SELECT p.*, s.id AS store_id FROM products p JOIN stores s ON p.store_id = s.id WHERE p.id = ? AND s.vendor_id = ?");
$stmt->execute([$product_id, $vendor_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    // Product doesn't exist or doesn't belong to this vendor
    header("Location: ../dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'delete') {
        // Delete product
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$product_id]);

        if ($stmt->rowCount() > 0) {
            displayToast("Product deleted successfully!", 'success');
            header("Location: ../dashboard.php");
            exit();
        } else {
            displayToast("Error deleting product. Please try again.", 'error');
        }
    } else {
        // Update product
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
        $image = $product['image'];
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

        // Update product in database
        $stmt = $pdo->prepare("UPDATE products SET name = ?, image = ?, description = ?, price = ?, stock_quantity = ?, brand = ?, model = ?, processor = ?, ram = ?, storage = ?, display_size = ?, graphics_card = ?, operating_system = ?, weight = ?, dimensions = ?, color = ?, battery_life = ?, connectivity = ?, camera = ?, additional_features = ? WHERE id = ?");
        $stmt->execute([$name, $image, $description, $price, $stock_quantity, $brand, $model, $processor, $ram, $storage, $display_size, $graphics_card, $operating_system, $weight, $dimensions, $color, $battery_life, $connectivity, $camera, $additional_features, $product_id]);

        if ($stmt->rowCount() > 0) {
            displayToast("Product updated successfully!", 'success');
        } else {
            displayToast("No changes were made or error updating product. Please try again.", 'error');
        }
    }
}
?>

<div class="container mx-auto my-20 w-2/4">
    <h1 class="text-center text-3xl font-bold mb-8 merriweather">Update Product</h1>

    <form action="update_product.php?product_id=<?php echo $product_id; ?>" method="post" enctype="multipart/form-data" class="space-y-8">
        <div class="space-y-6">
            <h2 class="text-2xl font-semibold">Basic Information</h2>
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="description" name="description" rows="3" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                <input type="number" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>
            <div>
                <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                <input type="number" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="stock_quantity" name="stock_quantity" value="<?php echo htmlspecialchars($product['stock_quantity']); ?>" required>
            </div>
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Product Image</label>
                <input type="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" id="image" name="image">
                <?php if ($product['image']): ?>
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Current product image" class="mt-2 h-32 w-32 object-cover">
                <?php endif; ?>
            </div>
        </div>

        <div class="space-y-6">
            <h2 class="text-2xl font-semibold">Technical Specifications</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="brand" class="block text-sm font-medium text-gray-700">Brand</label>
                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="brand" name="brand" value="<?php echo htmlspecialchars($product['brand']); ?>">
                </div>
                <div>
                    <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="model" name="model" value="<?php echo htmlspecialchars($product['model']); ?>">
                </div>
                <div>
                    <label for="processor" class="block text-sm font-medium text-gray-700">Processor</label>
                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="processor" name="processor" value="<?php echo htmlspecialchars($product['processor']); ?>">
                </div>
                <div>
                    <label for="ram" class="block text-sm font-medium text-gray-700">RAM</label>
                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="ram" name="ram" value="<?php echo htmlspecialchars($product['ram']); ?>">
                </div>
                <div>
                    <label for="storage" class="block text-sm font-medium text-gray-700">Storage</label>
                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="storage" name="storage" value="<?php echo htmlspecialchars($product['storage']); ?>">
                </div>
                <div>
                    <label for="display_size" class="block text-sm font-medium text-gray-700">Display Size</label>
                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="display_size" name="display_size" value="<?php echo htmlspecialchars($product['display_size']); ?>">
                </div>
                <div>
                    <label for="graphics_card" class="block text-sm font-medium text-gray-700">Graphics Card</label>
                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="graphics_card" name="graphics_card" value="<?php echo htmlspecialchars($product['graphics_card']); ?>">
                </div>
                <div>
                    <label for="operating_system" class="block text-sm font-medium text-gray-700">Operating System</label>
                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="operating_system" name="operating_system" value="<?php echo htmlspecialchars($product['operating_system']); ?>">
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <h2 class="text-2xl font-semibold">Physical Characteristics</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="weight" class="block text-sm font-medium text-gray-700">Weight (kg)</label>
                    <input type="number" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="weight" name="weight" step="0.01" value="<?php echo htmlspecialchars($product['weight']); ?>">
                </div>
                <div>
                    <label for="dimensions" class="block text-sm font-medium text-gray-700">Dimensions</label>
                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="dimensions" name="dimensions" value="<?php echo htmlspecialchars($product['dimensions']); ?>">
                </div>
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="color" name="color" value="<?php echo htmlspecialchars($product['color']); ?>">
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <h2 class="text-2xl font-semibold">Additional Features</h2>
            <div>
                <label for="battery_life" class="block text-sm font-medium text-gray-700">Battery Life</label>
                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="battery_life" name="battery_life" value="<?php echo htmlspecialchars($product['battery_life']); ?>">
            </div>
            <div>
                <label for="connectivity" class="block text-sm font-medium text-gray-700">Connectivity</label>
                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="connectivity" name="connectivity" value="<?php echo htmlspecialchars($product['connectivity']); ?>">
            </div>
            <div>
                <label for="camera" class="block text-sm font-medium text-gray-700">Camera</label>
                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="camera" name="camera" value="<?php echo htmlspecialchars($product['camera']); ?>">
            </div>
            <div>
                <label for="additional_features" class="block text-sm font-medium text-gray-700">Additional Features</label>
                <textarea class="mt-1 block w-full rounded-md border-gray-300 p-2 nunito border outline-none shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="additional_features" name="additional_features" rows="3" required><?php echo htmlspecialchars($product['additional_features']); ?></textarea>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Update Product
            </button>
        </div>
    </form>
</div>
