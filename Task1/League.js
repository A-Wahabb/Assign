// Function to fetch data from the championship data file
async function fetchChampionshipData() {
  const response = await fetch('LeagueData.json');
  const data = await response.json();
  return data;
}

// Function to update Championship table
function updateChampionshipTable() {
  fetchChampionshipData().then(data => {
    const tableBody = document.getElementById('championship-table-body');
    tableBody.innerHTML = '';
    console.log({ data })
    data.clubs.forEach(club => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${club.rank}</td>
        <td>${club.name}</td>
        <td>${club.played}</td>
        <td>${club.won}</td>
        <td>${club.drawn}</td>
        <td>${club.lost}</td>
        <td>${club.goal_diff}</td>
        <td>${club.points}</td>
      `;
      tableBody.appendChild(row);
    });
  });
}


// Function to update Top Scorers table
function updateTopScorersTable() {
  fetchChampionshipData().then(data => {
    const tableBody = document.getElementById('championship-top-scorers-body');
    console.log({ tableBody })
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
  updateChampionshipTable();
  updateTopScorersTable();
}

// Call refreshData function when page is first loaded
refreshData();

// Refresh data every 5 minutes (300,000 milliseconds)
setInterval(refreshData, 300000);
