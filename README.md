# P25 - GESTÃO LOGÍSTICA
Os sistemas de gestão logística requerem uma aplicação web com uma interação com um servidor de base de dados
para gerir dados de expedição, níveis de inventário, e informação de entrega. A aplicação web precisa de interagir com
o servidor de base de dados para gerir dados de expedição, controlar os níveis de inventário, e atualizar a informação
de entrega.


#### Autenticação
- Login através utilizador e password.

### Tipos de Utilizadores
- **Cliente**
- **Operador**
- **Administrador**

### Funcionalidades por Utilizador

#### Cliente
- Consultar o histórico, a previsão de entrega e o estado atual das suas expedições.

#### Operador
- Consultar e atualizar níveis de inventário.
- Consultar, criar e atualizar registos de expedição.
- Gerenciar manualmente o estado das expedições.

#### Administrador
- Todas as funcionalidades do Operador.
- Gerenciar e criar utilizadores.

### Estrutura de Páginas
- **Login**
  Página inicial para autenticação (utilizador + password).

- **Dashboard (Operador & Administrador)**
  Visualização de gráficos com KPI's.
  Ver as ultimas 5 encomendas.

- **Expedições (Operador & Administrador)**
  Criar e gerenciar expedições.

- **Inventário (Operador & Administrador)**
  Ir para página Consultar Inventário ou Alterar iventário.

- **Consultar Inventário (Operador & Administrador)**
  Consultar inventário.

- **Alterar Inventário (Operador & Administrador)**
  Adicionar ou remover itens em stock.

- **Registo (Adminstrador)**
  Criar utilizadores e dar as permissões desejadas(Administrador/Operador/Cliente).
  Consultar utilizadores existentes e as respetivas permissões.

- **Dashboard (Cliente)**
  Área para o cliente consultar ID, a data prevista e o estado das suas expedições.


## Utilização
### 1. Docker build
`make docker-build`

### 2. Docker up
`make docker-up`

### 3. Aceder atraves de browser a:
`http://localhost:8888/login.php`