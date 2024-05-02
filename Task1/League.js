// Function to fetch data from the league data file
function getData() {
  return fetch('LeagueData.json')
    .then(response => response.json())
    .then(data => data);
}

// Function to update League table
function updateLeagueTable() {
  getData().then(data => {
    const tableBody = document.getElementById('league-table-body');
    tableBody.innerHTML = '';

    data.teams.forEach(team => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${team.position}</td>
        <td>${team.name}</td>
        <td>${team.played}</td>
        <td>${team.won}</td>
        <td>${team.drawn}</td>
        <td>${team.lost}</td>
        <td>${team.goalDifference}</td>
        <td>${team.points}</td>
      `;
      tableBody.appendChild(row);
    });
  });
}

// Function to update Top Scorers table
function updateTopScorersTable() {
  getData().then(data => {
    const tableBody = document.getElementById('top-scorers-body');
    tableBody.innerHTML = '';

    data.topScorers.forEach(player => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${player.name}</td>
        <td>${player.team}</td>
        <td>${player.goals}</td>
      `;
      tableBody.appendChild(row);
    });
  });
}

// Function to refresh data
function refreshData() {
  updateLeagueTable();
  updateTopScorersTable();
}

// Call refreshData function when page is first loaded
refreshData();

// Refresh data every 5 minutes (300,000 milliseconds)
setInterval(refreshData, 300000);
