# Ficha técnica — SIGEDTEC

## 1. Identificación

| Campo | Detalle |
|---|---|
| **Nombre** | SIGEDTEC — Sistema de Gestión de Inventario y Despacho Tecnológico |
| **Tipo** | Aplicación web institucional |
| **Objetivo** | Gestionar el inventario de equipos tecnológicos y el flujo de solicitudes, aprobación, asignación y seguimiento de equipos por oficina. |
| **Estado** | En desarrollo / funcional según módulos implementados |

## 2. Stack tecnológico

| Capa | Tecnología | Versión / detalle |
|---|---|---|
| Lenguaje backend | PHP | `^8.3` (entorno actual: 8.4) |
| Framework | Laravel | `^13.17` |
| Autenticación | Laravel Breeze | `^2.4` |
| Base de datos | Compatible con Eloquent/SQL (migraciones Laravel) | Configurable por `.env` |
| Frontend | Blade, Tailwind CSS, Alpine.js | Tailwind `^3`, Alpine `^3` |
| Build frontend | Vite | `^8` |
| Pruebas | PHPUnit | `^12.5` |
| Formato PHP | Laravel Pint | `^1.32` |
| Iconos | Blade Icons | `blade-ui-kit/blade-icons` |

## 3. Roles y permisos

| Rol | Valor enum | Descripción | Ruta inicial |
|---|---|---|---|
| Administrador | `admin` | Soporte Técnico. Administra inventario, categorías, usuarios y solicitudes. | `/dashboard` |
| Solicitante | `solicitante` | Jefe de Oficina. Registra solicitudes y consulta sus equipos asignados. | `/requests/mine` |

El control de acceso se aplica mediante middleware `auth` y `role:*`.

## 4. Módulos funcionales

### 4.1 Autenticación y perfil
- Inicio de sesión.
- Visualización y actualización de perfil.
- Actualización de contraseña.
- Confirmación/verificación según flujo de Breeze instalado.

### 4.2 Panel administrativo
- Dashboard con métricas: equipos disponibles, asignados, en mantenimiento, total y solicitudes pendientes.

### 4.3 Gestión de categorías (Admin)
- Listar, crear, editar y eliminar lógicamente categorías.
- Campos: nombre, descripción.
- Eliminación lógica mediante `softDeletes`.

### 4.4 Gestión de equipos (Admin)
- CRUD de equipos tecnológicos.
- Filtros por categoría y estado.
- Campos: código patrimonial único, marca, modelo, CPU, RAM, almacenamiento, estado, categoría.
- Estados: `disponible`, `asignado`, `mantenimiento`.
- Eliminación lógica mediante `softDeletes`.

### 4.5 Gestión de usuarios (Admin)
- Listar usuarios.
- Crear, editar y eliminar solicitantes.
- Las cuentas administrador están protegidas contra eliminación por flujo normal.

### 4.6 Gestión de solicitudes
- **Solicitante**: crear solicitud, ver historial propio, ver detalle propio, ver equipos asignados.
- **Admin**: listar solicitudes por estado, revisar detalle, aprobar asignando equipos disponibles, rechazar con motivo.
- Estados de solicitud: `pendiente`, `aprobada`, `rechazada`.
- Al aprobar: se crean `request_items`, se asignan equipos y su estado pasa a `asignado`.
- Al rechazar: se registra `rejection_reason`.

## 5. Modelo de datos

### 5.1 Entidades principales

| Tabla | Descripción | Claves / restricciones relevantes |
|---|---|---|
| `users` | Usuarios del sistema. | `email` único; `role` enum `admin/solicitante` con default `solicitante`. |
| `categories` | Categorías de equipos. | `softDeletes`. |
| `equipments` | Equipos tecnológicos. | `codigo_patrimonial` único; `estado` enum; FK `category_id` → `categories` (`restrictOnDelete`); `softDeletes`. |
| `requests` | Solicitudes de equipos. | FK `user_id` → `users` (`cascadeOnDelete`); FK `category_id` → `categories` (`restrictOnDelete`); `status` enum; `quantity` entero sin signo. |
| `request_items` | Equipos asignados a una solicitud aprobada. | FK `request_id` → `requests` (`cascadeOnDelete`); FK `equipment_id` → `equipments` (`restrictOnDelete`); `assigned_at` timestamp. |

### 5.2 Relaciones

- Un **User** tiene muchas **Request**.
- Una **Category** tiene muchos **Equipment**.
- Una **Category** tiene muchas **Request**.
- Una **Request** pertenece a **User** y **Category**.
- Una **Request** tiene muchos **RequestItem**.
- Un **Equipment** puede aparecer en muchos **RequestItem**.
- Un **RequestItem** pertenece a **Request** y **Equipment**.

## 6. Rutas principales

| Método | Ruta | Rol | Función |
|---|---|---|---|
| GET | `/dashboard` | admin | Panel con estadísticas |
| Resource | `/categories` | admin | CRUD categorías (sin show) |
| Resource | `/equipments` | admin | CRUD equipos |
| GET/POST | `/users...` | admin | Gestión de solicitantes |
| GET | `/requests` | admin | Listado/gestión de solicitudes |
| GET/POST | `/requests/{solicitud}/aprobar` | admin | Aprobar solicitud |
| GET/POST | `/requests/{solicitud}/rechazar` | admin | Rechazar solicitud |
| GET | `/my-equipments` | solicitante | Equipos asignados al solicitante |
| GET | `/requests/mine` | solicitante | Historial de solicitudes propias |
| GET/POST | `/requests/create`, `/requests` | solicitante | Crear solicitud |
| GET | `/requests/{solicitud}` | auth | Detalle de solicitud, con control en controlador |
| GET/PATCH | `/profile` | auth | Perfil de usuario |

## 7. Reglas de negocio relevantes

- Solo `admin` accede a dashboard, CRUD de categorías/equipos/usuarios y aprobación/rechazo.
- Solo `solicitante` crea solicitudes y consulta “mis solicitudes/mis equipos”.
- Una solicitud se crea con estado `pendiente`.
- La aprobación asigna equipos disponibles de la categoría solicitada y cambia su estado a `asignado`.
- El rechazo requiere motivo.
- Categorías y equipos usan eliminación lógica; las restricciones de FK protegen integridad referencial.
- El detalle de solicitud valida que el solicitante solo vea sus propias solicitudes.

## 8. Requisitos no funcionales / técnicos

- **Seguridad**: autenticación, autorización por rol, validación por Form Requests, protección CSRF, hashing de contraseñas.
- **Integridad**: claves foráneas, enums, unicidad de código patrimonial y correo.
- **Mantenibilidad**: arquitectura MVC Laravel, enums para estados/roles, componentes Blade reutilizables.
- **Pruebas**: suite PHPUnit de features; ejecución con `php artisan test --compact`.
- **Despliegue**: compatible con Render/Docker; en producción se fuerza HTTPS desde `AppServiceProvider`.

## 9. Ejecución local

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

Para desarrollo frontend: `npm run dev`.
