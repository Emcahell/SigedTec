# Sistema de Gestión de Inventario y Despacho Tecnológico

## Descripción general

Sistema Laravel para la gestión de bienes informáticos en instituciones públicas. Permite:

- Control de equipos tecnológicos (laptops, monitores, impresoras) con código patrimonial único
- Asignación de equipos a oficinas con flujos de aprobación
- Roles diferenciados: **Administrador** (Soporte Técnico) y **Solicitante** (Jefe de Oficina)
- Middleware de control de acceso por rol (`role:admin` y `role:solicitante`)
- Eliminación lógica (soft deletes) en categorías y equipos
- Historial de asignaciones organizadas por oficina con filtros y paginación
- Dashboard con indicadores estadísticos en tiempo real y gráfico Chart.js
- Perfil de usuario editable (nombre y correo electrónico)

## Requisitos previos

- **PHP** >= 8.3 con extensiones: `PDO`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`
- **Composer** (gestor de dependencias PHP)
- **PostgreSQL** >= 12 (base de datos recomendada; los tests corren en SQLite en memoria)
- **Node.js** >= 18 y **npm** (para assets y Tailwind CSS v4)
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
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_PORT=5432
# DB_DATABASE=sigedtec
# DB_USERNAME=tu_usuario
# DB_PASSWORD=tu_contraseña

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

# Login (creado por DatabaseSeeder)
#   admin: admin@sigedtec.com / password → rol: admin, home: /dashboard
#   Los usuarios solicitantes son creados por el administrador desde /users
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
- **Email:** `admin@sigedtec.com`
- **Password:** `password`
- **Rol:** `admin`
- **Permisos:** Dashboard, CRUD categorías/equipos, gestión de solicitudes (aprobar/rechazar),
  registro de usuarios solicitantes

### Usuario Solicitante (Jefe de Oficina)
El registro público está deshabilitado. El administrador crea las cuentas de los
solicitantes en **Usuarios → Nuevo solicitante**, indicando nombre, correo y la
contraseña con la que iniciarán sesión. Cada usuario creado recibe el rol
`solicitante` y accede a `/requests/mine`.

### Datos iniciales
El seeder deja únicamente la cuenta del administrador; no se crean categorías,
equipos ni solicitudes de ejemplo. Esos registros se cargan desde el sistema.

## Estructura de carpetas y arquitectura

```
app/
├── Enums/              # Enums backed: EquipmentStatus, RequestStatus, UserRole
├── Http/
│   ├── Controllers/    # Controladores: Dashboard, Equipment, Category, Request,
│   │                     Report, User, Profile
│   ├── Middleware/     # CheckRole (alias 'role:admin' y 'role:solicitante')
│   └── Requests/       # FormRequests: StoreEquipment, UpdateEquipment,
│                       # StoreCategory, UpdateCategory, StoreRequest,
│                       # StoreUser, UpdateUser, ProfileUpdateRequest
├── Models/
│   ├── User.php        # Role cast, isAdmin(), isSolicitante()
│   ├── Category.php    # SoftDeletes, hasMany equipments()
│   ├── Equipment.php   # SoftDeletes, belongsTo category(), enum estado
│   ├── Request.php     # BelongsTo user/category, hasMany items, isPending()
│   └── RequestItem.php # BelongsTo request/equipment, assigned_at
database/
├── factories/          # User, Category, Equipment (con estados)
├── migrations/         # users, categories, equipments, requests, request_items
├── seeders/            # DatabaseSeeder (solo admin), UserSeeder, CategorySeeder,
│                       └── EquipmentSeeder
└ resources/
    ├── views/
    │   ├── layouts/    # app-layout, guest, navigation (role-aware)
    │   ├── auth/       # login (registro público deshabilitado)
    │   ├── users/      # listado y alta de solicitantes (admin)
    │   ├── dashboard.blade.php  # admin dashboard con tarjetas + Chart.js
    │   ├── equipments/ # CRUD completo con filtros + vista mis equipos
    │   ├── categories/ # CRUD categorías
    │   ├── requests/   # create, my_requests, show, admin_index, approve, reject
    │   │       └── partials/ # form, details
    │   ├── reports/    # historial de asignaciones con filtros
    │   ├── profile/    # edición de perfil
    │   └── components/ # componentes Blade reutilizables
    └── css/            # Tailwind compilado
routes/
    ├── web.php         # Grupos middleware: auth, role:admin, role:solicitante
    └── auth.php        # Rutas de autenticación (login, logout, verificación)
tests/
    ├── Feature/        # RequestFlow, RoleAccess, UserManagement, CategoryCrud,
    │                   EquipmentCrud, AssignedEquipment, Profile
    └── Unit/           # ExampleTest
```

## Arquitectura y decisiones clave

1. **Enums backed**: `EquipmentStatus`, `RequestStatus`, `UserRole` son enums de PHP 8.1 con método `label()`, `options()`, `badgeClass()`/`textClass()` para UI.
2. **Middleware `CheckRole`**: alias `role` en `bootstrap/app.php` para usar `->middleware('role:admin')` en rutas.
3. **Soft deletes**: en `categories` y `equipments`; eliminación lógica con confirmación.
4. **Transacciones atómicas**: `DB::transaction` en `approve` y `reject` del `RequestController` con `lockForUpdate` para evitar race conditions.
5. **Eager loading**: `with('category')`, `with('user', 'category', 'items.equipment')` para evitar N+1 queries.
6. **Validación en FormRequests**: mensajes en español, reglas personalizadas (`Rule::unique`, `Rule::enum`, `Rule::exists` con `whereNull('deleted_at')`).
7. **Estilo minimalista**: solo tonos azules (`blue-50` a `blue-900`), rojo/verde solo para estados de rechazo/aprobación.
8. **Rutas por rol**:
   - `auth`, `role:admin`: dashboard, CRUD categorías/equipos, gestión de usuarios y solicitudes.
   - `auth`, `role:solicitante`: creación y seguimiento de solicitudes propias, equipos asignados.
   - `auth`: detalle de solicitud (acceso restringido al propietario o admin) y perfil.

## Tests

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar tests de una característica
php artisan test --filter=RequestFlow

# Formateo de código
vendor/bin/pint --dirty --format agent
```

## Historia de fases

### Fase 1: Migración y control de acceso
- Migración `role` en tabla `users` (enum: admin, solicitante)
- Modelo User con accessor/mutator `role`
- Middleware `CheckRole` y registro en `bootstrap/app.php`
- Seeder 1 admin
- Login institucional (sin registro público)
- Redirección por rol post-login
- Alta de solicitantes desde el panel del administrador

### Fase 2: CRUD categorías/equipos + módulo solicitudes
- Migraciones `categories` y `equipments` (código patrimonial único, enum estado)
- Modelos Category/Equipment con SoftDeletes y relación hasMany/belongsTo
- Enum `EquipmentStatus` (disponible/asignado/mantenimiento)
- CRUD completo categorías y equipos (controllers, FormRequests, vistas)
- Factories y seeders (3 categorías, 20 equipos coherentes)
- Flujo de solicitudes: crear→aprobar/rechazar→asignar equipos
- Dashboard admin con indicadores y gráfico Chart.js
- Reportes con filtros oficina/fecha/categoría

### Próximas fases (planeadas)
- Notificaciones flash tras aprobar/rechazar
- Historial detallado por oficina con exportaciones
- Integración con sistema de notificaciones (Laravel Notifications)
