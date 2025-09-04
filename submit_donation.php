<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once 'db_connect.php';

// Initialize response
$response = [
    'success' => false,
    'message' => '',
    'donation_id' => null
];

try {
    // Create database connection
    $db = new Database();
    $conn = $db->conn;

    // Validate and sanitize input
    $category = $conn->real_escape_string($_POST['category'] ?? '');
    $subcategory = $conn->real_escape_string($_POST['subcategory'] ?? '');
    $size = $conn->real_escape_string($_POST['size'] ?? '');
    $color = $conn->real_escape_string($_POST['color'] ?? '');
    $description = $conn->real_escape_string($_POST['description'] ?? '');

    // Validate required fields
    if (empty($category) || empty($subcategory)) {
        throw new Exception("Category and subcategory are required.");
    }

    // Prepare and execute donation insertion
    $sql = "INSERT INTO donations (category, subcategory, size, color, description) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $category, $subcategory, $size, $color, $description);
    
    if (!$stmt->execute()) {
        throw new Exception("Error inserting donation: " . $stmt->error);
    }

    // Get the ID of the inserted donation
    $donation_id = $stmt->insert_id;

    // Handle image uploads
    if (!empty($_FILES['images'])) {
        // Create uploads directory if it doesn't exist
        $upload_dir = 'uploads/' . $donation_id . '/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Process multiple image uploads
        $image_count = count($_FILES['images']['name']);
        $uploaded_images = 0;

        for ($i = 0; $i < $image_count; $i++) {
            $tmp_name = $_FILES['images']['tmp_name'][$i];
            $original_name = $_FILES['images']['name'][$i];
            
            // Generate unique filename
            $file_extension = pathinfo($original_name, PATHINFO_EXTENSION);
            $new_filename = uniqid() . '.' . $file_extension;
            $destination = $upload_dir . $new_filename;

            // Move uploaded file
            if (move_uploaded_file($tmp_name, $destination)) {
                // Insert image path into database
                $image_sql = "INSERT INTO donation_images (donation_id, image_path) VALUES (?, ?)";
                $image_stmt = $conn->prepare($image_sql);
                $image_stmt->bind_param("is", $donation_id, $destination);
                $image_stmt->execute();
                $uploaded_images++;
            }
        }

        $response['images_uploaded'] = $uploaded_images;
    }

    // Success response
    $response['success'] = true;
    $response['message'] = "Donation submitted successfully!";
    $response['donation_id'] = $donation_id;

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// Send JSON response
echo json_encode($response);