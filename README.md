# Points API

_Api/REST para consultar localización de puntos (sensores)_

### Pre-requisitos 📋

- Laravel 6
- PHP 7.4
- BD Mysql / Gestor Mysql Workbench (opcional)
- XAMPP (última versión preferiblemente)
- Postman (testear endpoints)
- Extensiones Tokenizer y xDebug (verificar la cobertura de código)

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
_6) Paso Opcional: Instalar xDebug y Tokenizer_
xDebug: Es una extensión de PHP para hacer debug.
-https://xdebug.org/docs/install
-https://gist.github.com/odan/1abe76d373a9cbb15bed
Tokenizer: 
- Windows: Incorporado al instalar XAMPP o WAMPP.
- Ubuntu:
```
sudo apt install php-tokenizer
```

### Ejecutando la API 🔧

_1) En la raíz del proyecto, ejecutar el comando:_
```
php artisan serve
```
_2)Iniciar el servidor de BD Mysql_
_3) Abrir la aplicación Postman, crear las siguientes solicitudes:_
 
- Tipo: POST, Url: http://localhost:8000/api/points (Crear punto), Headers: Content-Type => application/x-www-form-urlencoded
```
Parámetros de prueba en la sección Body:
name:punto 1
coordinate_x:2
coordinate_y:2
```
- Tipo: PUT, Url: http://localhost:8000/api/points/{id} (Actualizar un punto), Headers: Content-Type => application/x-www-form-urlencoded
```
Parámetros de prueba en la sección Body:
name:punto 1
coordinate_x:5
coordinate_y:3
```
- Tipo: DELETE, Url: http://localhost:8000/api/points/{id} (Eliminar un punto), Headers: Content-Type => application/json
- Tipo: GET, Url: http://localhost:8000/api/points/{id} (Obtener un punto), Headers: Content-Type => application/json
- Tipo: GET, Url: http://localhost:8000/api/nearest-points/{id}/{cantidad} (Obtener los puntos más cercanos dado un punto y cantidad), Headers: Content-Type => application/json

_Después de completar los datos para cada solicitud, hacer click en el botón "Send". Verificar las respuestas en la pestaña Body de la sección Respuesta._

### Ejecutando Unit Tests 🔧

_1) Para verififcar si los casos de prueba pasaron, ejecutar el comando en la raíz del proyecto:_
```
vendor\bin\phpunit
```
_2) *Paso Opcional:* Para verificar la cobertura en la consola, ejecutar_
```
vendor\bin\phpunit --coverage-text
```