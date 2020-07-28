<?php
//Necesitamos el código de los siguientes archivos
require_once 'recursos/Conexion.php';
require_once 'recursos/Usuario.php';

// Comprobamos si ya se ha enviado el formulario
if (isset($_POST['enviar'])) {

    //Primero guardamos los datos introducidos por el usuario
    //pero para evitar el Cross-site scripting
    //utilizamos htmlentities para transformar los caracteres especiales a html
    //y addslashes para escaparlos
    $nick = htmlentities(addslashes($_POST['nick']));
    $password = htmlentities(addslashes($_POST['pass']));
    //Además transformamos las contraseñas con la función md5
    $pass = md5($password);


    // Conectamos a la base de datos
    try {
        $conexion = new Conexion();
    }
    //En el caso de que haya algún error en la conexión lo capturamos
    catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }

    //Guardamos en esta variable el resultado de buscar una coincidencia
    //en la tabla usuarios con el nick escrito por el usuario
    $repetido = Usuario::buscarNick($nick);
    //Guardamos en esta variable el resultado de buscar una coincidencia
    //pero esta vez con nick y contraseña insertado por el usuario
    $usuario = Usuario::buscar($nick, $pass);

    //Una vez tenemos el valor de dichas consultas
    try {
        //Si se repite el nick pero no la contraseña
        if ($repetido && !$usuario) {
            //Lanzamos el mensaje para que o bien cambie de nick o revise su contraseña
            $mensaje = "<p class='alerta'>El nombre de usuario está repetido.<br/>Por favor elige otro nombre o revisa tu contraseña.</p><br/>";
        } elseif ($usuario) {
            //En el caso de que coincida nick y contraseña
            //Que quiere decir que ya está registrado en nuestra BBDD
            //Iniciamos la sesión
            session_start();
            //Y guardamos en dicha sesión todos los valores (que obtenemos de la BBDD gracias a Usuario)
            $_SESSION['nick'] = $nick;
            $_SESSION['pass'] = $pass;
            $_SESSION['id'] = $usuario->get_id();
            $_SESSION['rol'] = $usuario->get_rol();
            $_SESSION['idEstacion'] = $usuario->get_idEstacion();
            //Y redireccionamos directamente a la página con los datos meteorológicos
            header("Location: datosMeteo.php");
        } else {
            //En el caso que no coincida, quiere decir que es un usuario no registrado
            //Iniciamos la sesión
            session_start();
            //Guardamos en la sesión el nick y el pass
            $_SESSION['nick'] = $nick;
            $_SESSION['pass'] = $pass;
            //Y redireccionamos a la página donde el usuario completa los datos con el rol y el id de la estación
            header("Location: registrados.php");
        }
    } catch (Exception $e) {
        //Si hay algún error lo capturamos y mostramos el mensaje correspondiente
        echo $e->getMessage();
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title>DWES - Trabajo final</title>
        <link href="recursos/estilos.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Lobster|Montserrat" rel="stylesheet">
    </head>

    <body>
        <div class='contenedorBase'>
            <h1>Bienvenid@</h1>
            <p class='textoParrafo'>al módulo de DWES y a su tarea final</p>
            <hr>
            <form action='index.php' method='post'>
                <fieldset>
                    <div class='contenedor'>
                        <label for='nick' >Usuario</label><br/>
                        <input type='text' name='nick' id='usuario' maxlength="50" required/><br/>
                        <!-- Si el mensaje existe, lo mostramos -->
                        <?php if (isset($mensaje)) echo $mensaje ?>
                        <label for='pass' >Contraseña</label><br/>
                        <input type='password' name='pass' id='password' maxlength="50" required/><br/>
                        <input type='submit' name='enviar' value='Enviar' />
                    </div>
                </fieldset>
            </form>
        </div>
    </body>
</html>
