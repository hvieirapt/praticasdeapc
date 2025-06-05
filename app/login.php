<?php require_once __DIR__ . "/scripts/login.php";
$error       = $error       ?? '';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Login</title>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">


  <main class="p-4">
    <h1 class="text-2xl font-semibold mb-4 text-center">Iniciar Sessão</h1>

    <?php if ($error): ?>
      <p class="text-red-600 mb-4"><?= htmlspecialchars($error, ENT_QUOTES) ?></p>
    <?php endif; ?>

    <form method="post" action="login.php" id="loginForm" class="space-y-4 max-w-sm mx-auto mt-4" novalidate>
      <div class="flex flex-col">
        <label for="username" class="mb-1">Utilizador:</label>
        <input type="text" id="username" name="username" required class="border border-gray-300 rounded p-2" />
      </div>
      <div class="flex flex-col">
        <label for="password" class="mb-1">Palavra-passe:</label>
        <input type="password" id="password" name="password" required class="border border-gray-300 rounded p-2" />
      </div>
      <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Entrar</button>
    </form>
  </main>
  <?php include __DIR__ . '/components/footer.php'; ?>
  <script src="scripts/validate.js"></script>
</body>
</html>
