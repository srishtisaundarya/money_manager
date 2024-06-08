<?php 
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['UserId'])) {
    header('Location: dashboard.php');
    exit;
}

// Include your functions for budget, bills, and available amount
include('includes/functions.php');

// Call the functions to get required data
$budget = getBudget();
$bills = getBills();
$availableAmount = calculateAvailableAmount($budget, $bills);

// Initialize page variable
$page = '';

// Link to page
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

// Include global notification
include('includes/global.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Title Here</title>
    <!-- Include your CSS files here -->
    <link rel="stylesheet" href="C:\xampp\htdocs\hackelite\css\bootstrap.css">
    <link rel="stylesheet" href="C:\xampp\htdocs\hackelite\css\bootstrap.min.css">
    <link rel="stylesheet" href="C:\xampp\htdocs\hackelite\css\custom.css">

    
</head>
<body>

    

    <div class="container">
        <?php
        // Initialize global message notification
        $msgBox = "";

        // Load the requested page if it exists, otherwise display an error
        if (file_exists('pages/'.$page.'.php')) {
            include('pages/'.$page.'.php');
        } else {
            echo '
                <div class="wrapper">
                    <h3>Error</h3>
                    <div class="alertMsg default">
                        <i class="icon-warning-sign"></i> The page "'.$page.'" could not be found.
                    </div>
                </div>
            ';
        }
        ?>
    </div>

    
</body>
</html>