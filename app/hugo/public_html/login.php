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
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" href="style/styles.css">
  <title>Login</title>
</head>
<body>
  <header>
    <nav>
      <ul class="main-nav">
        <li><a href="login.php" class="current">Login</a></li>
        <li><a href="expedicoes.php">Expedições</a></li>
        <li><a href="dashboard.php">Dashboard</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <h1 class="page-title">Iniciar Sessão</h1>

    <?php if ($error): ?>
      <p class="highlight"><?= htmlspecialchars($error, ENT_QUOTES) ?></p>
    <?php endif; ?>

    <form method="post" action="login.php" class="login-form">
      <div class="form-group">
        <label for="username">Utilizador:</label><br>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="password">Palavra-passe:</label><br>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit">Entrar</button>
    </form>
  </main>
</body>
</html>
