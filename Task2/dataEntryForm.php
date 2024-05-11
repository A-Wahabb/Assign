<?php
session_start();

// Include the database connection file
require_once 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: loginForm.php');
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
    $goal_diff = $_POST['goal_diff'];
    $played = $_POST['played'];
    $position = $_POST['position'];

    // Prepare SQL statement to insert data into the database
    $stmt = $pdo->prepare("INSERT INTO teams (name, position, played, won, lost, drawn, goal_diff, points) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    try {
        // Execute the SQL statement
        $stmt->execute([$team_name, $position, $played, $won, $lost, $drawn, $goal_diff, $points]);
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
    <link rel="stylesheet" href="enteryForm.css">
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
    <h3>Football Teams Entry Form</h3>
    <form action="dataEntryForm.php" method="post">
        <label for="team_name">Team Name:</label><br>
        <input type="text" id="name" name="name" required><br>
        <label for="goal_diff">Goal Difference:</label><br>
        <input type="number" id="goal_diff" name="goal_diff" required><br>
        <label for="position">Position:</label><br>
        <input type="number" id="position" name="position" required><br>
        <label for="played">Played:</label><br>
        <input type="number" id="played" name="played" required><br>
        <label for="points">Points:</label><br>
        <input type="number" id="points" name="points" required><br>
        <label for="draws">Draws:</label><br>
        <input type="number" id="draws" name="draws" required><br>
        <label for="wins">Wins:</label><br>
        <input type="number" id="wins" name="wins" required><br>
        <label for="losses">Losses:</label><br>
        <input type="number" id="losses" name="losses" required><br>

        
        <input type="submit" value="Add Team">
    </form>
</main>
<footer>&copy; CSYM019 2024</footer>
</body>
</html>
