<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Gestão Logística</title>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
  <header class="bg-gray-800 text-gray-100">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center space-x-4">
        <a href="#" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Home</a>
        <a href="#" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Quem Somos</a>
        <a href="#" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Contactos</a>
        <a href="dashboard_cliente.php" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Seguir Expedições</a>
        <a href="login.php" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Login</a>
      </div>
    </nav>
  </header>
  <main class="flex-grow container mx-auto p-4 text-center">
    <h1 class="text-3xl font-bold mb-4">Bem-vindo à nossa empresa de Logística</h1>
    <p class="text-gray-700 mb-6">Soluções integradas de transporte e armazenamento para otimizar o seu negócio.</p>
    <img src="components/static/paletes.jpg" alt="Operações de logística" class="mx-auto w-full max-w-xl rounded-lg shadow-md">
  </main>
  <?php include __DIR__ . '/components/footer.php'; ?>
</body>
</html>
