<?php
// Database Connection Configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "smart_waste_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Household Waste Management</title>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #27ae60;
            --bg-light: #e8f5e9;
            --text-color: #333;
            --accent-color: #2ecc71;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            background-color: var(--bg-light);
            color: var(--text-color);
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background-color: var(--secondary-color);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .image-marquee {
            width: 100%;
            height: 250px;
            background-color: #f0f4f0;
            margin-bottom: 30px;
            overflow: hidden;
            position: relative;
            border: 2px solid var(--secondary-color);
        }

        .marquee-content {
            display: flex;
            position: absolute;
            white-space: nowrap;
            animation: marquee 20s linear infinite;
        }

        .marquee-content:hover {
            animation-play-state: paused;
        }

        .marquee-image {
            width: 200px;
            height: 200px;
            object-fit: cover;
            margin: 0 20px;
            border-radius: 10px;
            border: 3px solid var(--accent-color);
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-100%);
            }
        }

        .waste-timeline {
            position: relative;
            padding: 50px 0;
        }

        .timeline-item {
            position: relative;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #f4f4f4;
            border-radius: 10px;
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .timeline-item.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            background-color: var(--secondary-color);
            border-radius: 50%;
            left: -30px;
            top: 30px;
        }

        #wasteCanvas {
            width: 100%;
            height: 300px;
            background-color: #f0f0f0;
            margin-bottom: 30px;
        }

        .smart-scheduling {
            background-color: var(--accent-color);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
        }

        .waste-categories {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .waste-category {
            text-align: center;
            margin: 10px;
            padding: 15px;
            background-color: #e8f4f8;
            border-radius: 10px;
            width: 200px;
            transition: transform 0.3s ease;
        }

        .waste-category:hover {
            transform: scale(1.05);
        }

        .interactive-schedule {
            margin-top: 30px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
        }

        #scheduleForm {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        #scheduleForm input, 
        #scheduleForm select,
        #scheduleForm button {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }

        #scheduleForm button {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #scheduleForm button:hover {
            background-color: var(--accent-color);
        }

        #scheduleResult {
            margin-top: 20px;
            padding: 15px;
            background-color: #e8f4f8;
            border-radius: 5px;
        }

        .homepage-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background-color: var(--secondary-color);
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .homepage-button:hover {
            background-color: var(--accent-color);
        }

        #userDetailsModal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .user-details-form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 300px;
            text-align: center;
        }

        .user-details-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .user-details-form button {
            width: 100%;
            padding: 10px;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .user-details-form button:hover {
            background-color: var(--accent-color);
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
        .success-message {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Smart Domestic Waste Management</h1>
            <p>Efficient, Sustainable, and Responsible Waste Handling</p>
        </div>

        <div class="image-marquee">
            <div class="marquee-content">
                <!-- Placeholder images for continuous scroll -->
                <img src="bottles.png.jpg" alt="Waste Management Image 1" class="marquee-image">
                <img src="jocket.jpg" alt="Waste Management Image 2" class="marquee-image">
                <img src="e-wastematerial.jpg" alt="Waste Management Image 3" class="marquee-image">
                <img src="plaRe.jpg" alt="Waste Management Image 4" class="marquee-image">
                <img src="bottles2.jpg" alt="Waste Management Image 5" class="marquee-image">
                
                <!-- Duplicate images for continuous scroll -->
                <img src="neckT.jpg" alt="Waste Management Image 1" class="marquee-image">
                <img src="pla.jpg" alt="Waste Management Image 2" class="marquee-image">
                <img src="pl.jpg" alt="Waste Management Image 3" class="marquee-image">
                <img src="vegpeel.jpg" alt="Waste Management Image 4" class="marquee-image">
                <img src="pl1.jpg" alt="Waste Management Image 5" class="marquee-image">
            </div>
        </div>

        <?php
        // Handle form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sanitize and validate inputs
            $full_name = $conn->real_escape_string($_POST['userName'] ?? '');
            $phone_number = $conn->real_escape_string($_POST['userPhone'] ?? '');
            $waste_type = $conn->real_escape_string($_POST['wasteType'] ?? '');
            $collection_date = $conn->real_escape_string($_POST['collectionDate'] ?? '');
            $collection_time = $conn->real_escape_string($_POST['collectionTime'] ?? '');

            // Prepare the stored procedure call
            $stmt = $conn->prepare("CALL schedule_waste_pickup(?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $full_name, $phone_number, $waste_type, $collection_date, $collection_time);

            // Execute the procedure
            try {
                $stmt->execute();
                $success_message = "Waste pickup scheduled successfully!";
            } catch (Exception $e) {
                $error_message = "Error scheduling waste pickup: " . $e->getMessage();
            }

            $stmt->close();
        }
        ?>

        <div class="header">
            <h1>Smart Domestic Waste Management</h1>
            <p>Efficient, Sustainable, and Responsible Waste Handling</p>
        </div>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <?php if (isset($success_message)): ?>
            <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <div class="interactive-schedule">
            <h3>Schedule Your Waste Collection</h3>
            <form id="scheduleForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <select id="wasteType" name="wasteType" required>
                    <option value="">Select Waste Type</option>
                    <option value="recyclable">Recyclable Waste</option>
                    <option value="organic">Organic Waste</option>
                    <option value="non-recyclable">Non-Recyclable Waste</option>
                </select>
                <input type="date" id="collectionDate" name="collectionDate" required>
                <select id="collectionTime" name="collectionTime" required>
                    <option value="">Select Time Slot</option>
                    <option value="morning">Morning (6-9 AM)</option>
                    <option value="afternoon">Afternoon (2-5 PM)</option>
                    <option value="evening">Evening (6-9 PM)</option>
                </select>
                <div id="userDetailsSection">
                    <input type="text" id="userName" name="userName" placeholder="Full Name" required>
                    <input type="tel" id="userPhone" name="userPhone" placeholder="Phone Number" required pattern="[0-9]{10}">
                </div>
                <button type="submit">Schedule Pickup</button>
            </form>
            <div id="scheduleResult"></div>
        </div>

        <div id="userDetailsModal">
            <div class="user-details-form">
                <h3>Enter Your Details</h3>
                <form id="userDetailsForm">
                    <input type="text" id="userName" placeholder="Full Name" required>
                    <input type="tel" id="userPhone" placeholder="Phone Number" required pattern="[0-9]{10}">
                    <button type="submit">Save Details</button>
                </form>
            </div>
        </div>

        <a href="myweb.php" class="homepage-button">Return to Homepage</a>
    </div>

    <script>
        // Waste Timeline Animation
        function animateTimeline() {
            const timelineItems = document.querySelectorAll('.timeline-item');
            
            timelineItems.forEach((item, index) => {
                setTimeout(() => {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('visible');
                            }
                        });
                    }, { threshold: 0.1 });
                    
                    observer.observe(item);
                }, index * 200);
            });
        }

        // Waste Visualization Canvas
        class WasteParticle {
            constructor(x, y, type) {
                this.x = x;
                this.y = y;
                this.type = type;
                this.size = Math.random() * 10 + 5;
                this.speedX = (Math.random() - 0.5) * 3;
                this.speedY = (Math.random() - 0.5) * 3;
                this.color = this.getColor();
            }

            getColor() {
                switch(this.type) {
                    case 'recyclable': return '#3498db';
                    case 'organic': return '#2ecc71';
                    case 'non-recyclable': return '#e74c3c';
                    default: return '#95a5a6';
                }
            }

            update(ctx) {
                this.x += this.speedX;
                this.y += this.speedY;

                // Bounce off walls
                if (this.x < 0 || this.x > ctx.canvas.width) this.speedX *= -1;
                if (this.y < 0 || this.y > ctx.canvas.height) this.speedY *= -1;
            }

            draw(ctx) {
                ctx.beginPath();
                ctx.fillStyle = this.color;
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        function initWasteVisualization() {
            const canvas = document.getElementById('wasteCanvas');
            const ctx = canvas.getContext('2d');
            
            // Resize canvas
            canvas.width = canvas.offsetWidth;
            canvas.height = canvas.offsetHeight;

            const particles = [];
            const wasteTypes = ['recyclable', 'organic', 'non-recyclable'];

            // Create particles
            for (let i = 0; i < 100; i++) {
                const type = wasteTypes[Math.floor(Math.random() * wasteTypes.length)];
                particles.push(new WasteParticle(
                    Math.random() * canvas.width,
                    Math.random() * canvas.height,
                    type
                ));
            }

            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                particles.forEach(particle => {
                    particle.update(ctx);
                    particle.draw(ctx);
                });

                requestAnimationFrame(animate);
            }

            animate();

            // Resize handling
            window.addEventListener('resize', () => {
                canvas.width = canvas.offsetWidth;
                canvas.height = canvas.offsetHeight;
            });
        }

        // Interactive Scheduling
        function initScheduling() {
            const form = document.getElementById('scheduleForm');
            const resultDiv = document.getElementById('scheduleResult');
        }

           

        // Initialize all functions
        document.addEventListener('DOMContentLoaded', () => {
            animateTimeline();
            initWasteVisualization();
            initScheduling();
        });
    </script>
</body>
</html>

<?php
// Close the database connection at the end
$conn->close();
?>