<?php
declare(strict_types=1);

// Conexão SQLite
$dbPath = '/var/www/html/data/database.sqlite';
try {
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com a base de dados: " . $e->getMessage());
}

// Inicialização de variáveis
$error = '';
$username = '';
$password = '';
$grupo_permissoes = '';

// Processa form de registo
if ($_SERVER['REQUEST_METHOD'] === 'POST' 
    && isset($_POST['username'], $_POST['password'], $_POST['grupo_permissoes'])) {
    $username = trim((string)$_POST['username']);
    $password = trim((string)$_POST['password']);
    $grupo_permissoes = trim((string)$_POST['grupo_permissoes']);

    if ($username === '' || $password === '' || $grupo_permissoes === '') {
        $error = 'Preencha todos os campos.';
    } else {
        // Verifica se já existe
        $stmt = $db->prepare('SELECT id FROM User WHERE username = :username');
        $stmt->execute([':username' => $username]);
        if ($stmt->fetch()) {
            $error = 'Utilizador já existe.';
        } else {
            // Insere novo utilizador
            $stmt2 = $db->prepare(
                'INSERT INTO User (username, password, grupo_permissoes) VALUES (:username, :password, :grupo)'
            );
            $stmt2->execute([
                ':username'         => $username,
                ':password'         => $password,
                ':grupo'            => $grupo_permissoes,
            ]);
            header('Location: registo.php');
            exit;
        }
    }
}

// Listar utilizadores existentes
try {
    $stmtList = $db->query('SELECT id, username, grupo_permissoes FROM User');
    $users = $stmtList->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $users = [];
    $error = 'Erro ao listar utilizadores: ' . $e->getMessage();
}
?>