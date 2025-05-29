<?php
declare(strict_types=1);

// --- Configuração do SQLite e migrations ---
$dbPath = '/var/www/html/data/database.sqlite';
if (!is_dir(dirname($dbPath))) {
    mkdir(dirname($dbPath), 0777, true);
    chmod(dirname($dbPath), 0777);
}
if (!file_exists($dbPath)) {
    touch($dbPath);
    chmod($dbPath, 0666);
}

try {
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec('
        PRAGMA foreign_keys = ON;
        CREATE TABLE IF NOT EXISTS User (
          id INTEGER PRIMARY KEY AUTOINCREMENT,
          username TEXT NOT NULL UNIQUE,
          password TEXT NOT NULL,
          ultimo_login  DATE
        );
        CREATE TABLE IF NOT EXISTS expedicoes (
          id INTEGER PRIMARY KEY AUTOINCREMENT,
          data_criacao DATE NOT NULL,
          data_entrega DATE NOT NULL,
          cliente TEXT NOT NULL,
          morada TEXT NOT NULL,
          estado TEXT NOT NULL
        );
    ');
} catch (PDOException $e) {
    die("Erro na conexão com a base de dados ({$dbPath}): " . $e->getMessage());
}

// --- Inserção de nova expedição via POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente      = trim((string)($_POST['cliente'] ?? ''));
    $morada       = trim((string)($_POST['morada'] ?? ''));
    $data_entrega = trim((string)($_POST['data_entrega'] ?? ''));

    if ($cliente === '' || $morada === '' || $data_entrega === '') {
        $error = "Por favor, preencha todos os campos.";
    } else {
        $stmt = $db->prepare(
            "INSERT INTO expedicoes
             (data_criacao, data_entrega, cliente, morada, estado)
             VALUES (:data_criacao, :data_entrega, :cliente, :morada, :estado)"
        );
        $stmt->execute([
            ':data_criacao' => date('Y-m-d'),
            ':data_entrega' => $data_entrega,
            ':cliente'      => $cliente,
            ':morada'       => $morada,
            ':estado'       => 'em processamento',
        ]);
        header('Location: expedicoes.php');
        exit;
    }
}

// --- Leitura de todas as expedições ---
$stmt = $db->query("SELECT * FROM expedicoes ORDER BY id DESC");
$expedicoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style/styles.css" />
  <title>Expedições</title>
</head>
<body>
  <header>
    <nav>
      <ul class="main-nav">
        <li><a href="inventario.php">Inventário</a></li>
        <li><a href="expedicoes.php" class="current">Expedições</a></li>
        <li><a href="dashboard.php">Dashboard</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <h1 class="page-title">Tabela de Expedições</h1>

    <?php if (!empty($error)): ?>
      <p class="highlight"><?= htmlspecialchars($error, ENT_QUOTES) ?></p>
    <?php endif; ?>

    <!-- Formulário no topo -->
    <section class="new-expedicao">
      <h2 class="page-title">Adicionar Nova Expedição</h2>
      <form method="post" action="expedicoes.php">
        <div class="form-group">
          <label for="cliente">Cliente:</label>
          <input type="text" id="cliente" name="cliente" required />
        </div>
        <div class="form-group">
          <label for="morada">Morada:</label>
          <input type="text" id="morada" name="morada" required />
        </div>
        <div class="form-group">
          <label for="data_entrega">Data de Entrega:</label>
          <input type="date" id="data_entrega" name="data_entrega" required />
        </div>
        <button type="submit">Criar Expedição</button>
      </form>
    </section>

    <table class="exp-table">
      <thead>
        <tr>
          <th>Selecionar</th>
          <th>Data de Criação</th>
          <th>Data de Entrega</th>
          <th>Cliente</th>
          <th>Morada</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($expedicoes)): ?>
          <tr><td colspan="6">Não há expedições registadas.</td></tr>
        <?php else: ?>
          <?php foreach ($expedicoes as $exp): ?>
            <tr>
              <td><input type="checkbox" /></td>
              <td><?= htmlspecialchars($exp['data_criacao'], ENT_QUOTES) ?></td>
              <td><?= htmlspecialchars($exp['data_entrega'], ENT_QUOTES) ?></td>
              <td><?= htmlspecialchars($exp['cliente'], ENT_QUOTES) ?></td>
              <td><?= htmlspecialchars($exp['morada'], ENT_QUOTES) ?></td>
              <td><?= htmlspecialchars($exp['estado'], ENT_QUOTES) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </main>
</body>
</html>
