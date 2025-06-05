document.addEventListener("DOMContentLoaded", function () {
    const botoesFiltro = document.querySelectorAll("#filtrosEstado button");
    const linhas = document.querySelectorAll("table tbody tr");

    botoesFiltro.forEach(function (botao) {
        botao.addEventListener("click", function () {
            const estadoFiltrar = botao.getAttribute("data-status").toLowerCase();

            linhas.forEach(function (linha) {
                const statusCelula = linha.querySelector("td:last-child");
                if (statusCelula) {
                    const statusTexto = statusCelula.textContent.toLowerCase();
                    if (estadoFiltrar === "todos" || statusTexto === estadoFiltrar) {
                        linha.style.display = "";
                    } else {
                        linha.style.display = "none";
                    }
                }
            });
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const logoutButton = document.getElementById("logoutButton");

    if (logoutButton) { // Para evitar erros se o botão não existir em todas as páginas
        logoutButton.addEventListener("click", function (event) {
            event.preventDefault();
            if (confirm("Queres terminar a sessão?")) {
                window.location.href = "scripts/logout.php";
            }
        });
    }
});
