<?php require_once __DIR__ . "/../scripts/expedicoes.php"; ?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="style/styles.css" />
  <title>Expedições</title>
</head>
<body>
  <header>
    <nav>
      <ul class="main-nav">
        <li><a href="inventario.php">Inventário</a></li>
        <li><a href="expedicoes.php" class="current">Expedições</a></li>
        <li><a href="dashboard.php">Dashboard</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <h1 class="page-title">Tabela de Expedições</h1>

    <?php if (!empty($error)): ?>
      <p class="highlight"><?= htmlspecialchars($error, ENT_QUOTES) ?></p>
    <?php endif; ?>

    <!-- Formulário no topo -->
    <section class="new-expedicao">
      <h2 class="page-title">Adicionar Nova Expedição</h2>
      <form method="post" action="expedicoes.php" class="space-y-4 max-w-md mx-auto mt-4">
        <div class="form-group flex flex-col">
          <label for="cliente" class="mb-1">Cliente:</label>
          <input type="text" id="cliente" name="cliente" required class="border rounded p-2" />
        </div>
        <div class="form-group flex flex-col">
          <label for="morada" class="mb-1">Morada:</label>
          <input type="text" id="morada" name="morada" required class="border rounded p-2" />
        </div>
        <div class="form-group flex flex-col">
          <label for="data_entrega" class="mb-1">Data de Entrega:</label>
          <input type="date" id="data_entrega" name="data_entrega" required class="border rounded p-2" />
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Criar Expedição</button>
      </form>
    </section>

    <table class="exp-table min-w-full divide-y divide-gray-200 mt-6">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-2">Selecionar</th>
          <th class="px-4 py-2">Data de Criação</th>
          <th class="px-4 py-2">Data de Entrega</th>
          <th class="px-4 py-2">Cliente</th>
          <th class="px-4 py-2">Morada</th>
          <th class="px-4 py-2">Estado</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <?php if (empty($expedicoes)): ?>
          <tr><td colspan="6" class="px-4 py-2 text-center">Não há expedições registadas.</td></tr>
        <?php else: ?>
          <?php foreach ($expedicoes as $exp): ?>
            <tr>
              <td class="px-4 py-2"><input type="checkbox" /></td>
              <td class="px-4 py-2"><?= htmlspecialchars($exp['data_criacao'], ENT_QUOTES) ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($exp['data_entrega'], ENT_QUOTES) ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($exp['cliente'], ENT_QUOTES) ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($exp['morada'], ENT_QUOTES) ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($exp['estado'], ENT_QUOTES) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </main>
</body>
</html>
