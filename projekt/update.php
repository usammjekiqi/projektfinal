<?php
session_start();
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get current user information
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch();

// If no user is found, redirect to login page
if (!$user) {
    header('Location: login.php');
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role_id = isset($_POST['role_id']) ? $_POST['role_id'] : $user['role_id']; // Default to current role
    $grade = isset($_POST['grade']) ? $_POST['grade'] : $user['grade']; // Admin can change grade

    // Handle password change
    if ($password && $password === $confirm_password) {
        // Hash new password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE users SET username = :username, password = :password, role_id = :role_id, grade = :grade WHERE id = :user_id");
        $stmt->execute(['username' => $username, 'password' => $hashed_password, 'role_id' => $role_id, 'grade' => $grade, 'user_id' => $user_id]);
        $message = "Profile updated successfully!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Update username or role without changing password or grade (for non-admins)
        $stmt = $pdo->prepare("UPDATE users SET username = :username, role_id = :role_id, grade = :grade WHERE id = :user_id");
        $stmt->execute(['username' => $username, 'role_id' => $role_id, 'grade' => $grade, 'user_id' => $user_id]);
        $message = "Profile updated successfully!";
    }
}

// Admin check
$is_admin = $_SESSION['role_id'] == 1; // Check if logged-in user is admin
?>

<!-- Update Form -->
<h2>Update Profile</h2>

<?php if (isset($message)) { echo "<p style='color: green;'>$message</p>"; } ?>
<?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>

<form method="POST">
    <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($user['username']) ?>" required>

    <input type="password" name="password" placeholder="New Password">
    <input type="password" name="confirm_password" placeholder="Confirm New Password">

    <?php if ($is_admin): ?>
        <!-- Admin can update role and grade -->
        <select name="role_id">
            <option value="1" <?= $user['role_id'] == 1 ? 'selected' : '' ?>>Admin</option>
            <option value="2" <?= $user['role_id'] == 2 ? 'selected' : '' ?>>User</option>
        </select>

        <!-- Admin can update grade -->
        <input type="text" name="grade" placeholder="Grade" value="<?= htmlspecialchars($user['grade']) ?>">
    <?php endif; ?>

    <?php if (!$is_admin): ?>
        <!-- Users can only see their grade, not edit -->
        <p><strong>Your Grade: </strong><?= htmlspecialchars($user['grade']) ?></p>
    <?php endif; ?>

    <button type="submit">Update</button>
</form>

