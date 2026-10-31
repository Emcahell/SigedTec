# Sistema de Gestión de Inventario y Despacho Tecnológico

## Descripción general

Sistema Laravel para la gestión de bienes informáticos en instituciones públicas. Permite:

- Control de equipos tecnológicos (laptops, monitores, impresoras) con código patrimonial único
- Asignación de equipos a oficinas con flujos de aprobación
- Roles diferenciados: **Administrador** (Soporte Técnico) y **Solicitante** (Jefe de Oficina)
- Middlewares de control de acceso por rol
- Eliminación lógica (soft deletes) en categorías y equipos
- Historial de asignaciones organizadas por oficina
- Dashboard con indicadores estadísticos en tiempo real

## Requisitos previos

- **PHP** >= 8.1 con extensiones: `PDO`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`
- **Composer** (gestor de dependencias PHP)
- **PostgreSQL** >= 12 (base de datos)
- **Node.js** >= 18 y **npm** (para assets y Tailwind CSS)
- Extensiones PHP recomendadas: `bcmath`, `curl`, `fileinfo`, `intl`, `gd`, `zip`

## Instalación

```bash
# 1. Clonar el repositorio
git clone <url-del-repositorio>
cd <nombre-del-proyecto>

# 2. Instalar dependencias de PHP
composer install

# 3. Instalar dependencias de Node.js
npm install

# 4. Configurar el entorno
cp .env.example .env
php artisan key:generate

# 5. Editar el archivo .env con tus credenciales de PostgreSQL
# DATABASE_URL=pgsql://usuario:password@localhost/puerto/dbname
# Adjust other env vars as needed (MAIL, BROADCAST_DRIVER, etc.)

# 6. Ejecutar migraciones y seeders
php artisan migrate --force
php artisan db:seed --force

# 7. Compilar assets CSS/JS
npm run build

# 8. Iniciar el servidor de desarrollo
php artisan serve
```

## Configuración .env

Variables necesarias:

```env
APP_ENV=local
APP_DEBUG=true
APP_KEY=base64:...

# Base de datos PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sistema_gestor
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

# Configuración de aplicación
APP_URL=http://localhost:8000

# Configuración de roles (establecidos en los seeders)
# - admin: Soporte Técnico, acceso a dashboard y CRUD
# - solicitante: Jefe de Oficina, acceso a sus solicitudes

# Login de prueba (creados por DatabaseSeeder)
#   admin: admin@sistema.test / password → rol: admin, home: /dashboard
#   solicitante: solicitante@sistema.test / password → rol: solicitante, home: /requests/mine
```

## Comandos de migración y seeders

```bash
# Crear y ejecutar nueva migración
php artisan make:migration nombre_migracion
php artisan migrate --force

# Revertir última migración
php artisan migrate:rollback

# Reejecutar todas las migraciones
php artisan migrate:refresh

# Ejecutar seeders
php artisan db:seed --force

# Crear seeder
php artisan make:seed NombreSeeder

# Factory para datos de prueba
php artisan make:factory NombreFactory
```

## Credenciales de prueba

### Usuario Administrador (Soporte Técnico)
- **Email:** `admin@sistema.test`
- **Password:** `password`
- **Rol:** `admin`
- **Permisos:** Dashboard, CRUD categorías/equipos, gestión de solicitudes (aprobar/rechazar)

### Usuario Solicitante (Jefe de Oficina)
- **Email:** `solicitante@sistema.test`
- **Password:** `password`
- **Rol:** `solicitante`
- **Permisos:** Crear y ver sus propias solicitudes (`/requests/mine`)

### Cuentas adicionales (seeders)
- Se crean 3 categorías: **Laptops**, **Monitores**, **Impresoras**
- Se crean 20 equipos con códigos `BI-2026-0001` a `BI-2026-0020`
- Estados de equipos: 12 disponibles, 5 asignados, 3 en mantenimiento

## Estructura de carpetas y arquitectura

```
app/
├── Enums/              # Enums backed: EquipmentStatus, RequestStatus, UserRole
├── Http/
│   ├── Controllers/    # Controladores: Dashboard, Equipment, Category, Request, Profile
│   ├── Middleware/     # CheckRole (alias 'role:admin' y 'role:solicitante')
│   └── Requests/       # FormRequests: StoreEquipment, UpdateEquipment,
│                       # StoreCategory, UpdateCategory,
│                       # StoreRequest, UpdateRequest (pending)
├ Models/
│   ├── User.php        # Has role cast, isAdmin(), isSolicitante()
│   ├── Category.php    # SoftDeletes, hasMany equipments()
│   ├── Equipment.php   # SoftDeletes, belongsTo category(), enum estado
│   ├── Request.php     # BelongsTo user/category, hasMany items, isPending()
│   └── RequestItem.php # BelongsTo request/equipment, assigned_at
├ Enums/UserRole.php     # Admin/Solicitante, homePath() por rol
database/
├── factories/          # User, Category, Equipment (con estados)
├── migrations/         # users, categories, equipments, requests, request_items
├── seeders/            # DatabaseSeeder (ordena categorías → equipos)
│   └── CategorySeeder, EquipmentSeeder, RequestSeeder
└ resources/
    ├── views/
    │   ├── layouts/    # app-layout, navigation (role-aware)
    │   ├── auth/       # login, register
    │   ├── dashboards/ # admin dashboard con tarjetas + Chart.js
    │   ├── equipments/ # CRUD completo con filtros
    │   ├── categories/ # CRUD categorías
    │   └── requests/   # create, my_requests, show, admin_index, approve, reject
    │       └── partials/ # form, details
    └── css/            # Tailwind compilado
routes/
    └── web.php         # Grupos middleware: auth, role:admin, role:solicitante
```

## Arquitectura y decisiones clave

1. **Enums backed**: `EquipmentStatus`, `RequestStatus`, `UserRole` son enums de PHP 8.1 con método `label()`, `options()`, `badgeClass()`/`textClass()` para UI.
2. **Middleware `CheckRole`**: alias `role` en `Kernel.php` para usar `->middleware('role:admin')` en rutas.
3. **Soft deletes**: en `categories` y `equipments`; eliminación lógica con `X-modal` de confirmación.
4. **Transacciones atómicas**: `DB::transaction` en `approve` y `reject` del `RequestController` con `lockForUpdate` para evitar race conditions.
5. **Eager loading**: `with('category')`, `with('user', 'category', 'items.equipment')` para evitar N+1 queries.
6. **Validación en FormRequests**: mensajes en español, reglas personalizadas (`Rule::unique`, `Rule::enum`, `Rule::exists` con `whereNull('deleted_at')`).
7. **Estilo minimalista**: solo tonos azules (`blue-50` a `blue-900`), sin badges, sin degradados; rojo/verde solo para estados de rej/aprobación.

## Tests

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar tests de una característica
php artisan test --feature=RequestFlow

# Ver cobertura
vendor/bin/pint --dirty --format agent  # formateo de código
```

## Historia de fases

### Fase 1: Migración y control de acceso
- Migración `role` en tabla `users` (enum: admin, solicitante)
- Modelo User con accessor/mutator `role`
- Middleware `CheckRole` y registro en `Kernel.php`
- Seeder 1 admin + 1 solicitante
- Vistas de registro/login personalizadas
- Redirección por rol post-login

### Fase 2: CRUD categorías/equipos + módulo solicitudes
- Migraciones `categories` y `equipments` (código patrimonial único, enum estado)
- Modelos Category/Equipment con SoftDeletes y relación hasMany/belongsTo
- Enum `EquipmentStatus` (disponible/asignado/mantenimiento)
- CRUD completo categorías y equipos (controllers, FormRequests, vistas x-table)
- Factories y seeders (3 categorías, 20 equipos coherentes)
- Flujo de solicitudes: crear→aprobar/rechazar→asignar equipos
- Dashboard admin con indicadores y gráfico Chart.js
- Reportes con filtros oficina/fecha/categoría

### Próximas fases (planeadas)
- Notificaciones flash tras aprobar/rechazar
- Historial detallado por oficina con exportaciones
- Integración con sistema de notificaciones (Laravel Notifications)