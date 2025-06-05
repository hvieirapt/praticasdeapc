#!/bin/bash
set -e

# Permissões patch
mkdir -p /var/www/html/data
chown -R www-data:www-data /var/www/html
chmod -R 0777            /var/www/html

# Cria o ficheiro DB se não existir
DB_DIR=/var/www/html/data
DB_FILE=$DB_DIR/database.sqlite

if [ ! -f "$DB_FILE" ]; then
  # Garantir que existe
  touch "$DB_FILE"
  chown www-data:www-data "$DB_FILE"
  chmod 0666 "$DB_FILE"
  echo "Ficheiro de base de dados criado: $DB_FILE"
fi

# Migrações
# Executa sempre, adiciona novas tabelas sem apagar nada
sqlite3 "$DB_FILE" <<SQL
PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS User (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username TEXT NOT NULL UNIQUE,
  password TEXT NOT NULL,
  grupo_permissoes TEXT NOT NULL,
  ultimo_login DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS expedicoes (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  data_criacao DATE NOT NULL,
  data_entrega DATE,
  cliente TEXT NOT NULL,
  morada TEXT,
  estado TEXT
);

-- Seed utilizadores de testing
INSERT OR IGNORE INTO User (username, password, grupo_permissoes) VALUES ('admin', 'admin', 'Administrador');
INSERT OR IGNORE INTO User (username, password, grupo_permissoes) VALUES ('cliente', 'cliente', 'Cliente');
INSERT OR IGNORE INTO User (username, password, grupo_permissoes) VALUES ('operador', 'operador', 'Operador');
-- Seed expedicoes de testing
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (1, '2025-06-01', '2025-06-01', 'Luís Silva', 'Rua Nova, 12', 'pendente aprovação');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (2, '2025-06-01', '2025-06-01', 'Ana Costa', 'Avenida Central, 45', 'em processamento');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (3, '2025-06-02', '2025-06-04', 'José Ferreira', 'Rua das Flores, 3', 'concluída');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (4, '2025-06-02', '2025-06-02', 'Maria Rodrigues', 'Travessa do Sol, 7', 'cancelada');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (5, '2025-06-03', '2025-06-03', 'Pedro Gomes', 'Rua Alta, 10', 'pendente aprovação');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (6, '2025-06-03', '2025-06-05', 'Inês Martins', 'Praça da República, 1', 'concluída');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (7, '2025-06-04', '2025-06-04', 'Miguel Lopes', 'Rua do Carmo, 22', 'em processamento');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (8, '2025-06-04', '2025-06-04', 'Sofia Pereira', 'Avenida do Mar, 88', 'pendente aprovação');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (9, '2025-06-05', '2025-06-05', 'Rui Carvalho', 'Rua da Liberdade, 14', 'cancelada');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (10, '2025-06-05', '2025-06-05', 'Beatriz Almeida', 'Rua das Laranjeiras, 5', 'em processamento');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (11, '2025-06-06', '2025-06-07', 'José Ferreira', 'Rua das Flores, 3', 'pendente aprovação');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (12, '2025-06-06', '2025-06-08', 'José Ferreira', 'Rua das Flores, 3', 'em processamento');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (13, '2025-06-07', '2025-06-09', 'José Ferreira', 'Rua das Flores, 3', 'concluída');
SQL

echo "Migrations concluídas em $DB_FILE"

# Inicia o Apache
exec "$@"
