<?php
session_start();

if (!isset($_SESSION['order_details'])) {
    header('Location: index.php'); 
    exit();
}

$orderDetails = $_SESSION['order_details'];
unset($_SESSION['order_details']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
</head>
<body>
    <h1>Order Confirmation</h1>
    <p><?php echo nl2br(htmlspecialchars($orderDetails)); ?></p>
    <a href="index.php">Go Back</a>
</body>
</html>
