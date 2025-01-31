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
    <input type="text"class="form-control form-control-lg" name="username" placeholder="Username" required>
    <input type="password" class="form-control form-control-lg"name="password" placeholder="Password" required>
    <input type="password" class="form-control form-control-lg"name="confirm_password" placeholder="Confirm Password" required>
    <button type="submit">Sign Up</button>
    <button type="submit"><a href="login.php">lognin</a></button>
</form>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<?php

if (isset($error)) {
    echo '<p style="color: red;">' . $error . '</p>';
}
?>
