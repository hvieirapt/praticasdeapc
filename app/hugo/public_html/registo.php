<?php require_once __DIR__ . "/../scripts/registo.php"; ?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
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

    <form method="post" action="registo.php" class="space-y-4 max-w-sm mx-auto mt-4">
      <div class="form-group flex flex-col">
        <label for="username" class="mb-1">Utilizador:</label>
        <input type="text" id="username" name="username" required class="border rounded p-2" />
      </div>
      <div class="form-group flex flex-col">
        <label for="password" class="mb-1">Palavra-passe:</label>
        <input type="password" id="password" name="password" required class="border rounded p-2" />
      </div>
      <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Registar</button>
    </form>
  </main>
</body>
</html>
