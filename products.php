<?php
require 'config.php';
$title = 'Products - Livelap';
require './partials/Header.php';
require './partials/notify.php';
?>

<div class="wrapper">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-500 to-purple-600 text-white py-20">
        <div class="container mx-auto text-center">
            <h1 class="text-5xl font-bold mb-6">Discover Amazing Products</h1>
            <p class="text-xl mb-8">Find the perfect product that suits your needs</p>
            <div class="max-w-3xl mx-auto">
                <form action="" method="GET" class="flex">
                    <input type="text" name="search" placeholder="Search for products..." class="w-full px-4 py-3 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-300 text-gray-800">
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 px-6 py-3 rounded-r-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="bg-gray-100 py-8">
        <div class="container mx-auto">
            <form action="" method="GET" class="flex flex-wrap items-center justify-center gap-4">
                <div>
                    <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                    <select name="brand" id="brand" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">All Brands</option>
                        <?php
                        // Fetch brands from the database
                        $stmt = $pdo->query("SELECT DISTINCT brand FROM products");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . htmlspecialchars($row['brand']) . "'>" . htmlspecialchars($row['brand']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="price_range" class="block text-sm font-medium text-gray-700 mb-1">Price Range</label>
                    <select name="price_range" id="price_range" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">All Prices</option>
                        <option value="0-500">$0 - $500</option>
                        <option value="501-1000">$501 - $1000</option>
                        <option value="1001-2000">$1001 - $2000</option>
                        <option value="2001+">$2001+</option>
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md mt-4">Apply Filters</button>
            </form>
        </div>
    </section>

    <!-- Products Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Our Products</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                <?php
                // Build the query based on filters
                $query = "SELECT * FROM products WHERE 1=1";
                $params = [];

                if (isset($_GET['search']) && !empty($_GET['search'])) {
                    $query .= " AND (name LIKE ? OR description LIKE ?)";
                    $searchTerm = '%' . $_GET['search'] . '%';
                    $params[] = $searchTerm;
                    $params[] = $searchTerm;
                }

                if (isset($_GET['brand']) && !empty($_GET['brand'])) {
                    $query .= " AND brand = ?";
                    $params[] = $_GET['brand'];
                }

                if (isset($_GET['price_range']) && !empty($_GET['price_range'])) {
                    list($min, $max) = explode('-', $_GET['price_range']);
                    if ($max === '+') {
                        $query .= " AND price >= ?";
                        $params[] = $min;
                    } else {
                        $query .= " AND price BETWEEN ? AND ?";
                        $params[] = $min;
                        $params[] = $max;
                    }
                }

                $stmt = $pdo->prepare($query);
                $stmt->execute($params);
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($products as $product) {
                    echo '<div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <img src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-xl font-semibold mb-2">' . htmlspecialchars(substr($product['name'], 0, 50)) . '...</h3>
                            <p class="text-gray-600 mb-4">' . htmlspecialchars(substr($product['description'], 0, 100)) . '...</p>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-800 font-semibold">$' . number_format($product['price'], 2) . '</span>
                                <a href="/livelap/product.php?id=' . $product['id'] . '" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">View</a>
                            </div>
                        </div>
                    </div>';
                }

                if (empty($products)) {
                    echo '<p class="text-center col-span-full text-gray-500">No products found.</p>';
                }
                ?>
            </div>
        </div>
    </section>
</div>

<?php require './partials/footer.php'; ?>
