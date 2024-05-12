<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="app.css">
    <style>
 
    </style>
</head>
<body>
    <div class="main-login-container">

    <div id="login-container">
        <h2>Please Login</h2>
        <?php 
            session_start();
            $databaseHost = 'localhost';
            $databaseName = 'dataBase';
            $databaseUsername = 'root';
            $databasePassword = '';
            
            try {
                $pdo = new PDO("mysql:host=$databaseHost;dbname=$databaseName", $databaseUsername, $databasePassword);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $inputUsername = $_POST['username'];
                $inputPassword = $_POST['password'];
            
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->execute([$inputUsername]);
                $user = $stmt->fetch();
            
                if ($user && $inputPassword === $user['password']) {
                    $_SESSION['username'] = $user['username'];
                    header('Location: dataEntryForm.php');
                    exit();
                } else {
                    $error = "Invalid username or password";
                }
            }
        ?>
        <?php if (isset($error)) : ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="loginForm.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Login">
        </form>
    </div>
    </div>
</body>
</html>
