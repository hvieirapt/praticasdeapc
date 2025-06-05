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
      </div>
    </div>
  </div>
</nav>