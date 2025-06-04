# Análise de funcionalidades executadas no browser

## index.html
- Formulário `indexLoginForm` para autenticação inicial.
- Campos obrigatórios: `username`, `password`.

## login.php
- Formulário `loginForm` para entrada na aplicação.
- Campos obrigatórios: `username`, `password`.

## registo.php
- Formulário `registoForm` para criação de utilizador.
- Campos obrigatórios: `username`, `password`.

## expedicoes.php
- Formulário `createForm` para registar nova expedição.
- Campos obrigatórios: `cliente`, `morada`, `data_entrega`.
- Formulário `editForm` para editar/aprovar/apagar expedição.
- Campos obrigatórios: `cliente`, `morada`, `data_entrega`, `estado`.

As validações de preenchimento são agora garantidas por funções em `validate.js`, executadas apenas no browser.
