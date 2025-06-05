<?php
$group = $_SESSION['grupo_permissoes'] ?? '';
$menu = [];
if ($group === 'Administrador') {
    $menu = [
        'Dashboard'    => 'dashboard.php',
        'Expedições'   => 'expedicoes.php',
        'Inventário'   => 'inventario.php',
        'Registo'      => 'registo.php',
    ];
} elseif ($group === 'Operador') {
    $menu = [
        'Dashboard'  => 'dashboard.php',
        'Expedições' => 'expedicoes.php',
        'Inventário' => 'inventario.php',
    ];
} elseif ($group === 'Cliente') {
    $menu = [
        'Minhas Encomendas' => 'dashboard_cliente.php',
    ];
}
$current = basename($_SERVER['PHP_SELF']);
?>
<nav class="bg-gray-800 text-gray-100 shadow-md">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16 items-center">
      <div class="flex space-x-4">
        <?php foreach ($menu as $label => $link): 
            $active = $link === $current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white';
        ?>
          <a href="<?= $link ?>" class="px-3 py-2 rounded-md text-sm font-medium <?= $active ?>">
            <?= htmlspecialchars($label, ENT_QUOTES) ?>
          </a>
        <?php endforeach; ?>
      </div>
      <div class="flex items-center space-x-4">
        <span class="text-sm">Olá, <?= htmlspecialchars($_SESSION['username'] ?? '', ENT_QUOTES) ?></span>
        <a href="/logout.php" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm font-medium">
          Logout
        </a>
        <button id="infoBtn" class="px-3 py-2 bg-gray-600 hover:bg-gray-500 text-white rounded-md text-sm font-medium">
          ℹ️
        </button>
      </div>
    </div>
  </div>
</nav>

<!-- Info Modal -->
<div id="infoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
  <div class="bg-white rounded-lg shadow-lg max-w-md mx-auto p-6">
    <h2 class="text-xl font-semibold mb-4">Sobre o Projeto</h2>
    <p class="mb-2">Projeto P25 - Gestão Logística, uma aplicação web para gerir expedições e inventário.</p>
    <p class="mb-4">Sistema de gestão logística com roles Cliente, Operador e Administrador.</p>
    <p class="font-semibold">Grupo:</p>
    <ul class="list-disc list-inside mb-4">
      <li>1201734 - Guilherme Moreira</li>
      <li>1151392 - Hugo Vieira</li>
      <li>1230390 - Sergio Almeida</li>
    </ul>
    <button id="closeInfo" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Fechar</button>
  </div>
</div>
<script>
  document.getElementById('infoBtn').addEventListener('click', function() {
    document.getElementById('infoModal').classList.remove('hidden');
    document.getElementById('infoModal').classList.add('flex');
  });
  document.getElementById('closeInfo').addEventListener('click', function() {
    document.getElementById('infoModal').classList.add('hidden');
    document.getElementById('infoModal').classList.remove('flex');
  });
</script>