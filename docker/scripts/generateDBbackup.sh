#!/bin/bash

# =========================
# CONFIG
# =========================

CONTAINER="laravel_mysql"
DATABASE="latinpaychallenge"
USER="root"
PASSWORD="root"

BACKUP_DIR="/home/ubuntu/backups"

DATE=$(date +"%Y-%m-%d_%H-%M-%S")

FILE_NAME="backup_${DATABASE}_${DATE}.sql"
COMPRESSED_FILE="${FILE_NAME}.tar.gz"

# =========================
# CREAR CARPETA
# =========================

mkdir -p $BACKUP_DIR

echo "Generando backup..."

# =========================
# EXPORTAR SQL
# =========================

docker exec $CONTAINER \
mysqldump -u$USER -p$PASSWORD $DATABASE \
> "$BACKUP_DIR/$FILE_NAME"

# =========================
# VALIDAR BACKUP
# =========================

if [ $? -ne 0 ]; then
    echo "Error generando backup"
    exit 1
fi

echo "Comprimiendo backup..."

# =========================
# COMPRIMIR
# =========================

tar -czf "$BACKUP_DIR/$COMPRESSED_FILE" \
-C "$BACKUP_DIR" "$FILE_NAME"

# =========================
# ELIMINAR SQL ORIGINAL
# =========================

rm "$BACKUP_DIR/$FILE_NAME"

echo "Backup comprimido correctamente:"
echo "$BACKUP_DIR/$COMPRESSED_FILE"

# =========================
# ELIMINAR BACKUPS > 30 DÍAS
# =========================

find $BACKUP_DIR -name "*.tar.gz" -type f -mtime +30 -delete

echo "Backups antiguos eliminados"

#Para usar este archivo tienes que estar dentro de linux, ejecutarlo desde powershell da error porque no reconoce el comando date
    