<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>
<h1>Register</h1>

<!--debug用-->
<?php
if (!empty($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']); // エラーメッセージをクリア
}
?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="/register" enctype="multipart/form-data">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>
    <br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <label for="profile_image">Profile Image:</label><br>
    <input type="file" id="profile_image" name="profile_image" accept="image/*" required><br><br>
    <button type="submit">Register</button>
</form>
</body>
