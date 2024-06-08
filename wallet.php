<?php
include('includes/db.php');
include('includes/Functions.php');
include('includes/notification.php');

$msgBox = '';

session_start();
$user_id = $_SESSION['user_id']; // Assuming you have user authentication

if(isset($_POST['create_wallet'])) {
    $limit_amount = $_POST['limit_amount'];

    // Create a new wallet
    $sql = "INSERT INTO wallets (user_id, balance, limit_amount) VALUES (?, 0.00, ?)";
    if($statement = $mysqli->prepare($sql)) {
        $statement->bind_param('id', $user_id, $limit_amount);
        if($statement->execute()) {
            $msgBox = alertBox("Wallet created successfully!");
        } else {
            $msgBox = alertBox("Error creating wallet.");
        }
    }
}

if(isset($_POST['update_wallet'])) {
    $amount = $_POST['amount'];
    $action = $_POST['action']; // 'add' or 'subtract'

    // Get current wallet balance and limit
    $sql = "SELECT balance, limit_amount FROM wallets WHERE user_id = ?";
    if($statement = $mysqli->prepare($sql)) {
        $statement->bind_param('i', $user_id);
        $statement->execute();
        $statement->bind_result($balance, $limit_amount);
        $statement->fetch();
        $statement->close();

        if($action == 'add') {
            $new_balance = $balance + $amount;
        } elseif($action == 'subtract') {
            if($balance - $amount < 0) {
                $msgBox = alertBox("Insufficient balance!");
            } elseif($balance - $amount < $limit_amount) {
                $msgBox = alertBox("Transaction exceeds wallet limit!");
            } else {
                $new_balance = $balance - $amount;
            }
        }

        if(isset($new_balance)) {
            // Update wallet balance
            $sql = "UPDATE wallets SET balance = ? WHERE user_id = ?";
            if($statement = $mysqli->prepare($sql)) {
                $statement->bind_param('di', $new_balance, $user_id);
                if($statement->execute()) {
                    $msgBox = alertBox("Wallet updated successfully!");
                } else {
                    $msgBox = alertBox("Error updating wallet.");
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Wallet Management</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
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
<body>
    <div class="container">
        <h2>Wallet Management</h2>
        <?php if ($msgBox) { echo $msgBox; } ?>
        
        <h3>Create Wallet</h3>
        <form method="post" action="createwallet.php">
            <div class="form-group">
                <label for="limit_amount">Set Wallet Limit</label>
                <input type="number" class="form-control" name="limit_amount" required>
            </div>
            <button type="submit" name="create_wallet" class="btn btn-primary">Create Wallet</button>
        </form>
        
        <h3>Update Wallet</h3>
        <form method="post" action="">
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" class="form-control" name="amount" required>
            </div>
            <div class="form-group">
                <label for="action">Action</label>
                <select class="form-control" name="action" required>
                    <option value="add">Add</option>
                    <option value="subtract">Subtract</option>
                </select>
            </div>
            <button type="submit" name="update_wallet" class="btn btn-primary">Update Wallet</button>
        </form>
    </div>
</body>
</html>
