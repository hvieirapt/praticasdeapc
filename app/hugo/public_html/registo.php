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
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style/styles.css" />
  <title>Registo</title>
</head>
<body>
  <header>
    <nav>
      <ul class="main-nav">
        <li><a href="login.php">Login</a></li>
        <li><a href="registo.php" class="current">Registo</a></li>
        <li><a href="expedicoes.php">Expedições</a></li>
        <li><a href="dashboard.php">Dashboard</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <h1 class="page-title">Criar Nova Conta</h1>

    <?php if ($error): ?>
      <p class="highlight"><?= htmlspecialchars($error, ENT_QUOTES) ?></p>
    <?php endif; ?>

    <form method="post" action="registo.php" class="login-form">
      <div class="form-group">
        <label for="username">Utilizador:</label><br />
        <input type="text" id="username" name="username" required />
      </div>
      <div class="form-group">
        <label for="password">Palavra-passe:</label><br />
        <input type="password" id="password" name="password" required />
      </div>
      <button type="submit">Registar</button>
    </form>
  </main>
</body>
</html>
