<?php
session_start();

// Include the database connection file
require_once 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Retrieve teams data ordered by points achieved
$stmt = $pdo->query("SELECT * FROM teams ORDER BY points ASC");
$teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Premier League Report</title>
    <link rel="stylesheet" href="layout.css">
    <!-- Include jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Include jQuery DataTables plugin -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready( function () {
            $('#teamFootballTable').DataTable();
        });
    </script>
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
    <h3>Premier League Report</h3>
    <form action="generateReport.php" method="post">
        <table id="teamFootballTable">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Team Name</th>
                    <th>Position</th>
                    <th>Played</th>
                    <th>Points</th>
                    <th>Wins</th>
                    <th>Losses</th>
                    <th>Draws</th>
                    <th>Goal Difference</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teams as $team) : ?>
                    <tr>
                        <td><input type="checkbox" name="selectedTeams[]" value="<?php echo $team['id']; ?>"></td>
                        <td><?php echo $team['name']; ?></td>
                        <td><?php echo $team['position']; ?></td>
                        <td><?php echo $team['played']; ?></td>
                        <td><?php echo $team['points']; ?></td>
                        <td><?php echo $team['won']; ?></td>
                        <td><?php echo $team['lost']; ?></td>
                        <td><?php echo $team['drawn']; ?></td>
                        <td><?php echo $team['goalDifference']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <input type="submit" value="Generate Report">
    </form>
</main>
<footer>&copy; CSYM019 2024</footer>
</body>
</html>
