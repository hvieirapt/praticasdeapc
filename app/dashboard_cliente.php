<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}
$grupo = $_SESSION['grupo_permissoes'] ?? '';
if ($grupo !== 'Cliente') {
    header('Location: /403.php');
    exit();
}

// Ligar base de dados
$db = new SQLite3('/var/www/html/data/database.sqlite');

// Expedições de José Ferreira para demonstração
$expJose = [];
$resJose = $db->query("SELECT id, data_criacao AS data, data_entrega AS entrega, morada, estado FROM expedicoes WHERE cliente = 'José Ferreira' ORDER BY data_criacao DESC");
while ($r = $resJose->fetchArray(SQLITE3_ASSOC)) {
    $expJose[] = $r;
}

?>


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <script src="https://cdn.tailwindcss.com"></script>


</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <?php include __DIR__ . '/components/navbar.php'; ?>

        <main class="mb-20 w-full p-4">

        <!-- Seção Minhas Expedições (José Ferreira) movida para dentro de <main> -->
        <section class="p-4 mt-8">
            <h2 class="text-2xl font-semibold">Minhas Expedições:</h2>
            <div class="overflow-x-auto mt-4">
                <table class="w-full divide-y divide-gray-200 table-auto">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-center">ID</th>
                            <th class="px-4 py-2 text-center">Morada</th>
                            <th class="px-4 py-2 text-center">Previsão Entrega</th>
                            <th class="px-4 py-2 text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($expJose as $ej): ?>
                            <tr>
                                <td class="px-4 py-2 text-center"><?= htmlspecialchars($ej['id'], ENT_QUOTES) ?></td>
                                <td class="px-4 py-2 text-center"><?= htmlspecialchars($ej['morada'], ENT_QUOTES) ?></td>
                                <td class="px-4 py-2 text-center"><?= htmlspecialchars($ej['entrega'], ENT_QUOTES) ?></td>
                                <td class="px-4 py-2 text-center"><?= htmlspecialchars($ej['estado'], ENT_QUOTES) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <?php include __DIR__ . '/components/footer.php'; ?>

</body>
</html>
