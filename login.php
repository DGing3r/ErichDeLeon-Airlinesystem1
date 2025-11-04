<?php
session_start();

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Hardcoded admin credentials
    $valid_username = "admin";
    $valid_password = "user";

    // Validate credentials
    if ($username === $valid_username && $password === $valid_password) {
        // Store session name to match homepage.php expectation
        $_SESSION['airlinedb'] = $username;

        // Redirect to homepage
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Invalid username or password.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Airline System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #a4b1ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        form {
            background: white;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            width: 340px;
        }
        input, button {
            display: block;
            width: 100%;
            margin: 12px 0;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background: #0056b3;
        }
        h2 {
            text-align: center;
            color: #333;
        }
    </style>
</head>
<body>
    <form method="POST" autocomplete="off">
        <h2>Airline System Login</h2>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
