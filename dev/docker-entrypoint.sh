#!/bin/bash
set -e

# ─── Build-time em entrypoint (na primeira execução do container) ────────────

# Lista de todos os paths que queres garantir
DIRS=(
  /var/www/html/hugo/cgi-bin
  /var/www/html/hugo/public_html
  /var/www/html/sergio/cgi-bin
  /var/www/html/sergio/public_html
  /var/www/html/guilherme/cgi-bin
  /var/www/html/guilherme/public_html
  /var/www/html/data
  /var/www/html/scripts
)

# Cria e ajusta permissões
for d in "${DIRS[@]}"; do
  if [ ! -d "$d" ]; then
    mkdir -p "$d"
  fi
  chmod -R 0777 "$d"
done

# ─── Inicialização do SQLite ────────────────────────────────────────────────

DB_DIR=/var/www/html/data
DB_FILE=$DB_DIR/database.sqlite

if [ ! -f "$DB_FILE" ]; then
  sqlite3 "$DB_FILE" <<SQL
CREATE TABLE IF NOT EXISTS User (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username TEXT NOT NULL UNIQUE,
  password TEXT NOT NULL
);
SQL
  echo "Banco de dados inicializado em $DB_FILE"
fi

# ─── Lança o Apache ──────────────────────────────────────────────────────────
exec "$@"
