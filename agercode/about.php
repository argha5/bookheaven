<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Book Heaven</title>
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        h1 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 2rem;
        }

        .about-hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 3rem;
        }

        .about-hero img {
            max-width: 200px;
            border-radius: 50%;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .about-section {
            background-color: var(--card-bg);
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .about-section h2 {
            color: var(--primary-color);
            margin-top: 0;
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 0.5rem;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }

        .team-member {
            background-color: var(--aside-bg);
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .team-member img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
        }

        .values-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .value-item {
            background-color: var(--light-purple);
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
        }

        .value-item h3 {
            margin-top: 0;
            color: var(--primary-color);
        }

        .value-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .cta-section {
            text-align: center;
            padding: 3rem;
            background-color: var(--light-purple);
            border-radius: 10px;
            margin-top: 2rem;
        }

        @media (max-width: 768px) {
            main {
                padding: 1rem;
            }
            
            .team-grid, .values-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include_once("../header.php") ?>
    <main>
        <div class="about-hero">
            <img src="https://via.placeholder.com/200" alt="Book Heaven Logo">
            <h1>Welcome to Book Heaven</h1>
            <p style="max-width: 800px; font-size: 1.2rem;">Your ultimate destination for discovering, exploring, and collecting books from around the world. Since 2010, we've been connecting readers with their next favorite book.</p>
        </div>
        
        <div class="about-section">
            <h2>Our Story</h2>
            <p>Book Heaven began as a small independent bookstore in a quiet neighborhood. What started as a passion project between two literature-loving friends quickly grew into one of the most beloved book destinations in the country.</p>
            <p>In 2015, we launched our online store to share our carefully curated collection with book lovers worldwide. Today, we serve thousands of customers across the globe while maintaining the personal touch and expert recommendations that made our physical store so special.</p>
        </div>
        
        <div class="about-section">
            <h2>Our Mission</h2>
            <p>At Book Heaven, we believe in the transformative power of books. Our mission is to:</p>
            <ul>
                <li>Connect readers with books that inspire, educate, and entertain</li>
                <li>Support authors and publishers by promoting diverse voices</li>
                <li>Foster a community of book lovers through events and discussions</li>
                <li>Make quality literature accessible to everyone</li>
            </ul>
        </div>
        
        <div class="about-section">
            <h2>Our Values</h2>
            <div class="values-list">
                <div class="value-item">
                    <div class="value-icon">📚</div>
                    <h3>Literary Excellence</h3>
                    <p>We carefully select each title in our collection for its quality and impact.</p>
                </div>
                
                <div class="value-item">
                    <div class="value-icon">🤝</div>
                    <h3>Community</h3>
                    <p>We believe books bring people together and strengthen communities.</p>
                </div>
                
                <div class="value-item">
                    <div class="value-icon">🌍</div>
                    <h3>Diversity</h3>
                    <p>We champion diverse voices and perspectives in literature.</p>
                </div>
                
                <div class="value-item">
                    <div class="value-icon">💡</div>
                    <h3>Knowledge</h3>
                    <p>We're committed to spreading knowledge and fostering lifelong learning.</p>
                </div>
            </div>
        </div>
        
        <div class="about-section">
            <h2>Meet Our Team</h2>
            <div class="team-grid">
                <div class="team-member">
                    <img src="https://via.placeholder.com/150" alt="Sarah Johnson">
                    <h3>Sarah Johnson</h3>
                    <p><em>Founder & CEO</em></p>
                    <p>Literature professor turned entrepreneur, Sarah curates our fiction collection.</p>
                </div>
                
                <div class="team-member">
                    <img src="https://via.placeholder.com/150" alt="Michael Chen">
                    <h3>Michael Chen</h3>
                    <p><em>Co-Founder & COO</em></p>
                    <p>Former librarian with an encyclopedic knowledge of non-fiction titles.</p>
                </div>
                
                <div class="team-member">
                    <img src="https://via.placeholder.com/150" alt="Emma Rodriguez">
                    <h3>Emma Rodriguez</h3>
                    <p><em>Head of Customer Experience</em></p>
                    <p>Makes sure every Book Heaven customer feels valued and understood.</p>
                </div>
                
                <div class="team-member">
                    <img src="https://via.placeholder.com/150" alt="David Kim">
                    <h3>David Kim</h3>
                    <p><em>Head Buyer</em></p>
                    <p>Our resident expert on emerging authors and independent presses.</p>
                </div>
            </div>
        </div>
        
        <div class="cta-section">
            <h2>Join Our Community</h2>
            <p style="max-width: 700px; margin: 0 auto 1.5rem;">Sign up for our newsletter to receive book recommendations, author interviews, and exclusive offers straight to your inbox.</p>
            <form style="display: flex; max-width: 500px; margin: 0 auto;">
                <input type="email" placeholder="Your email address" style="flex-grow: 1; padding: 0.75rem; border: 1px solid var(--accent-color); border-radius: 5px 0 0 5px; border-right: none;">
                <button type="submit" style="background-color: var(--primary-color); color: white; border: none; padding: 0 1.5rem; border-radius: 0 5px 5px 0; cursor: pointer;">Subscribe</button>
            </form>
        </div>
    </main>
    <?php include_once("../footer.php") ?>
</body>
</html>