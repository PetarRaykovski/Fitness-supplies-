<?php
// login.php
session_start();
require 'db.php';

// Ако потребителят вече е влязъл, го пренасочваме
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Проверка на паролата
        if ($user && password_verify($password, $user['password'])) {
            // Успешен вход -> Създаваме сесия
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "Грешно потребителско име или парола!";
        }
    } else {
        $message = "Моля, попълнете всички полета.";
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
</head>
<body>
    <h2>Вход в системата</h2>
    <?php if($message): ?>
        <p style="color:red;"><?= $message ?></p>
    <?php endif; ?>
    
    <form action="login.php" method="post">
        <div>
            <label>Потребителско име:</label><br>
            <input type="text" name="username" required>
        </div>
        <div>
            <label>Парола:</label><br>
            <input type="password" name="password" required>
        </div>
        <br>
        <button type="submit">Вход</button>
    </form>
    <p>Нямате акаунт? <a href="register.php">Регистрация</a></p>
</body>
</html>
