# DWESproject
Proyecto final para el módulo de 'Desarrollo web en entorno servidor' del C.S. Desarrollo de aplicaciones web, en el que había que obtener información de meteogalicia, cuyos servicios web se basan en REST, y mostrársela al usuario.
Paso a paso:
1) Primero el usuario accede a la página index.php donde le dan la bienvenida y le piden un usuario y una contraseña.
2) Si el usuario aún no está registrado, le dirige a la página de registro, donde, a mayores de usuario y contraseña, que ya los ha dado, se le pide que seleccione un rol y una estación meteorológica. 
3)En el caso de que el usuario ya se haya registrado, el index.php le dirige a la ‘página del tiempo’, donde se le mostrarán los datos actualizados de su estación meteorológica.
4)En esta página, además puede borrar sus datos y desconectarse. Si su rol es de administrador, además podrá ver los datos de los usuarios registrados.
