<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}
$username = $_SESSION['username'] ?? '';
$grupo = $_SESSION['grupo_permissoes'] ?? '';
if (!in_array($grupo, ['Administrador','Operador'], true)) {
    header('Location: /403.php');
    exit();
}

// Liga à base de dados
$db = new SQLite3('/var/www/html/data/database.sqlite');

// Dados principais para o dashboard
$res = $db->query("SELECT COUNT(*) AS total FROM expedicoes WHERE estado = 'pendente aprovação'");
$row = $res->fetchArray();
$expedicoes_pendentes = $row['total'] ?? 0;

$res = $db->query("SELECT COUNT(*) AS total FROM expedicoes WHERE estado = 'concluída'");
$row = $res->fetchArray();
$entregas_realizadas = $row['total'] ?? 0;

$res = $db->query("SELECT COUNT(*) AS total FROM expedicoes WHERE estado = 'em processamento'");
$row = $res->fetchArray();
$expedicoes_em_processamento = $row['total'] ?? 0;

$res = $db->query("SELECT COUNT(*) AS total FROM expedicoes WHERE estado = 'cancelada'");
$row = $res->fetchArray();
$expedicoes_canceladas = $row['total'] ?? 0;

// Entregas por dia nos últimos 30 dias
$entregasPorDia = [];
$diasUltimos30 = [];
for ($i = 29; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-{$i} days"));
    $label = date('d/m', strtotime("-{$i} days"));
    $res = $db->query("SELECT COUNT(*) AS total FROM expedicoes WHERE estado = 'concluída' AND date(data_entrega) = '$date'");
    $row = $res->fetchArray();
    $entregasPorDia[] = $row['total'] ?? 0;
    $diasUltimos30[] = $label;
}
$entregas_por_dia_json = json_encode($entregasPorDia);
$dias_do_mes_json = json_encode($diasUltimos30);

// Buscar últimas 50 encomendas para filtragem no frontend
$res = $db->query("SELECT id, cliente, data_criacao, estado FROM expedicoes ORDER BY data_criacao DESC LIMIT 50");
$ultimas_encomendas = [];
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $ultimas_encomendas[] = $row;
}
$ultimas_encomendas_json = json_encode($ultimas_encomendas);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <?php include __DIR__ . '/components/navbar.php'; ?>

    <main class="p-4 flex-grow">
        <div id="grafico" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1 h-64">
                <canvas id="pieChart" class="w-full h-full"></canvas>
            </div>
            <div class="md:col-span-2 h-64">
                <canvas id="lineChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <!-- Filtro de Estado -->
        <div class="mb-4 flex flex-wrap gap-4 items-end">
            <div class="flex flex-col">
                <label for="estadoFilter" class="mb-1">Estado:</label>
                <select id="estadoFilter" class="border border-gray-300 rounded p-2">
                    <option value="">Todos</option>
                    <option value="pendente aprovação">Pendente Aprovação</option>
                    <option value="em processamento">Em Processamento</option>
                    <option value="concluída">Concluída</option>
                    <option value="cancelada">Cancelada</option>
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="filtrarEncomendas()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Filtrar</button>
            </div>
        </div>

        <!-- Tabela de Últimas Encomendas -->
        <div class="table-container">
            <h2 class="text-2xl font-semibold">Últimas 5 Encomendas:</h2>
            <table class="min-w-full divide-y divide-gray-200 mt-4 table-auto text-center">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-center">ID</th>
                        <th class="px-4 py-2 text-center">Cliente</th>
                        <th class="px-4 py-2 text-center">Data Entrada</th>
                        <th class="px-4 py-2 text-center">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-center">
                    <!-- Linhas preenchidas dinamicamente pelo JS -->
                </tbody>
            </table>
        </div>
    </main>

    <?php include __DIR__ . '/components/footer.php'; ?>

    <script>
        const expedicoesPendentes = <?php echo $expedicoes_pendentes; ?>;
        const expedicoesEmProcessamento = <?php echo $expedicoes_em_processamento; ?>;
        const expedicoesCanceladas = <?php echo $expedicoes_canceladas; ?>;
        const entregasRealizadas = <?php echo $entregas_realizadas; ?>;
        const diasUltimos30 = <?php echo $dias_do_mes_json; ?>;
        const entregasPorDia = <?php echo $entregas_por_dia_json; ?>;

        const encomendas = <?php echo $ultimas_encomendas_json; ?>;

    </script>

    <script src="scripts/dashboard.js"></script>
    <script>
      if (typeof Chart !== 'undefined' && Chart.defaults.plugins) {
        Chart.defaults.plugins.legend.display = true;
      }
    </script>
</body>
</html>
