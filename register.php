<?php
// register.php
require 'db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        // Проверка дали потребителят вече съществува
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        if ($stmt->rowCount() > 0) {
            $message = "Това потребителско име вече е заето!";
        } else {
            // Хаширане на паролата (сигурност)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);
            
            if ($stmt->execute([$username, $hashed_password])) {
                $message = "Успешна регистрация! <a href='login.php'>Влезте тук</a>.";
            } else {
                $message = "Възникна грешка при регистрацията.";
            }
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
    <title>Регистрация</title>
</head>
<body>
    <h2>Регистрация</h2>
    <?php if($message): ?>
        <p style="color:red;"><?= $message ?></p>
    <?php endif; ?>
    
    <form action="register.php" method="post">
        <div>
            <label>Потребителско име:</label><br>
            <input type="text" name="username" required>
        </div>
        <div>
            <label>Парола:</label><br>
            <input type="password" name="password" required>
        </div>
        <br>
        <button type="submit">Регистрирай се</button>
    </form>
    <p>Имате акаунт? <a href="login.php">Вход</a></p>
</body>
</html>
