<?php
include 'config.php';
include './partials/Header.php';
?>

<!-- Her Section -->
<section id="hero">
    <div class="max-w-screen-xl mx-auto px-4 py-28 gap-12 text-gray-600 overflow-hidden md:px-8 md:flex">
        <div class="flex-none space-y-5 max-w-xl">
            <h1 class="text-4xl mt-5 text-gray-800 font-extrabold sm:text-5xl merriweather">
                Your Next Laptop, Your Next Adventure
            </h1>
            <p>
                Explore our vast collection of laptops from top brands, carefully curated to meet your unique needs and
                budget.
                Discover the perfect blend of performance, portability, and style to elevate your productivity and fun.
            </p>
            <div class="flex btns items-center gap-x-3 sm:text-sm">
                <a href="/livelap/products.php"
                    class="flex items-center justify-center gap-x-1 py-2 px-4 text-white font-medium bg-blue-800 duration-150 raleway hover:bg-blue-700 active:bg-blue-900 rounded-full md:inline-flex">
                    Browse Laptops
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                        <path fillRule="evenodd"
                            d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                            clipRule="evenodd" />
                    </svg>
                </a>
                <a href="/livelap/store/create.php"
                    class="flex items-center justify-center gap-x-1 py-2 px-4 text-gray-700 hover:text-gray-900 font-medium raleway duration-150 md:inline-flex">
                    Start Selling
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                        <path fillRule="evenodd"
                            d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                            clipRule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
        <div class="flex-1 hidden md:block">
            <img src="https://raw.githubusercontent.com/sidiDev/remote-assets/c86a7ae02ac188442548f510b5393c04140515d7/undraw_progressive_app_m-9-ms_oftfv5.svg"
                class="max-w-xl" />
        </div>
    </div>
</section>


<!-- Brand section -->
<section id="brands" class="py-16 bg-gray-100">
    <div class="max-w-screen-xl mx-auto px-4 py-4 md:px-8">
        <h2 class="text-4xl font-bold text-center text-gray-800 py-5 mb-8 merriweather">Choost Best Brands</h2>
        <div class="relative">
            <div class="overflow-hidden">
                <div class="flex transition-transform duration-500 ease-in-out transform translate-x-0"
                    id="brandCarousel">
                    <div class="flex-none w-1/3 px-4">
                        <img src="https://images.unsplash.com/photo-1651614423613-47ab0aaf3527" alt="Apple"
                            class="mx-auto h-40 rounded-md cursor-pointer object-contain">
                        <p class="text-center mt-2 text-gray-600 nunito">Apple</p>
                    </div>
                    <div class="flex-none w-1/3 px-4">
                        <img src="https://as2.ftcdn.net/v2/jpg/04/18/67/57/1000_F_418675719_ugbq30S6G8DT6DsDjadOWD7OuQifOOO9.jpg"
                            alt="Dell" class="mx-auto h-40 rounded-md cursor-pointer object-contain">
                        <p class="text-center mt-2 text-gray-600 nunito">Dell</p>
                    </div>
                    <div class="flex-none w-1/3 px-4">
                        <img src="https://www.slashgear.com/img/gallery/dear-hp-your-new-logo-is-amazing-and-you-should-use-it-everywhere/hplogonew.jpg"
                            alt="HP" class="mx-auto h-40 rounded-md cursor-pointer object-contain">
                        <p class="text-center mt-2 text-gray-600 nunito">HP</p>
                    </div>
                    <div class="flex-none w-1/3 px-4">
                        <img src="https://helios-i.mashable.com/imagery/articles/00aCU88GpMpllwwV0fPaKt3/hero-image.fill.size_1248x702.v1708008971.jpg"
                            alt="Lenovo" class="mx-auto h-40 rounded-md cursor-pointer object-contain">
                        <p class="text-center mt-2 text-gray-600 nunito">Lenovo</p>
                    </div>
                    <div class="flex-none w-1/3 px-4">
                        <img src="https://www.joyoshare.com/images/resource/how-to-screen-record-on-asus-laptop.jpg"
                            alt="Asus" class="mx-auto h-40 rounded-md cursor-pointer object-contain">
                        <p class="text-center mt-2 text-gray-600 nunito">Asus</p>
                    </div>
                    <div class="flex-none w-1/3 px-4">
                        <img src="https://filestore.community.support.microsoft.com/api/images/583421cc-1a33-46db-a5b9-cc18b9a4a7b7"
                            alt="Acer" class="mx-auto h-40 rounded-md cursor-pointer object-contain">
                        <p class="text-center mt-2 text-gray-600 nunito">Acer</p>
                    </div>
                </div>
            </div>
            <button class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white p-2 rounded-full shadow-md"
                onclick="moveCarousel(-1)">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white p-2 rounded-full shadow-md"
                onclick="moveCarousel(1)">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>
</section>


<!-- Top Laptops of the Month -->
<section id="top-products" class=" py-16 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4 py-4 md:px-8">
        <h2 class="text-4xl font-bold text-center text-gray-800 py-5 mb-8 merriweather">Top Laptops of the Month</h2>
        <div class="relative">
            <div class="overflow-hidden">
                <div class="flex transition-transform duration-500 ease-in-out transform translate-x-0"
                    id="laptopCarousel">
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM products ORDER BY RAND() LIMIT 8");
                    $stmt->execute();
                    $laptops = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($laptops as $laptop) {
                        echo '<div class="flex-none w-1/4 px-4 mb-8">
                            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                <img src="' . $laptop['image'] . '" alt="' . $laptop['name'] . '" class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h3 class="text-xl font-semibold mb-2">' . implode(' ', array_slice(explode(' ', $laptop['name']), 0, 5)) . '...</h3>
                                    <p class="text-gray-600 mb-4">' . implode(' ', array_slice(explode(' ', $laptop['description']), 0, 10)) . '...</p>                                    <div class="flex justify-between items-center">
                                    <span class="text-gray-800 font-semibold">$' . number_format($laptop['price'], 2) . '</span>
                                        <button class="buttons bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">View</button>
                                        <button onclick="addToCart(' . $laptop['id'] . ')"
                        class="bg-green-500 hover:bg-green-600 text-white raleway font-semibold py-2 px-4 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path
                                d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                        </svg>
                    </button>
                                    </div>
                                </div>
                            </div>
                        </div>';
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


<!-- Banner For Vendor -->
<div id="bevendor"
    class="relative overflow-hidden before:absolute before:top-0 before:start-1/2 before:bg-[url('https://preline.co/assets/svg/examples/squared-bg-element.svg')] before:bg-no-repeat before:bg-top before:size-full before:-z-[1] before:transform before:-translate-x-1/2">
    <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-10">

        <!-- Title -->
        <div class="mt-5 max-w-xl text-center mx-auto">
            <h1 class="block font-bold merriweather text-gray-800 text-4xl md:text-5xl lg:text-6xl">
                Be the Part of LiveLap!
            </h1>
        </div>
        <!-- End Title -->

        <div class="mt-5 max-w-3xl text-center mx-auto">
            <p class="text-lg text-gray-600 raleway">Have a business of selling and buying laptops? Create Store Now and
                start selling laptops online!</p>
        </div>

        <!-- Buttons -->
        <div class="mt-8 gap-3 flex raleway justify-center">
            <a class="inline-flex justify-center items-center gap-x-3 text-center bg-gradient-to-tl from-blue-600 to-violet-600 hover:from-violet-600 hover:to-blue-600 focus:outline-none focus:from-violet-600 focus:to-blue-600 border border-transparent text-white text-sm font-medium rounded-full py-3 px-4"
                href="/livelap/store/create.php">
                Create Store Now
            </a>
        </div>
        <!-- End Buttons -->
    </div>
</div>
<!-- End Hero -->




<?php
include './partials/footer.php';

?>
<script>
    let currentPosition = 0;
    const carousel = document.getElementById('brandCarousel');
    const itemWidth = carousel.children[0].offsetWidth;
    const itemCount = carousel.children.length;

    function moveCarousel(direction) {
        currentPosition += direction;
        if (currentPosition < 0) currentPosition = itemCount - 4;
        if (currentPosition > itemCount - 4) currentPosition = 0;
        carousel.style.transform = `translateX(-${currentPosition * itemWidth}px)`;
    }

    // Auto-scroll every 5 seconds
    setInterval(() => moveCarousel(1), 5000);


    let laptopCurrentPosition = 0;
    const laptopCarousel = document.getElementById('laptopCarousel');
    const laptopItemWidth = laptopCarousel.children[0].offsetWidth;
    const laptopItemCount = laptopCarousel.children.length;

    function moveLaptopCarousel(direction) {
        laptopCurrentPosition += direction;
        if (laptopCurrentPosition < 0) laptopCurrentPosition = laptopItemCount - 4;
        if (laptopCurrentPosition > laptopItemCount - 4) laptopCurrentPosition = 0;
        laptopCarousel.style.transform = `translateX(-${laptopCurrentPosition * laptopItemWidth}px)`;
    }

    // Auto-scroll every 5 seconds
    setInterval(() => moveLaptopCarousel(1), 5000);

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

</script>