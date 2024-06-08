<?php
session_start();

$msgBox = ''; // Initialize $msgBox variable

// Database Connection
$mysqli = new mysqli('localhost', 'root', '', 'money_manager');

// Check connection
if ($mysqli->connect_error) {
    die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Check if the session variable for wallets exists, if not, initialize it
if (!isset($_SESSION['wallets'])) {
    $_SESSION['wallets'] = array();
}

// Check if the form is submitted for creating a new wallet
if(isset($_POST['create_wallet'])) {
    if(isset($_POST['wallet_name']) && isset($_POST['limit_amount'])) {
        $wallet_name = $mysqli->real_escape_string($_POST['wallet_name']);
        $limit_amount = $mysqli->real_escape_string($_POST['limit_amount']);

        // Store wallet data in session variables
        $_SESSION['wallets'][$wallet_name] = array(
            'limit_amount' => $limit_amount,
            'balance' => 0.00
        );

        // Insert wallet data into the database
        $insertSql = "INSERT INTO wallets (wallet_name, limit_amount, balance) VALUES ('$wallet_name', '$limit_amount', 0.00)";
        if ($mysqli->query($insertSql) === TRUE) {
            $msgBox = "<div class='alert alert-success'>Wallet '$wallet_name' created successfully!</div>";
        } else {
            $msgBox = "<div class='alert alert-danger'>Error creating wallet: " . $mysqli->error . "</div>";
        }
    } else {
        // Display error message if wallet name or limit amount is missing
        $msgBox = "<div class='alert alert-danger'>Please enter wallet name and limit amount.</div>";
    }
}

// Check if the form is submitted for adding to an existing wallet
if(isset($_POST['add_wallet'])) {
    if(isset($_POST['wallet_name']) && isset($_POST['amount'])) {
        $wallet_name = $mysqli->real_escape_string($_POST['wallet_name']);
        $amount = $mysqli->real_escape_string($_POST['amount']);

        // Check if the wallet exists
        if(isset($_SESSION['wallets'][$wallet_name])) {
            // Update the balance of the existing wallet
            $_SESSION['wallets'][$wallet_name]['balance'] += $amount;
            $updateSql = "UPDATE wallets SET balance = balance + $amount WHERE wallet_name = '$wallet_name'";
            if ($mysqli->query($updateSql) === TRUE) {
                $msgBox = "<div class='alert alert-success'>Added $amount to wallet '$wallet_name'!</div>";
            } else {
                $msgBox = "<div class='alert alert-danger'>Error updating wallet: " . $mysqli->error . "</div>";
            }
        } else {
            // Display error message if the wallet doesn't exist
            $msgBox = "<div class='alert alert-danger'>Wallet '$wallet_name' does not exist.</div>";
        }
    } else {
        // Display error message if wallet name or amount is missing
        $msgBox = "<div class='alert alert-danger'>Please enter wallet name and amount.</div>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Create Wallet</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
    font-family: Arial, sans-serif;
    background-image: url('image3.png'); /* Adjust the URL as needed */
    background-size: cover;
    background-repeat: no-repeat;
    margin: 0;
    padding: 20px;
}

.container {
    background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent black background */
    color: #fff; /* Text color */
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.5); /* White shadow */
}

h2, h3 {
    text-align: center;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

input[type="number"], input[type="text"], select {
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #ccc;
    margin-bottom: 10px;
    width: calc(100% - 20px);
    box-sizing: border-box;
}

button {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.3s;
}

button:hover {
    background-color: #45a049;
    transform: translateY(-3px);
}

</style>
</head>
<body>
    <div class="container">
        <h2>Create Wallet</h2>
        <?php echo $msgBox; ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="wallet_name">Wallet Name</label>
                <input type="text" class="form-control" name="wallet_name" required>
            </div>
            <div class="form-group">
                <label for="limit_amount">Set Wallet Limit</label>
                <input type="number" class="form-control" name="limit_amount" required>
            </div>
            <button type="submit" name="create_wallet" class="btn btn-primary">Create Wallet</button>
        </form>

        <h2>Add to Existing Wallet</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="wallet_name">Wallet Name</label>
                <input type="text" class="form-control" name="wallet_name" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" class="form-control" name="amount" required>
            </div>
            <button type="submit" name="add_wallet" class="btn btn-primary">Add to Wallet</button>
        </form>
    </div>
</body>
</html>
