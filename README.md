# Points API

_Api/REST para consultar localización de puntos (sensores)_

### Pre-requisitos 📋

_Laravel 6_
_PHP 7.4_
_BD Mysql - Gestor Mysql Workbench (opcional)_
_XAMPP (última versión preferiblemente)_
_Postman (testear endpoints)_
_Extensiones Tokenizer y xDebug (verificar la cobertura de código)_

### Instalación 🔧

_1)	Usando la consola, clonar proyecto dentro del directorio xampp/htdocs/. Si se usa otro servidor distinto a XAMPP, clonar en la carpeta contenedora de proyectos respectivamente._
_2)	En la consola, ir a la raíz del proyecto (points-api) y ejecutar el comando:_
```
composer install
```
_3)	Los datos de conexión a la BD se hallan en el archivo .env (Prefijo: DB_). Una vez conectado, crear esquema ejecutando el siguiente comando:_
```
CREATE SCHEMA IF NOT EXISTS locations;
```
_4)	En la raíz del proyecto ejecutar migraciones:_
```
php artisan migrate
```
_5)	Ejecutar seeder:_
```
php artisan db:seed --class=PointSeeder
```

### Ejecutando la API 🔧

_1) En la raíz del proyecto, ejecutar el comando:_
```
php artisan serve
```
_2) Abrir la aplicación Postman, crear las siguientes solicitudes:_

 
- Tipo: POST, Headers: Content-Type => application/x-www-form-urlencoded, Url: http://localhost:8000/api/points (Crear punto)
```
Parámetros de prueba en la sección Body:
name:punto 1
coordinate_x:2
coordinate_y:2
```
- Tipo: PUT, Headers: Content-Type => application/x-www-form-urlencoded, Url: http://localhost:8000/api/points/{id} (Actualizar un punto)
```
Parámetros de prueba en la sección Body:
name:punto 1
coordinate_x:5
coordinate_y:3
```
- Tipo: DELETE, Headers: Content-Type => application/json, Url: http://localhost:8000/api/points/{id} (Eliminar un punto)
- Tipo: GET, Headers: Content-Type => application/json, Url: http://localhost:8000/api/points/{id} (Obtener un punto)
- Tipo: GET, Headers: Content-Type => application/json, Url: http://localhost:8000/api/nearest-points/{id}/{cantidad} (Obtener los puntos más cercanos dado un punto y cantidad)

