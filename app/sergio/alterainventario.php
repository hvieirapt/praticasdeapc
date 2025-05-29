<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/styles.css">
    <title>Dashboard</title>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="inventario.html" class="current">Inventário</a></li>
                <li><a href="expedicoes.html">Expedições</a></li>
                <li><a href="dashboard.html">Dashboard</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <a href="javascript:history.back()" class="back-button">←</a>
        <h1>Inventário</h1>

        <form action="processar_inventario.php" method="post">
            <h2>Adicionar Novo Item</h2>
            <label for="sku">SKU:</label>
            <input type="text" id="sku" name="sku" required>
            <label for="descricao">Descrição:</label>
            <input type="text" id="descricao" name="descricao" required>
            <label for="lote">Lote:</label>
            <input type="text" id="lote" name="lote" required>
            <label for="quantidade">Quantidade:</label>
            <input type="number" id="quantidade" name="quantidade" required>
            <label for="unidade">Unidade:</label>
            <input type="text" id="unidade" name="unidade" required>
            <label for="estado">Estado:</label>
            <select id="estado" name="estado" required>
                <option value="1">Livre</option>
                <option value="0">Bloqueado</option>
            </select>
            <label for="sscc">SSCC:</label>
            <input type="text" id="sscc" name="sscc" required>
            <label for="localizacao">Localização:</label>
            <input type="text" id="localizacao" name="localizacao" required>
            <button type="submit" name="acao" value="adicionar">Adicionar Item</button>
        </form>

        <form action="processar_inventario.php" method="post">
            <h2>Remover Itens</h2>
            <table>
                <tr>
                    <th>Selecionar</th>
                    <th>SKU</th>
                    <th>Descrição</th>
                    <th>Lote</th>
                    <th>Quantidade</th>
                    <th>Unidade</th>
                    <th>Estado</th>
                    <th>SSCC</th>
                    <th>Localização</th>
                </tr>
                <?php
                $dbPath = '/data/stock.sqlite';
                $conn = new PDO("sqlite:$dbPath");
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $query = "SELECT * FROM stock";
                $stmt = $conn->query($query);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($rows as $row) {
                    echo "<tr>";
                    echo "<td><input type='checkbox' name='itens[]' value='" . $row['id'] . "'></td>";
                    echo "<td>" . htmlspecialchars($row['SKU']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Descrição']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Lote']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Quantidade']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Unidade']) . "</td>";
                    echo "<td>" . ($row['Estado'] ? 'Livre' : 'Bloqueado') . "</td>";
                    echo "<td>" . htmlspecialchars($row['SSCC']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Localização']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
            <button type="submit" name="acao" value="remover">Remover Itens Selecionados</button>
        </form>
    </main>
</body>
</html>

