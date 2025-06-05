<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$group = $_SESSION['grupo_permissoes'] ?? '';
if (!in_array($group, ['Administrador','Operador'], true)) {
    header('Location: 403.php');
    exit();
}
$dbPath = '/var/www/html/data/database.sqlite';
$conn = new PDO("sqlite:$dbPath");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$items = $conn->query("SELECT * FROM stock ORDER BY SKU")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Alterar Inventário</title>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
  <?php include __DIR__ . '/components/navbar.php'; ?>
  <main class="flex-grow p-6 max-w-5xl mx-auto">
    <div class="flex items-center mb-6">
      <a href="inventario.php" class="text-gray-600 hover:text-gray-800">&larr; Voltar</a>
      <h1 class="text-2xl font-semibold ml-4">Alterar Inventário</h1>
    </div>
    <button id="toggleAdd" class="mb-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Adicionar Novo Item</button>
    <div id="addForm" class="hidden bg-white p-6 rounded shadow mb-8">
      <form action="processainventario.php" method="post" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <input type="hidden" name="acao" value="adicionar">
        <div>
          <label class="block text-sm font-medium mb-1">SKU</label>
          <input name="sku" required class="w-full border border-gray-300 rounded p-2" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Descrição</label>
          <input name="descricao" required class="w-full border border-gray-300 rounded p-2" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Lote</label>
          <input name="lote" required class="w-full border border-gray-300 rounded p-2" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Quantidade</label>
          <input type="number" name="quantidade" required class="w-full border border-gray-300 rounded p-2" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Unidade</label>
          <input name="unidade" required class="w-full border border-gray-300 rounded p-2" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Estado</label>
          <select name="estado" required class="w-full border border-gray-300 rounded p-2">
            <option value="1">Livre</option>
            <option value="0">Bloqueado</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">SSCC</label>
          <input name="sscc" required class="w-full border border-gray-300 rounded p-2" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Localização</label>
          <input name="localizacao" required class="w-full border border-gray-300 rounded p-2" />
        </div>
        <div class="md:col-span-2 flex justify-end">
          <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Adicionar Item</button>
        </div>
      </form>
    </div>
    <form action="processainventario.php" method="post" class="bg-white p-6 rounded shadow">
      <input type="hidden" name="acao" value="remover">
      <h2 class="text-xl font-semibold mb-4">Itens em Stock</h2>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 table-auto">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-2"><input type="checkbox" id="selectAll" /></th>
              <th class="px-4 py-2">SKU</th>
              <th class="px-4 py-2">Descrição</th>
              <th class="px-4 py-2">Lote</th>
              <th class="px-4 py-2">Quantidade</th>
              <th class="px-4 py-2">Unidade</th>
              <th class="px-4 py-2">Estado</th>
              <th class="px-4 py-2">SSCC</th>
              <th class="px-4 py-2">Localização</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($items as $row): ?>
            <tr>
              <td class="px-4 py-2 text-center"><input type="checkbox" name="itens[]" value="<?= $row['id'] ?>" class="itemCheckbox" /></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['SKU'], ENT_QUOTES) ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['Descrição'], ENT_QUOTES) ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['Lote'], ENT_QUOTES) ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['Quantidade'], ENT_QUOTES) ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['Unidade'], ENT_QUOTES) ?></td>
              <td class="px-4 py-2"><?= $row['Estado'] ? 'Livre' : 'Bloqueado' ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['SSCC'], ENT_QUOTES) ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['Localização'], ENT_QUOTES) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="mt-4 flex justify-end">
        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Remover Selecionados</button>
      </div>
    </form>
  </main>
  <?php include __DIR__ . '/components/footer.php'; ?>
  <script>
    document.getElementById('toggleAdd').addEventListener('click', () => {
      document.getElementById('addForm').classList.toggle('hidden');
    });
    document.getElementById('selectAll').addEventListener('change', function() {
      document.querySelectorAll('.itemCheckbox').forEach(cb => cb.checked = this.checked);
    });
  </script>
</body>
</html>