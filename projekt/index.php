<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_role_id = $_SESSION['role_id'];
$admin_role_id = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role_id = $_POST['role_id'];

        $stmt = $pdo->prepare("INSERT INTO users (username, password, role_id) VALUES (?, ?, ?)");
        $stmt->execute([$username, $password, $role_id]);
    }

    if (isset($_POST['update_role'])) {
        $user_id = $_POST['user_id'];
        $new_role_id = $_POST['new_role_id'];

        if ($user_role_id == $admin_role_id) { 
            $stmt = $pdo->prepare("UPDATE users SET role_id = ? WHERE id = ?");
            $stmt->execute([$new_role_id, $user_id]);
        }
    }
}

$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();
?>

<!-- Simple CRUD UI -->
<?php if ($user_role_id == $admin_role_id): ?>
    <h2>Create New User</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="role_id">
            <option value="1">Admin</option>
            <option value="2">User</option>
        </select>
        <button type="submit" name="create">Create</button>
    </form>

    <h2>Change User Role</h2>
    <form method="POST">
        <select name="user_id">
            <?php foreach ($users as $user): ?>
                <option value="<?= $user['id'] ?>"><?= $user['username'] ?></option>
            <?php endforeach; ?>
        </select>
        <select name="new_role_id">
            <option value="1">Admin</option>
            <option value="2">User</option>
        </select>
        <button type="submit" name="update_role">Change Role</button>
    </form>
<?php endif; ?>

<h2>User List</h2>
<table class="table table-striped table-bordered">
    <tr>
        <th>Username</th>
        <th>Role</th>
        <?php if ($user_role_id == $admin_role_id): ?>
            <th>Actions</th>
        <?php endif; ?>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td colspan="4"><?= $user['username'] ?></td>
            <td colspan="4"><?= $user['role_id'] == 1 ? 'Admin' : 'User' ?></td>
            <?php if ($user_role_id == $admin_role_id): ?>
                <td colspan="4">
                    <a href="delete.php?id=<?= $user['id'] ?>">Delete</a>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>

    <button><a href="index.html">Faqja</a></button>
</table>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>