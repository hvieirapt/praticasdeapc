document.addEventListener('DOMContentLoaded', () => {
  // --- Pie Chart ---
  const pieCanvas = document.getElementById('pieChart').getContext('2d');
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
        },
        title: {
          display: true,
          text: 'Distribuição de Estados'
        }
      }
    }
  });

  // --- Line Chart ---
  const lineCanvas = document.getElementById('lineChart').getContext('2d');
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
        },
        title: {
          display: true,
          text: 'Entregas Últimos 30 Dias'
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

  // --- Logout Button ---
  const logoutButton = document.getElementById('logoutButton');
  if (logoutButton) {
    logoutButton.addEventListener('click', event => {
      event.preventDefault();
      if (confirm('Queres terminar a sessão?')) {
        window.location.href = '/logout.php';
      }
    });
  }

  // --- Filtro de Encomendas ---
  function filtrarEncomendas() {
    const estadoSelecionado = document.getElementById('estadoFilter').value;
    const tbody = document.querySelector("tbody");
    tbody.innerHTML = ""; // Limpa a tabela

    let filtradas = encomendas;
    if (estadoSelecionado !== '') {
      filtradas = encomendas.filter(e => e.estado === estadoSelecionado);
    }

    const ultimas5 = filtradas.slice(0, 5);

    if (ultimas5.length === 0) {
      const tr = document.createElement("tr");
      tr.innerHTML = `<td colspan="4" class="px-4 py-2 text-center text-gray-500">Nenhuma encomenda encontrada.</td>`;
      tbody.appendChild(tr);
    } else {
      ultimas5.forEach(encomenda => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td class="px-4 py-2 text-center">${encomenda.id}</td>
          <td class="px-4 py-2 text-center">${encomenda.cliente}</td>
          <td class="px-4 py-2 text-center">${encomenda.data_criacao}</td>
          <td class="px-4 py-2 text-center">${encomenda.estado}</td>
        `;
        tbody.appendChild(tr);
      });
    }
  }

  filtrarEncomendas();

  // Event Listener no botão
  const filtroButton = document.querySelector('button[onclick="filtrarEncomendas()"]');
  if (filtroButton) {
    filtroButton.addEventListener('click', filtrarEncomendas);
  }
});
