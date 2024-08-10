<?php
require 'config.php';
$product_id = (int) $_GET['id'];

// Fetch product data from the database
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt2 = $pdo->prepare("SELECT * FROM stores WHERE id = ?");
$stmt2->execute([$product['store_id']]);
$store = $stmt2->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Product not found";
    exit;
}
$title = $product['name'];
require 'partials/Header.php';
require 'partials/notify.php';


?>

<div class="container mx-auto px-4 py-8">
    <div class="flex flex-wrap -mx-4">
        <div class="w-full md:w-1/2 px-4 mb-8">
            <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"
                class="w-full h-auto object-cover rounded-lg shadow-md">
        </div>
        <div class="w-full md:w-1/2 px-4 mb-8">
            <h1 class="text-3xl pt-28 font-bold mb-4 merriweather"><?php echo htmlspecialchars($product['name']); ?>
            </h1>
            <p class="text-2xl mb-4 luto">By: <?php echo $store['store_name']; ?></p>
            <p class="text-2xl mb-4 luto">Stock: <?php echo $product['stock_quantity']; ?></p>
            <p class="text-2xl luto mb-6">Price: $<?php echo number_format($product['price'], 2); ?></p>
            <div class="flex items-center mb-4">
                <label for="quantity" class="text-lg luto mr-2">Quantity:</label>
                <input type="number" id="quantity" name="quantity" min="1" value="1"
                    class="w-16 px-2 py-1 text-center border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button class="bg-blue-500 raleway hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mr-4"
                onclick="addToCart(<?php echo $product_id; ?>)">Add to Cart</button> <a
                href="https://wa.me/?text=Check%20out%20this%20product:%20<?php echo urlencode($product['name']); ?>"
                target="_blank"
                class="bg-green-500 hover:bg-green-600 text-white raleway font-bold py-2 px-4 rounded">Notify on
                WhatsApp</a>
        </div>
    </div>

    <div class="mb-12">
        <div class="border-b flex justify-center border-gray-200">
            <nav class="-mb-px flex" aria-label="Tabs">
                <button
                    class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 mx-3 font-medium text-sm merriweather"
                    id="description-tab" onclick="switchTab('description')">
                    Description
                </button>
                <button
                    class="border-indigo-500 text-indigo-600 whitespace-nowrap merriweather py-4 px-1 border-b-2 font-medium text-sm"
                    id="specifications-tab" onclick="switchTab('specifications')">
                    Specifications
                </button>
            </nav>
        </div>
        <div id="description-content" class="hidden w-3/4 mx-auto lato mt-4">
            <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
        </div>
        <div id="specifications-content" class="mt-4 w-3/4 mx-auto">
            <table class="w-full border-collapse border border-gray-300 lato">
                <tbody>
                    <?php foreach ($product as $key => $value): ?>
                        <?php if ($key !== 'id' && $key !== 'name' && $key !== 'price' && $key !== 'stock_quantity' && $key !== 'image' && $key !== 'store_id' && $key !== 'vendor_id' && $key !== 'description' && $key !== 'created_at' && $key !== 'updated_at'): ?>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2 font-semibold">
                                    <?php echo ucfirst(str_replace('_', ' ', $key)); ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($value); ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        function switchTab(tabName) {
            const tabs = ['description', 'specifications'];
            tabs.forEach(tab => {
                const tabElement = document.getElementById(`${tab}-tab`);
                const contentElement = document.getElementById(`${tab}-content`);
                if (tab === tabName) {
                    tabElement.classList.remove('border-transparent', 'text-gray-500');
                    tabElement.classList.add('border-indigo-500', 'text-indigo-600');
                    contentElement.classList.remove('hidden');
                } else {
                    tabElement.classList.add('border-transparent', 'text-gray-500');
                    tabElement.classList.remove('border-indigo-500', 'text-indigo-600');
                    contentElement.classList.add('hidden');
                }
            });
        }
        // Set description tab as active by default
        switchTab('description');
    </script>
    <div>
        <h2 class="text-2xl font-bold mb-4">Related Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id != ? ORDER BY RAND() LIMIT 4");            $stmt->execute([$product_id]);
            $related_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($related_products as $related_product):
                ?>
                <div class="border rounded-lg shadow-md p-4">
                    <img src="<?php echo htmlspecialchars($related_product['image']); ?>"
                        alt="<?php echo htmlspecialchars($related_product['name']); ?>"
                        class="w-full h-48 object-cover rounded-lg mb-4">
                    <h3 class="text-lg font-semibold mb-2"><?php echo htmlspecialchars($related_product['name']); ?></h3>
                    <p class="text-gray-600 mb-2">$<?php echo number_format($related_product['price'], 2); ?></p>
                    <a href="product.php?id=<?php echo $related_product['id']; ?>"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded inline-block">View
                        Product</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php include './partials/footer.php'; ?>
<script>

    // products.js
function addToCart(product_id) {
    const quantity = document.getElementById(`quantity`).value;
    fetch('cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=add_to_cart&product_id=${product_id}&quantity=${quantity}`,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart popup
            displayCartPopup();
        } else {
            alert('Error adding to cart');
        }
    });
}

    // Wait for GSAP and ScrollTrigger to load
    window.addEventListener('load', function () {
        gsap.registerPlugin(ScrollTrigger);

        // Animate product image
        gsap.from('.w-full.md\\:w-1\\/2.px-4.mb-8 img', {
            opacity: 0,
            y: 50,
            duration: 1,
            scrollTrigger: {
                trigger: '.w-full.md\\:w-1\\/2.px-4.mb-8 img',
                start: 'top 80%',
                end: 'bottom 20%',
                toggleActions: 'play none none reverse'
            }
        });

        // Animate product title
        gsap.from('.text-3xl.pt-28.font-bold.mb-4', {
            opacity: 0,
            x: -50,
            duration: 1,

        });

        // Animate product stock
        gsap.from('.text-xl.mb-4', {
            opacity: 0,
            x: 50,
            duration: 1,
        });

        // Animate product price
        gsap.from('.text-2xl.font-semibold.mb-6', {
            opacity: 0,
            x: 50,
            duration: 1,
        });

        // Animate buttons
        gsap.from('.bg-blue-500, .bg-green-500', {
            opacity: 0,
            y: 30,
            duration: 1,
            stagger: 0.2,
        });

        // Animate description and specifications tabs
        gsap.from('.border-b.flex.justify-center.border-gray-200', {
            opacity: 0,
            y: 30,
            duration: 1,
            scrollTrigger: {
                trigger: '.border-b.flex.justify-center.border-gray-200',
                start: 'top 80%',
                end: 'bottom 20%',
                toggleActions: 'play none none reverse'
            }
        });

        // Animate description and specifications content
        gsap.from('#description-content, #specifications-content', {
            opacity: 0,
            y: 30,
            duration: 1,
            stagger: 0.2,
            scrollTrigger: {
                trigger: '#description-content',
                start: 'top 80%',
                end: 'bottom 20%',
                toggleActions: 'play none none reverse'
            }
        });

        // Animate related products title
        gsap.from('.text-2xl.font-bold.mb-4', {
            opacity: 0,
            y: 30,
            duration: 1,
            scrollTrigger: {
                trigger: '.text-2xl.font-bold.mb-4',
                start: 'top 80%',
                end: 'bottom 20%',
                toggleActions: 'play none none reverse'
            }
        });

        // Animate related products
        gsap.from('.grid > .border.rounded-lg.shadow-md.p-4', {
            opacity: 0,
            y: 50,
            duration: 1,
            stagger: 0.2,
            scrollTrigger: {
                trigger: '.grid',
                start: 'top 80%',
                end: 'bottom 20%',
                toggleActions: 'play none none reverse'
            }
        });
    });

</script>