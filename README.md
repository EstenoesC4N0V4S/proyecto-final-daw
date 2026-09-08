# 🏎️ F1 Setup Manager & Store

Aplicación web centrada en el mundo de la **Fórmula 1**, desarrollada como **Proyecto Final del Grado Superior en Desarrollo de Aplicaciones Web (DAW)**.

El proyecto combina información sobre la Fórmula 1 con un sistema de **gestión y visualización de setups** inspirado en los videojuegos oficiales de F1.

Cada usuario puede consultar y compartir sus propias configuraciones, explorar los setups creados por otros jugadores y acceder a una **tienda online** con catálogo de productos y carrito de compra.

🌐 **Proyecto online:** `https://f1setupsim.infinityfreeapp.com/login`

---

### 🚀 Funcionalidades principales

#### 🏎️ Gestión de setups

* ⚙️ Creación y gestión de setups personalizados.
* 🔧 Configuración de diferentes parámetros del vehículo.
* 📋 Consulta de setups creados por otros usuarios.
* 🔄 Edición y eliminación de setups propios.
* 🏁 Configuración según circuito y condiciones.
* 📊 Visualización de datos y telemetría mediante gráficos.

#### 👤 Usuarios y seguridad

* 📝 Registro de usuarios.
* 🔐 Inicio y cierre de sesión.
* 👤 Gestión de cuentas de usuario.
* 🔒 Sistema de autenticación y autorización.
* 🛡️ Protección de URLs y rutas según el tipo de usuario.
* 👨‍💼 Rutas y funcionalidades exclusivas para administradores.
* 👤 Restricción de determinadas funcionalidades para usuarios normales.
* 🚫 Los usuarios normales no pueden acceder a las rutas reservadas para administradores.
* 🔐 Control de permisos según el rol del usuario.

#### 🛒 Tienda online

* 🛍️ Catálogo de productos.
* 🔎 Consulta de productos.
* 🛒 Añadir productos al carrito.
* ➕ Modificación de cantidades.
* 🗑️ Eliminación de productos del carrito.
* 💳 Gestión del proceso de compra.
* 📦 Consulta de pedidos.

#### 👨‍💼 Panel de administración

* 👥 Gestión de usuarios.
* 📦 Gestión de productos.
* 🛒 Gestión de pedidos.
* ⚙️ Gestión de la información de la aplicación.
* 🔐 Acceso protegido mediante control de permisos.
* 📊 Administración de los datos almacenados.

#### ⚡ Funcionalidades dinámicas

* 🔄 Peticiones asíncronas mediante AJAX.
* 📊 Gráficos y telemetría con Chart.js.
* ⏳ Actualización dinámica de información.
* ❌ Validación de datos y gestión de errores.
* 📱 Diseño responsive.
* 🎨 Animaciones mediante AOS.

---

### 🔐 Credenciales de demostración

Para probar las funcionalidades de administración de la aplicación:

**Administrador**

```text
Usuario: rodrigo@correo.com
Contraseña: 1234
```

> Estas credenciales corresponden únicamente a una cuenta de demostración del proyecto.

---

### 🛠️ Tecnologías utilizadas

| Tecnología          | Uso                         |
| ------------------- | --------------------------- |
| **HTML5**           | Estructura de la aplicación |
| **CSS3**            | Estilos y diseño            |
| **JavaScript**      | Funcionalidades dinámicas   |
| **Bootstrap 5**     | Diseño responsive           |
| **SCSS**            | Preprocesamiento de CSS     |
| **PHP**             | Backend y lógica de negocio |
| **MySQL / MariaDB** | Gestión de la base de datos |
| **PDO**             | Acceso a la base de datos   |
| **AJAX**            | Peticiones asíncronas       |
| **MVC**             | Arquitectura del proyecto   |
| **Composer**        | Gestión de dependencias     |
| **Chart.js**        | Gráficos y telemetría       |
| **AOS**             | Animaciones                 |
| **Figma**           | Diseño y prototipado        |

---

### 🏗️ Arquitectura

El proyecto utiliza el patrón de arquitectura **MVC (Modelo-Vista-Controlador)**, separando las diferentes responsabilidades de la aplicación:

* **Modelo:** gestión de datos y comunicación con la base de datos.
* **Vista:** interfaz y presentación de la información.
* **Controlador:** gestión de la lógica de negocio y comunicación entre modelos y vistas.

Esta arquitectura permite mantener una aplicación más **organizada, escalable y fácil de mantener**.

---

### ⚙️ Instalación

Para ejecutar el proyecto en local:

### 1. Clonar el repositorio

```bash
git clone URL_DEL_REPOSITORIO
```

### 2. Instalar XAMPP

Instalar y ejecutar **Apache** y **MySQL/MariaDB** desde XAMPP.

### 3. Copiar el proyecto

Colocar la carpeta del proyecto dentro de:

```text
C:\xampp\htdocs\
```

### 4. Crear la base de datos

Acceder a **phpMyAdmin**, crear la base de datos e importar el archivo `.sql` incluido en el proyecto.

### 5. Configurar la conexión

Configurar los datos de conexión a la base de datos en el archivo correspondiente del proyecto.

### 6. Instalar dependencias

Si el proyecto utiliza Composer:

```bash
composer install
```

### 7. Acceder a la aplicación

Abrir el navegador y acceder mediante:

```text
http://localhost/proyecto-final-daw
```

---

### 📂 Estructura del proyecto

```text
proyecto-final-daw/
│
├── app/
│   ├── controllers/
│   ├── models/
│   └── views/
│
├── public/
│   ├── css/
│   ├── js/
│   └── img/
│
├── config/
├── database/
├── vendor/
├── composer.json
└── README.md
```

> La estructura puede variar dependiendo de la organización final del proyecto.

---

### 🎓 Proyecto Final DAW

Proyecto desarrollado como parte del **Grado Superior en Desarrollo de Aplicaciones Web**, aplicando los conocimientos adquiridos durante el ciclo en:

* Desarrollo frontend.
* Desarrollo backend.
* Bases de datos.
* Arquitectura MVC.
* Diseño responsive.
* Autenticación y autorización.
* Control de acceso mediante roles.
* Desarrollo de aplicaciones web dinámicas.
* Peticiones AJAX.
* Gestión de usuarios.
* Desarrollo de una tienda online.

---

### 👨‍💻 Autor

**Rodrigo Cánovas Moreta**

Proyecto desarrollado con fines académicos y como parte de mi **portfolio profesional**.

---

⭐ Si te gusta el proyecto, ¡no dudes en darle una estrella al repositorio!

