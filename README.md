# 👨🏼‍🏫 Proyecto DBprofes 👩🏼‍🏫

DBprofes es un proyecto de código abierto para que los alumnos puedan agregar opiniones sobre sus profesores para ayudar a futuros estudiantes.

## Version

Version: BETA 0.2.1

## ⚒️ Funcionalidades

<li>Cuentas de usuario</li>
<li>Opiniones sobre los profesores</li>
<li>Respuestas a opiniones</li>
<li>Sistema de likes</li>
<li>Carga parcial de registros</li>
<li>Carga de imágenes</li>
<li>Búsqueda avanzada</li>
<li>Tema claro/oscuro</li>
<li>Personalización de perfil de usuario</li>

## 🚀Instalación

# Requerimientos

Debes poder ejecutar proyectos php y crear una base de datos local, para ello puedes instalar XAMPP o Apache y MySQL, también necesitarás composer y node.js

# Clona el repositorio

```bash

git clone https://github.com/cindyita/dbprofes.git
```

# Navega al directorio

```bash
cd dbprofes
```

# Instala las dependencias

Necesitas node.js y composer

```bash
npm install
```

```bash
composer install
```

# Configuraremos nuestro .env y la base de datos local

> [!NOTE]
> Tendremos que clonar los archivos .env.example y .env.local.example y remover el .example de los archivos clonados para que queden como: .env y .env.local (No borres los originales)

Configuraremos nuestra base de datos local en el archivo .env.local

Importa los datos de las tablas necesarias con el archivo db/dbprofes_db.sql en tu base de datos local

# Carpetas necesarias

Si no existen las siguientes carpetas debes crearlas:
<li>/log</li>
<li>/assets/img/user</li>
<li>/assets/img/posts</li>
<li>/assets/img/responses</li>

## Proyecto en vivo

Proyecto en vivo: http://dbprofes.theblux.com

## Agradecimientos

¡Espero que te sea de ayuda!
Puedes mejorar el proyecto enviando un pull request
Si te ha gustado dame una estrella ⭐
