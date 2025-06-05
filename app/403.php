<?php
http_response_code(403);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Erro 403 - Acesso Negado</title>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="text-center p-6 bg-white rounded shadow-lg">
    <h1 class="text-6xl font-bold text-red-600 mb-4">403</h1>
    <p class="text-xl text-gray-700 mb-6">Acesso Negado.<br>Você não tem permissão para visualizar esta página.</p>
    <a href="login.php" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded">
      Voltar ao início
    </a>
  </div>
</body>
</html>