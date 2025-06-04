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
        if ($id && $cliente !== '' && $morada !== '' && $data_entrega !== '') {
            $stmt = $db->prepare(
                'UPDATE expedicoes SET cliente = :cliente, morada = :morada, data_entrega = :entrega WHERE id = :id'
            );
            $stmt->execute([
                ':cliente' => $cliente,
                ':morada'  => $morada,
                ':entrega' => $data_entrega,
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

// --- Leitura de todas as expedições ---
$stmt = $db->query("SELECT * FROM expedicoes ORDER BY id DESC");
$expedicoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
