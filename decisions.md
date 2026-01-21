# Documentación de Decisiones Técnicas

## 1. Arquitectura y Patrones

### Modelo Vista Controlador (MVC)
Se ha implementado el patrón MVC estándar de Yii2 para mantener una separación clara de responsabilidades.
- **Modelos**: Encapsulan la lógica de negocio y reglas de validación (ej. `User`, `Product`).
- **Vistas**: Responsables de la presentación, utilizando Bootstrap 5 para el diseño responsivo.
- **Controladores**: Manejan las peticiones del usuario y orquestan la respuesta.

### RBAC (Role-Based Access Control)
Se utiliza el componente `authManager` de Yii2 para la gestión de permisos.
- **Roles definidos**:
    - `admin`: Acceso total (Gestión de usuarios y productos).
    - `editor`: Puede editar productos pero no gestionar usuarios.
    - `viewer`: Solo lectura.
- **Inicialización**: Se proporciona un comando `InitRbacController` para configurar los roles iniciales y el usuario administrador por defecto.

## 2. Gestión de Usuarios y Seguridad

### Login y Estados
- Se ha modificado el flujo de login para detectar intentos de acceso de usuarios **Inactivos**.
- El formulario de login valida explícitamente el estado del usuario (`Active` vs `Inactive`) proporcionando feedback claro.
- **Seguridad**: `User::findIdentity` solo retorna usuarios activos para prevenir la reutilización de sesiones de usuarios desactivados.

### Contraseñas
- Se utiliza `Yii::$app->security` para el hash de contraseñas (bcrypt por defecto).

## 3. Entorno y Despliegue

### Docker
Se incluye configuración de Docker para facilitar el despliegue y pruebas.
- `docker-compose.yml`: Define los servicios de App (PHP-FPM) y Nginx.

## 4. UI/UX

### Estilos
- Se utiliza Bootstrap 5 nativo.
- Iconografía con FontAwesome.
- Tema oscuro para la barra de navegación para mejorar contraste.

## 5. Localización
- Todo el código, comentarios y textos de interfaz están localizados al Español.
