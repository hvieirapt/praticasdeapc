<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: /index.html');
    exit();
}

// Ligar base de dados
$db = new SQLite3('database.sqlite');
$cliente = $_SESSION['username'] ?? '';

// Buscar as encomendas deste cliente
$stmt = $db->prepare("SELECT id, produto, quantidade, data, status FROM expedicoes WHERE cliente = :cliente ORDER BY data DESC");
$stmt->bindValue(':cliente', $cliente, SQLITE3_TEXT);
$result = $stmt->execute();

$encomendas = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $encomendas[] = $row;
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
            <!-- <ul>
                <li><a href="inventario.html">Inventário</a></li>
                <li><a href="expedicoes.html">Expedições</a></li>
                <li><a href="dashboard.php" class="current">Dashboard</a></li>
            </ul> -->
        </nav>
    </header>


    <main>
        <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

            <h2>Minhas Encomendas</h2>
            <span>Filtrar por estado:</span>

            <div id="filtrosEstado">
                <button data-status="Pendente">Pendente</button>
                <button data-status="Em Expedição">Em Expedição</button>
                <button data-status="Entregue">Entregue</button>
                <button data-status="Todos">Todos</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Produto + Unidades</th>
                        <th>Data</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($encomendas as $enc): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($enc['id']); ?></td>
                            <td><?php echo htmlspecialchars($enc['produto']) . " (" . htmlspecialchars($enc['quantidade']) . ")"; ?></td>
                            <td><?php echo htmlspecialchars($enc['data']); ?></td>
                            <td><?php echo htmlspecialchars($enc['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <footer class="footer" style="text-align: center;">
            <p>© 2025 Projeto de Gestão Logística – ISEP</p>
        </footer>
    </main>
<script src="scripts/dashboard_cliente.js"></script>
</body>
</html>
