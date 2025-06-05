<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$group = $_SESSION['grupo_permissoes'] ?? '';
if (!in_array($group, ['Administrador', 'Operador'], true)) {
    header('Location: 403.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventário</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <?php include __DIR__ . '/components/navbar.php'; ?>
    <main class="p-6 flex-grow">
        <h1 class="text-3xl font-semibold mb-8">Inventário</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <a href="consultainventario.php" class="bg-white rounded-lg shadow-lg hover:shadow-xl transition p-8 flex flex-col items-center">
                <img src="components/static/paletes.jpg" alt="Consultar Stock" class="w-40 h-40 object-cover mb-4 transform hover:scale-105 transition">
                <span class="text-xl font-medium">Consultar Stock</span>
            </a>
            <a href="alterainventario.php" class="bg-white rounded-lg shadow-lg hover:shadow-xl transition p-8 flex flex-col items-center">
                <img src="components/static/inventario.jpg" alt="Alterar Inventário" class="w-40 h-40 object-cover mb-4 transform hover:scale-105 transition">
                <span class="text-xl font-medium">Alterar Inventário</span>
            </a>
        </div>
    </main>
    <?php include __DIR__ . '/components/footer.php'; ?>
</body>
</html>
