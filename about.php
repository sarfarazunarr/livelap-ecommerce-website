<?php 

include 'config.php';
include './partials/Header.php'

?>

    <main class="container mx-auto px-6 py-8">
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-8">About Us</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Our Story</h2>
                <p class="text-gray-600 leading-relaxed">
                    Founded in 2010, our company has been at the forefront of innovation in our industry. We started with a simple idea and have grown into a team of passionate individuals dedicated to making a difference.
                </p>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Our Mission</h2>
                <p class="text-gray-600 leading-relaxed">
                    We strive to deliver exceptional products and services that improve the lives of our customers. Our mission is to innovate, inspire, and create positive change in the world through our work.
                </p>
            </div>
        </div>

        <div class="mt-12">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Our Team</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <img src="https://via.placeholder.com/150" alt="Team Member" class="rounded-full mx-auto mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">John Doe</h3>
                    <p class="text-gray-600">CEO & Founder</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <img src="https://via.placeholder.com/150" alt="Team Member" class="rounded-full mx-auto mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">Jane Smith</h3>
                    <p class="text-gray-600">CTO</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <img src="https://via.placeholder.com/150" alt="Team Member" class="rounded-full mx-auto mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">Mike Johnson</h3>
                    <p class="text-gray-600">Lead Developer</p>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-gray-800 text-white py-6 mt-12">
        <div class="container mx-auto px-6 text-center">
            <p>© 2023 Our Company. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
