#!/bin/bash
set -e

# ─── 1. Permissões e dono ────────────────────────────────────────────────────
mkdir -p /var/www/html/data
chown -R www-data:www-data /var/www/html
chmod -R 0777            /var/www/html

# ─── 2. Cria o ficheiro DB se não existir ────────────────────────────────────
DB_DIR=/var/www/html/data
DB_FILE=$DB_DIR/database.sqlite

if [ ! -f "$DB_FILE" ]; then
  # Apenas para garantir que existe
  touch "$DB_FILE"
  chown www-data:www-data "$DB_FILE"
  chmod 0666 "$DB_FILE"
  echo "Ficheiro de base de dados criado: $DB_FILE"
fi

# ─── 3. “Migrations”: garante que as tabelas existem ─────────────────────────
# Executa sempre, adiciona novas tabelas sem apagar nada
sqlite3 "$DB_FILE" <<SQL
PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS User (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username TEXT NOT NULL UNIQUE,
  password TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS expedicoes (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  data_criacao DATE NOT NULL,
  data_entrega DATE,
  cliente TEXT NOT NULL,
  morada TEXT,
  estado TEXT
);
SQL

# 3.5) Adiciona ultimo_login se faltar
if ! sqlite3 "$DB_FILE" "PRAGMA table_info(User);" \
     | cut -d'|' -f2 | grep -q '^ultimo_login$'; then
  echo "Adicionando coluna ultimo_login à tabela User..."
  sqlite3 "$DB_FILE" "ALTER TABLE User ADD COLUMN ultimo_login DATETIME;"
fi

echo "Migrations concluídas em $DB_FILE"

# ─── 4. Inicia o Apache ─────────────────────────────────────────────────────
exec "$@"
