<?php
session_start();
if (!isset($_SESSION['airlinedb'])) header("Location: login.php");
include 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$flight = $conn->query("SELECT * FROM airlinedb WHERE ID=$id")->fetch_assoc();

if (!$flight) {
    echo "<script>alert('Flight not found');window.location='index.php';</script>";
    exit;
}

// Fetch all passenger data for pie comparison
$data = $conn->query("SELECT CITY, PASSENGERS_AMOUNT FROM airlinedb ORDER BY PASSENGERS_AMOUNT DESC");
$totalPassengers = 0;
$labels = [];
$values = [];
while ($r = $data->fetch_assoc()) {
    $labels[] = $r['CITY'];
    $values[] = $r['PASSENGERS_AMOUNT'];
    $totalPassengers += $r['PASSENGERS_AMOUNT'];
}

// Calculate percentage for the selected city
$cityPercent = $totalPassengers > 0 ? round(($flight['PASSENGERS_AMOUNT'] / $totalPassengers) * 100, 2) : 0;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
      <title>View Flight - <?= htmlspecialchars($flight['CITY']) ?></title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
            body{font-family:Arial;
              margin:0;
              background:#e6ebff}

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

            a{color:#004aad;
              text-decoration:none}

              a:hover{text-decoration:underline}

              .chart-container{height:300px}

              .detail-list p{margin:6px 0}
  
        </style>
  </head>
<body>
<header>
  <h2>✈ Flight Details</h2>
  <p>Welcome, <?= htmlspecialchars($_SESSION['airlinedb']) ?> | <a href="logout.php" style="color:#ffdbdb;">Logout</a></p>
</header>

<main>
  <div class="card">
    <h3>Flight Information</h3>
    <div class="detail-list">
      <p><b>City:</b> <?= htmlspecialchars($flight['CITY']) ?></p>
      <p><b>Country:</b> <?= htmlspecialchars($flight['COUNTRY']) ?></p>
      <p><b>Passengers:</b> <?= number_format($flight['PASSENGERS_AMOUNT']) ?></p>
      <p><b>Duration:</b> <?= $flight['DURATION'] ?> mins</p>
      <p><b>Rating:</b> <?= $flight['RATINGS'] ?>★</p>
      <p><b>Passenger Share:</b> <?= $cityPercent ?>% of total</p>
    </div>
    <a href="index.php">← Back to Dashboard</a>
  </div>

  <div class="card chart-container">
    <h3>Passenger Comparison</h3>
    <canvas id="pieChart"></canvas>
  </div>
</main>

<footer>© <?= date('Y') ?> Airline System</footer>

<script>
const ctx = document.getElementById('pieChart').getContext('2d');
const cities = [<?php echo implode(",", array_map(fn($c)=>"'".addslashes($c)."'", $labels)); ?>];
const passengers = [<?= implode(",", $values) ?>];

new Chart(ctx, {
  type: 'pie',
  data: {
    labels: cities,
    datasets: [{
      data: passengers,
      backgroundColor: cities.map(c => c === '<?= addslashes($flight['CITY']) ?>' ? '#004aad' : 'rgba(0,75,173,0.3)'),
      borderColor: '#fff',
      borderWidth: 1
    }]
  },
  options: {
    plugins: {
      legend: { position: 'right' },
      title: { display: true, text: 'Passenger Distribution by City' }
    }
  }
});
</script>
</body>
</html>
