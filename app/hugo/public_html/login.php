<?php require_once __DIR__ . "/../scripts/login.php"; ?>
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
