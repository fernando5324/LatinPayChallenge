#!/bin/bash

#Para generar el backup de la base de datos ahora mismo
# chmod +x generateBackupNow.sh
# ./generateBackupNow.sh

# =========================
# CONFIGURACIÓN
# =========================

CONTAINER="laravel_mysql"
DATABASE="latinpaychallenge"
BACKUP_DIR="../backups2"

# Fecha actual
DATE=$(date +"%Y-%m-%d_%H-%M-%S")

# Nombre archivo
FILE_NAME="backup_${DATABASE}_${DATE}.sql"

# =========================
# CREAR DIRECTORIO
# =========================

mkdir -p "$BACKUP_DIR"

# =========================
# GENERAR BACKUP
# =========================

echo "Generando backup..."

docker exec -i \
-e MYSQL_PWD=root \
$CONTAINER \
mysqldump -u root $DATABASE \
> "$BACKUP_DIR/$FILE_NAME"

# =========================
# VALIDAR RESULTADO
# =========================

if [ $? -eq 0 ]; then
    echo "Backup generado correctamente:"
    echo "$BACKUP_DIR/$FILE_NAME"
else
    echo "Error generando backup"
    exit 1
fi
