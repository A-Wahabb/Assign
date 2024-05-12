<?php
session_start();

// Include the database connection file
require_once 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
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
    <title>Report</title>
    <link rel="stylesheet" href="layout.css">
    <link rel="stylesheet" href="app.css">
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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
    <h3>Report</h3>
    <?php if (isset($selectedTeamsData) && !empty($selectedTeamsData)) : ?>
        <h4>Selected Teams Information</h4>
        <table class="team-table">
    <thead>
        <tr>
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
        <?php foreach ($selectedTeamsData as $team) : ?>
            <tr>
                <td><?php echo $team['name']; ?></td>
                <td><?php echo $team['position']; ?></td>
                <td><?php echo $team['played']; ?></td>
                <td><?php echo $team['points']; ?></td>
                <td><?php echo $team['won']; ?></td>
                <td><?php echo $team['lost']; ?></td>
                <td><?php echo $team['drawn']; ?></td>
                <td><?php echo $team['goal_diff']; ?></td>
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

        <!-- Bar chart comparing all selected teams -->
        <?php if (count($selectedTeamsData) > 1) : ?>
            <h4>Comparison of Selected Teams</h4>
            <canvas id="teamsComparisonChart" width="400" height="400"></canvas>
            <script>
                var ctx2 = document.getElementById('teamsComparisonChart').getContext('2d');
                var teamsComparisonChart = new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: ['Wins', 'Losses', 'Draws', 'Games Remaining'],
                        datasets: [
                            <?php foreach ($selectedTeamsData as $team) : ?>
                            {
                                label: '<?php echo $team['name']; ?>',
                                data: [<?php echo $team['won']; ?>, <?php echo $team['lost']; ?>, <?php echo $team['drawn']; ?>, <?php echo (38 - $team['played']); ?>],
                                backgroundColor: getRandomColor(),
                                borderWidth: 1
                            },
                            <?php endforeach; ?>
                        ]
                    },
                    options: {
                        responsive: false,
                        maintainAspectRatio: false,
                        scales: {
                            xAxes: [{
                                stacked: true
                            }],
                            yAxes: [{
                                stacked: true
                            }]
                        }
                    }
                });

                // Function to generate random color for bar chart
                function getRandomColor() {
                    var letters = '0123456789ABCDEF';
                    var color = '#';
                    for (var i = 0; i < 6; i++) {
                        color += letters[Math.floor(Math.random() * 16)];
                    }
                    return color;
                }
            </script>
        <?php endif; ?>
    <?php else : ?>
        <p>No teams selected.</p>
    <?php endif; ?>
</main>
<footer>&copy; CSYM019 2024</footer>
</body>
</html>
