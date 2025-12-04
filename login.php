<?php
session_start();

require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index2.html');
    exit;
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['pass'] ?? '';

if ($email === '' || $password === '') {
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Login error</title>
    </head>
    <body>
        <h2>Login error</h2>
        <p>Enter your email and password.</p>
        <p><a href="index2.html">Return to login?</a></p>
    </body>
    </html>
    <?php
    exit;
}

$stmt = $pdo->prepare('SELECT id, username, password_hash FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Login error</title>
    </head>
    <body>
        <h2>Login error</h2>
        <p>User not found</p>
        <p><a href="index2.html">Return to login?</a></p>
    </body>
    </html>
    <?php
    exit;
}

if (!password_verify($password, $user['password_hash'])) {
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Login Error</title>
    </head>
    <body>
        <h2>Login Error</h2>
        <p>Incorrect password.</p>
        <p><a href="index2.html">Return to login?</a></p>
    </body>
    </html>
    <?php
    exit;
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];

header('Location: mainpage.html');
exit;