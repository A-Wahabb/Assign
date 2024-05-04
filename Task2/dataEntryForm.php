<?php
session_start();

// Include the database connection file
require_once 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $team_name = $_POST['name'];
    $points = $_POST['points'];
    $won = $_POST['wins'];
    $lost = $_POST['losses'];
    $drawn = $_POST['draws'];
    $goalDifference = $_POST['goalDifference'];
    $played = $_POST['played'];
    $position = $_POST['position'];

    // Prepare SQL statement to insert data into the database
    $stmt = $pdo->prepare("INSERT INTO football_teams (name, position, played, won, lost, drawn, goalDifference, points) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    try {
        // Execute the SQL statement
        $stmt->execute([$team_name, $position, $played, $won, $lost, $drawn, $goalDifference, $points]);
        // Redirect to the same page with success parameter
        header('Location: dataEntryForm.php?success=true');
        exit();
    } catch (PDOException $e) {
        $errorTeams = $e->getMessage();
    }
}

// Check for success query parameter and display success message
if (isset($_GET['success']) && $_GET['success'] === 'true') {
    $successMessage = "Team added successfully.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>New Football Team</title>
    <link rel="stylesheet" href="layout.css">
    <style>
        form {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    grid-column: 2; /* Position the form in the second column */
}

form label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}

form input[type="text"],
form input[type="number"],
form input[type="password"] {
    width: calc(100% - 40px);
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

form input[type="submit"] {
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

form input[type="submit"]:hover {
    background-color: #0056b3;
}

.error {
    color: red;
    margin-top: 10px;
}

.success {
    color: green;
    margin-top: 10px;
}

    </style>
</head>
<body>
<header>
    <h3>CSYM019 - Premier League Results</h3>
</header>
<nav>
    <ul>
        <li><a href="./SelectionForm.php">Premier League Report</a></li>
        <li><a href="./dataEntryForm.php">Add New Football Team</a></li>
        <li><a href="./logout.php">Logout</a></li>
    </ul>
</nav>
<main>
    <?php if (isset($errorTeams)) : ?>
        <p style="color: red;"><?php echo $errorTeams; ?></p>
    <?php elseif (isset($successMessage)) : ?>
        <p style="color: green;"><?php echo $successMessage; ?></p>
    <?php endif; ?>
    <h3>Sample Football Teams Entry Form</h3>
    <form action="dataEntryForm.php" method="post">
        <label for="team_name">Team Name:</label><br>
        <input type="text" id="name" name="name" required><br>
        <label for="position">Position:</label><br>
        <input type="number" id="position" name="position" required><br>
        <label for="played">Played:</label><br>
        <input type="number" id="played" name="played" required><br>
        <label for="points">Points:</label><br>
        <input type="number" id="points" name="points" required><br>
        <label for="wins">Wins:</label><br>
        <input type="number" id="wins" name="wins" required><br>
        <label for="losses">Losses:</label><br>
        <input type="number" id="losses" name="losses" required><br>
        <label for="draws">Draws:</label><br>
        <input type="number" id="goalDifference" name="goalDifference" required><br>
        <label for="goalDifference">Goal Difference:</label><br>
        <input type="number" id="draws" name="draws" required><br><br>
        <input type="submit" value="Add Team">
    </form>
</main>
<footer>&copy; CSYM019 2024</footer>
</body>
</html>
