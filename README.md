# Freestyle Shop

**Freestyle Shop** es una plataforma web de e-commerce desarrollada en PHP y PostgreSQL, orientada a la gestión y venta de productos de moda. Incluye un panel administrativo robusto y una experiencia de compra moderna para el cliente final.

## Características principales

- **Catálogo público de productos** con imágenes, precios y ofertas.
- **Carrito de compras** persistente por usuario o sesión.
- **Gestión de productos, categorías y subcategorías** desde el panel admin.
- **Gestión de inventario** por sucursal.
- **Módulo de ingresos y transferencias de productos** entre sucursales.
- **Gestión de pedidos** y control de estados.
- **Panel de usuario y perfil**.
- **Sistema de autenticación** para clientes y administradores.
- **Ofertas y descuentos** configurables por producto.
- **Reportes y exportación a PDF/Excel**.
- **Frontend responsivo** con Tailwind CSS y componentes modernos.
- **Uso de iconos Font Awesome** para una mejor experiencia visual.

## Instalación

1. **Clona el repositorio:**
   ```bash
   git clone https://github.com/tuusuario/freestyle-shop.git
   ```

2. **Configura la base de datos:**
   - Crea una base de datos PostgreSQL.
   - Importa el script `bd/freestyle-shop.sql`.

3. **Configura la conexión:**
   - Edita los archivos de conexión en `conexion/cone.php` con tus credenciales de PostgreSQL.

4. **Instala dependencias PHP (si usas Composer):**
   ```bash
   composer install
   ```

5. **Configura el servidor web:**
   - Puedes usar XAMPP, WAMP, o cualquier servidor compatible con PHP 8+.

6. **Accede a la aplicación:**
   - Navega a `http://localhost/freestyle-shop` en tu navegador.

## Uso

- **Panel de administración:** Permite gestionar productos, inventario, pedidos, usuarios, sucursales y reportes.
- **Vista cliente:** Permite navegar el catálogo, agregar productos al carrito, realizar pedidos y ver ofertas.
- **Autenticación:** Usuarios y administradores pueden iniciar sesión y gestionar su perfil.

## Dependencias principales

- **PHP 8+**
- **PostgreSQL**
- **Tailwind CSS** (CDN)
- **Font Awesome** (CDN)
- **jQuery** (solo para algunos componentes admin)
- **Swiper.js** (para sliders de ofertas)
- **Composer** (opcional, para dependencias PHP)

## Seguridad

- Uso de sesiones PHP para autenticación.
- Hash de contraseñas con `password_hash`.
- Validación de roles para acceso a paneles restringidos.
- Consultas SQL parametrizadas para evitar inyecciones.

## Personalización

- Puedes modificar los estilos en los archivos de Tailwind o agregar tus propios assets en la carpeta `assets/`.
- Las consultas SQL están centralizadas en la carpeta `utils/` y en los archivos *_queries.php de cada módulo.

## Créditos

Desarrollado por el equipo de Freestyle Shop.
