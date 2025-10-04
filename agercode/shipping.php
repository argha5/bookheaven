<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Policy | Book Heaven</title>
    <style>
        :root {
            --primary-color: #57abd2;
            --secondary-color: #f8f5fc;
            --accent-color: rgb(223, 219, 227);
            --text-color: #333;
            --light-purple: #e6d9f2;
            --dark-text: #212529;
            --light-text: #f8f9fa;
            --card-bg: #f8f9fa;
            --aside-bg: #f0f2f5;
            --nav-hover: #e0e0e0;
        }

        .dark-mode {
            --primary-color: #57abd2;
            --secondary-color: #2d3748;
            --accent-color: #4a5568;
            --text-color: #f8f9fa;
            --light-purple: #4a5568;
            --dark-text: #f8f9fa;
            --light-text: #212529;
            --card-bg: #1a202c;
            --aside-bg: #1a202c;
            --nav-hover: #4a5568;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--secondary-color);
            margin: 0;
            padding: 0;
        }

        main {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }

        h1 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 2rem;
        }

        .policy-container {
            background-color: var(--card-bg);
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: var(--primary-color);
            margin-top: 2rem;
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 0.5rem;
        }

        .shipping-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .method-card {
            background-color: var(--aside-bg);
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .method-card h3 {
            margin-top: 0;
            color: var(--primary-color);
        }

        .delivery-table {
            width: 100%;
            border-collapse: collapse;
            margin: 2rem 0;
        }

        .delivery-table th, .delivery-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--accent-color);
        }

        .delivery-table th {
            background-color: var(--light-purple);
            color: var(--dark-text);
        }

        .note-box {
            background-color: var(--light-purple);
            padding: 1.5rem;
            border-radius: 8px;
            margin: 2rem 0;
        }

        @media (max-width: 768px) {
            main {
                padding: 1rem;
            }
            
            .shipping-methods {
                grid-template-columns: 1fr;
            }
            
            .delivery-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <?php include_once("../header.php") ?>
    <main>
        <div class="policy-container">
            <h1>Shipping Policy</h1>
            
            <p>At Book Heaven, we strive to get your books to you as quickly and safely as possible. Please review our shipping policy below for detailed information about our shipping methods, delivery times, and fees.</p>
            
            <h2>Shipping Methods & Delivery Times</h2>
            
            <div class="shipping-methods">
                <div class="method-card">
                    <h3>Standard Shipping</h3>
                    <p><strong>Delivery Time:</strong> 3-5 business days</p>
                    <p><strong>Cost:</strong> Free on orders over $35, otherwise $4.99</p>
                    <p>Our most economical option with reliable delivery times.</p>
                </div>
                
                <div class="method-card">
                    <h3>Expedited Shipping</h3>
                    <p><strong>Delivery Time:</strong> 2 business days</p>
                    <p><strong>Cost:</strong> $9.99</p>
                    <p>Priority processing and faster transit times.</p>
                </div>
                
                <div class="method-card">
                    <h3>Express Shipping</h3>
                    <p><strong>Delivery Time:</strong> 1 business day</p>
                    <p><strong>Cost:</strong> $14.99</p>
                    <p>For when you need your books as soon as possible.</p>
                </div>
            </div>
            
            <h2>Estimated Delivery Times by Region</h2>
            
            <table class="delivery-table">
                <thead>
                    <tr>
                        <th>Region</th>
                        <th>Standard</th>
                        <th>Expedited</th>
                        <th>Express</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>East Coast</td>
                        <td>2-3 days</td>
                        <td>1-2 days</td>
                        <td>Next day</td>
                    </tr>
                    <tr>
                        <td>Midwest</td>
                        <td>3-4 days</td>
                        <td>2 days</td>
                        <td>Next day</td>
                    </tr>
                    <tr>
                        <td>West Coast</td>
                        <td>4-5 days</td>
                        <td>2 days</td>
                        <td>Next day</td>
                    </tr>
                    <tr>
                        <td>International</td>
                        <td>7-14 days</td>
                        <td>5-7 days</td>
                        <td>3-5 days</td>
                    </tr>
                </tbody>
            </table>
            
            <h2>Order Processing Time</h2>
            <p>All orders are processed within 1-2 business days (excluding weekends and holidays) after receiving your order confirmation email. You will receive another notification when your order has shipped.</p>
            
            <div class="note-box">
                <h3>Important Notes:</h3>
                <ul>
                    <li>Delivery times are estimates and not guaranteed</li>
                    <li>Customs and import taxes may apply to international orders</li>
                    <li>Some remote areas may experience longer delivery times</li>
                    <li>Delivery times may be extended during peak seasons</li>
                </ul>
            </div>
            
            <h2>International Shipping</h2>
            <p>We ship to most countries worldwide. International orders may be subject to customs fees, import duties, and taxes, which are the responsibility of the customer. Book Heaven has no control over these charges and cannot predict what they may be.</p>
            
            <h2>Tracking Your Order</h2>
            <p>Once your order has shipped, you will receive an email with tracking information. You can track your package using the link provided or by logging into your account.</p>
            
            <h2>Shipping Restrictions</h2>
            <p>Some items may be restricted from shipping to certain locations. If we cannot ship an item to your location, we will notify you during checkout or shortly after you place your order.</p>
            
            <h2>Questions?</h2>
            <p>If you have any questions about our shipping policy, please contact our customer service team at <a href="mailto:shipping@bookheaven.com">shipping@bookheaven.com</a> or call us at (555) 123-4567.</p>
        </div>
    </main>
    <?php include_once("../footer.php") ?>
</body>
</html>