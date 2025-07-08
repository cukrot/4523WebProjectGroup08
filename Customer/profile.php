<?php
session_start();
include 'config.php';

if (!isset($_SESSION['customer_id'])) {
header('Location: auth.php');
exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$telephone = $_POST['telephone'];
$address = $_POST['address'];
$stmt = $pdo->prepare('UPDATE customer SET cpassword = ?, ctel = ?, caddr = ? WHERE cid = ?');
$stmt->execute([$password, $telephone, $address, $_SESSION['customer_id']]);
echo 'Information has been updated';
}

$stmt = $pdo->prepare('SELECT * FROM customer WHERE cid = ?');
$stmt->execute([$_SESSION['customer_id']]);
$customer = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
<title>Personal information</title>
</head>
<body>
<form method="post">
<label>New password: <input type="password" name="password" required></label><br>
<label>Telephone: <input type="text" name="telephone" value="<?php echo $customer['ctel']; ?>" required></label><br>
<label>Address: <input type="text" name="address" value="<?php echo $customer['caddr']; ?>" required></label><br>
<input type="submit" value="Update">
</form>
</body>
</html>