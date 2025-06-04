<?php
// login.php
declare(strict_types=1);
session_start();

// --- 1) Configuração da ligação SQLite ---
$dbPath = '/var/www/html/data/database.sqlite';
try {
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro BD: " . $e->getMessage());
}

// --- 2) Processa form de login ---
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = trim((string)($_POST['password'] ?? ''));

    if ($username === '' || $password === '') {
        $error = 'Preencha utilizador e palavra-passe.';
    } else {
        $stmt = $db->prepare(
            'SELECT id, password FROM User WHERE username = :username'
        );
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // compara password em texto (para produção use password_verify)
        if ($user && $password === $user['password']) {
            // atualiza ultimo_login
            $stmt2 = $db->prepare(
                'UPDATE User
                 SET ultimo_login = :ts
                 WHERE id = :id'
            );
            $stmt2->execute([
                ':ts' => date('Y-m-d H:i:s'),
                ':id' => $user['id'],
            ]);

            // guarda sessão e redireciona para expedicoes.php
            $_SESSION['user_id'] = $user['id'];
            header('Location: expedicoes.php');
            exit;
        } else {
            $error = 'Credenciais inválidas.';
        }
    }
}
