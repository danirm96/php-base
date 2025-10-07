# PHP Framework Custom - Guía de Uso

## Descripción

Este es un framework PHP completamente desarrollado desde cero que sigue el patrón MVC (Modelo-Vista-Controlador). Incluye funcionalidades avanzadas como generación automática de código, sistema de autenticación, enrutamiento dinámico y componentes reutilizables.

## Índice

1. [Estructura del Proyecto](#estructura-del-proyecto)
2. [Configuración Inicial](#configuración-inicial)
3. [Generación de Código con Framecode](#generación-de-código-con-framecode)
4. [Sistema de Rutas](#sistema-de-rutas)
5. [Controladores](#controladores)
6. [Modelos](#modelos)
7. [Vistas y Layouts](#vistas-y-layouts)
8. [Sistema de Autenticación](#sistema-de-autenticación)
9. [Componentes y Helpers](#componentes-y-helpers)
10. [Base de Datos](#base-de-datos)
11. [Migraciones](#migraciones-y-seeders)

## Estructura del Proyecto

```
php-base/
├── framecode                     # CLI para generación de código
├── index.php                     # Punto de entrada
├── config/                       # Configuración del proyecto
│   ├── config.php               # Configuración principal
│   ├── menu.php                 # Configuración del menú
│   └── routes.php               # Definición de rutas
├── core/                        # Núcleo del framework
│   ├── classes/                 # Clases principales
│   │   ├── Auth.php             # Clase base de autenticación
│   │   ├── BaseController.php   # Controlador base
│   │   ├── BaseModel.php        # Modelo base
│   │   ├── Db.php               # Gestión de base de datos
│   │   └── Routes.php           # Sistema de rutas
│   ├── config/                  # Configuración del core
│   ├── components/              # Componentes reutilizables
│   ├── generations/             # Sistema de generación de código
│   └── views/                   # Plantillas del core
├── controllers/                 # Controladores de la aplicación
├── models/                      # Modelos de la aplicación
├── views/                       # Vistas de la aplicación
├── auth/                        # Clases de autenticación
└── public/                      # Archivos públicos (CSS, JS)
```

## Configuración Inicial

### 1. Configurar la Base de Datos

Edita el archivo `config/config.php`:

```php
<?php
define('BASE_URL', 'https://tu-dominio.com/');
define('HOME_URL', 'https://tu-dominio.com/dashboard/');
define('APP_NAME', 'Mi Aplicación');
define('DB_HOST', 'localhost');
define('DB_NAME', 'mi_base_datos');
define('DB_USER', 'usuario');
define('DB_PASS', 'contraseña');
define('IPS_ALLOW', ['127.0.0.1']); // IPs permitidas para rutas privadas
define('EXPIRED_SESSION', 3600 * 24); // Duración de la sesión
```

### 2. Configurar el Servidor Web

El framework requiere que todas las peticiones se redirijan a `index.php`. Configura tu servidor web:

**Apache (.htaccess):**
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

**Nginx:**
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

## Generación de Código con Framecode

El framework incluye una potente herramienta CLI llamada `framecode` para generar código automáticamente.

### Uso del CLI

```bash
php framecode <tipo> <nombre> [auth]
```

### Comandos Disponibles

#### 1. Generar Solo Modelo
```bash
php framecode model Product
```
Crea: `models/Product.php`

#### 2. Generar Solo Controlador
```bash
php framecode controller Product
```
Crea: `controllers/ProductController.php`

#### 3. Generar Recurso Completo (Recomendado)
```bash
php framecode resource Product authUser
```
Crea:
- `models/Product.php`
- `controllers/ProductController.php`
- Rutas CRUD en `config/routes.php`

### Estructura Generada

Cuando generas un recurso completo, se crean automáticamente:

**Rutas generadas:**
- `GET /product/list` - Listar productos
- `GET /product/view/:id` - Ver producto específico
- `GET /product/edit/:id` - Formulario de edición
- `POST /product/update` - Actualizar producto
- `GET /product/new` - Formulario de creación
- `POST /product/create` - Crear producto

## Sistema de Rutas

### Definición de Rutas

Las rutas se definen en `config/routes.php`:

```php
<?php
// Sintaxis: $routes->método(ruta, controlador::método, autenticación, privado)

// Rutas públicas
$routes->get('', 'AuthController::login', false, false);
$routes->get('login', 'AuthController::login', false, false);
$routes->post('access', 'AuthController::access', false, false);

// Rutas protegidas
$routes->get('dashboard', 'DashboardController::index', 'authUser.admin.user', false);

// Rutas con parámetros
$routes->get('users/view/:id', 'UsersController::view', 'authUser.admin.user', false);
$routes->get('products/:category/:id', 'ProductsController::show', 'authUser', false);
```

### Tipos de Rutas

1. **GET** - Para mostrar datos
2. **POST** - Para crear/procesar datos
3. **PUT** - Para actualizar (futuro)
4. **DELETE** - Para eliminar (futuro)

### Parámetros de Ruta

- `:id` - Parámetro dinámico
- Los parámetros se pasan automáticamente al método del controlador

### Niveles de Autenticación

- `false` - Sin autenticación
- `'authUser'` - Usuario autenticado
- `'authUser.admin'` - Usuario con rol admin
- `'authUser.admin.user'` - Usuario con rol admin o user

### Rutas Privadas

Las rutas marcadas como privadas (`true`) solo son accesibles desde IPs definidas en `IPS_ALLOW`.

## Controladores

### Controlador Base

Todos los controladores extienden `BaseController`:

```php
<?php
namespace Controllers;
use core\classes\BaseController;
use Models\Product;

class ProductsController extends BaseController {
    protected $model_name = 'Product';
    
    public function __construct() {
        parent::__construct();
    }
}
```

### Métodos CRUD Automáticos

El `BaseController` proporciona métodos CRUD automáticos:

- `list($params)` - Lista paginada con búsqueda
- `view($params)` - Ver registro específico
- `edit($params)` - Formulario de edición
- `update($params)` - Procesar actualización
- `new($params)` - Formulario de creación
- `create($params)` - Procesar creación

### Métodos Personalizados

```php
public function customMethod($params) {
    // Tu lógica personalizada
    $data = $this->model->customQuery();
    
    charge_layout(
        'general',
        ['custom/view'],
        [
            'title' => 'Título Personalizado',
            'data' => $data
        ]
    );
}
```

## Modelos

### Modelo Base

Todos los modelos extienden `BaseModel`:

```php
<?php
namespace Models;
use core\classes\BaseModel;

class Product extends BaseModel
{
    public $table = 'products';
    public $primaryKey = 'id';
    public $timestamps = true;
    
    // Campos para búsqueda
    public $searchable = ['name', 'description'];
    
    // Columnas para mostrar en listas
    public $head = ['ID', 'Nombre', 'Precio', 'Categoría'];
    
    // Etiquetas
    public $label = 'Producto';
    public $plural = 'Productos';
    
    // Acciones disponibles
    public $actions = [
        'view' => ['Ver', '/products/view'],
        'edit' => ['Editar', '/products/edit'],
        'delete' => ['Eliminar', '/products/delete'],
    ];

    public function columns(): array
    {
        return [
            'id' => [
                'type' => 'int',
                'required' => true,
                'show_in_list' => true,
                'show_in_form' => false,
                'auto_increment' => true,
                'label' => 'ID'
            ],
            'name' => [
                'type' => 'varchar',
                'max_length' => 100,
                'required' => true,
                'unique' => false,
                'show_in_list' => true,
                'show_in_form' => true,
                'label' => 'Nombre',
                'type_input' => 'text'
            ],
            'price' => [
                'type' => 'decimal',
                'required' => true,
                'show_in_list' => true,
                'show_in_form' => true,
                'label' => 'Precio',
                'type_input' => 'number'
            ],
            'category_id' => [
                'type' => 'int',
                'required' => true,
                'show_in_list' => true,
                'show_in_form' => true,
                'label' => 'Categoría',
                'type_input' => 'select',
                'foreign_key' => [
                    'table' => 'categories',
                    'column' => 'id',
                    'display_column' => 'name'
                ]
            ],
            'description' => [
                'type' => 'text',
                'required' => false,
                'show_in_list' => false,
                'show_in_form' => true,
                'label' => 'Descripción',
                'type_input' => 'text_area'
            ]
        ];
    }
}
```

### Propiedades de Columnas

- `type` - Tipo de dato (int, varchar, text, etc.)
- `required` - Campo obligatorio
- `unique` - Valor único en la base de datos
- `show_in_list` - Mostrar en listados
- `show_in_form` - Mostrar en formularios
- `auto_increment` - Campo auto-incremental
- `encrypted` - Encriptar valor (para contraseñas)
- `label` - Etiqueta para mostrar
- `type_input` - Tipo de input HTML
- `foreign_key` - Relación con otra tabla
- `select_options` - Opciones para selects

### Métodos Disponibles

```php
// Crear registro
$model->create($data);

// Buscar todos
$model->all();

// Buscar con condiciones
$model->find($where, $type, $order, $limit, $page);

// Buscar por campo
$model->findBy($field, $value);

// Actualizar
$model->update($id, $data);

// Eliminar
$model->delete($id);

// Consulta personalizada
$model->query($sql, $params);
```

## Vistas y Layouts

### Sistema de Layouts

El framework usa un sistema de layouts para mantener consistencia:

```php
charge_layout($layout, $views, $data);
```

**Parámetros:**
- `$layout` - Layout a usar ('general', 'nouser')
- `$views` - Array de vistas a cargar
- `$data` - Datos a pasar a las vistas

### Ejemplo de Uso

```php
charge_layout(
    'general',
    ['products/list'],
    [
        'title' => 'Lista de Productos',
        'products' => $products,
        'categories' => $categories
    ]
);
```

### Layouts Disponibles

1. **general** - Layout con autenticación (header, sidebar)
2. **nouser** - Layout sin autenticación (solo login)

### Componentes (Partials)

Carga componentes reutilizables:

```php
charge_partial('header', $data);
charge_partial('sidebar', $data);
charge_partial('footer', $data);
```

### Plantillas Automáticas

El framework incluye plantillas automáticas para CRUD:

- `templates/list` - Lista con paginación y búsqueda
- `templates/form` - Formulario automático basado en el modelo

## Sistema de Autenticación

### Clase de Autenticación

Crea clases de autenticación extendiendo `Auth`:

```php
<?php
namespace Auth;
use core\classes\Auth;

class AuthUser extends Auth
{
    public function canAccess($roles = []): bool
    {
        global $user;
        
        // Verificar si el usuario está logueado
        if (!$user) {
            return false;
        }
        
        // Si no se especifican roles, cualquier usuario autenticado puede acceder
        if (empty($roles)) {
            return true;
        }
        
        // Verificar si el usuario tiene el rol requerido
        return in_array($user['role'], $roles);
    }
}
```

### Gestión de Sesiones

El framework maneja automáticamente:
- Inicio de sesión
- Expiración de sesiones
- Verificación de permisos
- Redirecciones automáticas

### Usuario Global

El usuario autenticado está disponible globalmente:

```php
global $user;
echo $user['name']; // Nombre del usuario
echo $user['role']; // Rol del usuario
```

## Componentes y Helpers

### Componentes de Formulario

```php
// Input de texto
input('text', 'name', 'Nombre', $value);

// Select
select('category', $options, 'Categoría', $selected);

// Textarea
textarea('description', $value, 'Descripción');

// Botón
button('submit', 'Guardar');
```

### Helpers Útiles

```php
// Debug
e($variable); // Muestra variable con información de debug

// URLs
current_url(); // URL actual completa
current_path(); // Ruta actual
current_segments(); // Segmentos de la URL como array

// HTTP
method_request(); // Método HTTP actual

// Archivos
get_files_in_directory($directory); // Lista archivos en directorio
```

## Base de Datos

### Configuración

La configuración se realiza en `config/config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'mi_base_datos');
define('DB_USER', 'usuario');
define('DB_PASS', 'contraseña');
```

### Clase Db

```php
use core\classes\Db;

$db = new Db();
$conn = $db->getConnection(); // Retorna instancia PDO
```

### Consultas Personalizadas

```php
// En un modelo
public function customQuery() {
    $sql = "SELECT * FROM products WHERE price > :price";
    $params = [':price' => 100];
    return $this->query($sql, $params);
}
```

## Flujo de Trabajo Recomendado

### 1. Crear un Nuevo Módulo

```bash
# Generar recurso completo
php framecode resource Product authUser
```

### 2. Personalizar el Modelo

Edita `models/Product.php`:
- Define las columnas en `columns()`
- Ejecuta las migraciones
- Configura propiedades del modelo
- Añade métodos personalizados si es necesario

### 3. Personalizar el Controlador

Edita `controllers/ProductController.php`:
- Añade métodos personalizados
- Sobrescribe métodos CRUD si es necesario

### 4. Crear Vistas Personalizadas

Si necesitas vistas más allá de las plantillas automáticas:
- Crea archivos en `views/pages/products/`
- Usa `charge_layout()` en el controlador

### 5. Configurar Permisos

Ajusta los permisos en las rutas según tus necesidades de autenticación.

## Consejos y Mejores Prácticas

1. **Usa el generador de código** - Te ahorra tiempo y mantiene consistencia
2. **Define bien las columnas** - El sistema automático depende de esta definición
3. **Mantén la estructura** - No modifiques los archivos del core sin necesidad
4. **Usa las plantillas automáticas** - Son responsive y funcionales
5. **Aprovecha los helpers** - Facilitan tareas comunes
6. **Configura bien la autenticación** - Define roles claros para tu aplicación

## Soporte y Extensión

Este framework está diseñado para ser extensible. Puedes:
- Añadir nuevas clases en `core/classes/`
- Crear componentes reutilizables en `core/components/`
- Añadir helpers en `core/config/helpers.php`
- Crear nuevos layouts en `views/layouts/`

Para soporte o contribuciones, revisa el código fuente y adapta según tus necesidades específicas.


## Migraciones y Seeders

El framework incluye un sistema completo de migraciones que permite gestionar la estructura de la base de datos y poblarla con datos iniciales.

### URLs de Migración

El sistema proporciona tres endpoints principales para gestionar las migraciones:

#### 1. Ejecutar Solo Migraciones
```
GET /migrations
```
Ejecuta únicamente las migraciones pendientes, creando o modificando la estructura de las tablas.

#### 2. Ejecutar Migraciones y Seeders
```
GET /migrations/all
```
Ejecuta las migraciones pendientes y posteriormente ejecuta todos los seeders para poblar las tablas con datos iniciales.

#### 3. Recrear Base de Datos Completa
```
GET /migrations/all/fresh
```
- Elimina todas las tablas existentes
- Recrea la estructura completa de la base de datos
- Ejecuta todas las migraciones desde cero
- Ejecuta todos los seeders

### Sistema de Migraciones

Las migraciones se generan automáticamente cuando creas un modelo con el comando `framecode`. El sistema:

1. **Analiza la estructura** definida en el método `columns()` del modelo
2. **Genera las tablas** con los tipos de datos correctos
3. **Crea índices** automáticamente para claves foráneas
4. **Establece relaciones** entre tablas

### Ejemplo de Migración Automática

Cuando defines un modelo como este:

```php
public function columns(): array
{
    return [
        'id' => [
            'type' => 'int',
            'auto_increment' => true,
            'required' => true
        ],
        'name' => [
            'type' => 'varchar',
            'max_length' => 100,
            'required' => true,
            'unique' => true
        ],
        'category_id' => [
            'type' => 'int',
            'required' => true,
            'foreign_key' => [
                'table' => 'categories',
                'column' => 'id'
            ]
        ]
    ];
}
```

El sistema genera automáticamente:
- Tabla `products` con las columnas especificadas
- Clave primaria en `id`
- Índice único en `name`
- Clave foránea referenciando `categories.id`
- Campos `created_at` y `updated_at` si `$timestamps = true`

### Seeders

Los seeders permiten poblar las tablas con datos iniciales. Para crear un seeder:

1. **Crear archivo en la carpeta `seeders/`** con el mismo nombre del modelo
2. **Definir variable `$seed`** que contenga un array con los datos a insertar

#### Estructura de Seeders

```
seeders/
├── Product.php
├── Category.php
├── User.php
└── ...
```

#### Ejemplo de Seeder

**Archivo: `seeders/Product.php`**
```php
<?php

$seed = [
    [
        'name' => 'Producto Demo 1',
        'price' => 99.99,
        'category_id' => 1,
        'description' => 'Descripción del producto demo'
    ],
    [
        'name' => 'Producto Demo 2',
        'price' => 149.99,
        'category_id' => 2,
        'description' => 'Otro producto de ejemplo'
    ],
    [
        'name' => 'Producto Demo 3',
        'price' => 79.99,
        'category_id' => 1,
        'description' => 'Tercer producto de la línea demo'
    ]
];
```

**Archivo: `seeders/Category.php`**
```php
<?php

$seed = [
    [
        'name' => 'Electrónicos',
        'description' => 'Productos electrónicos y tecnología'
    ],
    [
        'name' => 'Ropa',
        'description' => 'Vestimenta y accesorios'
    ],
    [
        'name' => 'Hogar',
        'description' => 'Artículos para el hogar'
    ]
];
```

#### Orden de Ejecución

Los seeders se ejecutan automáticamente respetando las dependencias de claves foráneas. En el ejemplo anterior, `Category` se ejecutaría antes que `Product` para garantizar que las categorías existan cuando se inserten los productos.

### Flujo de Trabajo con Migraciones

#### Desarrollo Inicial
```bash
# 1. Generar modelo con estructura
php framecode resource Product authUser

# 2. Ejecutar migraciones para crear las tablas
GET /migrations

# 3. Poblar con datos de prueba
GET /migrations/all
```

#### Durante el Desarrollo
```bash
# Modificar estructura del modelo en columns()
# Ejecutar migraciones para aplicar cambios
GET /migrations
```

#### Reset Completo (Desarrollo)
```bash
# Cuando necesites empezar desde cero
GET /migrations/all/fresh
```

#### Producción
```bash
# Solo ejecutar migraciones nuevas
GET /migrations
```

### Consideraciones Importantes

1. **Seguridad**: Las rutas de migración deben estar protegidas en producción
2. **Backups**: Siempre respalda la base de datos antes de usar `/fresh`
3. **Orden**: Las migraciones respetan las dependencias de claves foráneas
4. **Rollback**: El sistema no incluye rollback automático - usa backups

