<?php
session_start();
if (!isset($_SESSION['airlinedb'])) header("Location: login.php");
include 'db.php';

// Add new flight
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $city = $_POST['city'];
    $country = $_POST['country'];
    $p = $_POST['passengers'];
    $d = $_POST['duration'];
    $r = $_POST['ratings'];
    $conn->query("INSERT INTO airlinedb (CITY, COUNTRY, PASSENGERS_AMOUNT, DURATION, RATINGS) VALUES ('$city','$country',$p,$d,$r)");
    header("Location: index.php");
    exit;
}

// Analytics
$mostVisited = $conn->query("SELECT CITY, PASSENGERS_AMOUNT FROM airlinedb ORDER BY PASSENGERS_AMOUNT DESC LIMIT 1")->fetch_assoc() ?? ['CITY'=>'N/A','PASSENGERS_AMOUNT'=>0];
$topRated = $conn->query("SELECT CITY, RATINGS FROM airlinedb ORDER BY RATINGS DESC LIMIT 1")->fetch_assoc() ?? ['CITY'=>'N/A','RATINGS'=>0];
$avgDuration = $conn->query("SELECT AVG(DURATION) AS avg FROM airlinedb")->fetch_assoc() ?? ['avg'=>0];
$rankings = $conn->query("SELECT CITY, PASSENGERS_AMOUNT FROM airlinedb ORDER BY PASSENGERS_AMOUNT DESC");
$flights = $conn->query("SELECT * FROM airlinedb");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
        <title>Airline Dashboard</title>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <style>
                    body{font-family:Arial;
                        margin:0;
                        background:#a4b1ff}
                    
                    header,footer{background:#004aad;
                        color:#fff;
                        padding:10px 30px;
                        text-align:center}
                    
                    main{padding:20px 30px}
                    
                    .card{background:#fff;
                        padding:15px;
                        border-radius:10px;
                        box-shadow:0 2px 5px rgba(0,0,0,.1);
                        margin-bottom:20px}
                    
                    h2,h3{color:#004aad;
                        margin:0 0 10px}
                    
                    table{width:100%;
                        border-collapse:collapse}
                    
                    th,td{border:1px solid #ccc;
                        padding:8px;
                        text-align:left}
                    
                    th{background:#004aad;
                        color:#fff}
                    
                    a{color:#004aad;
                        text-decoration:none}
                    
                    a:hover{text-decoration:underline}
                    
                    .chart-container{height:300px}
                    
                    input,button{padding:8px;
                        margin:4px;
                        border:1px solid #ccc;
                        border-radius:5px}
                    
                    button{background:#004aad;
                        color:white;cursor:pointer}
                    
                    button:hover{background:#00337a}
                </style>
    </head>
<body>
<header>
  <h2>✈ Airline Dashboard</h2>
  <p>Welcome, <?= htmlspecialchars($_SESSION['airlinedb']) ?> | <a href="logout.php" style="color:#ffdbdb;">Logout</a></p>
</header>

<main>
  <div class="card">
    <h3>Add New Flight</h3>
    <form method="POST">
      <input name="city" placeholder="City" required>
      <input name="country" placeholder="Country" required>
      <input type="number" name="passengers" placeholder="Passengers" required>
      <input type="number" name="duration" placeholder="Duration" required>
      <input type="number" step="0.1" name="ratings" placeholder="Ratings" required>
      <button type="submit">Add Flight</button>
    </form>
  </div>

  <div class="card">
    <h3>Statistics</h3>
    <p>Most Visited: <b><?= $mostVisited['CITY'] ?></b> (<?= $mostVisited['PASSENGERS_AMOUNT'] ?>)</p>
    <p>Top Rated: <b><?= $topRated['CITY'] ?></b> (<?= $topRated['RATINGS'] ?>★)</p>
    <p>Average Duration: <?= round($avgDuration['avg'],2) ?> mins</p>
  </div>

  <div class="card">
    <h3>Flight Records</h3>
    <table>
      <tr><th>ID</th><th>City</th><th>Country</th><th>Passengers</th><th>Duration</th><th>Rating</th><th>Actions</th></tr>
      <?php if ($flights->num_rows>0): while($r=$flights->fetch_assoc()): ?>
        <tr>
          <td><?= $r['ID'] ?></td>
          <td><?= htmlspecialchars($r['CITY']) ?></td>
          <td><?= htmlspecialchars($r['COUNTRY']) ?></td>
          <td><?= $r['PASSENGERS_AMOUNT'] ?></td>
          <td><?= $r['DURATION'] ?></td>
          <td><?= $r['RATINGS'] ?></td>
          <td>
            <a href="view.php?id=<?= $r['ID'] ?>">View</a> |
            <a href="edit.php?id=<?= $r['ID'] ?>">Edit</a> |
            <a href="delete.php?id=<?= $r['ID'] ?>" onclick="return confirm('Delete this record?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; else: ?><tr><td colspan="7" align="center">No data</td></tr><?php endif; ?>
    </table>
  </div>

  <div class="card chart-container">
    <h3>Passenger Trend</h3>
    <canvas id="chart"></canvas>
  </div>
</main>

<footer>© <?= date('Y') ?> Airline System</footer>

<script>
const ctx=document.getElementById('chart').getContext('2d');
const cities=[<?php
  $labels=[];$data=[];
  $rankings->data_seek(0);
  while($r=$rankings->fetch_assoc()){ $labels[]="'".addslashes($r['CITY'])."'"; $data[]=$r['PASSENGERS_AMOUNT']; }
  echo implode(",",$labels);
?>];
const passengers=[<?= implode(",",$data) ?>];
new Chart(ctx,{type:'line',data:{labels:cities,datasets:[{data:passengers,label:'Passengers',fill:true,tension:.3,backgroundColor:'rgba(0,75,173,.1)',borderColor:'#004aad'}]},options:{plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}});
</script>
</body>
</html>
