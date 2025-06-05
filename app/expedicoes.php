<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$grupo = $_SESSION['grupo_permissoes'] ?? '';
if (!in_array($grupo, ['Administrador', 'Operador'], true)) {
    header('Location: 403.php');
    exit();
}
require_once __DIR__ . "/scripts/expedicoes.php";
$error       = $error       ?? '';
$expedicoes  = $expedicoes  ?? [];
$estados     = $estados     ?? [];
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Expedições</title>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
  <?php include __DIR__ . '/components/navbar.php'; ?>


  <main class="p-4 flex-grow">
    <?php if (!empty($error)): ?>
      <p class="text-red-600 mb-4"><?= htmlspecialchars($error, ENT_QUOTES) ?></p>
    <?php endif; ?>

    <div class="flex items-center justify-between mb-4">
      <button id="openCreate" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Criar Expedição</button>
      <h1 class="text-2xl font-semibold">Tabela de Expedições</h1>
    </div>

    <form method="get" class="mb-4 flex flex-wrap gap-4 items-end">
      <div class="flex flex-col">
        <label for="date_from" class="mb-1">Data de Entrega Inicial:</label>
        <input type="date" id="date_from" name="date_from" value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>" class="border border-gray-300 rounded p-2" />
      </div>
      <div class="flex flex-col">
        <label for="date_to" class="mb-1">Data de Entrega Final:</label>
        <input type="date" id="date_to" name="date_to" value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>" class="border border-gray-300 rounded p-2" />
      </div>
      <div class="flex flex-col">
        <label for="estado" class="mb-1">Estado:</label>
        <select id="estado" name="estado" class="border border-gray-300 rounded p-2">
          <option value="">Todos</option>
          <?php foreach ($estados as $op): ?>
            <option value="<?= htmlspecialchars($op, ENT_QUOTES) ?>" <?= (($_GET['estado'] ?? '') === $op) ? 'selected' : '' ?>><?= htmlspecialchars($op) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="flex items-end">
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Filtrar</button>
      </div>
    </form>

    <table class="min-w-full divide-y divide-gray-200 mt-4 table-auto">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-2 text-center">Ações</th>
            <th class="px-4 py-2 text-center">ID</th>
            <th class="px-4 py-2 text-center">Data de Criação</th>
            <th class="px-4 py-2 text-center">Data de Entrega</th>
            <th class="px-4 py-2 text-center">Cliente</th>
            <th class="px-4 py-2 text-center">Morada</th>
            <th class="px-4 py-2 text-center">Estado</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <?php if (empty($expedicoes)): ?>
            <tr><td colspan="7" class="px-4 py-2 text-center">Não há expedições registadas.</td></tr>
          <?php else: ?>
            <?php foreach ($expedicoes as $exp): ?>
              <tr>
                <td class="px-4 py-2 text-center">
                  <button type="button" class="edit-btn text-blue-600 underline" data-id="<?= $exp['id'] ?>" data-cliente="<?= htmlspecialchars($exp['cliente'], ENT_QUOTES) ?>" data-morada="<?= htmlspecialchars($exp['morada'], ENT_QUOTES) ?>" data-entrega="<?= htmlspecialchars($exp['data_entrega'], ENT_QUOTES) ?>" data-estado="<?= htmlspecialchars($exp['estado'], ENT_QUOTES) ?>">
                    Editar
                  </button>
                </td>
                <td class="px-4 py-2 text-center"><?= htmlspecialchars($exp['id'], ENT_QUOTES) ?></td>
                <td class="px-4 py-2 text-center"><?= htmlspecialchars($exp['data_criacao'], ENT_QUOTES) ?></td>
                <td class="px-4 py-2 text-center"><?= htmlspecialchars($exp['data_entrega'], ENT_QUOTES) ?></td>
                <td class="px-4 py-2 text-center"><?= htmlspecialchars($exp['cliente'], ENT_QUOTES) ?></td>
                <td class="px-4 py-2 text-center"><?= htmlspecialchars($exp['morada'], ENT_QUOTES) ?></td>
                <td class="px-4 py-2 text-center"><?= htmlspecialchars($exp['estado'], ENT_QUOTES) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
      <?php if ($totalPages > 1): ?>
      <div class="mt-4 flex items-center justify-center">
        <label for="page_select" class="mr-2">Página:</label>
        <select id="page_select" class="border rounded p-2" onchange="const params = new URLSearchParams(window.location.search); params.set('page', this.value); window.location.search = params;">
          <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <option value="<?= $p ?>" <?= ($p === $page) ? 'selected' : '' ?>><?= $p ?></option>
          <?php endfor; ?>
        </select>
      </div>
      <?php endif; ?>
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
      <div class="bg-white p-6 rounded w-full max-w-md">
        <h2 class="text-lg font-semibold mb-4">Editar Expedição</h2>
        <form method="post" action="expedicoes.php" class="space-y-4" id="editForm" novalidate>
          <input type="hidden" name="id" id="edit_id">
          <div class="flex flex-col">
            <label for="edit_cliente" class="mb-1">Cliente:</label>
            <input type="text" id="edit_cliente" name="cliente" required class="border border-gray-300 rounded p-2" />
          </div>
          <div class="flex flex-col">
            <label for="edit_morada" class="mb-1">Morada:</label>
            <input type="text" id="edit_morada" name="morada" required class="border border-gray-300 rounded p-2" />
          </div>
          <div class="flex flex-col">
            <label for="edit_entrega" class="mb-1">Data de Entrega:</label>
            <input type="date" id="edit_entrega" name="data_entrega" required class="border border-gray-300 rounded p-2" />
          </div>
          <div class="flex flex-col">
            <label for="edit_estado" class="mb-1">Estado:</label>
            <select id="edit_estado" name="estado" class="border border-gray-300 rounded p-2">
              <?php foreach ($estados as $op): ?>
                <option value="<?= htmlspecialchars($op, ENT_QUOTES) ?>">
                  <?= htmlspecialchars($op) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="flex justify-between gap-2 pt-2">
            <button type="submit" name="action" value="atualizar" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Guardar</button>
            <button type="submit" name="action" value="aprovar" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Aprovar</button>
            <button type="submit" name="action" value="apagar" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Apagar</button>
            <button type="button" id="closeEdit" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
          </div>
        </form>
      </div>
    </div>
  </main>
  <?php include __DIR__ . '/components/footer.php'; ?>
<script src="scripts/validate.js"></script>
</body>
</html>
