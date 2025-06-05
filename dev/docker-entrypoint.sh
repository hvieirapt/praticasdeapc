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
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (14, '2025-06-08', '2025-06-10', 'Luís Silva', 'Rua Nova, 12', 'pendente aprovação');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (15, '2025-06-09', '2025-06-11', 'Ana Costa', 'Avenida Central, 45', 'em processamento');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (16, '2025-06-10', '2025-06-12', 'Miguel Lopes', 'Rua do Carmo, 22', 'concluída');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (17, '2025-06-11', '2025-06-13', 'Sofia Pereira', 'Avenida do Mar, 88', 'cancelada');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (18, '2025-06-12', '2025-06-14', 'Pedro Gomes', 'Rua Alta, 10', 'pendente aprovação');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (19, '2025-06-13', '2025-06-15', 'Beatriz Almeida', 'Rua das Laranjeiras, 5', 'em processamento');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (20, '2025-06-14', '2025-06-16', 'Inês Martins', 'Praça da República, 1', 'concluída');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (21, '2025-06-15', '2025-06-17', 'Maria Rodrigues', 'Travessa do Sol, 7', 'cancelada');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (22, '2025-06-16', '2025-06-18', 'Rui Carvalho', 'Rua da Liberdade, 14', 'pendente aprovação');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (23, '2025-06-17', '2025-06-19', 'José Ferreira', 'Rua das Flores, 3', 'em processamento');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (24, '2025-06-18', '2025-06-20', 'Carlos Teixeira', 'Rua do Sol, 2', 'concluída');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (25, '2025-06-19', '2025-06-21', 'Raquel Santos', 'Rua das Acácias, 8', 'cancelada');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (26, '2025-06-20', '2025-06-22', 'André Moura', 'Rua do Pinheiro, 4', 'pendente aprovação');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (27, '2025-06-21', '2025-06-23', 'Diana Lopes', 'Rua das Laranjeiras, 6', 'em processamento');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (28, '2025-06-22', '2025-06-24', 'Tiago Oliveira', 'Praça da Alegria, 9', 'concluída');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (29, '2025-06-23', '2025-06-25', 'Helena Costa', 'Avenida da Liberdade, 20', 'cancelada');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (30, '2025-06-24', '2025-06-26', 'Paulo Cardoso', 'Rua do Comércio, 3', 'pendente aprovação');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (31, '2025-06-25', '2025-06-27', 'Clara Ferreira', 'Rua Nova, 15', 'em processamento');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (32, '2025-06-26', '2025-06-28', 'Mariana Cruz', 'Rua das Flores, 9', 'concluída');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (33, '2025-06-27', '2025-06-29', 'José Ferreira', 'Rua das Flores, 3', 'cancelada');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (34, '2025-06-05', '2025-06-06', 'Tiago Sousa', 'Rua do Mercado, 10', 'concluída');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (35, '2025-06-06', '2025-06-07', 'Marta Pinto', 'Rua das Palmeiras, 7', 'concluída');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (36, '2025-06-07', '2025-06-08', 'Bruno Alves', 'Praça Velha, 2', 'concluída');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (37, '2025-06-08', '2025-06-09', 'Carla Dias', 'Rua Nova, 14', 'concluída');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (38, '2025-06-09', '2025-06-10', 'Rafael Teixeira', 'Rua do Sol, 5', 'concluída');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (39, '2025-06-10', '2025-06-11', 'Susana Marques', 'Avenida Central, 20', 'concluída');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (40, '2025-06-11', '2025-06-12', 'Diogo Ferreira', 'Travessa Nova, 3', 'concluída');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (41, '2025-06-12', '2025-06-13', 'Patrícia Ramos', 'Rua do Comércio, 12', 'concluída');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (42, '2025-06-13', '2025-06-14', 'Filipe Lopes', 'Rua da Liberdade, 8', 'concluída');
INSERT OR IGNORE INTO expedicoes (id, data_criacao, data_entrega, cliente, morada, estado) VALUES (43, '2025-06-14', '2025-06-15', 'Ana Furtado', 'Rua das Acácias, 6', 'concluída');
SQL

echo "Migrations concluídas em $DB_FILE"
# Seed dinâmico: entregas concluídas últimos 30 dias
for i in $(seq 0 29); do
  d=$(date -d "-${i} days" +%Y-%m-%d)
  count=$((RANDOM % 5 + 1))
  for j in $(seq 1 $count); do
    sqlite3 "$DB_FILE" "INSERT OR IGNORE INTO expedicoes (data_criacao, data_entrega, cliente, morada, estado) VALUES ('$d','$d','Auto Cliente','Rua Automática, ${i}-${j}','concluída')"
  done
done

# Inicia o Apache
exec "$@"
