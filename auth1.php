<?php
session_start();

// Database connection
function connectDB() {
    $host = "localhost";
    $username = "root";     // Change to your DB username
    $password = "";         // Change to your DB password
    $database = "mywebsite";
    
    $conn = new mysqli($host, $username, $password, $database);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

// Handle signup request
if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        header("Location: signup.php?error=All fields are required");
        exit();
    }
    
    // Check password length
    if (strlen($password) < 6) {
        header("Location: signup.php?error=Password must be at least 6 characters");
        exit();
    }
    
    $conn = connectDB();
    
    // Check if username or email already exists
    $check_query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        header("Location: signup.php?error=Username or email already exists");
        exit();
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    
    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        header("Location: myweb.php");
        exit();
    } else {
        header("Location: signup.php?error=Registration failed");
        exit();
    }
}

// Handle login request
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        header("Location: login.php?error=Username and password are required");
        exit();
    }
    
    $conn = connectDB();
    
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            header("Location: myweb.php");
            exit();
        } else {
            header("Location: login.php?error=Incorrect password");
            exit();
        }
    } else {
        header("Location: login.php?error=User not found");
        exit();
    }
}