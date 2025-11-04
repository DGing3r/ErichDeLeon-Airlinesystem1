<?php
include 'db.php';

// Sanitize ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch flight record
$flight = $conn->query("SELECT * FROM airlinedb WHERE ID = $id")->fetch_assoc();

if (!$flight) {
    die("<h3 style='color:red; text-align:center;'>Flight not found.</h3>");
}

// Handle update form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $city = $conn->real_escape_string($_POST['CITY']);
    $country = $conn->real_escape_string($_POST['COUNTRY']);
    $passengers = intval($_POST['PASSENGERS_AMOUNT']);
    $duration = floatval($_POST['DURATION']);
    $ratings = floatval($_POST['RATINGS']);

    $conn->query("
        UPDATE airlinedb 
        SET CITY='$city', COUNTRY='$country', PASSENGERS_AMOUNT=$passengers, DURATION=$duration, RATINGS=$ratings 
        WHERE ID=$id
    ");

    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Flight - Airline System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom right, #a4b1ff, #e9efff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            width: 400px;
        }
        h2 {
            text-align: center;
            color: #004aad;
            margin-bottom: 25px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }
        label {
            font-weight: bold;
            color: #333;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.2s ease;
        }
        button:hover {
            background: #0056b3;
        }
        .back {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }
        .back:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <form method="POST" class="card">
        <h2>✈ Edit Flight</h2>

        <label for="CITY">City</label>
        <input type="text" id="CITY" name="CITY" value="<?= htmlspecialchars($flight['CITY']) ?>" required>

        <label for="COUNTRY">Country</label>
        <input type="text" id="COUNTRY" name="COUNTRY" value="<?= htmlspecialchars($flight['COUNTRY']) ?>" required>

        <label for="PASSENGERS_AMOUNT">Passengers</label>
        <input type="number" id="PASSENGERS_AMOUNT" name="PASSENGERS_AMOUNT" value="<?= $flight['PASSENGERS_AMOUNT'] ?>" required>

        <label for="DURATION">Duration (mins)</label>
        <input type="number" id="DURATION" name="DURATION" value="<?= $flight['DURATION'] ?>" required>

        <label for="RATINGS">Ratings</label>
        <input type="number" step="0.1" id="RATINGS" name="RATINGS" value="<?= $flight['RATINGS'] ?>" required>

        <button type="submit">Update Flight</button>
        <a href="index.php" class="back">← Back to Homepage</a>
    </form>
</body>
</html>
