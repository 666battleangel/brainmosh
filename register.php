<?php
session_start();

require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: signup.html');
    exit;
}

$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['pass'] ?? '';

$errors = [];
if ($username === '' || $email === '' || $password === '') {
    $errors[] = 'Every field is required.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Incorrect email format.';
}

if (strlen($password) < 6) {
    $errors[] = 'The password must be at least 6 characters long.';
}

if (!$errors) {
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $errors[] = 'This email is already in use.';
    }
}
if ($errors) {
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>An error occured</title>
    </head>
    <body>
        <h2>An error occured</h2>
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?php echo htmlspecialchars($e, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></li>
            <?php endforeach; ?>
        </ul>
        <p><a href="signup.html">Return to sign up?</a></p>
    </body>
    </html>
    <?php
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)');
$stmt->execute([$username, $email, $hash]);
header('Location: index2.html');
exit;