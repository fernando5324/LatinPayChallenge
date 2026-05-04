

# Proyecto: Backend Technical Challenge LatinPay


## Author
- Luis Fernando Baltazar Valenzuela
- lfbaltazarv@gmail.com
- +51 942515709

## Instalación
    1. Clonar repositorio
        git clone https://github.com/fernando5324/LatinPayChallenge.git
        cd latinpayChallenge
    2. Instalar dependencias
        composer install
    3. Configurar base de datos
        cp .env.example .env
        [Editar .env con credenciales de MySQL]
        php artisan key:generate
    4. Ejecutar migraciones
        php artisan migrate
    6. Iniciar servidor
        php artisan serve
    7. Acceder
        http://127.0.0.1:8000
    9. Ejecutar pruebas
        php artisan test
    10.Los enpoints estan en postman en el archivo LatinPayChallenge.postman_collection.json de este proyecto.

## Comandos:

Refrescar el cache:

    php artisan cache:clear
    php artisan view:clear
    php artisan config:clear
    php artisan config:cache
    php artisan optimize
    php artisan config:cache

Ver las rutas:
    php artisan route:list

Migraciones

    php artisan migrate:fresh --seed
    php artisan db:seed


## Endpoints

POST /api/v1/payments
-| 

Se crea en estado PENDING

Se genera un payment_code único
    
```bash
  {
  "merchant_id": 10,
  "customer_document": "76359665",
  "amount": 150.50,
  "currency": "PEN",
  "description": "Pago mensual"
}
```  
POST /api/v1/bank/notifications 
-| 

Aquí guardo el evento tal cual llega

Luego lo proceso con un Job
```bash
{
  "event_id": "evt_001",
  "bank_transaction_id": "tx_001",
  "payment_code": "LTP-20260504-000001",
  "amount": 150.50,
  "currency": "PEN",
  "status": "PAID",
  "paid_at": "2026-05-04 20:44:00"
}
```  
POST /api/v1/bank/reconciliation
-|

Compara lo que mandó el banco vs lo que tengo

Detecta diferencias o pagos no encontrados
```bash
{
  "bank": "BANK_A",
  "process_date": "2026-04-24",
  "movements": [
    {
      "bank_movement_id": "mov_001",
      "bank_transaction_id": "bank_tx_999",
      "payment_code": "LTP-20260503-000001",
      "amount": 150.50,
      "currency": "PEN",
      "paid_at": "2026-04-24 20:44:30"
    },
    {
      "bank_movement_id": "mov_002",
      "bank_transaction_id": "bank_tx_1000",
      "payment_code": "LTP-20260504-000002",
      "amount": 200.00,
      "currency": "PEN",
      "paid_at": "2026-04-24 20:46:00"
    }
  ]
}
```  
Pagos candidatos a liquidación
-|

Solo devuelve pagos válidos para liquidar

Considera hora de corte (20:45)

## Decisiones que tomé :
#### Idempotencia (evitar duplicados)
    - event_id es único
    - bank_transaction_id también
    - Si el banco manda lo mismo 2 veces, no se duplica nada
    - Incluso controlo el error 1062 de MySQL para ignorarlo sin romper

#### Jobs
    - event_id es único
    - bank_transaction_id también
    - Si el banco manda lo mismo 2 veces, no se duplica nada
    - Incluso controlo el error 1062 de MySQL para ignorarlo sin romper


#### Validación de pagos
Cuando llega la notificación se hace en job:

    - Si monto o moneda no coincide → OBSERVED
    - Si todo coincide → PAID

#### Estados

No hice algo súper complejo, pero sí evité cosas incoherentes

Cuando llega la notificación se hace en job:

    - PENDING
    - PAID
    - OBSERVED
    - LATE_CONFIRMATION
    - INCONSISTENCY
    - UNMATCHED
    - RECONCILED

#### Índices en la base de datos
    - payment_code
    - event_id
    - bank_transaction_id
    - status

#### Manejo de montos

    Uso DECIMAL(8,2) y comparo cuidando el tipo de dato (para evitar errores raros)

#### Notificación externa

Cuando un pago pasa a PAID:

    - disparo un Job (NotifyPaymentConfirmedJob)
    - No llamo API real, lo simulo.
    - Lo dejé preparado para que se pueda cambiar fácil.

## Flujo completo

    1.Creo un pago → PENDING
    2.Llega notificación del banco
    3.Guardo el evento
    4.Job procesa:
    5.valida datos
    6.cambia estado
    7.Si es PAID:
    8.se notifica externamente
    9.Luego se puede conciliar
    10.Finalmente pasa a liquidación