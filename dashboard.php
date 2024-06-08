<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Manager</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('image3.png');
            background-size: cover;
            background-repeat: no-repeat;
            margin: 0;
            padding: 20px;
        }
        .section {
            background-color: rgba(128, 128, 128, 0.5);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }
        h1, h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            text-align: center;
            margin-bottom: 30px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        input[type="number"], input[type="text"] {
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
        p {
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="section">
        <h1>Budget Manager</h1>
    </div>

    <div class="section">
        <h2>Amount Available Calculator</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="income">Income:</label>
            <input type="number" id="income" name="income"><br>
            <label for="expenses">Expenses:</label>
            <input type="number" id="expenses" name="expenses"><br>
            <button type="submit" name="calculate">Calculate Amount Available</button>
        </form>
        <p><?php if(isset($_POST["calculate"])) { echo "Amount Available: $".(floatval($_POST["income"]) - floatval($_POST["expenses"])); } ?></p>
    </div>

    <div class="section">
        <h2>Currency Conversion</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount"><br>
            <label for="exchangeRate">Exchange Rate:</label>
            <input type="number" id="exchangeRate" name="exchangeRate"><br>
            <button type="submit" name="convert">Convert</button>
        </form>
        <p><?php if(isset($_POST["convert"])) { echo "Converted Amount: $".(floatval($_POST["amount"]) * floatval($_POST["exchangeRate"])); } ?></p>
    </div>

    <div class="section">
        <h2>Income Manager</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="source">Source of Income:</label>
            <input type="text" id="source" name="source"><br>
            <label for="income_amount">Amount:</label>
            <input type="number" id="income_amount" name="income_amount"><br>
            <button type="submit" name="add_income">Add Income</button>
        </form>
        <?php 
        if(isset($_POST["add_income"])) {
            echo "Income Added: ".$_POST["source"]." - $".$_POST["income_amount"];
        }
        ?>
    </div>

    <div class="section">
        <h2>List of Bills</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <?php 
            $bills = array(
                "Rent",
                "Utilities",
                "Groceries",
                "Transportation",
                "Entertainment"
            );

            $total_expense = 0;
            foreach ($bills as $bill) {
                echo "<label for='$bill'>$bill:</label>";
                echo "<input type='number' id='$bill' name='$bill'><br>";
                if(isset($_POST[$bill])) {
                    $total_expense += $_POST[$bill];
                }
            }
            ?>
            <button type="submit" name="calculate_expense">Calculate Total Expense</button>
        </form>
        <?php
        if(isset($_POST["calculate_expense"])) {
            echo "<p>Total Expense: $".$total_expense."</p>";
        }
        ?>
    </div>
</body>
</html>
