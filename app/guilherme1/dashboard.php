<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: /index.html');
    exit();
}

// Liga à base de dados
$db = new SQLite3('database.sqlite');

// Expedições Pendentes
$res = $db->query("SELECT COUNT(*) AS total FROM expedicoes WHERE status = 'Pendente'");
$row = $res->fetchArray();
$expedicoes_pendentes = $row['total'] ?? 0;

// Entregas Realizadas
$res = $db->query("SELECT COUNT(*) AS total FROM expedicoes WHERE status = 'Entregue'");
$row = $res->fetchArray();
$entregas_realizadas = $row['total'] ?? 0;

// Stock Crítico (quantidade < 5)
$res = $db->query("SELECT COUNT(*) AS total FROM stock WHERE quantidade < 5");
$row = $res->fetchArray();
$stock_critico = $row['total'] ?? 0;


// Últimas Encomendas (ex: últimos 5 registos)
$res = $db->query("SELECT id, cliente, data, status FROM expedicoes ORDER BY data DESC LIMIT 5");
$ultimas_encomendas = [];
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $ultimas_encomendas[] = $row;
}

// Produtos com Stock Crítico (quantidade < 5)
$res = $db->query("SELECT produto, quantidade FROM stock WHERE quantidade < 5");
$produtos_stock_critico = [];
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $produtos_stock_critico[] = $row;
}

?>


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Gestão Logística</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="style/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
    <header>
        <button id="logoutButton">Logout</button>
        <nav>
            <ul>
                <li><a href="inventario.html">Inventário</a></li>
                <li><a href="expedicoes.html">Expedições</a></li>
                <li><a href="dashboard.php" class="current">Dashboard</a></li>
            </ul>
        </nav>
    </header>


    <main>
        <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

        <button id="toggleGrafico">Ocultar Gráfico</button>

        <div id="grafico">
            <canvas id="kpiChart"></canvas>
        </div>

                <div class="table-container">
                    <h3>Últimas Encomendas</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Data</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ultimas_encomendas as $encomenda): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($encomenda['id']); ?></td>
                                    <td><?php echo htmlspecialchars($encomenda['cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($encomenda['data']); ?></td>
                                    <td><?php echo htmlspecialchars($encomenda['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>


                <div class="table-container">
                    <h3>Produtos com Stock Crítico</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produtos_stock_critico as $produto): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($produto['produto']); ?></td>
                                    <td><?php echo htmlspecialchars($produto['quantidade']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

        </section>

        <footer class="footer" style="text-align: center;">
            <p>© 2025 Projeto de Gestão Logística – ISEP</p>
        </footer>
    </main>


<script>
    const expedicoesPendentes = <?php echo $expedicoes_pendentes; ?>;
    const entregasRealizadas = <?php echo $entregas_realizadas; ?>;
    const stockCritico = <?php echo $stock_critico; ?>;
</script>
<script src="scripts/dashboard.js"></script>

</body>
</html>
