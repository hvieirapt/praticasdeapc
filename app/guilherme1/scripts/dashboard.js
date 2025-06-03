document.addEventListener("DOMContentLoaded", function () {
    const toggleButton = document.getElementById("toggleGrafico");
    const graficoDiv = document.getElementById("grafico");


    inicializarGrafico();
    graficoDiv.style.display = "block";

    toggleButton.addEventListener("click", function () {
        if (graficoDiv.style.display === "none" || graficoDiv.style.display === "") {
            graficoDiv.style.display = "block";
        } else {
            graficoDiv.style.display = "none";
        }
    });

    function inicializarGrafico() {
        const ctx = document.getElementById('kpiChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    'Expedições Pendentes',
                    'Entregas Realizadas Hoje',
                    'Produtos em Stock Crítico'
                ],
                datasets: [{
                    label: 'Quantidade',
                    data: [
                        expedicoesPendentes,
                        entregasRealizadas,
                        stockCritico
                    ],
                    backgroundColor: [
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 99, 132, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
});


document.addEventListener("DOMContentLoaded", function () {
    const logoutButton = document.getElementById("logoutButton");

    if (logoutButton) {
        logoutButton.addEventListener("click", function (event) {
            event.preventDefault();
            if (confirm("Queres terminar a sessão?")) {
                window.location.href = "scripts/logout.php";
            }
        });
    }
});

