<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoWaste - Cloud Waste Management Solution</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <img src="https://via.placeholder.com/150x50" alt="EcoWaste Logo" id="logo">
                <h1>EcoWaste</h1>
            </div>
            <div class="search-container">
                <input type="text" placeholder="Search waste disposal options...">
                <button type="submit" class="search-button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <nav>
                <ul>
                    <li><a href="#" class="active"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="#services"><i class="fas fa-recycle"></i> Services</a></li>
                    <li><a href="cart.html"><i class="fas fa-shopping-cart"></i> Cart <span class="cart-count">0</span></a></li>
                    <li><a href="#" id="login-button"><i class="fas fa-user"></i> Login</a></li>
                </ul>
            </nav>
            <div class="mobile-menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h2>Smart Waste Management Solutions</h2>
                <p>Sustainable waste disposal services powered by cloud technology</p>
                <div class="hero-buttons">
                    <a href="#services" class="btn btn-primary">Our Services</a>
                    <a href="#get-started" class="btn btn-secondary">Get Started</a>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="services">
        <div class="container">
            <h2 class="section-title">Our Services</h2>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-trash"></i>
                    </div>
                    <h3>Household Waste</h3>
                    <p>Regular collection and proper disposal of household waste with smart scheduling.</p>
                    <a href="#" class="btn btn-small">Learn More</a>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-industry"></i>
                    </div>
                    <h3>Industrial Waste</h3>
                    <p>Specialized handling and processing for industrial waste materials.</p>
                    <a href="#" class="btn btn-small">Learn More</a>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <h3>E-Waste</h3>
                    <p>Environmentally friendly disposal and recycling of electronic waste.</p>
                    <a href="#" class="btn btn-small">Learn More</a>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3>Organic Waste</h3>
                    <p>Composting and sustainable processing of organic and biodegradable waste.</p>
                    <a href="#" class="btn btn-small">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <section id="get-started" class="get-started">
        <div class="container">
            <h2 class="section-title">How It Works</h2>
            <div class="steps-container">
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-icon"><i class="fas fa-user-plus"></i></div>
                    <h3>Sign Up</h3>
                    <p>Create an account to access all our services</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-icon"><i class="fas fa-clipboard-list"></i></div>
                    <h3>Schedule Pickup</h3>
                    <p>Book waste collection at your convenience</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-icon"><i class="fas fa-truck"></i></div>
                    <h3>Collection</h3>
                    <p>Our team picks up your waste on schedule</p>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <div class="step-icon"><i class="fas fa-chart-line"></i></div>
                    <h3>Track Impact</h3>
                    <p>Monitor your environmental contribution</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-columns">
                <div class="footer-column">
                    <h3>EcoWaste</h3>
                    <p>Cloud-based waste management solutions for a sustainable future.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Contact Us</h3>
                    <ul class="contact-info">
                        <li><i class="fas fa-map-marker-alt"></i> 123 Green Street, Eco City</li>
                        <li><i class="fas fa-phone"></i> +1 (555) 123-4567</li>
                        <li><i class="fas fa-envelope"></i> info@ecowaste.com</li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Newsletter</h3>
                    <p>Subscribe to get updates on our services and sustainability tips.</p>
                    <form class="newsletter-form">
                        <input type="email" placeholder="Your email address">
                        <button type="submit" class="btn btn-small">Subscribe</button>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 EcoWaste. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Login Modal -->
    <div id="login-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="tabs">
                <button class="tab-button active" data-tab="login-tab">Login</button>
                <button class="tab-button" data-tab="signup-tab">Sign Up</button>
            </div>
            
            <div id="login-tab" class="tab-content active">
                <h2>Login</h2>
                <form id="login-form" action="auth1.php?action=login" method="POST">
                    <div class="form-group">
                        <label for="login-email">Email</label>
                        <input type="email" id="login-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <input type="password" id="login-password" name="password" required>
                    </div>
                    <div class="form-group">
                        <a href="#" class="forgot-password">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full"><a href="myindex.html">Login</a></button>
                </form>
                
            </div>
            
            <div id="signup-tab" class="tab-content">
                <h2>Create Account</h2>
                <form id="signup-form" action="auth1.php?action=signup" method="POST">
                    <div class="form-group">
                        <label for="signup-name">Full Name</label>
                        <input type="text" id="signup-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="signup-email">Email</label>
                        <input type="email" id="signup-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="signup-password">Password</label>
                        <input type="password" id="signup-password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="signup-confirm-password">Confirm Password</label>
                        <input type="password" id="signup-confirm-password" required>
                    </div>
                    <div class="form-group checkbox">
                        <input type="checkbox" id="terms" required>
                        <label for="terms">I agree to the <a href="#">Terms and Conditions</a></label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full"><a href="myindex.html">Sign Up</a></button>
                </form>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>