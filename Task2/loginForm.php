<?php
session_start();

// Database connection parameters
$databaseHost = 'localhost';
$databaseName = 'dataBase';
$databaseUsername = 'root';
$databasePassword = '';

// Attempt to establish the database connection using PDO
try {
    $pdo = new PDO("mysql:host=$databaseHost;dbname=$databaseName", $databaseUsername, $databasePassword);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If connection fails, display error message and stop execution
    die("Connection failed: " . $e->getMessage());
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    // Query the database to fetch user credentials
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$inputUsername]);
    $user = $stmt->fetch();
   
    // Check if the user exists and the password is correct
    if ($user && $inputPassword === $user['password']) {
        // Authentication successful
        $_SESSION['username'] = $user['username'];
        header('Location: dataEntryForm.php'); // Redirect to next page after successful login
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="loginForm.css">
</head>
<body>
<div id="login-container">
        <h2>Please Login</h2>
        <?php if (isset($error)) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="loginForm.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
