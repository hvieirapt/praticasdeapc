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
$dbPath = '/var/www/html/data/database.sqlite';
try {
    $conn = new PDO("sqlite:$dbPath");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Ensure stock table exists
    $conn->exec("CREATE TABLE IF NOT EXISTS stock (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        SKU TEXT NOT NULL,
        Descrição TEXT NOT NULL,
        Lote TEXT NOT NULL,
        Quantidade INTEGER NOT NULL,
        Unidade TEXT NOT NULL,
        Estado INTEGER NOT NULL,
        SSCC TEXT NOT NULL,
        Localização TEXT NOT NULL
    )");
    // Seed with initial data if empty
    $count = (int)$conn->query("SELECT COUNT(*) FROM stock")->fetchColumn();
    if ($count === 0) {
        $conn->exec("INSERT INTO stock (SKU, Descrição, Lote, Quantidade, Unidade, Estado, SSCC, Localização) VALUES
            ('PAL-001','Palete de Madeira','L001',80,'pcs',1,'SSCC0001','Zona A'),
            ('BOX-100','Caixa Pequena','B100',150,'pcs',1,'SSCC0100','Zona B'),
            ('DRM-200','Tambor 200L','D200',20,'uni',1,'SSCC0200','Zona C'),
            ('CRT-050','Caixote 50kg','C050',60,'uni',0,'SSCC0050','Zona D'),
            ('PAL-002','Palete de Plástico','L002',50,'pcs',1,'SSCC0002','Zona A')
        ");
    }
    $stmt = $conn->query("SELECT * FROM stock");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $livre = 0;
    $bloqueado = 0;
    foreach ($rows as $row) {
        if ($row['Estado']) {
            $livre += (int)$row['Quantidade'];
        } else {
            $bloqueado += (int)$row['Quantidade'];
        }
    }
} catch (PDOException $e) {
    $error = $e->getMessage();
    $rows = [];
    $livre = 0;
    $bloqueado = 0;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Consultar Inventário</title>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <?php include __DIR__ . '/components/navbar.php'; ?>
    <main class="p-6 flex-grow">
        <a href="inventario.php" class="inline-block text-gray-700 hover:text-gray-900 mb-4">&larr; Voltar</a>
        <h1 class="text-2xl font-semibold mb-6">Consultar Inventário</h1>
        <?php if (!empty($error)): ?>
            <p class="text-red-600 mb-4">Erro: <?= htmlspecialchars($error, ENT_QUOTES) ?></p>
        <?php endif; ?>
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full divide-y divide-gray-200 table-auto">
                <thead class="bg-gray-50">
                    <tr>
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
                <tbody class="bg-white divide-y divide-gray-200 text-center">
                    <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="8" class="px-4 py-2">Nenhum item em stock.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($rows as $row): ?>
                            <tr>
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
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-8">
            <canvas id="stockChart" class="w-64 h-64 bg-white rounded shadow p-4 mx-auto"></canvas>
        </div>
    </main>
    <?php include __DIR__ . '/components/footer.php'; ?>
    <script>
        const livre = <?= $livre ?>;
        const bloqueado = <?= $bloqueado ?>;
        const ctx = document.getElementById('stockChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Stock Livre', 'Stock Bloqueado'],
                datasets: [{
                    data: [livre, bloqueado],
                    backgroundColor: ['rgba(75, 192, 192, 0.2)','rgba(255, 99, 132, 0.2)'],
                    borderColor: ['rgba(75, 192, 192, 1)','rgba(255, 99, 132, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a,b)=>a+b,0);
                                const percent = Math.round((value/total)*100);
                                return `${label}: ${value} (${percent}%)`;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>