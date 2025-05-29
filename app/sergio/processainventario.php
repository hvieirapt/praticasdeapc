<?php
$dbPath = '/data/stock.sqlite';
$conn = new PDO("sqlite:$dbPath");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {
        $stmt = $conn->prepare("INSERT INTO stock (SKU, Descrição, Lote, Quantidade, Unidade, Estado, SSCC, Localização) VALUES (:sku, :descricao, :lote, :quantidade, :unidade, :estado, :sscc, :localizacao)");
        $stmt->execute([
            ':sku' => $_POST['sku'],
            ':descricao' => $_POST['descricao'],
            ':lote' => $_POST['lote'],
            ':quantidade' => $_POST['quantidade'],
            ':unidade' => $_POST['unidade'],
            ':estado' => $_POST['estado'],
            ':sscc' => $_POST['sscc'],
            ':localizacao' => $_POST['localizacao']
        ]);
    } elseif (isset($_POST['acao']) && $_POST['acao'] === 'remover' && isset($_POST['itens'])) {
        $ids = implode(',', array_map('intval', $_POST['itens']));
        $conn->exec("DELETE FROM stock WHERE id IN ($ids)");
    }
}

header("Location: inventario.html");
exit;
?>

