<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    // Validate data (basic validation)
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        die("Please fill all required fields!");
    }
    
    // Sanitize data
    $name = htmlspecialchars($name);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars($subject);
    $message = htmlspecialchars($message);
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format!");
    }
    
    // Save to database
    $db = new SQLite3('contacts.db');
    
    $stmt = $db->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (:name, :email, :subject, :message)");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':subject', $subject, SQLITE3_TEXT);
    $stmt->bindValue(':message', $message, SQLITE3_TEXT);
    
    if ($stmt->execute()) {
        echo "Thank you for your message! We'll get back to you soon.";
    } else {
        echo "Sorry, there was an error sending your message. Please try again later.";
    }
    
    $db->close();
} else {
    // Not a POST request, redirect to form
    header("Location: index.html");
    exit();
}
?>