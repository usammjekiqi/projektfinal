<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include('config.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

       
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role_id) VALUES (?, ?, ?)");
        $stmt->execute([$username, $hashed_password, 2]);
       
        header('Location: login.php');
        exit;
    }
}
?>


<form method="POST">
    <h2>Create Account</h2>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
    <button type="submit">Sign Up</button>
    <button type="submit"><a href="login.php">lognin</a></button>
</form>

<?php

if (isset($error)) {
    echo '<p style="color: red;">' . $error . '</p>';
}
?>
