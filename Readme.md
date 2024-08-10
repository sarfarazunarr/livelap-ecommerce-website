# LiveLap E-commerce Website

LiveLap is a complete e-commerce website with a wide range of features and functionalities to provide a seamless shopping experience for users and efficient management for store owners.

## Features and Functionalities

### User State Management
- User Registration
- User Login
- Account Verification
- Password Reset
- User Data Fetching

### Store Management
- Create Store
- Update Store Information

### Product Management
- Create Products
- Update Product Details
- View Product Listings

### Order Management
- Create Orders
- Update Order Status
- View Order History

### Shopping Experience
- Shopping Cart Functionality
- Checkout Process
- Stripe Payment Integration

## Getting Started

To set up and run the project locally, follow these steps:

1. Create a MySQL database named `livelap`.

2. Run the `setup.php` file to create all necessary tables for the website.

3. Open the `sendEmail.php` file and add your email details for email functionality:
   
   $mail->Host = 'your_smtp_host';
   $mail->SMTPAuth = true;
   $mail->Username = 'your_email@example.com';
   $mail->Password = 'your_email_password';
   $mail->SMTPSecure = 'tls';
   $mail->Port = 587;
   

4. Open the `place_order.php` file and add your Stripe API key for payment functionality:
   
   \Stripe\Stripe::setApiKey('your_stripe_secret_key');
   

5. Ensure you have PHP and MySQL installed on your local machine.

6. Install the required dependencies:
   - PHPMailer: `composer require phpmailer/phpmailer`
   - Toastify: Include the CSS and JS files in your HTML
   - Tailwind CSS: Follow the Tailwind CSS installation guide for your project setup

7. Start your local PHP server and navigate to the project in your web browser.

Note: Make sure to keep your API keys and sensitive information secure and never commit them to version control.


## Contact

For support and inquiries, please contact us at: <a href="mailto:sarfarazunarr@gmail.com">sarfarazunarr@gmail.com</a>