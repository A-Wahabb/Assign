<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Football Team</title>
    <link rel="stylesheet" href="layout.css">
    <link rel="stylesheet" href="app.css">
    
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
<div class="">
    <main style="max-width:800px">
        <form id="teamForm" class="entry-form" action="dataEntry.php" method="post">
            
    <h5>
        <h1>Add New Football Team</h1>
    </h5>
    <div class="form-group">
                <label for="teamName">Team Name:</label>
                <input type="text" id="teamName" name="teamName" required>
            </div>
            <div class="form-group">
                <label for="goal_diff">Goal Difference:</label>
                <input type="text" id="goal_diff" name="goal_diff" required>
            </div>
            <div class="form-group">
                <label for="position">Position:</label>
                <input type="number" id="position" name="position" required>
            </div>
            <div class="form-group">
                <label for="played">Played:</label>
                <input type="number" id="played" name="played" required>
            </div>
            <div class="form-group">
                <label for="points">Points:</label>
                <input type="number" id="points" name="points" required>
            </div>
            <div class="form-group">
                <label for="draws">Draws:</label>
                <input type="number" id="draws" name="draws" required>
            </div>
            <div class="form-group">
                <label for="wins">Wins:</label>
                <input type="number" id="wins" name="wins" required>
            </div>
            <div class="form-group">
                <label for="lost">Losses:</label>
                <input type="number" id="lost" name="lost" required>
            </div>
            <button type="submit">Add Team</button>
            <div id="message" class="message">
                <?php if (isset($errorTeams)) : ?>
                    <p style="color: red;"><?php echo $errorTeams; ?></p>
                <?php elseif (isset($successMessage)) : ?>
                    <p style="color: green;"><?php echo $successMessage; ?></p>
                <?php endif; ?>
            </div>
        </form>
    </main>
</div>
<footer>&copy; CSYM019 2024</footer>
</body>
</html>
