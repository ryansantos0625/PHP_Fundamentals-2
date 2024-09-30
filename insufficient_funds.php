<?php
if (!isset($_GET['message'])) {
    header('Location: index.php');
    exit();
}

$message = htmlspecialchars($_GET['message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insufficient Funds</title>
</head>
<body>
    <h1>Insufficient Funds</h1>
    <p><?php echo $message; ?></p>
    <a href="index.php">Go Back</a>
</body>
</html>
