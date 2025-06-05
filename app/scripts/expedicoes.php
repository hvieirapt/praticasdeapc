<?php
// Script de gestão de expedições
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

$estados = [
    'pendente aprovação',
    'em processamento',
    'concluída',
    'cancelada'
];

// --- Tratamento de pedidos POST ---
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'criar') {
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
                ':estado'       => 'pendente aprovação',
            ]);
            header('Location: expedicoes.php');
            exit;
        }
    } elseif ($action === 'atualizar') {
        $id          = (int)($_POST['id'] ?? 0);
        $cliente      = trim((string)($_POST['cliente'] ?? ''));
        $morada       = trim((string)($_POST['morada'] ?? ''));
        $data_entrega = trim((string)($_POST['data_entrega'] ?? ''));
        $estado       = trim((string)($_POST['estado'] ?? ''));
        if ($id && $cliente !== '' && $morada !== '' && $data_entrega !== '' && $estado !== '') {
            $stmt = $db->prepare(
                'UPDATE expedicoes SET cliente = :cliente, morada = :morada, data_entrega = :entrega, estado = :estado WHERE id = :id'
            );
            $stmt->execute([
                ':cliente' => $cliente,
                ':morada'  => $morada,
                ':entrega' => $data_entrega,
                ':estado'  => $estado,
                ':id'      => $id,
            ]);
        }
        header('Location: expedicoes.php');
        exit;
    } elseif ($action === 'aprovar') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            $stmt = $db->prepare("UPDATE expedicoes SET estado = 'em processamento' WHERE id = :id");
            $stmt->execute([':id' => $id]);
        }
        header('Location: expedicoes.php');
        exit;
    } elseif ($action === 'apagar') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            $stmt = $db->prepare('DELETE FROM expedicoes WHERE id = :id');
            $stmt->execute([':id' => $id]);
        }
        header('Location: expedicoes.php');
        exit;
    }
}

// --- Filtros e Paginação ---
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;
$where = [];
$params = [];
if (!empty($_GET['estado'])) {
    $where[] = 'estado = :estado';
    $params[':estado'] = $_GET['estado'];
}
if (!empty($_GET['date_from'])) {
    $where[] = 'date(data_entrega) >= :date_from';
    $params[':date_from'] = $_GET['date_from'];
}
if (!empty($_GET['date_to'])) {
    $where[] = 'date(data_entrega) <= :date_to';
    $params[':date_to'] = $_GET['date_to'];
}
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
// Contar total de expedições
$countSql = "SELECT COUNT(*) FROM expedicoes $where_sql";
$countStmt = $db->prepare($countSql);
foreach ($params as $key => $value) {
    $countStmt->bindValue($key, $value);
}
$countStmt->execute();
$total = (int)$countStmt->fetchColumn();
$totalPages = (int)ceil($total / $limit);
// Carregar expedições com filtros e paginação
$sql = "SELECT * FROM expedicoes $where_sql ORDER BY id DESC LIMIT :limit OFFSET :offset";
$stmt = $db->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$expedicoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- Disponibilizar páginas para o view ---
$pagination = [
    'current' => $page,
    'total'   => $totalPages
];
