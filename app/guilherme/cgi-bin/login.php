<?php
session_start();

// Captura os dados enviados pelo formulário
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Conecta à base de dados SQLite
$db = new SQLite3('../../data/database.sqlite');

// Prepara e executa a query
$stmt = $db->prepare('SELECT * FROM utilizadores WHERE username = :username AND password = :password');
$stmt->bindValue(':username', $username, SQLITE3_TEXT);
$stmt->bindValue(':password', $password, SQLITE3_TEXT);
$result = $stmt->execute();
$user = $result->fetchArray();

// Verifica se encontrou utilizador
if ($user) {
    $_SESSION['username'] = $user['username'];
    header('Location: dashboard.php');
    exit();
} else {
    echo "<p>Login inválido. <a href='login.html'>Voltar</a></p>";
}
?>
