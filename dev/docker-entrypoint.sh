#!/bin/bash
set -e

# Pasta onde ficará o ficheiro .sqlite
DB_DIR=/var/www/html/data
DB_FILE=$DB_DIR/database.sqlite

# Se ainda não existir, cria o diretório e o ficheiro, e define a tabela User
if [ ! -f "$DB_FILE" ]; then
  mkdir -p "$DB_DIR"
  sqlite3 "$DB_FILE" <<SQL
CREATE TABLE IF NOT EXISTS User (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username TEXT NOT NULL UNIQUE,
  password TEXT NOT NULL
);
SQL
  echo "Banco de dados inicializado em $DB_FILE"
fi

# Continua com o Apache
exec "$@"
