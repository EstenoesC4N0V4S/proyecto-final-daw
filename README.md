# 🏎️ F1 Setup Manager & Store

Aplicación web centrada en el mundo de la **Fórmula 1**, desarrollada como **Proyecto Final del Grado Superior en Desarrollo de Aplicaciones Web (DAW)**.

El proyecto combina información sobre la Fórmula 1 con un sistema de **gestión y visualización de setups** inspirado en los videojuegos oficiales de F1.

Cada usuario puede consultar y compartir sus propias configuraciones, explorar los setups creados por otros jugadores y acceder a una **tienda online** con catálogo de productos y carrito de compra.

---

## 🚀 Funcionalidades

* 🏎️ Gestión y consulta de información relacionada con la Fórmula 1.
* ⚙️ Creación y gestión de setups para los coches.
* 📊 Visualización de datos y telemetría mediante gráficos.
* 👤 Registro, inicio de sesión y gestión de usuarios.
* 🛒 Tienda online con catálogo de productos.
* 🛍️ Carrito de compra.
* 👨‍💼 Panel de administración.
* 📦 Gestión y consulta de pedidos.
* 🔄 Funcionalidades dinámicas mediante AJAX.
* 📱 Diseño responsive adaptado a diferentes dispositivos.

---

## 🔐 Credenciales de demostración

Para probar las funcionalidades de administración de la aplicación:

**Administrador**
- Usuario: `rodrigo@correo.com`
- Contraseña: `1234`

> Estas credenciales corresponden únicamente a una cuenta de demostración del proyecto.


---

## 🛠️ Tecnologías utilizadas

| Tecnología          | Uso                         |
| ------------------- | --------------------------- |
| **HTML5**           | Estructura de la aplicación |
| **CSS3**            | Estilos y diseño            |
| **JavaScript**      | Funcionalidades dinámicas   |
| **Bootstrap 5**     | Diseño responsive           |
| **SCSS**            | Preprocesamiento de CSS     |
| **PHP**             | Backend y lógica de negocio |
| **MySQL / MariaDB** | Base de datos               |
| **PDO**             | Acceso a base de datos      |
| **AJAX**            | Peticiones asíncronas       |
| **MVC**             | Arquitectura del proyecto   |
| **Composer**        | Gestión de dependencias     |
| **Chart.js**        | Gráficos y telemetría       |
| **AOS**             | Animaciones                 |
| **Figma**           | Diseño y prototipado        |

---

## 🏗️ Arquitectura

El proyecto utiliza el patrón de arquitectura **MVC (Modelo-Vista-Controlador)**, separando:

* **Modelo:** gestión de datos y comunicación con la base de datos.
* **Vista:** interfaz y presentación de la información.
* **Controlador:** lógica de negocio y comunicación entre modelos y vistas.

Esta arquitectura permite mantener una aplicación más **organizada, escalable y fácil de mantener**.

---

## ⚙️ Instalación

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

### 6. Acceder a la aplicación

Abrir el navegador y acceder mediante:

```text
http://localhost/proyecto-final-daw
```

---

## 📂 Estructura del proyecto

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

## 🎓 Proyecto Final DAW

Proyecto desarrollado como parte del **Grado Superior en Desarrollo de Aplicaciones Web**, con el objetivo de aplicar de forma práctica los conocimientos adquiridos durante el ciclo en:

* Desarrollo frontend.
* Desarrollo backend.
* Bases de datos.
* Arquitectura MVC.
* Diseño responsive.
* Gestión de usuarios.
* Desarrollo de aplicaciones web dinámicas.

---

## 👨‍💻 Autor

**Rodrigo Cánovas Moreta**

Proyecto desarrollado con fines académicos y como parte de mi **portfolio profesional**.

---

⭐ Si te gusta el proyecto, ¡no dudes en darle una estrella al repositorio!
