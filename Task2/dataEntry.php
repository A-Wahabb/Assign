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
    $team_name = $_POST['teamName'];
    $points = $_POST['points'];
    $won = $_POST['wins'];
    $lost = $_POST['lost'];
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
