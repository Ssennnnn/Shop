<?php
$value = '10';
$add = $value + 5;

$name = 'Janssen';
$favorites = ['Chocolate', 'Toffee', 'Fudge'];

$nutrition = [
    'fat' => 15,
    'sugar' => 67,
    'salt' => 5.3,
];

$candies = [
    'Chocolate' => 50,
    'Toffee' => 35,
    'Fudge' => 20,
    'Lollipop' => 40,
    'Candy Cane' => 25
];

$prices = [
    'Chocolate' => 5,
    'Toffee' => 3,
    'Fudge' => 4,
    'Lollipop' => 2,
    'Candy Cane' => 2.5
];

$storeOpen = true;

$customerName = '';
$selectedCandy = 'Chocolate';
$items = 0;
$cost = 0;
$subtotal = 0;
$tax = 0;
$total = 0;
$budget = 0;
$message = '';
$ordered = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customerName = trim($_POST['customerName'] ?? '');
    $selectedCandy = $_POST['candy'] ?? '';
    $items = (int) ($_POST['items'] ?? 0);
    $budget = (float) ($_POST['budget'] ?? 0);

    if (
        $customerName != '' &&
        isset($candies[$selectedCandy]) &&
        $items > 0 &&
        $budget >= 0
    ) {
        $cost = $prices[$selectedCandy];
        $subtotal = $cost * $items;
        $tax = $subtotal * 0.20;
        $total = $subtotal + $tax;

        if ($total <= $budget) {
            $message = 'Your order is ready for checkout!';
            $ordered = true;
        } else {
            $message = 'Your order exceeds your budget.';
        }
    } else {
        $message = 'Please enter valid order information.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Candy Shop</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <main>

        <section id="home">
            <h2>Welcome, <?= $name ?>!</h2>
            <p>Discover a wide selection of delicious candies handmade in our kitchen.</p>

            <?php if ($storeOpen): ?>
                <p>Our candy shop is currently open. Come and satisfy your sweet tooth!</p>
            <?php else: ?>
                <p>Our candy shop is currently closed. Please visit us again soon!</p>
            <?php endif; ?>

            <h2>Your Favorite Candies</h2>

            <?php for ($i = 0; $i < count($favorites); $i++): ?>
                <p><?= $i + 1 ?>. <?= $favorites[$i] ?></p>
            <?php endfor; ?>
        </section>

        <section id="candy">
            <h2>Available Menu</h2>
            <p>Here are the candies available in our shop:</p>

            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($candies as $candy => $stock): ?>
                        <tr>
                            <td><?= $candy ?></td>
                            <td>$<?= number_format($prices[$candy], 2) ?></td>
                            <td>
                                <?php if ($stock > 0): ?>
                                    <?= $stock ?> pieces
                                <?php else: ?>
                                    Out of stock
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section id="nutrition">
            <h2>Chocolate Nutrition Facts (per 100g)</h2>
            <p>Fat: <?= $nutrition['fat'] ?>%</p>
            <p>Sugar: <?= $nutrition['sugar'] ?>%</p>
            <p>Salt: <?= $nutrition['salt'] ?>%</p>
        </section>

        <section id="cart">
            <h2>Place Your Order</h2>

            <form method="POST">

                <label for="customerName">Name:</label>
                <input
                    type="text"
                    id="customerName"
                    name="customerName"
                    value="<?= htmlspecialchars($customerName) ?>"
                    required
                >

                <label for="candy">Product:</label>
                <select id="candy" name="candy" required>
                    <?php foreach ($candies as $candy => $stock): ?>
                        <option value="<?= htmlspecialchars($candy) ?>"
                            <?= $selectedCandy == $candy ? 'selected' : '' ?>>
                            <?= $candy ?> - $<?= number_format($prices[$candy], 2) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="items">Quantity:</label>
                <input
                    type="number"
                    id="items"
                    name="items"
                    min="1"
                    value="<?= $items > 0 ? $items : 1 ?>"
                    required
                >

                <label for="budget">Budget ($):</label>
                <input
                    type="number"
                    id="budget"
                    name="budget"
                    min="0"
                    step="0.01"
                    value="<?= $budget ?>"
                    required
                >

                <button type="submit">Process Order</button>

            </form>

            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>

                <h2>Shopping Cart</h2>
                <p><?= htmlspecialchars($message) ?></p>

                <?php if ($total > 0): ?>
                    <p>Customer: <?= htmlspecialchars($customerName) ?></p>
                    <p>Items: <?= $items ?></p>
                    <p>Cost per Pack: $<?= number_format($cost, 2) ?></p>
                    <p>Subtotal: $<?= number_format($subtotal, 2) ?></p>
                    <p>Tax (20%): $<?= number_format($tax, 2) ?></p>
                    <p>Total: $<?= number_format($total, 2) ?></p>
                    <p>Budget: $<?= number_format($budget, 2) ?></p>
                    <p>Remaining Budget: $<?= number_format($budget - $total, 2) ?></p>
                <?php endif; ?>

            <?php endif; ?>

        </section>

    </main>

    <?php include 'includes/footer.php'; ?>

</body>
</html>