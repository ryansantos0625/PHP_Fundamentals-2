<?php
session_start();

$host = 'localhost';
$db = 'orders';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order = $_POST['order'];
    $quantity = $_POST['quantity'];
    $cash = $_POST['cash'];

    $stmt = $pdo->prepare("SELECT price FROM menu WHERE item_name = :item_name");
    $stmt->execute(['item_name' => $order]);
    $price = $stmt->fetchColumn();

    $total = $price * $quantity;

    if ($cash < $total) {
        $message = "Sorry, Your balance is not enought";
        header("Location: insufficient_funds.php?message=" . urlencode($message));
        exit();
    } else {

        $change = $cash - $total;

        $timestamp = date("Y-m-d H:i:s");
        $orderDetails = "Order: $order\nQuantity: $quantity\nTotal: $total\nCash: $cash\nChange: $change\nTimestamp: $timestamp";
        $_SESSION['order_details'] = $orderDetails;
        header('Location: order_confirmation.php');
        exit();
    }
}
$stmt = $pdo->query("SELECT item_name, price FROM menu");
$menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
</head>
<body>
    <h1>Menu</h1>
    <table border="1">
        <tr>
            <th>Order</th>
            <th>Amount</th>
        </tr>
        <?php foreach ($menuItems as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                <td><?php echo htmlspecialchars($item['price']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <form method="post">
        <label for="order">Select an order:</label>
        <select name="order" id="order">
            <?php foreach ($menuItems as $item): ?>
                <option value="<?php echo htmlspecialchars($item['item_name']); ?>"><?php echo htmlspecialchars($item['item_name']); ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <br>
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" min="1" required>
        <br>
        <br>
        <label for="cash">Cash:</label>
        <input type="text" name="cash" id="cash" required>
        <br>
        <br>
        <button type="submit">Submit</button>
    </form>

    <?php if (isset($orderDetails)): ?>
        <h2>Order Details</h2>
        <p><?php echo nl2br(htmlspecialchars($orderDetails)); ?></p>
    <?php endif; ?>
</body>
</html>
