<?php
session_start();

// Include the database connection file
require_once 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: signin.php');
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if selectedTeams array exists in the POST data
    if (isset($_POST['selectedTeams'])) {
        $selectedTeams = $_POST['selectedTeams'];

        // Retrieve selected teams' data from the database
        $selectedTeamsData = [];
        foreach ($selectedTeams as $teamId) {
            $stmt = $pdo->prepare("SELECT * FROM teams WHERE id = ?");
            $stmt->execute([$teamId]);
            $teamData = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($teamData) {
                $selectedTeamsData[] = $teamData;
            }
        }

        // Function to calculate the total games played by the selected teams
        function totalGamesPlayed($teamsData) {
            $totalPlayed = 0;
            foreach ($teamsData as $team) {
                $totalPlayed += $team['played'];
            }
            return $totalPlayed;
        }

        // Function to calculate the total wins, losses, and draws by the selected teams
        function totalWinsLossesDraws($teamsData) {
            $totalWins = $totalLosses = $totalDraws = 0;
            foreach ($teamsData as $team) {
                $totalWins += $team['won'];
                $totalLosses += $team['lost'];
                $totalDraws += $team['drawn'];
            }
            return [$totalWins, $totalLosses, $totalDraws];
        }

        // Calculate total games played, wins, losses, and draws
        $totalPlayed = totalGamesPlayed($selectedTeamsData);
        list($totalWins, $totalLosses, $totalDraws) = totalWinsLossesDraws($selectedTeamsData);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>League Report</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<header>
    <h3>League Report</h3>
</header>
<main>
    <h4>Selected Teams Information</h4>
    <?php if (isset($selectedTeamsData) && !empty($selectedTeamsData)) : ?>
        <table>
            <thead>
                <tr>
                    <th>Team Name</th>
                    <th>Position</th>
                    <th>Played</th>
                    <th>Wins</th>
                    <th>Losses</th>
                    <th>Draws</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($selectedTeamsData as $team) : ?>
                    <tr>
                        <td><?php echo $team['name']; ?></td>
                        <td><?php echo $team['position']; ?></td>
                        <td><?php echo $team['played']; ?></td>
                        <td><?php echo $team['won']; ?></td>
                        <td><?php echo $team['lost']; ?></td>
                        <td><?php echo $team['drawn']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pie chart showing the percentages of matches played -->
        <canvas id="matchesPlayedChart" width="400" height="400"></canvas>
        <script>
            var ctx = document.getElementById('matchesPlayedChart').getContext('2d');
            var matchesPlayedChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Wins', 'Losses', 'Draws', 'Games Remaining'],
                    datasets: [{
                        data: [<?php echo $totalWins; ?>, <?php echo $totalLosses; ?>, <?php echo $totalDraws; ?>, <?php echo (38 - $totalPlayed); ?>],
                        backgroundColor: [
                            'green',
                            'red',
                            'blue',
                            'gray'
                        ]
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false
                }
            });
        </script>
    <?php else : ?>
        <p>No teams selected.</p>
    <?php endif; ?>
</main>
<footer>&copy; CSYM019 2024 <?php echo date('Y'); ?></footer>
</body>
</html>
