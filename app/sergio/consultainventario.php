<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
        <h1>Inventario</h1>
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="border: 1px solid #ccc;">
                <td style="padding: 10px;">SKU</td>
                <td style="padding: 10px;">Descrição</td>
                <td style="padding: 10px;">Lote</td>
                <td style="padding: 10px;">Quantidade</td>
                <td style="padding: 10px;">Unidade</td>
                <td style="padding: 10px;">Estado</td>
                <td style="padding: 10px;">SSCC</td>
                <td style="padding: 10px;">Localização</td>
            </tr>
            <?php
            $dbPath = '/data/stock.sqlite';

            try {
                $conn = new PDO("sqlite:$dbPath");

                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = "SELECT * FROM stock";
                $stmt = $conn->query($query);

                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($rows as $row) {
                    echo "<tr>";
                    echo "<td style='padding: 10px;'>" . htmlspecialchars($row['SKU']) . "</td>";
                    echo "<td style='padding: 10px;'>" . htmlspecialchars($row['Descrição']) . "</td>";
                    echo "<td style='padding: 10px;'>" . htmlspecialchars($row['Lote']) . "</td>";
                    echo "<td style='padding: 10px;'>" . htmlspecialchars($row['Quantidade']) . "</td>";
                    echo "<td style='padding: 10px;'>" . htmlspecialchars($row['Unidade']) . "</td>";
                    echo "<td style='padding: 10px;'>" . ($row['Estado'] ? 'Livre' : 'Bloqueado') . "</td>";
                    echo "<td style='padding: 10px;'>" . htmlspecialchars($row['SSCC']) . "</td>";
                    echo "<td style='padding: 10px;'>" . htmlspecialchars($row['Localização']) . "</td>";
                    echo "</tr>";
                }
            } catch (PDOException $e) {
                echo "<tr><td colspan='8' style='padding: 10px; color: red;'>Erro: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
            } finally {
                $conn = null;
            }
            ?>
        </table>
    </main>
</body>
</html>

