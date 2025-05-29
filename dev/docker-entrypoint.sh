#!/bin/bash
set -e

# ─── Garantir que /var/www/html e /var/www/html/data existem e têm permissão 777 ───
# (isto corre assim que o container arranca, imediatamente após o build)
chmod -R 0777 /var/www/html
chmod -R 0777 /var/www/html/data

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
