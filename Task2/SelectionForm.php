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
    
    <link rel="stylesheet" href="app.css">
    <script>
        $(document).ready( function () {
            $('#teamReportTable').DataTable();
        });
    </script>
    <style>

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
    <h3>Premier League Report</h3>
    <?php
    session_start();
    require_once 'connection.php';

    if (!isset($_SESSION['username'])) {
        header('Location: loginForm.php');
        exit();
    }

    $stmt = $pdo->query("SELECT * FROM teams ORDER BY position ASC");
    $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <form action="generateReport.php" method="post">
        <input type="hidden" name="selectedTeams[]" value="all"> <!-- Add a hidden input for selecting all teams -->
        <input type="submit" id="gnrt_rept" value="Generate Report"> <!-- This button will submit the form to generate the report -->
        <table id="teamReportTable">
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
                    <th>Calculated Points</th> <!-- New column header -->
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
                        <td><?php echo $team['goal_diff']; ?></td>
                        <td><?php echo ($team['won'] * 3) + ($team['drawn'] * 1); ?></td> <!-- Calculate points based on wins and draws -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </form>
</main>
<footer>&copy; CSYM019 2024</footer>
</body>
</html>
