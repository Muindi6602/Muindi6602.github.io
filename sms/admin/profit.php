<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profit Calculation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: #f0f2f5;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }
        h1 {
            margin-bottom: 20px;
            color: #007bff;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-control {
            width: 250px;
            padding: 10px;
            box-sizing: border-box;
            margin: 0 auto;
        }
        .rounded-0 {
            border-radius: 0;
        }
        .text-end {
            text-align: end;
        }
        .btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .result, .gross-profit {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .text-info {
            color: #17a2b8;
        }
        .pl-3 {
            padding-left: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Profit Calculation</h1>
        <?php
        session_start();
        if (!isset($_SESSION['items'])) {
            $_SESSION['items'] = [];
        }

        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $cost = isset($_POST['cost']) ? $_POST['cost'] : '';
        $buying_price = isset($_POST['buying_price']) ? $_POST['buying_price'] : '';

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
            if ($_POST['action'] == 'add') {
                if (!empty($name) && !empty($cost) && !empty($buying_price)) {
                    $profit = $cost - $buying_price;
                    $_SESSION['items'][] = [
                        'name' => $name,
                        'cost' => $cost,
                        'buying_price' => $buying_price,
                        'profit' => $profit
                    ];
                    $name = $cost = $buying_price = '';
                } else {
                    echo '<p class="text-danger">Please fill in all fields.</p>';
                }
            } elseif ($_POST['action'] == 'calculate_gross_profit') {
                $gross_profit = array_sum(array_column($_SESSION['items'], 'profit'));
            } elseif ($_POST['action'] == 'clear_data') {
                $_SESSION['items'] = [];
                $name = $cost = $buying_price = '';
            }
        }
        ?>

        <form action="profit.php" method="POST">
            <div class="form-group">
                <label for="name" class="control-label">Name</label>
                <input type="text" name="name" id="name" class="form-control rounded-0" value="<?php echo htmlspecialchars($name); ?>">
            </div>
            <div class="form-group">
                <label for="cost" class="control-label">Cost</label>
                <input type="number" name="cost" id="cost" step="any" class="form-control rounded-0 text-end" value="<?php echo htmlspecialchars($cost); ?>">
            </div>
            <div class="form-group">
                <label for="buying_price" class="control-label">Buying Price</label>
                <input type="number" name="buying_price" id="buying_price" step="any" class="form-control rounded-0 text-end" value="<?php echo htmlspecialchars($buying_price); ?>">
            </div>
            <input type="hidden" name="action" value="add">
            <button type="submit" class="btn">Add Item</button>
        </form>

        <form action="profit.php" method="POST">
            <input type="hidden" name="action" value="calculate_gross_profit">
            <button type="submit" class="btn">Calculate Gross Profit</button>
        </form>

        <form action="profit.php" method="POST">
            <input type="hidden" name="action" value="clear_data">
            <button type="submit" class="btn">Clear Data</button>
        </form>

        <?php if (isset($gross_profit)): ?>
            <div class="gross-profit">
                <dt class="text-info">Gross Profit:</dt>
                <dd class="pl-3"><?php echo htmlspecialchars($gross_profit); ?></dd>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
