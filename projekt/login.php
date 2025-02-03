<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role_id'] = $user['role_id'];

            echo '<pre>';
            print_r($_SESSION); 
            echo '</pre>';

            header('Location: index.php');
            exit;
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Username not found!";
    }
}
?>

<form method="POST">
    <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" required>
    <input type="password" name="password"class="form-control form-control-lg" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<?php if (isset($error)) { echo $error; } ?>
