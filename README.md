## 🔧 Resumen de comandos para clonar el proyecto con Docker (DESARROLLO)

### 1. Clonar el repositorio (si aún no lo has hecho)
Clona el repositorio y navega a la carpeta del proyecto
```bash
git clone https://github.com/adrianC05/sistema-tutorias.git
cd sistemas-tutorias
```
#### 1.1 Generar archivo .env y editar las variables de entorno
```bash
cp .env.example  .env
```

### 2. Construir los contenedores
Construye las imágenes de los contenedores (Laravel, MySQL, Nginx) definidos en el archivo docker-compose.yml


```bash
docker-compose build
```

O bien, construye y levanta los contenedores al mismo tiempo
```bash
docker-compose up --build
```

### 3. Levantar los contenedores
Levanta todos los contenedores definidos en el archivo docker-compose.yml en segundo plano
```bash
docker-compose up -d
```

### 4. Verificar los contenedores en ejecución
Verifica que los contenedores se están ejecutando correctamente
```bash
docker ps
```

### 5. Acceder al contenedor de Laravel
Accede al contenedor de Laravel para ejecutar comandos dentro de él
```bash
docker exec -it sistema_app_dev bash
```

Ejecuta comandos de Laravel como migraciones o generación de clave
```bash
php artisan key:generate
php artisan migrate
php artisan make:filament-user
## Recrear base de datos
php artisan migrate:refresh --seed