# Sistema de Inventario Abitmedia

## Descripción del Proyecto
Este es un sistema de gestión de inventario desarrollado en **PHP 8.2** utilizando el framework **Yii2**. El proyecto sigue una arquitectura MVC robusta con implementación de Service/Repository pattern para lógica de negocio compleja, contenedorización con Docker, y un estricto control de acceso basado en roles (RBAC).

El objetivo es proporcionar una solución "production-ready" con buenas prácticas de seguridad, escalabilidad y mantenibilidad.

## Requisitos Previos
- **Docker Desktop** (instalado y ejecutándose)
- **Git**

## Instrucciones de Instalación

1.  **Clonar el repositorio**:
    ```bash
    git clone <url-del-repositorio>
    cd prueba_abitmedia
    ```

2.  **Levantar el entorno Docker**:
    Este comando descargará las imágenes necesarias, construirá el contenedor de la aplicación y levantará los servicios (App, Web/Nginx, DB).
    ```bash
    docker-compose up -d --build
    ```

3.  **Instalar dependencias de PHP** (si no se instalaron en el build):
    ```bash
    docker-compose exec app composer install
    ```

4.  **Ejecutar Migraciones y Seeders**:
    Iniciar la base de datos y poblar con datos de prueba.
    ```bash
    # Ejecutar migraciones
    docker-compose exec app php yii migrate --interactive=0
    
    # Inicializar RBAC (Permisos y Roles)
    docker-compose exec app php yii init-rbac
    
    # Poblar base de datos (SeedProductos)
    docker-compose exec app php yii seed
    ```

## Acceso al Sistema
- **URL**: [http://localhost:8000](http://localhost:8000)
- **Credenciales por defecto**:
    - **Usuario**: `admin`
    - **Contraseña**: `admin123`

## Estructura de Base de Datos
La base de datos MySQL 8.0 está normalizada e incluye las siguientes tablas principales:

- **user**: Almacena credenciales y datos de perfil.
- **product**: Catálogo de productos con SKU, precio, stock, etc.
- **auth_item / auth_assignment**: Tablas del sistema RBAC de Yii2 para gestión flexible de permisos.

## Decisiones Técnicas

### Arquitectura
- **MVC (Modelo-Vista-Controlador)**: Separación clara de responsabilidades.
- **Docker Multi-Stage**: Optimizamos el Dockerfile para construir dependencias en una etapa separada, reduciendo el tamaño y mejorando la caché.

### Seguridad
- **RBAC (Role-Based Access Control)**: Implementado nativamente.
    - `admin`: Acceso total.
    - `editor`: Puede editar pero no administrar usuarios.
    - `viewer`: Solo lectura.
- **OWASP**:
    - Sanitización automática de inputs por Yii2.
    - CSRF Protection habilitado globalmente.
    - Contraseñas hasheadas con Bcrypt.

### Calidad de Código
- **Type Hinting**: Uso de tipos estrictos en PHP 8.2.
- **PSR-12**: Estándares de codificación.
