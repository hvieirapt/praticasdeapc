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

  <main class="p-4">
    <h1 class="text-2xl font-semibold mb-4">Tabela de Expedições</h1>

    <?php if (!empty($error)): ?>
      <p class="text-red-600 mb-4"><?= htmlspecialchars($error, ENT_QUOTES) ?></p>
    <?php endif; ?>

    <button id="openModal" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mb-4">Criar Expedição</button>

    <div id="expModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
      <div class="bg-white p-6 rounded w-full max-w-md">
        <h2 class="text-lg font-semibold mb-4">Nova Expedição</h2>
        <form method="post" action="expedicoes.php" class="space-y-4" id="createForm">
          <input type="hidden" name="action" value="criar">
          <div class="flex flex-col">
            <label for="cliente" class="mb-1">Cliente:</label>
            <input type="text" id="cliente" name="cliente" required class="border rounded p-2" />
          </div>
          <div class="flex flex-col">
            <label for="morada" class="mb-1">Morada:</label>
            <input type="text" id="morada" name="morada" required class="border rounded p-2" />
          </div>
          <div class="flex flex-col">
            <label for="data_entrega" class="mb-1">Data de Entrega:</label>
            <input type="date" id="data_entrega" name="data_entrega" required class="border rounded p-2" />
          </div>
          <div class="flex justify-end gap-2">
            <button type="button" id="closeModal" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Criar</button>
          </div>
        </form>
      </div>
    </div>

    <form method="post" action="expedicoes.php" id="approveForm">
      <input type="hidden" name="action" value="aprovar">
      <table class="min-w-full divide-y divide-gray-200 mt-4 table-auto">
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
                <td class="px-4 py-2 text-center"><input type="checkbox" name="aprovar_ids[]" value="<?= $exp['id'] ?>" class="row-checkbox" /></td>
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
      <button type="submit" id="approveBtn" class="mt-4 px-4 py-2 bg-green-500 text-white rounded disabled:opacity-50" disabled>Aprovar</button>
    </form>
  </main>

  <script>
    const modal = document.getElementById('expModal');
    document.getElementById('openModal').addEventListener('click', () => modal.classList.remove('hidden'));
    document.getElementById('closeModal').addEventListener('click', () => modal.classList.add('hidden'));

    const checkboxes = document.querySelectorAll('.row-checkbox');
    const approveBtn = document.getElementById('approveBtn');
    checkboxes.forEach(cb => cb.addEventListener('change', () => {
      approveBtn.disabled = document.querySelectorAll('.row-checkbox:checked').length === 0;
    }));
  </script>
</body>
</html>
