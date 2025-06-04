<?php
// registo.php
declare(strict_types=1);
session_start();

// --- 1) Conexão SQLite ---
$dbPath = '/var/www/html/data/database.sqlite';
try {
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com a base de dados: " . $e->getMessage());
}

// --- 2) Processa form de registo ---
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = trim((string)($_POST['password'] ?? ''));

    if ($username === '' || $password === '') {
        $error = 'Preencha utilizador e palavra-passe.';
    } else {
        // Verifica se já existe
        $stmt = $db->prepare('SELECT id FROM User WHERE username = :username');
        $stmt->execute([':username' => $username]);
        if ($stmt->fetch()) {
            $error = 'Utilizador já existe.';
        } else {
            // Insere novo user
            $stmt2 = $db->prepare(
                'INSERT INTO User (username, password) VALUES (:username, :password)'
            );
            $stmt2->execute([
                ':username' => $username,
                ':password' => $password,
            ]);
            // Guarda sessão e redireciona
            $_SESSION['user_id'] = (int)$db->lastInsertId();
            header('Location: expedicoes.php');
            exit;
        }
    }
}
