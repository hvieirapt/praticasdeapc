document.addEventListener("DOMContentLoaded", function () {
    console.log("Script login.js carregado"); // testee

    const form = document.querySelector("form");
    const username = document.getElementById("username");
    const password = document.getElementById("password");




    // Limpar a borda quando o utilizador começa a escrever no campo que antes estava vazio
    username.addEventListener("input", function () {
        username.style.border = "";
    });

    password.addEventListener("input", function () {
        password.style.border = "";
    });

    form.addEventListener("submit", function (event) {
        let valid = true;

        if (username.value.trim() === "") {
            username.style.border = "2px solid red";
            valid = false;
        } else {
            username.style.border = "";
        }

        if (password.value.trim() === "") {
            password.style.border = "2px solid red";
            valid = false;
        } else {
            password.style.border = "";
        }

        if (!valid) {
            event.preventDefault();
            alert("Por favor, preencha todos os campos obrigatórios.");
        }
    });
});
