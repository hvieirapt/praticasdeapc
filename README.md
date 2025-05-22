# praticasdeapc
### P25 - GESTÃO LOGÍSTICA
Os sistemas de gestão logística requerem uma aplicação web com uma interação com um servidor de base de dados
para gerir dados de expedição, níveis de inventário, e informação de entrega. A aplicação web precisa de interagir com
o servidor de base de dados para gerir dados de expedição, controlar os níveis de inventário, e atualizar a informação
de entrega.


### Tipos de Utilizadores
- **Cliente**
- **Operador**
- **Administrador**

### Funcionalidades por Função

#### Autenticação
- Login através de e-mail e password.

#### Cliente
- Consultar o histórico e o estado atual das suas expedições
- Criar encomendas -> Gerar Expedição.

#### Operador
- Atualizar registos de expedição.
- Criar e atualizar níveis de inventário.
- **Ajustar manualmente o estado das expedições.**

#### Administrador
- Todas as funcionalidades do Operador.
- Aprovar Expedições.

### Estrutura de Páginas
- **Home / Login**
  Página inicial para autenticação (e-mail + password).

- **Expedições (Cliente)**
  Inventário disponível -> Criar encomenda.
  Visualização das suas expedições e respetivos estados.

- **Expedições (Operador & Administrador)**
  Painel para consulta e atualizar expedições.

- **Inventário (Administrador)**
  Área para consultar, criar e editar níveis de stock.
