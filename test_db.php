<?php

require_once __DIR__ . '/db.php';

echo "<h1>Подключение к БД работает!</h1>";

try {
    $stmt = $pdo->query("SELECT COUNT(*) AS cnt FROM users");
    $row = $stmt->fetch();
    $count = $row['cnt'] ?? 0;

    echo "<p>Таблица <b>users</b> существует. Количество пользователей: <b>$count</b>.</p>";
} catch (PDOException $e) {
    echo "<p>Ошибка при запросе к таблице users: " .
        htmlspecialchars($e->getMessage(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') .
        "</p>";
}