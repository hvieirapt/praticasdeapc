document.addEventListener('DOMContentLoaded', () => {
  const pieCanvas = document.getElementById('pieChart').getContext('2d');
  const lineCanvas = document.getElementById('lineChart').getContext('2d');

  const pieChart = new Chart(pieCanvas, {
    type: 'pie',
    data: {
      labels: ['Pendente Aprovação', 'Em Processamento', 'Concluída', 'Cancelada'],
      datasets: [{
        data: [expedicoesPendentes, expedicoesEmProcessamento, entregasRealizadas, expedicoesCanceladas],
        backgroundColor: [
          'rgba(255, 206, 86, 0.7)',
          'rgba(54, 162, 235, 0.7)',
          'rgba(75, 192, 192, 0.7)',
          'rgba(255, 99, 132, 0.7)'
        ],
        borderColor: [
          'rgba(255, 206, 86, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(255, 99, 132, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: 'bottom',
          labels: { boxWidth: 20, padding: 10 }
        }
      }
    }
  });

  const lineChart = new Chart(lineCanvas, {
    type: 'line',
    data: {
      labels: diasUltimos30,
      datasets: [{
        label: 'Entregas Concluídas',
        data: entregasPorDia,
        borderColor: 'rgba(75, 192, 192, 1)',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        fill: true,
        tension: 0.4
      }]
    },
    options: {
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: 'bottom',
          labels: { boxWidth: 20, padding: 10 }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { stepSize: 1 }
        }
      }
    }
  });

  const logoutButton = document.getElementById('logoutButton');
  if (logoutButton) {
    logoutButton.addEventListener('click', event => {
      event.preventDefault();
      if (confirm('Queres terminar a sessão?')) {
        window.location.href = '/logout.php';
      }
    });
  }
});