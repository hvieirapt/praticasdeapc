<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
if (($_SESSION['grupo_permissoes'] ?? '') !== 'Administrador') {
    header('Location: 403.php');
    exit();
}
require_once __DIR__ . "/scripts/registo.php";
$error             = $error             ?? '';
$grupo_permissoes  = $grupo_permissoes  ?? '';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Registo</title>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
  <?php include __DIR__ . '/components/navbar.php'; ?>

  <main class="p-4 flex-grow">
    <?php if (!empty($error)): ?>
      <p class="text-red-600 mb-4"><?= htmlspecialchars($error, ENT_QUOTES) ?></p>
    <?php endif; ?>

    <div class="flex items-center justify-between mb-4">
      <button id="openCreate" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Criar Utilizador</button>
      <h1 class="text-2xl font-semibold">Tabela de Utilizadores</h1>
    </div>

    <div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
      <div class="bg-white p-6 rounded w-full max-w-md">
        <h2 class="text-lg font-semibold mb-4">Novo Utilizador</h2>
        <form method="post" action="registo.php" class="space-y-4" id="registoForm" novalidate>
          <input type="hidden" name="action" value="criar">
          <div class="flex flex-col">
            <label for="username" class="mb-1">Utilizador:</label>
            <input type="text" id="username" name="username" required class="border border-gray-300 rounded p-2" />
          </div>
          <div class="flex flex-col">
            <label for="password" class="mb-1">Palavra-passe:</label>
            <input type="password" id="password" name="password" required class="border border-gray-300 rounded p-2" />
          </div>
          <div class="flex flex-col">
            <label for="grupo_permissoes" class="mb-1">Grupo de Permissões:</label>
            <select id="grupo_permissoes" name="grupo_permissoes" required class="border border-gray-300 rounded p-2">
              <?php foreach (['Cliente', 'Administrador', 'Operador'] as $opt): ?>
                <option value="<?= htmlspecialchars($opt, ENT_QUOTES) ?>"><?= htmlspecialchars($opt) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="flex justify-end gap-2">
            <button type="button" id="closeCreate" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Criar</button>
          </div>
        </form>
      </div>
    </div>

    <table class="min-w-full divide-y divide-gray-200 mt-4 table-auto">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-2 text-center">ID</th>
          <th class="px-4 py-2 text-center">Username</th>
          <th class="px-4 py-2 text-center">Grupo Permissões</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <?php foreach ($users as $u): ?>
          <tr>
            <td class="px-4 py-2 text-center"><?= htmlspecialchars($u['id'], ENT_QUOTES) ?></td>
            <td class="px-4 py-2 text-center"><?= htmlspecialchars($u['username'], ENT_QUOTES) ?></td>
            <td class="px-4 py-2 text-center"><?= htmlspecialchars($u['grupo_permissoes'], ENT_QUOTES) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </main>
  <?php include __DIR__ . '/components/footer.php'; ?>
  <script src="scripts/validate.js"></script>
</body>
</html>
