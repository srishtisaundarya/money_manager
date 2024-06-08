<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$msgBox = ''; // Initialize $msgBox variable

// Database Connection
$mysqli = new mysqli('localhost', 'root', '', 'money_manager');

// Check connection
if ($mysqli->connect_error) {
    die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Check if the form is submitted for user registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $mysqli->real_escape_string($_POST['name']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $currency = $mysqli->real_escape_string($_POST['currency']);
    $password = $mysqli->real_escape_string($_POST['password']);
    $rpassword = $mysqli->real_escape_string($_POST['rpassword']);

    // Validate form data
    if (empty($name) || empty($email) || empty($currency) || empty($password) || empty($rpassword)) {
        $msgBox = "<div class='alert alert-danger'>All fields are required.</div>";
    } elseif ($password !== $rpassword) {
        $msgBox = "<div class='alert alert-danger'>Passwords do not match.</div>";
    } else {
        // Check if email already exists
        $checkEmailSql = "SELECT * FROM users WHERE email='$email'";
        $checkEmailResult = $mysqli->query($checkEmailSql);

        if ($checkEmailResult->num_rows > 0) {
            $msgBox = "<div class='alert alert-danger'>Email already exists.</div>";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user data into the database
            $insertSql = "INSERT INTO users (name, email, currency, password) VALUES ('$name', '$email', '$currency', '$hashed_password')";
            if ($mysqli->query($insertSql) === TRUE) {
                $msgBox = "<div class='alert alert-success'>Registration successful!</div>";
            } else {
                $msgBox = "<div class='alert alert-danger'>Error: " . $mysqli->error . "</div>";
            }
        }
    }
}

// Close the database connection
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom CSS */
        body {
            background-image: url('image2.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }
        .container {
            margin-top: 50px;
        }
        .panel-default {
            background: rgba(76, 175, 80, 0.0);
            border-radius: 10px;
            box-shadow: none;
        }
        .panel-body {
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            padding: 30px;
        }
        .form-control {
            border-radius: 5px;
            border: none;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }
        .btn {
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }
        .btn:hover {
            transform: translateY(-3px);
        }
        .footer {
            background-color: rgba(0, 0, 0, 0.5);
            color: #fff;
            padding: 20px 0;
            border-radius: 10px;
            margin-top: 50px;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h2 class="text-center">Register</h2>
                        <form action="" method="post">
                            <div class="form-group">
                                <input class="form-control" placeholder="Name" name="name" type="text">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Email" name="email" type="email">
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="currency">
                                    <option value="USD">US Dollar (USD)</option>
                                    <option value="EUR">Euro (EUR)</option>
                                    <option value="GBP">British Pound (GBP)</option>
                                    <option value="INR">Indian Rupee (INR)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Password" name="password" type="password">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Re-enter Password" name="rpassword" type="password">
                            </div>
                            <button type="submit" name="signup" class="btn btn-success btn-block"><span class="glyphicon glyphicon-ok"></span> Register</button>
                        </form>
                        <?php if ($msgBox) { echo $msgBox; } ?>
                        <p class="text-center"><a href="index.php">Sign In</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
